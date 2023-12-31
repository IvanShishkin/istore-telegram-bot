<?php

namespace Domain\Transactions\Services;

use App\Domain\Transactions\Dto\TransactionDto;
use App\Domain\Transactions\Enums\TransactionDirectionEnum;
use App\Domain\Transactions\Enums\TransactionStatusEnum;
use App\Domain\Transactions\Exceptions\ErrorApplyTransactionException;
use App\Domain\Transactions\Exceptions\IncorrectStatusException;
use App\Domain\Transactions\Exceptions\TransactionNotFoundException;
use App\Domain\Transactions\Models\Transaction;
use App\Domain\Transactions\Models\TransactionItem;
use App\Domain\Transactions\Services\TransactionService;
use App\Domain\Wallets\Enums\WalletTypesEnum;
use App\Domain\Wallets\Interfaces\WalletInterface;
use App\Domain\Wallets\StoreWallet;
use App\Domain\Wallets\UserWallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use Tests\Unit\Domain\Traits\WalletMockTrait;
use function PHPUnit\Framework\assertEquals;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;
    use WalletMockTrait;

    protected TransactionService $service;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->service = \App::make(TransactionService::class);
    }

    protected function mockTransaction(TransactionStatusEnum $status = TransactionStatusEnum::NEW): Transaction
    {
        /** @var Transaction $transaction */
        $transaction = Transaction::factory()->create([
            'status' => $status
        ])->first();

        TransactionItem::factory()->create([
            'transaction_id' => $transaction->id
        ]);

        return $transaction;
    }

    /**
     * @param string $transactionId
     * @return Transaction|null
     */
    protected function getTransactionState(string $transactionId): ?Transaction
    {
        return Transaction::where(['id' => $transactionId])->first();
    }

    /**
     * @param string $transactionId
     * @return TransactionItem|null
     */
    protected function getFromTransactionItemState(string $transactionId): ?TransactionItem
    {
        return TransactionItem::where(['transaction_id' => $transactionId, 'direction' => TransactionDirectionEnum::FROM])->first();
    }

    /**
     * @param string $transactionId
     * @return TransactionItem|null
     */
    protected function getToTransactionItemState(string $transactionId): ?TransactionItem
    {
        return TransactionItem::where(['transaction_id' => $transactionId, 'direction' => TransactionDirectionEnum::TO])->first();
    }

    public function testCreate()
    {
        $userWalletModel = $this->mockUserWalletModel();
        $storeWalletModel = $this->mockStoreWalletModel();

        $dto = new TransactionDto(
            from: new UserWallet($userWalletModel->number),
            value: 100,
            to: new StoreWallet($storeWalletModel->number)
        );

        $transactionId = $this->service->create($dto);

        $currentTransactionState = $this->getTransactionState($transactionId);
        $fromTransactionState = $this->getFromTransactionItemState($transactionId);
        $toTransactionState = $this->getToTransactionItemState($transactionId);

        $this->assertEquals(TransactionStatusEnum::NEW, $currentTransactionState->status);
        $this->assertEquals(100, $currentTransactionState->value);

        $this->assertNotNull($fromTransactionState);
        $this->assertEquals($userWalletModel->number, $fromTransactionState->wallet_number);
        $this->assertEquals(WalletTypesEnum::USER, $fromTransactionState->type);

        $this->assertNotNull($toTransactionState);
        $this->assertEquals($storeWalletModel->number, $toTransactionState->wallet_number);
        $this->assertEquals(WalletTypesEnum::STORE, $toTransactionState->type);
    }

    public function createWithOutTo(): void
    {
        $userWalletModel = $this->mockUserWalletModel();

        $dto = new TransactionDto(
            from: new UserWallet($userWalletModel->number),
            value: 100,
        );

        $transactionId = $this->service->create($dto);

        $currentTransactionState = $this->getTransactionState($transactionId);
        $fromTransactionState = $this->getFromTransactionItemState($transactionId);
        $toTransactionState = $this->getToTransactionItemState($transactionId);

        $this->assertEquals(TransactionStatusEnum::NEW, $currentTransactionState->status);
        $this->assertEquals(100, $currentTransactionState->value);

        $this->assertNotNull($fromTransactionState);
        $this->assertEquals($userWalletModel->number, $fromTransactionState->wallet_number);
        $this->assertEquals(WalletTypesEnum::USER, $fromTransactionState->type);

        $this->assertEquals(null, $toTransactionState);
    }

    public function testCancel()
    {
        $createdTransaction = $this->mockTransaction();

        $transactionDto = $this->service->cancel($createdTransaction->id);

        $state = $this->getTransactionState($createdTransaction->id);

        $this->assertEquals(TransactionStatusEnum::CLOSED, $transactionDto->status);
        $this->assertEquals(TransactionStatusEnum::CLOSED, $state->status);
    }

    public function testCancelNotFound()
    {
        $this->expectException(TransactionNotFoundException::class);

        $this->service->cancel(Uuid::uuid4()->toString());
    }

    public function testCancelIncorrectStatus()
    {
        $createdTransaction = $this->mockTransaction(TransactionStatusEnum::COMPLETED);
        $this->expectException(IncorrectStatusException::class);
        $this->service->cancel($createdTransaction->id);

        $createdTransaction = $this->mockTransaction(TransactionStatusEnum::CLOSED);
        $this->expectException(IncorrectStatusException::class);
        $this->service->cancel($createdTransaction->id);
    }


    public function testApply()
    {
        $createdTransaction = $this->mockTransaction();

        $walletModel = $this->mockUserWalletModel();
        $toWallet = new UserWallet($walletModel->number);

        $result = $this->service->apply($createdTransaction->id, $toWallet);

        $state = $this->getTransactionState($createdTransaction->id);

        $this->assertEquals(TransactionStatusEnum::COMPLETED, $result->status);
        $this->assertIsObject($result->to, WalletInterface::class);
        $this->assertEquals(TransactionStatusEnum::COMPLETED, $state->status);
    }

    public function testApplyWithTo()
    {
        $createdTransaction = $this->mockTransaction();

        // создадим получателя транзакции
        TransactionItem::factory()->create([
            'direction' => TransactionDirectionEnum::TO,
            'transaction_id' => $createdTransaction->id
        ]);

        $result = $this->service->apply($createdTransaction->id);

        $state = $this->getTransactionState($createdTransaction->id);

        $this->assertEquals(TransactionStatusEnum::COMPLETED, $result->status);
        $this->assertEquals(TransactionStatusEnum::COMPLETED, $state->status);
    }

    public function testApplyError()
    {
        $createdTransaction = $this->mockTransaction();

        $this->expectException(ErrorApplyTransactionException::class);

        $this->service->apply($createdTransaction->id);
    }

    public function testApplyIncorrectStatus()
    {
        $createdTransaction = $this->mockTransaction(TransactionStatusEnum::COMPLETED);
        $this->expectException(IncorrectStatusException::class);
        $this->service->apply($createdTransaction->id);

        $createdTransaction = $this->mockTransaction(TransactionStatusEnum::CLOSED);
        $this->expectException(IncorrectStatusException::class);
        $this->service->apply($createdTransaction->id);
    }

    public function testApplyNotFound()
    {
        $this->expectException(TransactionNotFoundException::class);

        $this->service->apply(Uuid::uuid4()->toString());
    }
}
