<?php
declare(strict_types=1);

namespace App\Domain\Store\Actions;

use App\Domain\Products\Dto\ProductDto;
use App\Domain\Store\Dto\OrderDto;
use App\Domain\Store\Events\OrderCreatedEvent;
use App\Domain\Store\Exceptions\ErrorCreateOrderException;
use App\Domain\Store\Exceptions\ErrorOrderActionException;
use App\Domain\Store\Services\OrderService;
use App\Domain\Transactions\Actions\CreateTransactionAction;
use App\Domain\Transactions\Dto\TransactionDto;
use App\Domain\User\Dto\UserDto;
use App\Domain\Wallets\Services\StoreWalletService;
use App\Domain\Wallets\Services\UserWalletService;
use Illuminate\Support\Facades\DB;

final class CreateOrderAction
{
    protected OrderService $test;
    public function __construct(
        protected StoreWalletService $storeWalletService,
        protected UserWalletService $userWalletService,
        protected CreateTransactionAction $createTransactionAction,
        protected OrderService $orderService,
    ) {
    }

    /**
     * @throws \Throwable
     * @throws ErrorCreateOrderException
     */
    public function execute(
        UserDto $userDto,
        ProductDto $productDto
    ): OrderDto {
        try {
            DB::beginTransaction();

            $storeWallet = $this->storeWalletService->initWallet();
            $userWallet = $this->userWalletService->initWallet($userDto->id);

            $transactionId = $this->createTransactionAction->execute(
                new TransactionDto(
                    from: $userWallet,
                    value: $productDto->price,
                    to: $storeWallet,
                    comment: 'Оформление заказа'
                )
            );

            $orderDto = $this->orderService->create($userDto, $productDto, $transactionId);

            DB::commit();

            OrderCreatedEvent::dispatch($orderDto);

            return $orderDto;

        } catch (\Throwable $e) {
            DB::rollBack();

            throw new ErrorOrderActionException("Ошибка создания заказа. Детально: {$e->getMessage()}");
        }
    }
}
