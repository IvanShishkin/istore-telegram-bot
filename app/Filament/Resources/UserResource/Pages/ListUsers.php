<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Domain\User\Dto\RegistrationDto;
use App\Domain\User\Models\User;
use App\Domain\User\Services\UserAuthService;
use App\Domain\Wallets\Models\UserWalletModel;
use App\Domain\Wallets\Services\ChangeBalanceService;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('register_user')
                ->label('Зарегистрировать')
                ->color('success')
                ->form([
                    TextInput::make('name'),
                    TextInput::make('last_name'),
                    TextInput::make('email'),
                ])
                ->action(function (
                    array $data,
                    UserAuthService $authService
                ) {
                    $data['active'] = false;

                    $registerDto = RegistrationDto::from($data);
                    $authService->register($registerDto);
                })
        ];
    }
}
