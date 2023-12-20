<?php

declare(strict_types=1);

namespace App\Domain\Сurrency\Payments\Actions;

use App\Domain\Transactions\Actions\CreateTransactionAction;
use App\Domain\Transactions\Dto\TransactionDto;
use App\Domain\User\Dto\UserDto;
use App\Domain\Wallets\Exceptions\WalletNotExistsException;
use App\Domain\Wallets\Interfaces\WalletInterface;
use App\Domain\Wallets\Services\StoreWalletService;
use App\Domain\Сurrency\Payments\Dto\CreatePaymentDto;
use App\Domain\Сurrency\RateService;
use Carbon\Carbon;
use YooKassa\Client;

final class CreatePaymentAction
{
    public function __construct(
        protected CreateTransactionAction $createTransactionAction,
        protected StoreWalletService $storeWalletService,
        protected RateService $rateService,
        protected Client $paymentClient
    )
    {
    }

    public function execute(WalletInterface $wallet, int $amount): string
    {
        $transactionId = $this->createTransactionAction->execute(new TransactionDto(
            from: $this->getStoreWallet(),
            value: $amount,
            to: $wallet,
            term_at: Carbon::now()->addHours(2)
        ));

        $price = $this->rateService->convertToRuble($amount);

        $builder = \YooKassa\Request\Payments\CreatePaymentRequest::builder();

        $builder->setConfirmation([
            'type'      => \YooKassa\Model\Payment\ConfirmationType::REDIRECT,
            'returnUrl' => 'https://merchant-site.ru/payment-return-page',
        ]);

        $builder->setAmount($price)
            ->setCurrency(\YooKassa\Model\CurrencyCode::RUB)
            ->setCapture(true)
            ->setDescription("Транзакция {$transactionId}")
            ->setMetadata([
                'transaction_id' => $transactionId,
            ]);

        $builder->setPaymentMethodData(\YooKassa\Model\Payment\PaymentMethodType::BANK_CARD);


        $request = $builder->build();

        $idempotenceKey = uniqid('', true);
        $response = $this->paymentClient->createPayment($request, $idempotenceKey);

        //получаем confirmationUrl для дальнейшего редиректа
        return $response->getConfirmation()->getConfirmationUrl();
    }

    /**
     * @throws WalletNotExistsException
     */
    protected function getStoreWallet(): \App\Domain\Wallets\StoreWallet
    {
        return $this->storeWalletService->initWallet();
    }

}
