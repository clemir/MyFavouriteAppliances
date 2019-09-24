<?php

namespace App\Repositories;

use App\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface ApplianceRepositoryContract
{
    public function all($section, User $user = null, $column, $direction);
}
