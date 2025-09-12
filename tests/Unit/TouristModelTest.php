<?php

use App\Models\Tourist;
use Illuminate\Support\Facades\Hash;

describe('Tourist Model', function () {
    it('has correct fillable attributes', function () {
        $tourist = new Tourist();
        $expected = [
            'name',
            'email',
            'mobile',
            'password',
            'otp',
            'otp_verified',
            'location'
        ];

        expect($tourist->getFillable())->toBe($expected);
    });

    it('hides sensitive attributes', function () {
        $tourist = new Tourist();
        $expected = ['password', 'remember_token'];

        expect($tourist->getHidden())->toBe($expected);
    });

    it('can hash password correctly', function () {
        $password = 'test-password';
        $hashedPassword = Hash::make($password);

        expect(Hash::check($password, $hashedPassword))->toBeTrue();
        expect(Hash::check('wrong-password', $hashedPassword))->toBeFalse();
    });

    it('uses tourist guard', function () {
        $reflection = new ReflectionClass(Tourist::class);
        $property = $reflection->getProperty('guard');
        $property->setAccessible(true);
        $tourist = new Tourist();
        
        expect($property->getValue($tourist))->toBe('tourist');
    });

    it('extends authenticatable user class', function () {
        $tourist = new Tourist();
        
        expect($tourist)->toBeInstanceOf(\Illuminate\Foundation\Auth\User::class);
    });

    it('uses notifiable trait', function () {
        $traits = class_uses(Tourist::class);
        
        expect($traits)->toContain(\Illuminate\Notifications\Notifiable::class);
    });

    it('has bookings relationship method', function () {
        $tourist = new Tourist();
        
        expect(method_exists($tourist, 'bookings'))->toBeTrue();
    });
});