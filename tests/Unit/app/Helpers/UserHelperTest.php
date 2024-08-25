<?php

use App\Helpers\UserHelper;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;

uses(DatabaseMigrations::class);


describe('Test UserHelper', function () {
    it('Should check if is a customer', function () {
        $user = User::factory()->create();

        $this->assertTrue(UserHelper::isCustomer($user));
    });

    it('Should check if user has balance in wallet', function () {
        $user = User::factory()->create();
        Wallet::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertFalse(UserHelper::hasSufficientBalance($user, 1000));
    });

    it('Should transform float amount in cents', function () {
        $expectedCents = 5000;

        $convertedCents = UserHelper::floatToCents(50);

        expect($convertedCents)->toBe($expectedCents);
    });

    it('Should transform cents amount in float', function () {
        $expectedCents = 5.0;

        $convertedCents = UserHelper::centsToFloat(500);

        expect($convertedCents)->toBe($expectedCents);
    });
});
