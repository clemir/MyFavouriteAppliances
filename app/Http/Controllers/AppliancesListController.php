<?php

namespace App\Http\Controllers;

use App\User;
use App\Appliance;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AppliancesListController extends Controller
{

    public function index(Request $request, User $user = null)
    {
        $column = $request->get('sort', 'created_at');
        $direction = Str::startsWith($column, '-') ? 'desc' : 'asc';
        $column = ltrim($column, '-');

        $appliances = Appliance::queryBySection($this->getRouteName(), $user)
            ->orderBy($column, $direction)
            ->paginate();

        $appliances->appends(['sort' => $column]);

        return view('appliances.index', [
            'appliances' => $appliances,
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
