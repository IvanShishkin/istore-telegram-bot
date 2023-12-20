<?php

declare(strict_types=1);

namespace App\Domain\Сurrency\Payments;

use App\Domain\Сurrency\Payments\Dto\CreatePaymentDto;
use App\Domain\Сurrency\Payments\Dto\PaymentDto;
use App\Domain\Сurrency\Payments\Enums\PaymentStatusEnum;
use App\Domain\Сurrency\Payments\Exceptions\ErrorCreatePaymentException;
use App\Domain\Сurrency\Payments\Models\Payment;
use YooKassa\Client;

final class PaymentService
{
    public function __construct(protected Client $paymentClient)
    {
    }

    /**
     * @param CreatePaymentDto $dto
     * @return string
     * @throws ErrorCreatePaymentException
     * @throws \Throwable
     */
    public function create(CreatePaymentDto $dto): string
    {
        try {
            \DB::beginTransaction();

            $paymentRecord = Payment::create([
                'status' => PaymentStatusEnum::NEW,
                'amount' => $dto->amount,
                'transaction_id' => $dto->transactionId
            ]);

            $builder = \YooKassa\Request\Payments\CreatePaymentRequest::builder();

            $builder->setConfirmation([
                'type'      => \YooKassa\Model\Payment\ConfirmationType::REDIRECT,
                'returnUrl' => 'https://merchant-site.ru/payment-return-page',
            ]);

            $builder->setAmount($dto->amount)
                ->setCurrency(\YooKassa\Model\CurrencyCode::RUB)
                ->setCapture(true)
                ->setDescription("Транзакция {$dto->transactionId}")
                ->setMetadata([
                    'payment_id' => $paymentRecord->id,
                    'transaction_id' => $dto->transactionId,
                ]);

            $builder->setPaymentMethodData(\YooKassa\Model\Payment\PaymentMethodType::BANK_CARD);


            $request = $builder->build();

            $idempotenceKey = uniqid('', true);
            $response = $this->paymentClient->createPayment($request, $idempotenceKey);

            //получаем confirmationUrl для дальнейшего редиректа
            $confirmationUrl = $response->getConfirmation()->getConfirmationUrl();

            $paymentRecord->link = $confirmationUrl;
            $paymentRecord->save();

            \DB::commit();

            return $confirmationUrl;
        } catch (\Throwable $exception) {
            \DB::rollBack();

            throw new ErrorCreatePaymentException('Ошибка создания платежа. Подробнее:' . $exception->getMessage());
        }
    }

    public function success(int $paymentId): PaymentDto
    {
        $payment = $this->getModel($paymentId);

        $payment->status = PaymentStatusEnum::SUCCESS;
        $payment->save();

        return self::makeDto($payment);
    }

    public function cancel($paymentId)
    {
        $payment = $this->getModel($paymentId);

        $payment->status = PaymentStatusEnum::CANCEL;
        $payment->save();

        return self::makeDto($payment);
    }

    protected function getModel(int $id): ?Payment
    {
        return Payment::whereId($id)->lockForUpdate()->first();
    }

    protected static function makeDto(Payment $payment): PaymentDto
    {
        return PaymentDto::from($payment->toArray());
    }
}
