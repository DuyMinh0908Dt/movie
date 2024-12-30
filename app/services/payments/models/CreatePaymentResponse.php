<?php

namespace App\Services\Payments\Models;

class CreatePaymentResponse
{
    public string $paymentId;
    public string $orderId;
    public bool $isRedirect;
    public string $redirectUrl;
    public string $mobileUrl;
}
