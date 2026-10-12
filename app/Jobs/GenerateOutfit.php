<?php

namespace App\Jobs;

use App\Models\Clothes;
use App\Models\Outfits;
use App\Services\GeminiImageGenerator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class GenerateOutfit implements ShouldQueue
{
    use Queueable;

    /**
     * The prompt sent to the model alongside the reference images.
     */
    private const string PROMPT = "The first image is a photo of a person, the second is a top, and the third is a pair of bottoms. Generate a single photorealistic image of the person wearing that top and those bottoms. Keep the person's face, hair, and body unchanged.";

    public int $tries = 3;

    /** @var array<int, int> */
    public array $backoff = [10, 60, 300];

    public int $timeout = 150;

    public function __construct(
        public Clothes $avatar,
        public Clothes $top,
        public Clothes $bottom,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(GeminiImageGenerator $generator): void
    {
        $images = collect([$this->avatar, $this->top, $this->bottom])
            ->map(function (Clothes $item): array {
                $contents = Storage::disk('s3')->get($item->photo);

                if ($contents === null) {
                    throw new RuntimeException("Unable to read the image for the clothing item [{$item->id}].");
                }

                return [
                    'mime_type' => 'image/webp',
                    'data' => base64_encode($contents),
                ];
            })
            ->all();

        $result = $generator->generate($images, self::PROMPT);

        $path = 'outfits/'.Str::uuid().'.'.$this->extensionFor($result['mime_type']);

        Storage::disk('s3')->put($path, base64_decode($result['data']));

        Outfits::create([
            'user_id' => $this->avatar->user_id,
            'photo' => $path,
        ]);
    }

    /**
     * Map a generated image MIME type to a file extension.
     */
    private function extensionFor(string $mimeType): string
    {
        return match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/webp' => 'webp',
            default => 'png',
        };
    }
}
