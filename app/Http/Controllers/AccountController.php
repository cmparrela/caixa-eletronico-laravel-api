<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function show($userId)
    {
        return $this->accountService->getByUser($userId);
    }

    public function create(Request $request, $userId)
    {
        $data = $request->only(['type', 'balance']);
        $user = $this->accountService->createNewAccount($data, $userId);
        return response()->json($user, 201);
    }

    public function destroy($id)
    {
        $this->accountService->deleteById($id);
        return response()->noContent();
    }
}
