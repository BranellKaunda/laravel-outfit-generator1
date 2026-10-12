<?php

use App\Enums\ImageType;
use App\Jobs\GenerateOutfit;
use App\Models\Clothes;
use App\Models\Outfits;
use App\Models\User;
use App\Services\GeminiImageGenerator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

function createClothesItem(ImageType $type, int $userId): Clothes
{
    return Clothes::create([
        'user_id' => $userId,
        'type' => $type,
        'photo' => 'clothes/'.fake()->uuid().'.webp',
    ]);
}

test('generating an outfit dispatches a queued job', function () {
    Queue::fake();

    $user = User::factory()->create();

    $avatar = createClothesItem(ImageType::Avatar, $user->id);
    $top = createClothesItem(ImageType::Top, $user->id);
    $bottom = createClothesItem(ImageType::Bottom, $user->id);

    $response = $this->actingAs($user)->post('/outfits', [
        'avatar_id' => $avatar->id,
        'top_id' => $top->id,
        'bottom_id' => $bottom->id,
    ]);

    $response->assertRedirect(route('outfits.index'));

    Queue::assertPushed(GenerateOutfit::class, fn (GenerateOutfit $job) => $job->avatar->is($avatar)
        && $job->top->is($top)
        && $job->bottom->is($bottom));
});

test('an outfit requires one image of each type', function () {
    Queue::fake();

    $user = User::factory()->create();

    $top = createClothesItem(ImageType::Top, $user->id);

    $response = $this->actingAs($user)->post('/outfits', [
        'avatar_id' => $top->id,
        'top_id' => $top->id,
        'bottom_id' => $top->id,
    ]);

    $response->assertSessionHasErrors(['avatar_id', 'bottom_id']);

    Queue::assertNothingPushed();
});

test('an outfit cannot use another users clothes', function () {
    Queue::fake();

    $user = User::factory()->create();
    $other = User::factory()->create();

    $avatar = createClothesItem(ImageType::Avatar, $other->id);
    $top = createClothesItem(ImageType::Top, $other->id);
    $bottom = createClothesItem(ImageType::Bottom, $other->id);

    $response = $this->actingAs($user)->post('/outfits', [
        'avatar_id' => $avatar->id,
        'top_id' => $top->id,
        'bottom_id' => $bottom->id,
    ]);

    $response->assertSessionHasErrors(['avatar_id', 'top_id', 'bottom_id']);

    Queue::assertNothingPushed();
});

test('the generate page renders', function () {
    $this->actingAs(User::factory()->create())
        ->get('/generate')
        ->assertOk()
        ->assertSee('Generate an outfit');
});

test('the wardrobe lists the generated outfits', function () {
    $user = User::factory()->create();

    Outfits::create(['user_id' => $user->id, 'photo' => 'outfits/example.png']);

    $this->actingAs($user)
        ->get('/wardrobe')
        ->assertOk()
        ->assertSee('Wardrobe')
        ->assertSee('Generated outfit 1');
});

test('the wardrobe shows a generating notice after dispatching', function () {
    Queue::fake();

    $user = User::factory()->create();

    $avatar = createClothesItem(ImageType::Avatar, $user->id);
    $top = createClothesItem(ImageType::Top, $user->id);
    $bottom = createClothesItem(ImageType::Bottom, $user->id);

    $this->actingAs($user)->post('/outfits', [
        'avatar_id' => $avatar->id,
        'top_id' => $top->id,
        'bottom_id' => $bottom->id,
    ])->assertSessionHas('generating', true);

    $this->actingAs($user)
        ->get('/wardrobe')
        ->assertOk()
        ->assertSee('being generated');
});

test('the wardrobe celebrates a freshly generated outfit', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/wardrobe?generated=1')
        ->assertOk()
        ->assertSee('new outfit is ready');
});

test('the status endpoint counts only the users outfits', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    Outfits::create(['user_id' => $user->id, 'photo' => 'outfits/mine.png']);
    Outfits::create(['user_id' => $other->id, 'photo' => 'outfits/theirs.png']);

    $this->actingAs($user)
        ->get('/wardrobe/status')
        ->assertOk()
        ->assertJson(['count' => 1]);
});

