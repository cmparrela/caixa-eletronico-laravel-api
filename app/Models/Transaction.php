<?php

namespace App\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    use HasFactory;

    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAWAL = 'withdrawal';

    protected $table = 'transactions';

    protected $fillable = ['type', 'value', 'account_id'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function findTrashedById(int $id)
    {
        return $this->onlyTrashed()->find($id);
    }

    public function getByAccount(int $accountId)
    {
        return $this->where(['account_id' => $accountId])->get();
    }
}
