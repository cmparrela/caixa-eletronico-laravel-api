<?php

namespace App\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'birth_date',
        'cpf',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function findTrashedById(int $id)
    {
        return $this->onlyTrashed()->find($id);
    }
}
