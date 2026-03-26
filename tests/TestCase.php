<?php

namespace Zaruto\ScrambleSpatieQueryBuilder\Tests;

use Dedoc\Scramble\ScrambleServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ScrambleServiceProvider::class,
        ];
    }
}
