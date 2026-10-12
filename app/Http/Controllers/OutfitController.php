<?php

namespace App\Http\Controllers;

use App\Enums\ImageType;
use App\Jobs\GenerateOutfit;
use App\Models\Clothes;
use App\Models\Outfits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class OutfitController extends Controller
{
    /**
     * Display the wardrobe: every outfit generated for the user.
     */
    public function index(Request $request)
    {
        $storage = Storage::disk('s3');

        $outfits = $request->user()->outfit()->latest()->get()->map(fn (Outfits $outfit) => [
            'id' => $outfit->id,
            'created_at' => $outfit->created_at,
            'photo' => $outfit->photo,
            'public_url' => $storage->url($outfit->photo),
        ]);

        return view('wardrobe', [
            'outfits' => $outfits,
            'generating' => $request->session()->get('generating', false),
        ]);
    }

    /**
     * Report how many outfits the user has, so the wardrobe can detect a new one.
     */
    public function status(Request $request)
    {
        return response()->json([
            'count' => $request->user()->outfit()->count(),
        ]);
    }

    /**
     * Show the form for generating a new outfit.
     */
    public function create(Request $request)
    {
        $storage = Storage::disk('s3');

        $clothes = $request->user()->clothes()->latest()->get()->map(fn (Clothes $item) => [
            'id' => $item->id,
            'type' => $item->type,
            'photo' => $item->photo,
            'public_url' => $storage->url($item->photo),
        ]);

        $used = RateLimiter::attempts($this->dailyLimitKey($request->user()->id));

        return view('generate', [
            'tops' => $clothes->where('type', ImageType::Top)->values(),
            'bottoms' => $clothes->where('type', ImageType::Bottom)->values(),
            'avatars' => $clothes->where('type', ImageType::Avatar)->values(),
            'remaining' => max(0, Outfits::DAILY_LIMIT - $used),
            'dailyLimit' => Outfits::DAILY_LIMIT,
        ]);
    }

    /**
     * Dispatch a queued job that generates an outfit from the selected images.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $key = $this->dailyLimitKey($user->id);

        if (RateLimiter::tooManyAttempts($key, Outfits::DAILY_LIMIT)) {
            return redirect()->route('outfits.create')->withErrors([
                'outfit' => 'You have reached your daily limit of '.Outfits::DAILY_LIMIT.' outfits. Please try again tomorrow.',
            ]);
        }

        $data = $request->validate([
            'avatar_id' => ['required', Rule::exists('clothes', 'id')->where('user_id', $user->id)->where('type', ImageType::Avatar->value)],
            'top_id' => ['required', Rule::exists('clothes', 'id')->where('user_id', $user->id)->where('type', ImageType::Top->value)],
            'bottom_id' => ['required', Rule::exists('clothes', 'id')->where('user_id', $user->id)->where('type', ImageType::Bottom->value)],
        ]);

        GenerateOutfit::dispatch(
            $user->clothes()->findOrFail($data['avatar_id']),
            $user->clothes()->findOrFail($data['top_id']),
            $user->clothes()->findOrFail($data['bottom_id']),
        );

        RateLimiter::hit($key, $this->secondsUntilEndOfDay());

        return redirect()->route('outfits.index')->with('generating', true);
    }

    /**
     * The rate limiter key tracking a user's outfit generations for the day.
     */
    private function dailyLimitKey(int $userId): string
    {
        return "outfit-generations:{$userId}";
    }

    /**
     * The number of seconds until the daily limit counter resets at midnight.
     */
    private function secondsUntilEndOfDay(): int
    {
        return max(1, (int) now()->diffInSeconds(now()->endOfDay()));
    }
}
