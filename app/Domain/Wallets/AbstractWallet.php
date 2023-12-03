<?php

declare(strict_types=1);

namespace App\Domain\Wallets;

use App\Domain\Wallets\Enums\WalletTypesEnum;
use App\Domain\Wallets\Exceptions\FailedSaveException;
use App\Domain\Wallets\Exceptions\IncreaseBalanceException;
use App\Domain\Wallets\Exceptions\InitializationException;
use App\Domain\Wallets\Exceptions\ReduceBalanceException;
use App\Domain\Wallets\Interfaces\WalletInterface;

abstract class AbstractWallet implements WalletInterface
{
    protected ?WalletTypesEnum $type = null;

    public function __construct(protected string $number)
    {
    }

    /**
     * @inheritDoc
     * @throws InitializationException
     * @throws FailedSaveException
     * @throws IncreaseBalanceException
     */
    public function increase(int $value): void
    {
        if ($value < 1) {
            throw new IncreaseBalanceException('Не допустимое значение для увеличения баланса');
        }

        $model = $this->init($this->getNumber());
        $model->balance += $value;

        if (!$model->save()) {
            throw new FailedSaveException('Ошибка сохранения счета');
        }
    }

    /**
     * @param int $value
     * @throws FailedSaveException
     * @throws InitializationException
     * @throws ReduceBalanceException
     */
    public function reduce(int $value): void
    {
        $model = $this->init($this->getNumber());
        $currentValue = $model->balance;

        if ($value > $currentValue) {
            throw new ReduceBalanceException('Баланс меньше переданного значения');
        }

        $model->balance = $currentValue - $value;

        if (!$model->save()) {
            throw new FailedSaveException('Ошибка сохранения счета');
        }
    }

    /**
     * @throws InitializationException
     */
    abstract public function init(string $uuid);

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }


    /**
     * @throws InitializationException
     */
    public function balance(): int
    {
        $model = $this->init($this->getNumber());

        return $model->balance;
    }

    /**
     * @return WalletTypesEnum|null
     */
    public function getType(): ?WalletTypesEnum
    {
        return $this->type;
    }
}
