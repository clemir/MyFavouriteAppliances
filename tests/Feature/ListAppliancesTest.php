<?php

namespace Tests\Feature;

use App\Appliance;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ListAppliancesTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function itShowsAppliancesHome()
    {
        factory(Appliance::class, 10)->create();

        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertViewHas('appliances')
            ->assertSee('My Favorite Appliances');

        $result = $response->viewData('appliances');

        $this->assertCount(10, $result);
    }
    /** @test */
    public function itShowsMyWishlistSection()
    {
        factory(Appliance::class, 5)->create();
        $user = $this->defaultUser();
        $user->favorite($applianceA = factory(Appliance::class)->create());
        $user->favorite($applianceB = factory(Appliance::class)->create());

        $response = $this->actingAs($user)->get('my-wishlist');

        $response->assertStatus(200)
            ->assertViewHas('appliances')
            ->assertSee('My Wishlist')
            ->assertViewCollection('appliances')
                ->contains($applianceA)
                ->contains($applianceB);

        $this->assertCount(2, $response->viewData('appliances'));
    }

    /** @test */
    public function itShowsUserWishlistSection()
    {
        factory(Appliance::class, 5)->create();
        $user = $this->defaultUser([
            'name' => 'Clemir Rondon'
        ]);
        $user->favorite($applianceA = factory(Appliance::class)->create());
        $user->favorite($applianceB = factory(Appliance::class)->create());

        $response = $this->get("users/{$user->id}/wishlist");

        $response->assertStatus(200)
            ->assertViewHas('appliances')
            ->assertSee("Clemir Rondon&#039;s wishlist")
            ->assertViewCollection('appliances')
                ->contains($applianceA)
                ->contains($applianceB);

        $this->assertCount(2, $response->viewData('appliances'));
    }
}
