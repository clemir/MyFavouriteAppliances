<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, TestHelpers;

    protected function setup() :void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->registerTestResponseMacros();
    }
}
