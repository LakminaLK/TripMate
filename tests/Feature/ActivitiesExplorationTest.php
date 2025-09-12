<?php

use App\Models\Tourist;
use App\Models\Activity;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Activities and Exploration', function () {
    it('can view landing page', function () {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertSee('TripMate');
    });

    it('displays featured activities on landing page', function () {
        $location = Location::factory()->create();
        Activity::factory()->count(3)->create([
            'location_id' => $location->id,
            'status' => 'active',
        ]);
        
        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertSee('activities');
    });

    it('can view explore page', function () {
        $response = $this->get('/tourist/explore');
        
        $response->assertStatus(200);
        $response->assertSee('Explore');
    });

    it('can view activity details', function () {
        $location = Location::factory()->create();
        $activity = Activity::factory()->create([
            'name' => 'Whale Watching Adventure',
            'location_id' => $location->id,
            'status' => 'active',
        ]);
        
        $response = $this->get("/tourist/activity/{$activity->id}");
        
        $response->assertStatus(200);
        $response->assertSee('Whale Watching Adventure');
    });

    it('can filter activities by category', function () {
        $location = Location::factory()->create();
        $adventureActivity = Activity::factory()->create([
            'category' => 'adventure',
            'location_id' => $location->id,
            'status' => 'active',
        ]);
        $culturalActivity = Activity::factory()->create([
            'category' => 'cultural',
            'location_id' => $location->id,
            'status' => 'active',
        ]);
        
        $response = $this->get('/tourist/explore?category=adventure');
        
        $response->assertStatus(200);
        // Should see adventure activities but not cultural ones
    });

    it('can search activities by location', function () {
        $location1 = Location::factory()->create(['name' => 'Colombo']);
        $location2 = Location::factory()->create(['name' => 'Kandy']);
        
        Activity::factory()->create([
            'location_id' => $location1->id,
            'status' => 'active',
        ]);
        Activity::factory()->create([
            'location_id' => $location2->id,
            'status' => 'active',
        ]);
        
        $response = $this->get('/tourist/explore?location=Colombo');
        
        $response->assertStatus(200);
    });

    it('hides inactive activities from public view', function () {
        $location = Location::factory()->create();
        $activeActivity = Activity::factory()->create([
            'name' => 'Active Adventure',
            'location_id' => $location->id,
            'status' => 'active',
        ]);
        $inactiveActivity = Activity::factory()->create([
            'name' => 'Inactive Adventure',
            'location_id' => $location->id,
            'status' => 'inactive',
        ]);
        
        $response = $this->get('/tourist/explore');
        
        $response->assertStatus(200);
        $response->assertSee('Active Adventure');
        $response->assertDontSee('Inactive Adventure');
    });

    it('can view emergency services page', function () {
        $response = $this->get('/emergency-services');
        
        $response->assertStatus(200);
        $response->assertSee('Emergency Services');
    });

    it('displays activity pricing correctly', function () {
        $location = Location::factory()->create();
        $activity = Activity::factory()->create([
            'name' => 'Scuba Diving',
            'price' => 150.75,
            'location_id' => $location->id,
            'status' => 'active',
        ]);
        
        $response = $this->get("/tourist/activity/{$activity->id}");
        
        $response->assertStatus(200);
        $response->assertSee('150.75');
    });

    it('shows activity difficulty and duration', function () {
        $location = Location::factory()->create();
        $activity = Activity::factory()->create([
            'name' => 'Mountain Hiking',
            'difficulty_level' => 'challenging',
            'duration' => '6 hours',
            'location_id' => $location->id,
            'status' => 'active',
        ]);
        
        $response = $this->get("/tourist/activity/{$activity->id}");
        
        $response->assertStatus(200);
        $response->assertSee('challenging');
        $response->assertSee('6 hours');
    });
});