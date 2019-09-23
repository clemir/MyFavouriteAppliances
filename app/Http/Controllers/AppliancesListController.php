<?php

namespace App\Http\Controllers;

use App\User;
use App\Appliance;
use Illuminate\Http\Request;

class AppliancesListController extends Controller
{

    public function index(Request $request, User $user = null)
    {
        return view('appliances.index', [
            'appliances' => Appliance::queryBySection($this->getRouteName(), $user)->paginate(),
            'title' => $this->getTitle($user),
            'subtitle' => $this->getSubtitle()
        ]);
    }

    private function getTitle(User $user = null)
    {
        $username = optional($user)->name;

        $titles = [
            'home' => config('app.name'),
            'my-wishlist' => 'My Wishlist',
            'user-appliances' => "$username's wishlist"
        ];
        return $titles[$this->getRouteName()];
    }

    private function getSubtitle()
    {
        $subtitles = [
            'home' => 'Save your favorites and share with your friends!',
            'my-wishlist' => 'Share your favorite appliances with your friends ',
            'user-appliances' => ''
        ];
        return $subtitles[$this->getRouteName()];
    }

    private function getRouteName()
    {
         return request()->route()->getName();
    }
}
