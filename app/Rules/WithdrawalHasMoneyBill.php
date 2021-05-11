<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class WithdrawalHasMoneyBill implements Rule
{
    public function passes($attribute, $amount)
    {
        $moneyBills = [100, 50, 20];
        $remainder = $amount;

        foreach ($moneyBills as $moneyBill) {
            if ($amount % $moneyBill == 0) {
                $remainder = 0;
                break;
            }

            $remainder = $remainder % $moneyBill;
        }

        return empty($remainder);
    }

    public function message()
    {
        return 'We do not have money bill available to withdraw this amount. Money bill available: 100, 50 and 20';
    }
}
