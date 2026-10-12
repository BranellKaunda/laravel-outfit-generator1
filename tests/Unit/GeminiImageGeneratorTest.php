<?php

use App\Services\GeminiImageGenerator;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

function makeGenerator(?string $apiKey = 'test-key'): GeminiImageGenerator
{
    return new GeminiImageGenerator(
        $apiKey,
        'gemini-2.5-flash-image',
        'https://generativelanguage.googleapis.com/v1beta',
    );
}

test('it sends the reference images and returns the generated image', function () {
    Http::preventStrayRequests();

    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [[
                'content' => [
                    'parts' => [
                        ['text' => 'Here is your outfit.'],
                        ['inlineData' => ['mimeType' => 'image/png', 'data' => base64_encode('generated-image')]],
                    ],
                ],
            ]],
        ]),
    ]);

    $result = makeGenerator()->generate([
        ['mime_type' => 'image/webp', 'data' => base64_encode('avatar')],
    ], 'Dress the person.');

    expect($result['mime_type'])->toBe('image/png')
        ->and(base64_decode($result['data']))->toBe('generated-image');

    Http::assertSent(fn (Request $request) => $request->url() === 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-image:generateContent'
        && $request->hasHeader('x-goog-api-key', 'test-key')
        && $request->data()['contents'][0]['parts'][0]['text'] === 'Dress the person.'
        && $request->data()['contents'][0]['parts'][1]['inline_data'] === ['mime_type' => 'image/webp', 'data' => base64_encode('avatar')]);
});

test('it throws when the api key is missing', function () {
    expect(fn () => makeGenerator(null)->generate([], 'prompt'))
        ->toThrow(RuntimeException::class);
});

test('it throws when the model returns no image', function () {
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [['content' => ['parts' => [['text' => 'I cannot do that.']]]]],
        ]),
    ]);

    expect(fn () => makeGenerator()->generate([], 'prompt'))
        ->toThrow(RuntimeException::class);
});
