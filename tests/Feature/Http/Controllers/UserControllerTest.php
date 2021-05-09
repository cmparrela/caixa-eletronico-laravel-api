<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestValidations;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestValidations;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create()->fresh();
    }

    public function testIndex()
    {
        $response = $this->get('/users');
        $response
            ->assertStatus(200)
            ->assertJson([$this->user->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get("/users/{$this->user->id}");
        $response
            ->assertStatus(200)
            ->assertJson($this->user->toArray());
    }

    public function testCreate()
    {
        $data = [
            'name' => 'teste',
            'birth_date' => '1992-06-02',
            'cpf' => '28059641522',
        ];
        $response = $this->json('POST', '/users', $data);

        $response
            ->assertStatus(201)
            ->assertJsonFragment($data);
    }

    public function testCreateValidationRequired()
    {
        $response = $this->json('POST', '/users', []);
        $this->assertInvalidationFields($response, ['name'], 'required');
    }

    public function testCreateValidationMax()
    {
        $data = [
            'name' => str_repeat('a', 101),
            'cpf' => str_repeat('a', 12),
        ];
        $response = $this->json('POST', '/users', $data);
        $this->assertInvalidationFields($response, ['name'], 'max.string', ['max' => 100]);
        $this->assertInvalidationFields($response, ['cpf'], 'max.string', ['max' => 11]);
    }

    public function testCreateValidationCpf()
    {
        $data = [
            'name' => 'teste',
            'cpf' => 'cpf invalido',
        ];
        $response = $this->json('POST', '/users', $data);
        $response->assertStatus(422);
        $response->assertJsonFragment(["CPF inválido"]);
    }

    public function testCreateValidationDate()
    {
        $data = [
            'name' => 'teste',
            'birth_date' => 'data invalida',
        ];
        $response = $this->json('POST', '/users', $data);
        $this->assertInvalidationFields($response, ['birth_date'], 'date');
    }

    public function testUpdate()
    {
        $data = [
            'name' => 'fulano',
        ];
        $response = $this->json('PUT', "/users/{$this->user->id}", $data);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function testDestroy()
    {
        $response = $this->json('DELETE', "/users/{$this->user->id}");
        $response->assertStatus(204);

        $model = new User();
        $user = $model->find($this->user->id);
        $this->assertNull($user);

        $userDeleted = $model->findTrashedById($this->user->id);
        $this->assertNotNull($userDeleted);
    }

}
