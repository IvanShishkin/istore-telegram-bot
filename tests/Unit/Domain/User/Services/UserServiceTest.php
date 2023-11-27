<?php

namespace Domain\User\Services;

use App\Domain\User\Dto\RegistrationDto;
use App\Domain\User\Dto\UserDto;
use App\Domain\User\Exception\AlreadyExistsException;
use App\Domain\User\Exception\RegistrationConfirmException;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Models\User;
use App\Domain\User\Services\UserAuthService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;


class UserServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected UserAuthService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(UserAuthService::class);
    }

    protected function mockRegisterDto(): RegistrationDto
    {
        return new RegistrationDto(
            name: fake()->firstName,
            last_name: fake()->lastName,
            email: fake()->email,
            active: false
        );
    }

    public function testRegisterSuccess()
    {
        $dto = $this->mockRegisterDto();

        $userDto = $this->service->register($dto);

        $this->assertIsObject($userDto, UserDto::class);
    }

    public function testRegisterAlreadyExist()
    {
        $testEmail = 'example@example.ru';
        User::factory(1)->create(['email' => $testEmail])->first();

        $dto = new RegistrationDto(
            name: fake()->firstName,
            last_name: fake()->lastName,
            email: $testEmail,
            active: false
        );

        $this->expectException(AlreadyExistsException::class);
        $this->service->register($dto);
    }

    public function testRegistrationConfirmation()
    {
        $registerUserDto = $this->service->register($this->mockRegisterDto());
        $confirmToken = $registerUserDto->getConfirmToken();
        $externalId = 123456;

        $confirmUserDto = $this->service->registrationConfirmation($confirmToken, $externalId);

        $this->assertEquals($confirmUserDto->getExternalId(), $externalId);
        $this->assertTrue($confirmUserDto->isActive());
    }

    public function testRegistrationConfirmationInvalidToken()
    {
        $this->expectException(RegistrationConfirmException::class);

        $this->service->registrationConfirmation(Str::random(8));
    }

    public function testAuthByExternalId()
    {
        $externalId = 12345678;
        $createdUser = User::factory(1)->create([
            'active' => true,
            'external_id' => $externalId
        ])->first();

        $this->service->authByExternalId($externalId);

        $this->assertAuthenticatedAs($createdUser);
    }

    public function testAuthByExternalIdNotFound()
    {
        $externalId = 12345678;

        $this->expectException(UserNotFoundException::class);
        $this->service->authByExternalId($externalId);
    }

    public function testAuthByExternalIdNotActiveUser()
    {
        $externalId = 12345678;
        User::factory(1)->create([
            'active' => false,
            'external_id' => $externalId
        ])->first();

        $this->expectException(UserNotFoundException::class);
        $this->service->authByExternalId($externalId);
    }

    public function testCheckExistsByEmail()
    {
        $email = 'test@test.test';
        User::factory(1)->create([
            'email' => $email,
        ])->first();

        $exists = $this->service->checkExistsByEmail($email);

        $this->assertTrue($exists);
    }
}
