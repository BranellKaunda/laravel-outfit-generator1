<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeminiImageGenerator
{
    public function __construct(
        private ?string $apiKey,
        private string $model,
        private string $baseUrl,
    ) {}

    /**
     * Generate a single image from the given reference images and prompt.
     *
     * @param  array<int, array{mime_type: string, data: string}>  $images  Base64-encoded images, in prompt order.
     * @return array{mime_type: string, data: string} The base64-encoded generated image.
     */
    public function generate(array $images, string $prompt): array
    {
        if (blank($this->apiKey)) {
            throw new RuntimeException('The Gemini API key is not configured.');
        }

        $parts = [['text' => $prompt]];

        foreach ($images as $image) {
            $parts[] = [
                'inline_data' => [
                    'mime_type' => $image['mime_type'],
                    'data' => $image['data'],
                ],
            ];
        }

        $response = Http::withHeaders(['x-goog-api-key' => $this->apiKey])
            ->connectTimeout(10)
            ->timeout(120)
            ->post("{$this->baseUrl}/models/{$this->model}:generateContent", [
                'contents' => [[
                    'role' => 'user',
                    'parts' => $parts,
                ]],
                'generationConfig' => [
                    'responseModalities' => ['TEXT', 'IMAGE'],
                ],
            ])
            ->throw();

        foreach ($response->json('candidates.0.content.parts', []) as $part) {
            if (isset($part['inlineData']['data'])) {
                return [
                    'mime_type' => $part['inlineData']['mimeType'] ?? 'image/png',
                    'data' => $part['inlineData']['data'],
                ];
            }
        }

        throw new RuntimeException('Gemini did not return an image.');
    }
}
