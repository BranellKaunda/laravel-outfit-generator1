<?php

namespace App\Http\Controllers;

use App\Enums\ImageType;
use App\Models\Clothes;
use App\Services\ImageNormalizer;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;

class ClothesController extends Controller
{
    public function __construct(private ImageNormalizer $normalizer) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var FilesystemAdapter $storage */
        $storage = Storage::disk('s3');

        $urls = $request->user()->clothes()->latest()->get()->map(fn (Clothes $clothes) => [
            'id' => $clothes->id,
            'type' => $clothes->type,
            'created_at' => $clothes->created_at,
            'photo' => $clothes->photo,
            'public_url' => $storage->url($clothes->photo),
        ]);

        $tops = $urls->where('type', ImageType::Top)->values();
        $bottoms = $urls->where('type', ImageType::Bottom)->values();
        $avatars = $urls->where('type', ImageType::Avatar)->values();

        return view('welcome', [
            'tops' => $tops,
            'bottoms' => $bottoms,
            'avatars' => $avatars,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $file = $request->validate([
            'photo' => ['required', 'mimes:jpg,png,webp,avif'],
            'type' => ['required', new Enum(ImageType::class)],
        ]);

        $photoPath = Image::fromBytes($this->normalizer->normalize($file['photo']))
            ->store('clothes');

        Clothes::create([
            'user_id' => $request->user()->id,
            'type' => $file['type'],
            'photo' => $photoPath,
        ]);

        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(Clothes $clothes)
    {

        // return view('clothes.show', compact('clothes', 'url'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clothes $clothes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clothes $clothes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clothes $clothes)
    {
        //
    }
}
