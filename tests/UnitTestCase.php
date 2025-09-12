<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class UnitTestCase extends BaseTestCase
{
    // Pure unit tests don't need Laravel app or database
    
    protected function setUp(): void
    {
        parent::setUp();
    }
}