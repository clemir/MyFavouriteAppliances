<?php

namespace App\Repositories;

use App\User;
use App\Appliance;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ApplianceEloquentRepository implements ApplianceRepositoryContract
{
    public function all($section, User $user = null, $column, $direction)
    {
        if ($section == 'home') {
            Cache::rememberForever('appliances_'.$column.'_'.$direction, function () use ($column, $direction) {
                    return $this->getAppliances('home', null, $column, $direction)
                        ->where('status', true)
                        ->paginate();
            });
        }
        return $this->getAppliances($section, $user, $column, $direction)
            ->paginate();
    }

    public function getAppliances($section, $user, $column, $direction)
    {
        return Appliance::queryBySection($section, $user)
            ->orderBy($column, $direction);
    }
}
