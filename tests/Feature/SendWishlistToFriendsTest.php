<?php

namespace Tests\Feature;

use App\User;
use App\Appliance;
use Tests\TestCase;
use Tests\TestHelpers;
use App\Mail\SendWishlist;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class SendWishlistToFriendsTest extends TestCase
{
    // use DatabaseTransactions;
    use RefreshDatabase;

    public function testGuestCanNotSendAWishlist()
    {
        Mail::fake();
        $this->withExceptionHandling();

        $response = $this->post(route('share.send', [
            'email' => 'friend@example.com'
        ]));

        $response->assertStatus(302)->assertRedirect('login');

        Mail::assertNotSent(SendWishlist::class);
    }

    public function testGuestCanNotSeeForm()
    {
        $this->withExceptionHandling();

        $response = $this->get(route('share.create'));

        $response->assertStatus(302)->assertRedirect('login');
    }

    public function testGuestCanSeeForm()
    {
        $user = $this->defaultUser();
        $user->favorite(factory(Appliance::class)->create());

        $response = $this->actingAs($user)->get(route('share.create'));

        $response->assertStatus(200);
    }

    public function testUserCanNotSendAWishlistWhenDoesNotHaveFavorites()
    {
        Mail::fake();
        $this->withExceptionHandling();

        $user = $this->defaultUser();

        $response = $this->actingAs($user)->post(route('share.send', [
            'email' => 'friend@example.com'
        ]));

        $response->assertStatus(403);

        Mail::assertNotSent(SendWishlist::class);
    }

    public function testUserCanSendAWishlistToFriend()
    {
        Mail::fake();

        $user = $this->defaultUser();
        $user->favorite(factory(Appliance::class)->create());

        $response = $this->actingAs($user)->post(route('share.send', [
            'email' => 'friend@example.com'
        ]));

        $response->assertStatus(200);

        Mail::assertSent(SendWishlist::class);
    }

    public function testUserCanSendAWishlistToMultiplesFriends()
    {
        Mail::fake();

        $user = $this->defaultUser();
        $user->favorite(factory(Appliance::class)->create());

        $response = $this->actingAs($user)->post(route('share.send', [
            'email' => ['friendA@example.com', 'friendB@example.com']
        ]));

        $response->assertStatus(200);

        Mail::assertSent(SendWishlist::class, function ($mail) {
            return $mail->hasTo(['friendA@example.com', 'friendB@example.com']);
        });
    }

    public function testEmailFriendIsRequired()
    {
        Mail::fake();
        $this->handleValidationExceptions();
        $user = $this->defaultUser();
        $user->favorite(factory(Appliance::class)->create());

        $response = $this->actingAs($user)
                        ->from('/share')
                        ->post(route('share.send', [
                            'email' => null
                        ]));

        $response->assertRedirect('share')
            ->assertSessionHasErrors('email');

        Mail::assertNotSent(SendWishlist::class);
    }
}
