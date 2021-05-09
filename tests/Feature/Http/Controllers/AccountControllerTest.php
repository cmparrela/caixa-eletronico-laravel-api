<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestValidations;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestValidations;

    private $account;

    protected function setUp(): void
    {
        parent::setUp();
        $this->account = Account::factory()->forUser(['name' => 'Jessica Archer'])->create()->fresh();
    }

    public function testShow()
    {
        $response = $this->get("/users/{$this->account->user_id}/accounts");
        $response
            ->assertStatus(200)
            ->assertJson([$this->account->toArray()]);
    }

    public function testCreate()
    {
        $user = User::factory()->create()->fresh();
        $data = [
            'type' => Account::TYPE_CHECKING,
            'balance' => '10.00',
        ];
        $response = $this->json('POST', "/users/{$this->account->user_id}/accounts", $data);

        $response
            ->assertStatus(201)
            ->assertJsonFragment($data);
    }

    public function testCreateValidationRequired()
    {   
        $response = $this->json('POST', "/users/{$this->account->user_id}/accounts", []);
        $this->assertInvalidationFields($response, ['type'], 'required');
    }

    public function testCreateValidationEnum()
    {
        $data = ['type' => 'tipo invalido'];
        $response = $this->json('POST', "/users/{$this->account->user_id}/accounts", $data);
        $this->assertInvalidationFields($response, ['type'], 'in');
    }
    public function testCreateValidationNumeric()
    {
        $data = ['balance' => 'saldo invalido'];
        $response = $this->json('POST', "/users/{$this->account->user_id}/accounts", $data);
        $this->assertInvalidationFields($response, ['balance'], 'numeric');
    }
    public function testCreateValidationExistsUser()
    {
        $response = $this->json('POST', "/users/2000000/accounts", []);
        $this->assertInvalidationFields($response, ['user_id'], 'exists');
    }

    public function testDestroy()
    {
        $response = $this->json('DELETE', "/accounts/{$this->account->id}");
        $response->assertStatus(204);

        $model = new Account();
        $result = $model->find($this->account->id);
        $this->assertNull($result);

        $userDeleted = $model->findTrashedById($this->account->id);
        $this->assertNotNull($userDeleted);
    }
}
