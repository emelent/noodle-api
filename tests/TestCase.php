<?php

use Lukasoppermann\Httpstatus\Httpstatuscodes;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase implements Httpstatuscodes
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}
