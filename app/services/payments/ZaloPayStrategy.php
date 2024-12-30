<?php

namespace App\Services\Payments;

use App\Core\App;
use App\Core\Logger;
use App\Services\Payments\Models\CreatePaymentResponse;

class ZaloPayStrategy implements PaymentStrategy
{
    public function createPayment(string $orderId, string $amount, string $description): CreatePaymentResponse
    {
        $amount = intval($amount);
        $config = $GLOBALS['config']['zalopay'];
        $app_id = $config['app_id'];
        $key1 = $config['key1'];
        $return_url = $config['return_url'];
        $ipn_url = $config['ipn_url'];
        $app_user = $config['app_user'];
        /////////////////////////////
        $embeddata = [
            "redirecturl" => $return_url,
        ];
        $embeddata = json_encode($embeddata);
        $items = '[]';
        $transID = str_replace('-', '', $orderId);

        $order = [
            "app_id" => $app_id,
            "app_time" => round(microtime(true) * 1000), // miliseconds
            "app_trans_id" => date("ymd") . "_" . $transID,
            "app_user" => $app_user,
            "item" => $items,
            "embed_data" => $embeddata,
            "amount" => $amount,
            "description" => "Thanh toán cho đơn hàng " . $transID,
            "bank_code" => ""
        ];
        $data = $order["app_id"] . "|" . $order["app_trans_id"] . "|" . $order["app_user"] . "|" . $order["amount"]
            . "|" . $order["app_time"] . "|" . $order["embed_data"] . "|" . $order["item"];
        $order["mac"] = hash_hmac("sha256", $data, $key1);
        Logger::info("ZaloPay order: " . http_build_query($order));
        $context = stream_context_create([
            "http" => [
                "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => http_build_query($order)
            ]
        ]);

        $resp = file_get_contents("https://sb-openapi.zalopay.vn/v2/create", false, $context);

        $response = json_decode($resp, true);
        $rs = new CreatePaymentResponse();
        $rs->isRedirect = true;
        $rs->orderId = $orderId;
        $rs->mobileUrl = $response['order_url'];
        $rs->paymentId = $response['order_token'];
        $rs->redirectUrl = $response['order_url'];
        return $rs;
    }
    /**
     *
     * @param mixed $data
     * @return PaymentStatus
     */
    public function callback($data): PaymentStatus
    {
        $status = $data['status'];
        if ($status != 1) {
            return PaymentStatus::Failed;
        }
        return PaymentStatus::Success;
    }
}