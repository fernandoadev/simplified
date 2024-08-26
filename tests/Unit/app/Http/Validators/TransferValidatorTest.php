<?php

use Mockery as M;
use App\Models\User;
use App\Clients\LogerClient;
use Illuminate\Http\Request;
use App\Clients\AuthorizeClient;
use Illuminate\Support\Facades\Http;
use App\Http\Validators\TransferValidator;
use Illuminate\Foundation\Testing\DatabaseMigrations;

uses(DatabaseMigrations::class);


describe('Should pass', function () {
    it('When are all clear', function () {
        $mockLoger = M::mock(LogerClient::class);
        $mockLoger->shouldNotReceive('log');
        
        assert($mockLoger instanceof LogerClient);

        Http::shouldReceive('withHeaders->get')
            ->once()
            ->andReturn(M::mock([
                'json' => ['data' => ['authorization' => true]]
            ]));

        $client = new AuthorizeClient();

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

        $request = Request::create('/transfer', 'POST', [
            'value' => 10.00,
            'payer' => $userFrom->id,
            'payee' => $userInto->id,
        ]);

        $validator = new TransferValidator($client, $mockLoger);

        $validated = $validator->validate($request);

        $this->assertTrue($validated);
    });
});

describe('Should NOT pass', function () {
    it('When authorize return false', function () {
        $mockLoger = M::mock(LogerClient::class);
        $mockLoger->shouldReceive('log')->once();
        
        assert($mockLoger instanceof LogerClient);

        Http::shouldReceive('withHeaders->get')
            ->once()
            ->andReturn(M::mock([
                'json' => ['data' => ['authorization' => false]]
            ]));

        $client = new AuthorizeClient();

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

        $request = Request::create('/transfer', 'POST', [
            'value' => 10.00,
            'payer' => $userFrom->id,
            'payee' => $userInto->id,
        ]);

        $validator = new TransferValidator($client, $mockLoger);

        $validated = $validator->validate($request);

        $this->assertFalse($validated);
    });

    it('When userfrom not found', function () {
        $mockLoger = M::mock(LogerClient::class);
        $mockLoger->shouldReceive('log')->once();
        
        assert($mockLoger instanceof LogerClient);

        Http::shouldReceive('withHeaders->get')
            ->once()
            ->andReturn(M::mock([
                'json' => ['data' => ['authorization' => true]]
            ]));

        $client = new AuthorizeClient();

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

        $request = Request::create('/transfer', 'POST', [
            'value' => 10.00,
            'payer' => '1234',
            'payee' => $userInto->id,
        ]);

        $validator = new TransferValidator($client, $mockLoger);

        $validated = $validator->validate($request);

        $this->assertFalse($validated);
    });

    it('When userinto not found', function () {
        $mockLoger = M::mock(LogerClient::class);
        $mockLoger->shouldReceive('log')->once();
        
        assert($mockLoger instanceof LogerClient);

        Http::shouldReceive('withHeaders->get')
            ->once()
            ->andReturn(M::mock([
                'json' => ['data' => ['authorization' => true]]
            ]));

        $client = new AuthorizeClient();

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

        $request = Request::create('/transfer', 'POST', [
            'value' => 10.00,
            'payer' => $userFrom->id,
            'payee' => '2134',
        ]);

        $validator = new TransferValidator($client, $mockLoger);

        $validated = $validator->validate($request);

        $this->assertFalse($validated);
    });

    it('When userfrom has no sufficient balance', function () {
        $mockLoger = M::mock(LogerClient::class);
        $mockLoger->shouldReceive('log')->once();
        
        assert($mockLoger instanceof LogerClient);

        Http::shouldReceive('withHeaders->get')
            ->once()
            ->andReturn(M::mock([
                'json' => ['data' => ['authorization' => true]]
            ]));

        $client = new AuthorizeClient();

        $userFrom = User::factory()->create();
        $userInto = User::factory()->create();

        $userFrom->wallet()->create([
            'name' => 'teste',
            'balance' => 10
        ]);
        $userInto->wallet()->create([
            'name' => 'teste 2',
            'balance' => 0
        ]);

        $request = Request::create('/transfer', 'POST', [
            'value' => 30.00,
            'payer' => $userFrom->id,
            'payee' => $userInto->id,
        ]);

        $validator = new TransferValidator($client, $mockLoger);

        $validated = $validator->validate($request);

        $this->assertFalse($validated);
    });
});
