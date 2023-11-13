<?php

namespace App\Domain\Wallets\Interfaces;

interface HasHolderInterface
{
    public function byHolder(int $userId);
}
