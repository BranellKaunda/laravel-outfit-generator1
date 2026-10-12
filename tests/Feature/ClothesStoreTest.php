<?php

use App\Enums\ImageType;
use App\Models\Clothes;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('an uploaded photo is normalized, converted to webp, and stored on the s3 disk', function () {
    Storage::fake('s3');

    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/clothes', [
        'photo' => UploadedFile::fake()->image('shirt.jpg', 2400, 1600),
        'type' => ImageType::Top->value,
    ]);

    $response->assertRedirect('/');

    $clothes = Clothes::first();

    expect($clothes)->not->toBeNull()
        ->and($clothes->type)->toBe(ImageType::Top)
        ->and($clothes->user_id)->toBe($user->id);

    Storage::disk('s3')->assertExists($clothes->photo);

    $contents = Storage::disk('s3')->get($clothes->photo);

    expect(substr($contents, 0, 4))->toBe('RIFF')
        ->and(substr($contents, 8, 4))->toBe('WEBP');

    [$width, $height] = getimagesizefromstring($contents);

    expect(abs($width / $height - 3 / 2))->toBeLessThan(0.01)
        ->and($width)->toBeLessThanOrEqual(1200)
        ->and($height)->toBeLessThanOrEqual(1200);
});
