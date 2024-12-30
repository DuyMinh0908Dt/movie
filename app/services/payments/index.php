<?php

require_once 'MomoStrategy.php';
require_once 'ZaloPayStrategy.php';

use App\Services\Payments\MomoPaymentStrategy;
use App\Services\Payments\PaymentStrategy;
use App\Services\Payments\ZaloPayStrategy;

enum PaymentType: string
{
    case Momo = 'momo';
    case ZaloPay = 'zalopay';
    case Mock_Succeed = 'mock_succeed';
    case Mock_Failed = 'mock_failed';
}

function getPaymentStrategy(PaymentType $type): PaymentStrategy
{
    switch ($type) {
        case PaymentType::Momo:
            return new MomoPaymentStrategy();
        case PaymentType::ZaloPay:
            return new ZaloPayStrategy();
    }
    throw new Exception("Not implemented");
}