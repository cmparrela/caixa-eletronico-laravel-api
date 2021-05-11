<?php

namespace App\Jobs;

use App\Models\Transaction;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaction;
    protected $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function handle(Transaction $transaction)
    {
        try {
            DB::beginTransaction();
            $transaction = $transaction->create($this->attributes)->fresh();
            $transaction->account->balance -= $this->attributes['value'];
            $transaction->push();

            DB::commit();
            Log::info('Transação criada com sucesso', $transaction->toArray());

        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
        }

    }
}
