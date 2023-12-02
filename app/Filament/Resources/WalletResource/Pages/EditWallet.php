<?php

namespace App\Filament\Resources\WalletResource\Pages;

use App\Domain\User\Models\User;
use App\Domain\Wallets\Models\UserWalletModel;
use App\Domain\Wallets\UserWallet;
use App\Filament\Resources\WalletResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditWallet extends EditRecord
{
    protected static string $resource = WalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        $result = '👛 Кошелек пользователя ';
        /** @var UserWalletModel $record */
        $record = $this->getRecord();
        /** @var User $holder */
        $holder = $record->holder()->first();

        if ($holder) {
            $result .= $holder->name . ' ' . $holder->last_name;
        }

        return $result;
    }
}
