<?php

namespace Tests\Feature;

use App\User;
use App\Appliance;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class SaveFavoriteApplianceTest extends TestCase
{
    // use DatabaseTransactions;
    use RefreshDatabase;

    public function testGuestCanNotSaveAnAppliance()
    {
        $this->withExceptionHandling();
        $appliance = factory(Appliance::class)->create();

        $response = $this->post(route('favorite', [$appliance->id]));

        $response->assertRedirect('login');

        $this->assertDatabaseEmpty('appliance_user');
    }

    public function testUserCanSaveAnAppliance()
    {
        $appliance = factory(Appliance::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/')->post(route('favorite', [$appliance->id]));

        $response->assertRedirect('/');

        $this->assertDatabaseHas('appliance_user', [
            'appliance_id' => $appliance->id,
            'user_id' => $user->id
        ]);
    }

    public function testUserCanRemoveAnAppliance()
    {
        ;
        $user = factory(User::class)->create();
        $user->favorite($appliance = factory(Appliance::class)->create());

        $response = $this->actingAs($user)->from('/')->delete(route('favorite', [$appliance->id]));

        $response->assertRedirect('/');

        $this->assertDatabaseMissing('appliance_user', [
            'appliance_id' => $appliance->id,
            'user_id' => $user->id
        ]);
    }
}
