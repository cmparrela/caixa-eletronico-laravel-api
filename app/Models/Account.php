<?php

namespace App\Models;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;
    use HasFactory;

    const TYPE_CHECKING = 'checking';
    const TYPE_SAVINGS = 'savings';

    protected $table = 'accounts';
    protected $fillable = ['type', 'balance', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function findTrashedById(int $id)
    {
        return $this->onlyTrashed()->find($id);
    }

    public function getByUser($userId)
    {
        return $this->where(['user_id' => $userId])->get();
    }

}
