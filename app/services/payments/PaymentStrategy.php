<?php

namespace App\Services\Payments;

use App\Services\Payments\Models\CreatePaymentResponse;

enum PaymentStatus: string
{
    case Pending = "PENDING";
    case Success = "SUCCESS";
    case Failed = "FAILED";
}
interface PaymentStrategy
{
    public function createPayment(string $orderId, string $amount, string $description): CreatePaymentResponse;
    public function callback($data): PaymentStatus;
}