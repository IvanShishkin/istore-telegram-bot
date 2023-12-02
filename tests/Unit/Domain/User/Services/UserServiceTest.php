<?php
declare(strict_types=1);

namespace Domain\User\Services;

use App\Domain\User\Dto\UserDto;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Models\User;
use App\Domain\User\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected UserService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(UserService::class);
    }

    public function testById()
    {
        $createdUser = User::factory(1)->create()->first();

        $get = $this->service->byId($createdUser->id);

        $this->assertIsObject($get, UserDto::class);
    }

    public function testByIdNotFound()
    {
        $this->expectException(UserNotFoundException::class);
        $this->service->byId(fake()->randomNumber());
    }

    public function testExistsByEmail()
    {
        $email = 'test@test.test';

        User::factory(1)->create([
            'email' => $email,
        ])->first();

        $exists = $this->service->existsByEmail($email);

        $this->assertTrue($exists);
    }

    public function testExistsExternalId()
    {
        $model = User::factory(1)->create()->first();

        $exists = $this->service->existsByExternalId($model->external_id);

        $this->assertTrue($exists);
    }
}
