<?php
declare(strict_types=1);

namespace App\Domain\Wallets\Repositories;

use App\Domain\Wallets\Dto\WalletLogDto;
use App\Domain\Wallets\Models\WalletLog;
use Illuminate\Support\Collection;

class WalletLogRepository
{
    public function write(WalletLogDto $dto): void
    {
        WalletLog::create($dto->toArray());
    }

    /**
     * @param string $walletNumber
     * @return Collection<WalletLogDto>
     */
    public function get(string $walletNumber): Collection
    {
        $get = WalletLog::where(['number' => $walletNumber])->get();

        return $get->map(fn($item) => WalletLogDto::from($item));
    }
}
