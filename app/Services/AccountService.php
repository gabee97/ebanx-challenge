<?php

namespace App\Services;

class AccountService
{
    private string $storagePath;

    public function __construct()
    {
        $this->storagePath = storage_path('app/accounts.json');
    }

    public function getAccounts(): array
    {
        if (!file_exists($this->storagePath)) {
            return [];
        }

        return json_decode(file_get_contents($this->storagePath), true) ?? [];
    }

    public function saveAccounts(array $accounts): void
    {
        file_put_contents($this->storagePath, json_encode($accounts, JSON_PRETTY_PRINT));
    }

    public function resetAccounts(): void
    {
        $this->saveAccounts([]);
    }

    public function getBalance(string $accountId): ?int
    {
        $accounts = $this->getAccounts();
        return $accounts[$accountId] ?? null;
    }

    public function deposit(string $destination, int $amount): array
    {
        $accounts = $this->getAccounts();

        if (!isset($accounts[$destination])) {
            $accounts[$destination] = 0;
        }

        $accounts[$destination] += $amount;
        $this->saveAccounts($accounts);

        return [
            "destination" => [
                "id" => $destination,
                "balance" => $accounts[$destination]
            ]
        ];
    }

    public function withdraw(string $origin, int $amount): ?array
    {
        $accounts = $this->getAccounts();

        if (!isset($accounts[$origin]) || $accounts[$origin] < $amount) {
            return null;
        }

        $accounts[$origin] -= $amount;
        $this->saveAccounts($accounts);

        return [
            "origin" => [
                "id" => $origin,
                "balance" => $accounts[$origin]
            ]
        ];
    }

    public function transfer(string $origin, string $destination, int $amount): ?array
    {
        $accounts = $this->getAccounts();

        if (!isset($accounts[$origin]) || $accounts[$origin] < $amount) {
            return null;
        }

        $accounts[$origin] -= $amount;
        if (!isset($accounts[$destination])) {
            $accounts[$destination] = 0;
        }
        $accounts[$destination] += $amount;
        $this->saveAccounts($accounts);

        return [
            "origin" => [
                "id" => $origin,
                "balance" => $accounts[$origin]
            ],
            "destination" => [
                "id" => $destination,
                "balance" => $accounts[$destination]
            ]
        ];
    }
}
