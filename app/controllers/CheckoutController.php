<?php

use App\Core\App;
use App\Core\Database\Database;
use App\Core\Logger;
use App\Dtos\JsonResponse;
use App\Services\ComboService;
use App\Services\OrderService;
use App\Services\Payments\Models\CreatePaymentResponse;
use App\Services\PromotionService;
use App\Services\SeatService;
use App\Services\ShowService;
use App\Services\TicketService;
use App\Services\UserService;
use Core\Attributes\Controller;
use Core\Attributes\Route;

class CheckoutController
{

    #[Route("/api/start-checkout", "POST")]
    public static function startCheckout()
    {
        needNotEmployee();
        $data = request_body();
        $result = OrderService::startCheckout($data);
        return json($result);
    }
    #[Route("/thanh-toan", "GET")]
    public static function index()
    {
        needNotEmployee();
        if (!isset($_SESSION['bookingData'])) {
            Logger::error("Booking data not found");
            redirect("");
        }
        $bookingData = $_SESSION['bookingData'];
        $lockToTime = $bookingData['lockTo'];
        $now = time();
        if ($now > $lockToTime) {
            redirect("");
        }
        $show = ShowService::getShowInfoById($bookingData['MaXuatChieu']);
        $tickets = TicketService::getTicketOfSeats(array_map(fn($item) => $item['MaGhe'], $bookingData['DanhSachVe']), $bookingData['MaXuatChieu']);
        $seats = SeatService::getSeatByIds(array_map(fn($item) => $item['MaGhe'], $bookingData['DanhSachVe']));
        $foods = ComboService::getFoodByIds(array_map(fn($item) => $item['MaThucPham'], $bookingData['ThucPhams']));
        $combos = ComboService::getComboByIds(array_map(fn($item) => $item['MaCombo'], $bookingData['Combos']));
        return view("checkout", [
            "show" => $show,
            "bookingData" => $bookingData,
            "tickets" => $tickets,
            "seats" => $seats,
            "foods" => $foods,
            "combos" => $combos
        ]);
    }

    #[Route("/thanh-toan", "POST")]
    public static function createPayUrl()
    {


        $data = request_body();
        $bookingData = $_SESSION['bookingData'];
        $discountCode = getArrayValueSafe($data, 'discount', null);
        if ($discountCode != null) {
            $discount = PromotionService::checkPromotion($discountCode, array_map(fn($item) => $item['MaLoaiVe'], $bookingData['DanhSachVe']), $bookingData['TongTien']);
            if ($discount->data['reducePrice'] > 0) {
                $_SESSION['bookingData']['TongTien'] = $bookingData['TongTien'] - $discount->data['reducePrice'];
                $_SESSION['bookingData']['promotion_code'] = $discountCode;

                $bookingData = $_SESSION['bookingData'];
            }
        }

        $payment_method = PaymentType::tryFrom($data['payment_method']);
        if (!isset($bookingData['userId'])) {
            $bookingData['userId'] = UserService::getUserOrCreateIfNotExist($data);
            $_SESSION['bookingData'] = $bookingData;
        }
        if (is_null($payment_method)) {
            return json(["message" => "Phương thức thanh toán không hợp lệ"], 400);
        }
        if ($payment_method == PaymentType::Mock_Succeed) {
            $payment = new CreatePaymentResponse();
            $payment->redirectUrl = "/pay/callback/mock_succeed";
            $payment->paymentId = "mock_payment_id";
            $payment->isRedirect = true;
            $payment->mobileUrl = true;
            return json($payment);
        } else if ($payment_method == PaymentType::Mock_Failed) {
            $payment = new CreatePaymentResponse();
            $payment->redirectUrl = "/pay/callback/mock_failed";
            $payment->paymentId = "mock_payment_id";
            $payment->isRedirect = true;
            $payment->mobileUrl = true;
            return json($payment);
        }

        $totalPrice = $bookingData['TongTien'];
        $displayText = "Thanh toán vé xem phim";
        $paymentStrategy = getPaymentStrategy($payment_method);
        $id = $_SESSION['bookingData']['id'];
        $payment = $paymentStrategy->createPayment($id, $totalPrice, $displayText);
        return json($payment);
    }
}