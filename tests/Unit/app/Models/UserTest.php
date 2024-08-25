<?php

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;

uses(DatabaseMigrations::class);

describe('Test user CRUD', function () {
    it('Should create a user', function () {
        $user = User::factory()->create();

        $this->assertModelExists($user);
    });

    it('Should delete a user', function () {
        $user = User::factory()->create();
        $user->delete();

        expect($user->deleted_at)->toBeTruthy();
    });

    it('Should select a user', function () {
        $user = User::factory()->create();
        $foundUser = User::find($user->id);

        expect($foundUser)->not->toBeNull();
        expect($foundUser->id)->toEqual($user->id);
    });

    it('Should update a user', function () {
        $user = User::factory()->create();
        $newName = 'Updated Name';
        $user->name = $newName;
        $user->save();

        $updatedUser = User::find($user->id);
        expect($updatedUser->name)->toEqual($newName);
    });
});

describe('Test user relationships', function () {
    it('Wallet relationship', function () {
        $user = User::factory()->create();

        Wallet::factory()->create();

        $wallet = Wallet::factory()->state([
            'user_id' => $user->id
        ])->create();

        $user->load('wallet');

        expect($user->wallet->id)->toBe($wallet->id);
    });
});

describe('Test user structure', function () {
    it('Should return the corrects fillable fields', function () {
        $expectedFillable = [
            'name',
            'type',
            'document',
            'email',
            'password'
        ];

        $fillableKeys = (new User())->getFillable();

        expect($fillableKeys)->toBe($expectedFillable);
    });

    it('Should return the correct table name', function () {
        $tableName = (new User())->getTable();

        expect($tableName)->toBe('users');
    });

    it('Should return the correct primary key field', function () {
        $primaryKeyField = (new User())->getKeyName();

        expect($primaryKeyField)->toBe('id');
    });

    it('Should return correct type of incrementing', function () {
        $incrementing = (new User())->getIncrementing();

        expect($incrementing)->toBe(false);
    });

    it('Should return correct key type of primary key', function () {
        $keyType = (new User())->getKeyType();

        expect($keyType)->toBe('string');
    });
});
