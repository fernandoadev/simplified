<?php

namespace App\Http\Controllers;

use App\Http\Services\TransferService;
use App\Http\Validators\TransferValidator;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0",
 *     description="API documentation"
 * )
 */
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
     * 
     * @OA\Post(
     *     path="/api/transfer",
     *     summary="Perform transfer between users",
     *     tags={"Transfer"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"value", "payer", "payee"},
     *             @OA\Property(property="value", type="number", format="float", example=150.75, description="Amount to transfer"),
     *             @OA\Property(property="payer", type="string", example="f30dab29-43aa-49b8-9bc5-d23108129166", description="Payer ID"),
     *             @OA\Property(property="payee", type="string", example="f0d8cb9f-5e5c-4c0c-b7e2-b6b954cfb406", description="Payee ID")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *     ),
     * )
     */
    public function transfer(Request $request)
    {
        $validation = new TransferValidator();
        if (empty($validation->validate($request))) {
            return $validation->getValidateReponse();
        }

        return $this->service->transfer($request);
    }
}
