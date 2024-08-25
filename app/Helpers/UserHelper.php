<?php

namespace App\Helpers;

use App\Enums\UserTypeEnum;
use App\Models\User;

class UserHelper
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public static function isCustomer(User $user): bool
    {
        if ($user->type != UserTypeEnum::CUSTOMER) {
            return false;
        }

        return true;
    }

    /**
     * @param User $user
     * @param float $amount
     *
     * @return bool
     */
    public static function hasSufficientBalance(User $user, float $value): bool
    {
        $valueInCents = self::floatToCents($value);

        $wallet = $user->wallet()->first();

        if (empty($wallet) || $wallet->balance < $valueInCents) {
            return false;
        }

        return true;
    }


    /**
     * @param float $value
     *
     * @return int
     */
    public static function floatToCents(float $value): int
    {
        $roundedValue = round($value * 100);

        return (int) $roundedValue;
    }

    /**
     * @param int $cents
     *
     * @return float
     */
    public static function centsToFloat(int $cents): float
    {
        return $cents / 100.0;
    }
}
