<?php
namespace Tests\Feature;

namespace Tests\Feature;

use Tests\TestCase;

class AccountTest extends TestCase
{
    // Aqui reseta tudo antes de rodar os testes
    /** @before */
    public function setUpTest(): void
    {
        parent::setUp();
        $this->postJson('/api/reset');
    }

    /** @test */
    public function it_resets_the_api()
    {
        $response = $this->postJson('/api/reset');
        $response->assertStatus(200);
    }


    /** @test */
    public function it_returns_404_for_non_existing_account_balance()
    {
        $response = $this->getJson('/api/balance?account_id=1234');
        $response->assertStatus(404)->assertExactJson([0]);
    }


    /** @test */
    public function it_creates_an_account_with_initial_balance()
    {
        $response = $this->postJson('/api/event', [
            "type" => "deposit",
            "destination" => "100",
            "amount" => 10
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    "destination" => [
                        "id" => "100",
                        "balance" => 10
                    ]
                ]);
    }


    /** @test */
    public function it_deposits_into_an_existing_account()
    {
        $this->postJson('/api/event', [
            "type" => "deposit",
            "destination" => "100",
            "amount" => 10
        ]);

        $response = $this->postJson('/api/event', [
            "type" => "deposit",
            "destination" => "100",
            "amount" => 10
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    "destination" => [
                        "id" => "100",
                        "balance" => 20
                    ]
                ]);
    }


    /** @test */
    public function it_returns_balance_of_existing_account()
    {
        $this->postJson('/api/event', [
            "type" => "deposit",
            "destination" => "100",
            "amount" => 10
        ]);

        $response = $this->getJson('/api/balance?account_id=100');

        $response->assertStatus(200)
                ->assertExactJson([10]);
    }


   /** @test */
    public function it_returns_404_when_withdrawing_from_non_existing_account()
    {
        $response = $this->postJson('/api/event', [
            "type" => "withdraw",
            "origin" => "200",
            "amount" => 10
        ]);

        $response->assertStatus(404)
                ->assertExactJson([0]);
    }

    /** @test */
    public function it_withdraws_from_an_existing_account()
    {
        $this->postJson('/api/event', [
            "type" => "deposit",
            "destination" => "100",
            "amount" => 10
        ]);

        $response = $this->postJson('/api/event', [
            "type" => "withdraw",
            "origin" => "100",
            "amount" => 5
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    "origin" => [
                        "id" => "100",
                        "balance" => 5
                    ]
                ]);
    }

    /** @test */
    public function it_transfers_money_between_existing_accounts()
    {
        $this->postJson('/api/event', [
            "type" => "deposit",
            "destination" => "100",
            "amount" => 15
        ]);

        $response = $this->postJson('/api/event', [
            "type" => "transfer",
            "origin" => "100",
            "amount" => 15,
            "destination" => "300"
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    "origin" => [
                        "id" => "100",
                        "balance" => 0
                    ],
                    "destination" => [
                        "id" => "300",
                        "balance" => 15
                    ]
                ]);
    }


    /** @test */
    public function it_returns_404_when_transferring_from_non_existing_account()
    {
        $response = $this->postJson('/api/event', [
            "type" => "transfer",
            "origin" => "200",
            "amount" => 15,
            "destination" => "300"
        ]);

        $response->assertStatus(404)
                ->assertExactJson([0]);
    }
}

