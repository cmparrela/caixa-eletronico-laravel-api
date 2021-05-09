<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserService extends BasicCrudService implements CrudServiceInterface
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function validateStore(array $attributes)
    {
        Validator::make($attributes, [
            'name' => 'required|max:100',
            'cpf' => 'max:11|cpf',
            'birth_date' => 'date',
        ])->validate();
    }
}
