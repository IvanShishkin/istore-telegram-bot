<?php

namespace App\Domain\Wallets\Repositories;

use App\Domain\Wallets\Dto\WalletLogDto;
use Illuminate\Support\Collection;

interface WalletLogRepositoryInterface
{
    public function write(WalletLogDto $dto): void;

    /**
     * @param string $walletNumber
     * @return Collection<WalletLogDto>
     */
    public function get(string $walletNumber): Collection;
}
