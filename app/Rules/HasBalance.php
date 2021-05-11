<?php

namespace App\Rules;

use App\Models\Account;
use App\Services\AccountService;
use Illuminate\Contracts\Validation\Rule;

class HasBalance implements Rule
{
    private $account;

    public function __construct(AccountService $accountService, $accountId)
    {
        $this->account = $accountService->findById($accountId);
    }

    public function passes($attribute, $value)
    {
        if (!empty($this->account) && $this->account->balance >= $value) {
            return true;
        }
        return false;
    }

    public function message()
    {
        return 'Insufficient balance to perform the transaction';
    }
}
