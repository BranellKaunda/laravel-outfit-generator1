<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Image;

class ImageNormalizer
{
    /**
     * The longest edge of a normalized image, in pixels.
     */
    private const int MAX_DIMENSION = 1200;

    /**
     * Normalize the given upload: auto-orient, downscale, and encode the
     * result as WebP image contents. The original aspect ratio is preserved.
     *
     * @return string The encoded WebP image contents.
     */
    public function normalize(UploadedFile $file): string
    {
        return Image::fromUpload($file)
            ->orient()
            ->scale(self::MAX_DIMENSION, self::MAX_DIMENSION)
            ->toWebp()
            ->quality(82)
            ->toBytes();
    }
}
