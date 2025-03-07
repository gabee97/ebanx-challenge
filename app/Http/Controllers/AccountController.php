<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Services\AccountService;

class AccountController extends Controller
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function reset(): Response
    {
        $this->accountService->resetAccounts();
        return response('OK', 200);
    }

    public function getBalance(Request $request): JsonResponse
    {
        $accountId = $request->query('account_id');
        $balance = $this->accountService->getBalance($accountId);

        if ($balance === null) {
            return response()->json(0, 404);
        }

        return response()->json($balance, 200);
    }

    public function postEvent(Request $request): JsonResponse
    {
        $data = $request->json()->all();

        if ($data['type'] === 'deposit') {
            return response()->json(
                $this->accountService->deposit($data['destination'], $data['amount']),
                201
            );
        }

        if ($data['type'] === 'withdraw') {
            $result = $this->accountService->withdraw($data['origin'], $data['amount']);
            if (!$result) {
                return response()->json(0, 404);
            }
            return response()->json($result, 201);
        }

        if ($data['type'] === 'transfer') {
            $result = $this->accountService->transfer($data['origin'], $data['destination'], $data['amount']);
            if (!$result) {
                return response()->json(0, 404);
            }
            return response()->json($result, 201);
        }

        return response()->json([], 400);
    }
}
