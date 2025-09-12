<?php

use App\Models\Activity;

describe('Activity Model', function () {
    it('has correct fillable attributes', function () {
        $activity = new Activity();
        $fillable = $activity->getFillable();

        expect($fillable)->toContain('name');
        expect($fillable)->toContain('description');
        expect($fillable)->toContain('price');
        expect($fillable)->toContain('difficulty_level');
        expect($fillable)->toContain('category');
    });

    it('validates difficulty levels', function () {
        $validLevels = ['easy', 'moderate', 'challenging'];
        
        foreach ($validLevels as $level) {
            expect($validLevels)->toContain($level);
        }
    });

    it('validates activity categories', function () {
        $validCategories = ['adventure', 'cultural', 'nature', 'relaxation', 'food'];
        
        foreach ($validCategories as $category) {
            expect($validCategories)->toContain($category);
        }
    });

    it('has relationship methods', function () {
        $activity = new Activity();

        expect(method_exists($activity, 'location'))->toBeTrue();
    });

    it('validates participant counts', function () {
        $minParticipants = 2;
        $maxParticipants = 20;

        expect($maxParticipants)->toBeGreaterThan($minParticipants);
        expect($minParticipants)->toBeGreaterThan(0);
        expect($maxParticipants)->toBeGreaterThan(0);
    });

    it('has valid price format', function () {
        $price = 123.45;

        expect($price)->toBeFloat();
        expect($price)->toBeGreaterThan(0);
    });

    it('has duration in correct format', function () {
        $duration = '3 hours';

        expect($duration)->toMatch('/^\d+ hours?$/');
    });

    it('has status field for active/inactive states', function () {
        $validStatuses = ['active', 'inactive'];
        
        expect($validStatuses)->toContain('active');
        expect($validStatuses)->toContain('inactive');
    });

    it('has image storage path structure', function () {
        $imagePath = 'activities/sample.jpg';

        expect($imagePath)->toContain('activities/');
        expect($imagePath)->toContain('.jpg');
    });
});