<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function favoritedAppliances()
    {
        return $this->belongsToMany(Appliance::class);
    }

    public function hasFavorited(Appliance $appliance)
    {
        return $this->favoritedAppliances()->where('appliance_id', $appliance->id)->count() > 0;
    }

    public function hasFavoritedAppliances()
    {
        return $this->favoritedAppliances()->count() > 0;
    }

    public function favorite(Appliance $appliance)
    {
        if ($this->hasFavorited($appliance)) {
            return false;
        }

        $this->favoritedAppliances()->attach($appliance);
        return true;
    }

    public function unfavorite(Appliance $appliance)
    {
        $this->favoritedAppliances()->detach($appliance);
    }

    public function getWishlistUrlAttribute()
    {
        return route('user-appliances', [$this->id]);
    }
}
