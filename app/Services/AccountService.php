<?php
namespace App\Services;

use App\Models\Account;
use Illuminate\Support\Facades\Validator;

class AccountService
{
    protected $model;

    public function __construct(Account $account)
    {
        $this->model = $account;
    }

    public function getByUser(int $userId)
    {
        return $this->model->getByUser($userId);
    }

    public function createNewAccount(array $attributes, int $userId)
    {
        $attributes['user_id'] = $userId;
        $this->validateStore($attributes);
        return $this->model->create($attributes)->fresh();
    }

    protected function validateStore(array $attributes)
    {
        $checking = Account::TYPE_CHECKING;
        $savings = Account::TYPE_SAVINGS;

        Validator::make($attributes, [
            'type' => "required|in:$checking,$savings",
            'balance' => 'numeric',
            'user_id' => 'required|exists:users,id,deleted_at,NULL',
        ])->validate();
    }

    public function deleteById(int $id)
    {
        $object = $this->model->find($id);
        if ($object) {
            return $object->delete();
        }
        return false;
    }
}
