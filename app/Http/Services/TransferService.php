<?php

namespace App\Http\Services;

use App\Models\User;
use App\Helpers\UserHelper;
use Illuminate\Http\Request;
use App\Clients\NotifyClient;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendExternalNotification;
use Illuminate\Http\JsonResponse;
use Throwable;

class TransferService
{
    /**
     * Performs the transfer
     *
     * @param Request $request
     */
    public function transfer(Request $request): JsonResponse
    {
        try {
            $userFrom = User::with('wallet')->find($request->payer);
            $userInto = User::with('wallet')->find($request->payee);

            DB::beginTransaction();

            $userFrom->wallet->balance = $userFrom->wallet->balance - UserHelper::floatToCents($request->value);
            $userFrom->wallet->save();

            $userInto->wallet->balance = $userInto->wallet->balance + UserHelper::floatToCents($request->value);
            $userInto->wallet->save();

            SendExternalNotification::dispatch(new NotifyClient());
            DB::commit();
            return response()->api(200, 'Transfer has perform with success.');
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->api(500, sprintf('An unexpected error occurred: %s', $e->getMessage()));
        }
    }
}
