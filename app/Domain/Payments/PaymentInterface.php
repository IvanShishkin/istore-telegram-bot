<?php

namespace App\Domain\Сurrency\Payments;

interface PaymentInterface
{
    public function create();

    public function get(string $paymentId);

    public function success(string $paymentId);

    public function cancel(string $paymentId);
}
