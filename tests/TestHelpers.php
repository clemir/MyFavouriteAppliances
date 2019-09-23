<?php

namespace Tests;

use App\User;
use Tests\TestCollectionData;
use PHPUnit\Framework\Assert as PHPUnit;
use Illuminate\Foundation\Testing\TestResponse;

trait TestHelpers
{
    protected $defaultData = [];
    protected $defaultUser;

    public function defaultUser(array $attributes = [])
    {
        if ($this->defaultUser != null) {
            return $this->defaultUser;
        }

        return $this->defaultUser = factory(User::class)->create($attributes);
    }

    public function registerTestResponseMacros()
    {
        TestResponse::macro('viewData', function ($key) {
            $this->assertViewHas($key);

            return $this->original->$key;
        });

        TestResponse::macro('assertViewHasModel', function ($key, $model) {
            $this->assertViewHas($key);

            PHPUnit::assertSame(
                $this->original->$key->getKey(), $model->getKey(),
                "The view does not have the model [{$key}] with {$model->getKeyName()} [{$model->getKey()}]"
            );

            return $this;
        });

        TestResponse::macro('assertViewCollection', function ($key) {
            return new TestCollectionData($this->viewData($key), $this);
        });
    }

    // - Database helpers

    protected function assertDatabaseEmpty($table, $connection = null)
    {
        $total = $this->getConnection($connection)->table($table)->count();

        $this->assertSame(
            0, $total, "Failed asserting the table [{$table}] is empty. {$total} rows found."
        );
    }
}
