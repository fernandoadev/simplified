<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use App\Http\Services\TransferService;
use App\Jobs\SendExternalNotification;
use Illuminate\Foundation\Testing\DatabaseMigrations;

uses(DatabaseMigrations::class);


describe('Should perform trasnfer', function () {
    it('When are all clear', function () {
        Queue::fake();

        $userFrom = User::factory()->create();
        $userInto = User::factory()->create();

        $userFrom->wallet()->create([
            'name' => 'teste',
            'balance' => 10000
        ]);
        $userInto->wallet()->create([
            'name' => 'teste 2',
            'balance' => 0
        ]);

        $transferService = new TransferService();

        $request = Request::create('/transfer', 'POST', [
            'value' => 50.00,
            'payer' => $userFrom->id,
            'payee' => $userInto->id,
        ]);

        $transferService->transfer($request);

        $this->assertEquals(5000, $userFrom->wallet->fresh()->balance);
        $this->assertEquals(5000, $userInto->wallet->fresh()->balance);
        Queue::assertPushed(SendExternalNotification::class);
    });
});


describe('Should NOT perform trasnfer', function () {
    it('When something bad happens', function () {
        Queue::fake();

        $userFrom = User::factory()->create([
            'type' => 'merchant'
        ]);
        $userInto = User::factory()->create();

        $userFrom->wallet()->create([
            'name' => 'teste',
            'balance' => 10000
        ]);
        $userInto->wallet()->create([
            'name' => 'teste 2',
            'balance' => 0
        ]);

        $transferService = new TransferService();

        $request = Request::create('/transfer', 'POST', [
            'value' => 50.00,
            'payer' => '123',
            'payee' => '1234',
        ]);

        $transferService->transfer($request);


        $this->assertEquals(10000, $userFrom->wallet->fresh()->balance);
        $this->assertEquals(0, $userInto->wallet->fresh()->balance);
        Queue::assertNotPushed(SendExternalNotification::class);
    });
});
