<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appliance extends Model
{
    public function getFormatPriceAttribute()
    {
        return $this->price/100;
    }

    public static function queryBySection($section, User $user = null)
    {
        switch ($section) {
            case 'my-wishlist':
                return auth()->user()->favoritedAppliances();
            case 'user-appliances':
                return $user->favoritedAppliances();
            case 'home':
                return static::query();
        }
    }
}
