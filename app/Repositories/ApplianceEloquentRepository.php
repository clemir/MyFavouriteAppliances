<?php

namespace App\Repositories;

use App\User;
use App\Appliance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ApplianceEloquentRepository implements ApplianceRepositoryContract
{
    public function all($section, User $user = null, $column, $direction)
    {
        return Appliance::queryBySection($section, $user)
            ->orderBy($column, $direction)
            ->paginate();
    }
}
