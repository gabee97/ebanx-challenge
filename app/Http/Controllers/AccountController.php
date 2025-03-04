<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    private static $accounts = [];

    public function reset(): JsonResponse
    {
        self::$accounts = [];
        return response()->json([], 200);
    }

    public function getBalance(Request $request): JsonResponse
    {
        $accountId = $request->query('account_id');

        if (!isset(self::$accounts[$accountId])) {
            return response()->json(0, 404);
        }

        return response()->json(self::$accounts[$accountId], 200);
    }

    public function postEvent(Request $request): JsonResponse
    {
        $data = $request->all();

        if ($data['type'] === 'deposit') {
            $destination = $data['destination'];
            $amount = $data['amount'];

            // Se a conta não existir, cria com saldo inicial
            if (!isset(self::$accounts[$destination])) {
                self::$accounts[$destination] = 0;
            }

            // Adiciona corretamente o saldo
            self::$accounts[$destination] += $amount;

            return response()->json([
                "destination" => [
                    "id" => (string) $destination,
                    "balance" => self::$accounts[$destination]
                ]
            ], 201);
        }

        if ($data['type'] === 'withdraw') {
            $origin = $data['origin'];
            $amount = $data['amount'];

            // Se a conta não existir ou saldo for insuficiente, erro
            if (!isset(self::$accounts[$origin]) || self::$accounts[$origin] < $amount) {
                return response()->json(0, 404);
            }

            // Subtrai o saldo corretamente
            self::$accounts[$origin] -= $amount;

            return response()->json([
                "origin" => [
                    "id" => (string) $origin,
                    "balance" => self::$accounts[$origin]
                ]
            ], 201);
        }

        if ($data['type'] === 'transfer') {
            $origin = $data['origin'];
            $destination = $data['destination'];
            $amount = $data['amount'];

            // Se a conta de origem não existir ou não tiver saldo suficiente, erro
            if (!isset(self::$accounts[$origin]) || self::$accounts[$origin] < $amount) {
                return response()->json(0, 404);
            }

            // Se a conta de destino não existir, cria
            if (!isset(self::$accounts[$destination])) {
                self::$accounts[$destination] = 0;
            }

            // Faz a transferência corretamente
            self::$accounts[$origin] -= $amount;
            self::$accounts[$destination] += $amount;

            return response()->json([
                "origin" => [
                    "id" => (string) $origin,
                    "balance" => self::$accounts[$origin]
                ],
                "destination" => [
                    "id" => (string) $destination,
                    "balance" => self::$accounts[$destination]
                ]
            ], 201);
        }

        return response()->json(["error" => "Invalid event type"], 400);
    }
}
