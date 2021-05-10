<?php
namespace App\Services;

use App\Models\Transaction;
use App\Rules\WithdrawalHasMoneyBill;
use Illuminate\Support\Facades\Validator;

class TransactionService
{
    private $model;

    public function __construct(Transaction $transaction)
    {
        $this->model = $transaction;
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
            'value' => ["integer", "exclude_if:type,$deposit", new WithdrawalHasMoneyBill],
            'account_id' => 'required|exists:accounts,id,deleted_at,NULL',
        ])->validate();
    }

}
