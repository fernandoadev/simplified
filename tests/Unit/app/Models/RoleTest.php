<?php

use App\Models\Role;
use App\Models\User;
use App\Exceptions\RoleNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;

uses(DatabaseMigrations::class);


describe('test role CRUD', function () {
    it('Should delete a role', function () {
        $role = Role::findByRole('customer');
        $role->delete();

        expect($role->deleted_at)->toBeTruthy();
    });

    it('Should select a role', function () {
        $role = Role::findByRole('customer');
        $foundRole = Role::find($role->id);

        expect($foundRole)->not->toBeNull();
        expect($foundRole->id)->toEqual($role->id);
    });

    it('Select role by findByRole', function () {
        $role = Role::findByRole('customer');

        expect($role)->not()->toBeNull();
        expect($role->role)->toBe('customer');
    });
});

describe('test role relationships', function () {
    it('users relationship', function () {
        $role = Role::findByRole('customer');

        $userWithRole1 = User::factory()->create();
        $userWithRole2 = User::factory()->create();
        $role->users()->attach([$userWithRole1->id, $userWithRole2->id]);

        $userWithoutRole = User::factory()->create();

        $roleWithUsers = Role::with('users')->find($role->id);

        expect($role->role)->toBe('customer');
        expect($roleWithUsers->users->count())->toBe(2);
        expect($roleWithUsers->users->pluck('id'))->toContain($userWithRole1->id);
        expect($roleWithUsers->users->pluck('id'))->toContain($userWithRole2->id);
        expect($roleWithUsers->users->pluck('id'))->not->toContain($userWithoutRole->id);
    });
});

describe('Should throw RoleNotFoundException', function () {
    it('When role not found', function () {
        expect(fn() => Role::findByRole('wrong-role'))
            ->toThrow(RoleNotFoundException::class);
    });
});

describe('test role structure', function () {
    it('Should return the corrects fillable fields', function () {
        $expectedFillable = [
            'role'
        ];

        $fillableKeys = (new Role())->getFillable();

        expect($fillableKeys)->toBe($expectedFillable);
    });

    it('Should return the correct table name', function () {
        $tableName = (new Role())->getTable();

        expect($tableName)->toBe('roles');
    });

    it('Should return the correct primary key field', function () {
        $primaryKeyField = (new Role())->getKeyName();

        expect($primaryKeyField)->toBe('id');
    });

    it('Should return correct type of incrementing', function () {
        $incrementing = (new Role())->getIncrementing();

        expect($incrementing)->toBe(false);
    });

    it('Should return correct key type of primary key', function () {
        $keyType = (new Role())->getKeyType();

        expect($keyType)->toBe('string');
    });
});
