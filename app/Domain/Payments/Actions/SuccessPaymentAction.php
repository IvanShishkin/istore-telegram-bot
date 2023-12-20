<?php

declare(strict_types=1);

namespace App\Domain\Сurrency\Payments\Actions;

use App\Domain\Transactions\Actions\ApplyTransactionAction;
use App\Domain\Сurrency\Payments\PaymentService;

final class SuccessPaymentAction
{
    public function __construct(
        protected PaymentService $paymentService,
        protected ApplyTransactionAction $applyTransactionAction
    )
    {
    }

    public function execute(string $transactionId)
    {
       $transactionDto = $this->applyTransactionAction->execute($transactionId);
    }
}
