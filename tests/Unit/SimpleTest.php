<?php

// Simple tests that don't touch the database at all
test('basic math operations work', function () {
    expect(2 + 2)->toBe(4);
    expect(10 * 5)->toBe(50);
    expect(100 / 4)->toBe(25);
});

test('string operations work', function () {
    $text = 'TripMate';
    expect(strlen($text))->toBe(8);
    expect(strtolower($text))->toBe('tripmate');
    expect(strtoupper($text))->toBe('TRIPMATE');
});

test('array operations work', function () {
    $array = ['adventure', 'cultural', 'nature'];
    expect(count($array))->toBe(3);
    expect(in_array('adventure', $array))->toBeTrue();
    expect(in_array('beach', $array))->toBeFalse();
});

test('PHP version is compatible', function () {
    expect(version_compare(PHP_VERSION, '8.0.0', '>='))->toBeTrue();
});

test('carbon date library works', function () {
    $date = \Carbon\Carbon::parse('2024-12-25');
    expect($date->format('Y-m-d'))->toBe('2024-12-25');
    expect($date->month)->toBe(12);
    expect($date->day)->toBe(25);
});