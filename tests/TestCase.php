<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Create a tourist for testing
     */
    protected function createTourist($attributes = [])
    {
        return \App\Models\Tourist::factory()->create($attributes);
    }

    /**
     * Create an admin for testing
     */
    protected function createAdmin($attributes = [])
    {
        return \App\Models\Admin::factory()->create($attributes);
    }

    /**
     * Create a hotel for testing
     */
    protected function createHotel($attributes = [])
    {
        return \App\Models\Hotel::factory()->create($attributes);
    }

    /**
     * Authenticate as tourist
     */
    protected function actingAsTourist($tourist = null)
    {
        $tourist = $tourist ?: $this->createTourist();
        return $this->actingAs($tourist, 'tourist');
    }

    /**
     * Authenticate as admin
     */
    protected function actingAsAdmin($admin = null)
    {
        $admin = $admin ?: $this->createAdmin();
        return $this->actingAs($admin, 'admin');
    }
}
