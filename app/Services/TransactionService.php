<?php
namespace App\Services;

use App\Jobs\TransactionJob;
use App\Models\Transaction;
use App\Rules\HasBalance;
use App\Rules\WithdrawalHasMoneyBill;
use Illuminate\Support\Facades\Validator;

class TransactionService
{
    protected $model;
    protected $accountService;

    public function __construct(Transaction $transaction, AccountService $accountService)
    {
        $this->model = $transaction;
        $this->accountService = $accountService;
    }

    public function getByAccount(int $accountId)
    {
        return $this->model->getByAccount($accountId);
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function createNew(array $attributes)
    {
        $this->validateStore($attributes);
        return $this->model->create($attributes)->fresh();
    }

    protected function validateStore(array $attributes)
    {
        $deposit = Transaction::TYPE_DEPOSIT;
        $withdrawal = Transaction::TYPE_WITHDRAWAL;

        Validator::make($attributes, [
            'type' => "required|in:$deposit,$withdrawal",
            'value' => ["integer"],
            'account_id' => 'required|exists:accounts,id,deleted_at,NULL',
        ])->validate();

        Validator::make($attributes, [
            'value' => ["exclude_if:type,$deposit", new WithdrawalHasMoneyBill, new HasBalance($this->accountService, $attributes['account_id'])],
        ])->validate();

    }

}
