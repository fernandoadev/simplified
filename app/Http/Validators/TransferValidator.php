<?php

namespace App\Http\Validators;

use App\Models\User;
use Illuminate\Http\Request;
use App\Clients\AuthorizeClient;
use Illuminate\Http\JsonResponse;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\AuthorizeClientException;
use Illuminate\Validation\ValidationException;

class TransferValidator
{
    private int $code;
    private string $message;
    private ?array $data = null;

    /**
     * @param Request $request
     *
     * @return bool
     *
     * @throws ValidationException
     * @throws AuthorizeClientException
     * @throws UserNotFoundException
     */
    public function validate(Request $request): bool
    {
        try {
            $request->validate([
                'value' => 'required|numeric|gt:0',
                'payer' => 'required',
                'payee' => 'required'
            ]);

            (new AuthorizeClient())->getAuthorize();

            if (empty(User::find($request->payer))) {
                throw UserNotFoundException::withId($request->payer);
            }

            if (empty(User::find($request->payee))) {
                throw UserNotFoundException::withId($request->payee);
            }

            return true;
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();

            $this->code = 400;
            $this->message = $e->getMessage();
            $this->data = $errors->all();

            return false;
        } catch (AuthorizeClientException $e) {
            $this->code = 400;
            $this->message = $e->getMessage();

            return false;
        } catch (UserNotFoundException $e) {
            $this->code = 404;
            $this->message = $e->getMessage();

            return false;
        }
    }

    /** @return JsonResponse */
    public function getValidateReponse(): JsonResponse
    {
        return response()->api($this->code, $this->message, $this->data);
    }
}
