<?php

namespace App\Http\Validators;

use App\Models\User;
use App\Helpers\UserHelper;
use Illuminate\Http\Request;
use App\Clients\LoggerClient;
use App\Clients\AuthorizeClient;
use Illuminate\Http\JsonResponse;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\AuthorizeClientException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\UserNotAllowedToPerformTransfer;
use App\Exceptions\UserHasNotEnoughtBalanceException;

class TransferValidator
{
    private int $code;
    private string $message;
    private ?array $data = null;
    private AuthorizeClient $authorizeClient;
    private LoggerClient $logger;

    public function __construct(AuthorizeClient $authorizeClient, LoggerClient $logger)
    {
        $this->authorizeClient = $authorizeClient;
        $this->logger = $logger;
    }

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

            $this->authorizeClient->getAuthorize();

            $userFrom = User::with('wallet')->find($request->payer);

            if (empty($userFrom)) {
                throw UserNotFoundException::withId($request->payer);
            }

            if (empty(User::find($request->payee))) {
                throw UserNotFoundException::withId($request->payee);
            }

            if (empty(UserHelper::isCustomer($userFrom))) {
                throw UserNotAllowedToPerformTransfer::withId($userFrom->id);
            }

            if (empty(UserHelper::hasSufficientBalance($userFrom, $request->value))) {
                throw UserHasNotEnoughtBalanceException::withId($userFrom->id);
            }

            return true;
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();

            $this->code = 400;
            $this->message = $e->getMessage();
            $this->data = $errors->all();

            $this->logger->log($this->message);
            return false;
        } catch (AuthorizeClientException $e) {
            $this->code = 401;
            $this->message = $e->getMessage();

            $this->logger->log($this->message);
            return false;
        } catch (UserNotFoundException $e) {
            $this->code = 404;
            $this->message = $e->getMessage();

            $this->logger->log($this->message);
            return false;
        } catch (UserNotAllowedToPerformTransfer $e) {
            $this->code = 401;
            $this->message = $e->getMessage();

            $this->logger->log($this->message);
            return false;
        } catch (UserHasNotEnoughtBalanceException $e) {
            $this->code = 401;
            $this->message = $e->getMessage();

            $this->logger->log($this->message);
            return false;
        }
    }

    /** @return JsonResponse */
    public function getValidateReponse(): JsonResponse
    {
        return response()->api($this->code, $this->message, $this->data);
    }
}
