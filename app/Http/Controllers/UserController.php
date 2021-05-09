<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return $this->userService->getAll();
    }

    public function show($id)
    {
        return $this->userService->findById($id);
    }

    public function create(Request $request)
    {
        $data = $request->only(['name', 'birth_date', 'cpf']);
        $user = $this->userService->createNew($data);
        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['name', 'birth_date', 'cpf']);
        return $this->userService->updateById($id, $data);
    }

    public function destroy($id)
    {
        $this->userService->deleteById($id);
        return response()->noContent();
    }
}
