<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $accountId = $request->get('account_id');
        if ($accountId) {
            return $this->transactionService->getByAccount($accountId);
        }

        return $this->transactionService->getAll();
    }

    public function create(Request $request)
    {
        $data = $request->only(['type', 'value', 'account_id']);
        $transaction = $this->transactionService->createNew($data);
        return response()->json($transaction, 202);
    }

}
