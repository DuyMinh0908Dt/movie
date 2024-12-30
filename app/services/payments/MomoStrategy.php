<?php

namespace App\Services\Payments;

use GuzzleHttp\{Client, Request, RequestOptions};
use App\Core\Logger;
use App\Services\Payments\Models\CreatePaymentResponse;

class MomoPaymentStrategy implements PaymentStrategy
{
    public function createPayment(string $requestId, string $amount, string $description): CreatePaymentResponse
    {
        $momo_config = $GLOBALS['config']['momo'];
        $partnerCode = $momo_config['partner-code'];
        $accessKey = $momo_config['access-key'];
        $secretKey = $momo_config['secret-key'];
        $extraData = "";
        $orderId = uid();

        $sb = sprintf(
            "accessKey=%s&amount=%s&extraData=%s&ipnUrl=%s&orderId=%s&orderInfo=%s&partnerCode=%s&redirectUrl=%s&requestId=%s&requestType=%s",
            $accessKey,
            $amount,
            $extraData,
            $momo_config['ipn-url'],
            $orderId,
            $description,
            $partnerCode,
            $momo_config['callback-url'],
            $requestId,
            "captureWallet"
        );
        $signature = hash_hmac("sha256", $sb, $secretKey);
        Logger::info("Momo signature: $signature");
        $body = array(
            "partnerCode" => $partnerCode,
            "partnerName" => "Pixel Cinema",
            "storeId" => "PIXEL_CINEMA",
            "requestId" => $requestId,
            "amount" => $amount,
            "orderId" => $orderId,
            "orderInfo" => $description,
            "redirectUrl" => $momo_config['callback-url'],
            "ipnUrl" => $momo_config['ipn-url'],
            "lang" => "vi",
            "extraData" => $extraData,
            "requestType" => "captureWallet",
            "signature" => $signature
        );
        $client = new Client();
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $response = $client->post($endpoint, [
            RequestOptions::JSON => $body,
        ]);
        $response = json_decode($response->getBody(), true);


        Logger::info("===================New Momo Payment===================");
        Logger::info("Body: " . json_encode($body));
        Logger::info("Momo create payment response: " . json_encode($response));
        Logger::info("=====================================================");
        $result = new CreatePaymentResponse();
        $result->isRedirect = true;
        $result->redirectUrl = $response['payUrl'];
        $result->mobileUrl = $response['deeplink'];
        $result->orderId = $response['orderId'];
        $result->paymentId = $response['requestId'];
        return $result;
    }
    /**
     *
     * @param mixed $data
     * @return PaymentStatus
     */
    public function callback($data): PaymentStatus
    {
        // /pay/callback/momo?partnerCode=MOMOBKUN20180529&orderId=aff414cd-9d7b-4a9b-b4d0-e3e0b3de478c&requestId=74a6c7db-c3e3-44a3-8da3-9070e56e8e7a&amount=110000&orderInfaff414cd-9d7bo=Thanh+to%C3%A1n+v%C3%A9+xem+phim&orderType=momo_wallet&transId=4018910890&resultCode=0&message=Th%C3%A0n%A1n+v%C3%A9+h+c%C3%B4ng.&payType=qr&responseTime=1712687780480&extraData=&signature=81ce1416f1dd7527615a76c4309f377565sponseTime=17ccb4d36710c6d5a2d35be24d943512&paymentOption=momo  
        //toDo: check signature
        $momo_config = $GLOBALS['config']['momo'];
        $accessKey = $momo_config['access-key'];
        $secretKey = $momo_config['secret-key'];
        $amount = $data['amount'];
        $orderId = $data['orderId'];
        $orderInfo = $data['orderInfo'];
        $orderType = $data['orderType'];
        $partnerCode = $data['partnerCode'];
        $payType = $data['payType'];
        $requestId = $data['requestId'];
        $responseTime = $data['responseTime'];
        $resultCode = $data['resultCode'];
        $transId = $data['transId'];
        $message = $data['message'];
        $extraData = $data['extraData'];
        $signature = $data['signature'];
        $raw = sprintf("accessKey=%s&amount=%s&extraData=%s&message=%s&orderId=%s&orderInfo=%s&orderType=%s&partnerCode=%s&payType=%s&requestId=%s&responseTime=%s&resultCode=%s&transId=%s", $accessKey, $amount, $extraData, $message, $orderId, $orderInfo, $orderType, $partnerCode, $payType, $requestId, $responseTime, $resultCode, $transId);
        $signature2 = hash_hmac("sha256", $raw, $secretKey);
        Logger::info("Momo callback signature: $signature2");
        if ($signature != $signature2) {
            Logger::error("Momo callback signature not match : $signature != $signature2");
            return PaymentStatus::Failed;
        }
        Logger::info("Momo callback signature matched : $signature");
        $status = $data['resultCode'];
        if ($status == 0) {
            return PaymentStatus::Success;
        }
        return PaymentStatus::Failed;
    }
}