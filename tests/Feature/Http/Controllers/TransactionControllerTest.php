<?php

namespace Tests\Feature\Http\Controllers;

use App\Jobs\TransactionJob;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Tests\Traits\TestValidations;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestValidations;

    private $transaction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transaction = $this->createTransaction();
    }

    private function createTransaction()
    {
        $account = Account::factory()->forUser()->create();
        return Transaction::factory()->for($account)->create()->fresh();
    }

    public function testIndex()
    {
        $response = $this->get('/transactions');
        $response
            ->assertStatus(200)
            ->assertJson([$this->transaction->toArray()]);
    }

    public function testIndexFilteredByAccount()
    {
        $transaction = $this->createTransaction();
        $response = $this->get("/transactions?account_id={$transaction->account_id}");
        $response
            ->assertStatus(200)
            ->assertJson([$transaction->toArray()]);
    }

    public function testCreateDeposit()
    {
        $data = [
            'type' => Transaction::TYPE_DEPOSIT,
            'value' => 10,
            'account_id' => $this->transaction->account_id,
        ];
        $response = $this->json('POST', "/transactions", $data);

        $data['value'] = number_format($data['value'], 2);
        $response
            ->assertStatus(202)
            ->assertJsonFragment(['Transaction was sent to queue']);
    }

    public function testCreateWithdrawalAndJobDispatche()
    {
        Queue::fake();

        $data = [
            'type' => Transaction::TYPE_WITHDRAWAL,
            'value' => 60,
            'account_id' => $this->transaction->account_id,
        ];
        $response = $this->json('POST', "/transactions", $data);

        $data['value'] = number_format($data['value'], 2);
        $response
            ->assertStatus(202)
            ->assertJsonFragment(['Transaction was sent to queue']);

        Queue::assertPushed(TransactionJob::class);

    }

    public function testCreateValidationRequired()
    {
        $response = $this->json('POST', "/transactions", []);
        $this->assertInvalidationFields($response, ['type', 'account_id'], 'required');
    }

    public function testCreateValidationEnum()
    {
        $data = ['type' => 'tipo invalido'];
        $response = $this->json('POST', "/transactions", $data);
        $this->assertInvalidationFields($response, ['type'], 'in');
    }

    public function testCreateValidationInteger()
    {
        $data = ['value' => 10.20];
        $response = $this->json('POST', "/transactions", $data);
        $this->assertInvalidationFields($response, ['value'], 'integer');
    }

    public function testCreateValidationExistsAccount()
    {
        $data = ['account_id' => 'conta invalido'];
        $response = $this->json('POST', "/transactions", $data);
        $this->assertInvalidationFields($response, ['account_id'], 'exists');
    }

    public function testCreateValidationHasMoneyBill()
    {
        $data = [
            'type' => Transaction::TYPE_WITHDRAWAL,
            'value' => 10,
            'account_id' => $this->transaction->account_id,
        ];
        $this->assertDontHasMoneyBill($data);

        $data['value'] = 155;
        $this->assertDontHasMoneyBill($data);
    }

    private function assertDontHasMoneyBill($data)
    {
        $response = $this->json('POST', "/transactions", $data);
        $response->assertStatus(422);
        $response->assertJsonFragment(['We do not have money bill available to withdraw this amount. Money bill available: 100, 50 and 20']);
    }

    public function testCreateValidationInsufficientBalance()
    {
        $data = [
            'type' => Transaction::TYPE_WITHDRAWAL,
            'value' => (int) $this->transaction->account->balance + 10,
            'account_id' => $this->transaction->account_id,
        ];
        $response = $this->json('POST', "/transactions", $data);
        $response->assertStatus(422);
        $response->assertJsonFragment(['Insufficient balance to perform the transaction']);
    }

}
