<?php

namespace App\Http\Controllers;

use App\Enums\ImageType;
use App\Models\Clothes;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;

class ClothesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var FilesystemAdapter $storage */
        //local type hint ("trust me this is a FilesystemAdapter") so that we can use the url() and temporaryUrl() methods without IDE errors
        
        $storage = Storage::disk('s3');

        $urls = Clothes::all()->map(fn ($clothes) => [
            'id' => $clothes->id,
            'photo' => $clothes->photo,
            'public_url' => $storage->url($clothes->photo),
            'temporary_url' => $storage->temporaryUrl($clothes->photo, now()->addMinutes(5)),
        ]);

        dd($urls);
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
            'photo' => ['required', 'mimes:jpg,png'],
            'type' => ['required', new Enum(ImageType::class)],
        ]);

        $photoPath = $file['photo']->store('clothes');

        Clothes::create([
            'user_id' => 1,
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
