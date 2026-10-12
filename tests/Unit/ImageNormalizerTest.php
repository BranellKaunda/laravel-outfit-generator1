<?php

use App\Services\ImageNormalizer;
use Illuminate\Http\UploadedFile;

test('normalize downscales preserving aspect ratio and encodes webp', function () {
    $image = imagecreatetruecolor(1600, 2400);
    $path = tempnam(sys_get_temp_dir(), 'photo').'.png';
    imagepng($image, $path);
    imagedestroy($image);

    $file = new UploadedFile($path, 'photo.png', 'image/png', null, true);

    $contents = (new ImageNormalizer)->normalize($file);

    [$width, $height] = getimagesizefromstring($contents);

    expect(substr($contents, 0, 4))->toBe('RIFF')
        ->and(substr($contents, 8, 4))->toBe('WEBP')
        ->and(abs($width / $height - 2 / 3))->toBeLessThan(0.01)
        ->and(max($width, $height))->toBeLessThanOrEqual(1200);

    @unlink($path);
});
