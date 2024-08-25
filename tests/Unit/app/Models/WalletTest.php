<?php

use App\Models\User;
use App\Models\Wallet;
use App\Exceptions\UserAlreadyHasWalletException;
use Illuminate\Foundation\Testing\DatabaseMigrations;

uses(DatabaseMigrations::class);

describe('test wallet CRUD', function () {
    it('Should create a wallet', function () {
        $wallet = Wallet::factory()->create();

        $this->assertModelExists($wallet);
    });

    it('Should delete a wallet', function () {
        $wallet = Wallet::factory()->create();
        $wallet->delete();

        expect($wallet->deleted_at)->toBeTruthy();
    });

    it('Should select a wallet', function () {
        $wallet = Wallet::factory()->create();
        $foundWallet = Wallet::find($wallet->id);

        expect($foundWallet)->not->toBeNull();
        expect($foundWallet->id)->toEqual($wallet->id);
    });

    it('Should update a wallet', function () {
        $wallet = Wallet::factory()->create();
        $newName = 'Updated Name';
        $wallet->name = $newName;
        $wallet->save();

        $updatedWallet = Wallet::find($wallet->id);
        expect($updatedWallet->name)->toEqual($newName);
    });
});

describe('test wallet relationships', function () {
    it('user relationship', function () {
        $wallet = Wallet::factory()->create();

        $wallet->load('user');

        $this->assertInstanceOf(User::class, $wallet->user);
    });
});

describe('test wallet structure', function () {
    it('Should return the corrects fillable fields', function () {
        $expectedFillable = [
            'name',
            'balance'
        ];

        $fillableKeys = (new Wallet())->getFillable();

        expect($fillableKeys)->toBe($expectedFillable);
    });

    it('Should return the correct table name', function () {
        $tableName = (new Wallet())->getTable();

        expect($tableName)->toBe('wallet');
    });

    it('Should return the correct primary key field', function () {
        $primaryKeyField = (new Wallet())->getKeyName();

        expect($primaryKeyField)->toBe('id');
    });

    it('Should return correct type of incrementing', function () {
        $incrementing = (new Wallet())->getIncrementing();

        expect($incrementing)->toBe(false);
    });

    it('Should return correct key type of primary key', function () {
        $keyType = (new Wallet())->getKeyType();

        expect($keyType)->toBe('string');
    });
});