test('a user cannot generate more than three outfits per day', function () {
    Queue::fake();

    $user = User::factory()->create();

    $avatar = createClothesItem(ImageType::Avatar, $user->id);
    $top = createClothesItem(ImageType::Top, $user->id);
    $bottom = createClothesItem(ImageType::Bottom, $user->id);

    $payload = [
        'avatar_id' => $avatar->id,
        'top_id' => $top->id,
        'bottom_id' => $bottom->id,
    ];

    foreach (range(1, 3) as $i) {
        $this->actingAs($user)->post('/outfits', $payload)->assertRedirect(route('outfits.index'));
    }

    $this->actingAs($user)->post('/outfits', $payload)->assertSessionHasErrors('outfit');

    Queue::assertPushed(GenerateOutfit::class, 3);
});

test('the daily limit resets the following day', function () {
    Queue::fake();

    $user = User::factory()->create();

    $avatar = createClothesItem(ImageType::Avatar, $user->id);
    $top = createClothesItem(ImageType::Top, $user->id);
    $bottom = createClothesItem(ImageType::Bottom, $user->id);

    $payload = [
        'avatar_id' => $avatar->id,
        'top_id' => $top->id,
        'bottom_id' => $bottom->id,
    ];

    foreach (range(1, 3) as $i) {
        $this->actingAs($user)->post('/outfits', $payload);
    }

    $this->actingAs($user)->post('/outfits', $payload)->assertSessionHasErrors('outfit');

    $this->travel(1)->days();

    $this->actingAs($user)->post('/outfits', $payload)->assertRedirect(route('outfits.index'));

    Queue::assertPushed(GenerateOutfit::class, 4);
});

test('the generate page shows how many outfits remain today', function () {
    Queue::fake();

    $user = User::factory()->create();

    $avatar = createClothesItem(ImageType::Avatar, $user->id);
    $top = createClothesItem(ImageType::Top, $user->id);
    $bottom = createClothesItem(ImageType::Bottom, $user->id);

    $this->actingAs($user)->post('/outfits', [
        'avatar_id' => $avatar->id,
        'top_id' => $top->id,
        'bottom_id' => $bottom->id,
    ]);

    $this->actingAs($user)
        ->get('/generate')
        ->assertOk()
        ->assertSee('2 more outfits');
});

test('the generate page shows the daily limit reached', function () {
    Queue::fake();

    $user = User::factory()->create();

    $avatar = createClothesItem(ImageType::Avatar, $user->id);
    $top = createClothesItem(ImageType::Top, $user->id);
    $bottom = createClothesItem(ImageType::Bottom, $user->id);

    $payload = [
        'avatar_id' => $avatar->id,
        'top_id' => $top->id,
        'bottom_id' => $bottom->id,
    ];

    foreach (range(1, 3) as $i) {
        $this->actingAs($user)->post('/outfits', $payload);
    }

    $this->actingAs($user)
        ->get('/generate')
        ->assertOk()
        ->assertSee('reached your daily limit');
});

test('the job generates an outfit image and records it', function () {
    Storage::fake('s3');
    Http::preventStrayRequests();

    config(['services.gemini.key' => 'test-key']);

    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [[
                'content' => [
                    'parts' => [
                        ['inlineData' => ['mimeType' => 'image/png', 'data' => base64_encode('generated-outfit')]],
                    ],
                ],
            ]],
        ]),
    ]);

    $user = User::factory()->create();

    $avatar = createClothesItem(ImageType::Avatar, $user->id);
    $top = createClothesItem(ImageType::Top, $user->id);
    $bottom = createClothesItem(ImageType::Bottom, $user->id);

    foreach ([$avatar, $top, $bottom] as $item) {
        Storage::disk('s3')->put($item->photo, 'fake-webp-bytes');
    }

    (new GenerateOutfit($avatar, $top, $bottom))->handle(app(GeminiImageGenerator::class));

    $outfit = Outfits::first();

    expect($outfit)->not->toBeNull()
        ->and($outfit->user_id)->toBe($user->id);

    Storage::disk('s3')->assertExists($outfit->photo);
    expect(Storage::disk('s3')->get($outfit->photo))->toBe('generated-outfit');
});
