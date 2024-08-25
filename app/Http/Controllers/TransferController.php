<?php

namespace App\Http\Controllers;

use App\Http\Services\TransferService;
use App\Http\Validators\TransferValidator;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    private TransferService $service;

    public function __construct(TransferService $service)
    {
        $this->service = $service;
    }

    /**
     * Performs the transfer
     * 
     * @param Request $request
     */
    public function transfer(Request $request)
    {
        $validation = new TransferValidator();
        if(empty($validation->validate($request))) {
            return $validation->getValidateReponse();
        }

        return $this->service->transfer($request);
    }
}
