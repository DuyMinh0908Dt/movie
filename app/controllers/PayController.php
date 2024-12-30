<?php
require_once ('app/services/payments/index.php');

use App\Core\Logger;
use App\Services\ComboService;
use App\Services\OrderService;
use App\Services\Payments\PaymentStatus;
use App\Services\PromotionService;
use App\Services\SeatService;
use App\Services\ShowService;
use App\Services\TicketService;
use Core\Attributes\Route;
use Dompdf\Options;

class PayController
{

    #[Route("/pay/callback/momo", "GET")]
    public static function momoCallback()
    {
        $paymentStrategy = getPaymentStrategy(PaymentType::Momo);
        $paymentStatus = $paymentStrategy->callback($_GET);
        if ($paymentStatus == PaymentStatus::Failed) {
            echo "Thanh toán thất bại";
            $_SESSION['bookingData'] = null;
            return;
        }
        $bookingData = isset($_SESSION['bookingData']) ? $_SESSION['bookingData'] : null;
        if ($bookingData == null) {
            return redirect("");
        }
        $orderModel = self::createOrderModel();
        $orderModel['paymentType'] = "Ví điện tử Momo";
        return view("checkout-success", $orderModel);
    }

    private static function createOrderModel()
    {
        $bookingData = $_SESSION['bookingData'];
        $_SESSION['bookingData'] = null;
        $show = ShowService::getShowInfoById($bookingData['MaXuatChieu']);
        $tickets = TicketService::getTicketOfSeats(array_map(fn($item) => $item['MaGhe'], $bookingData['DanhSachVe']), $bookingData['MaXuatChieu']);
        $seats = SeatService::getSeatByIds(array_map(fn($item) => $item['MaGhe'], $bookingData['DanhSachVe']));
        $foods = ComboService::getFoodByIds(array_map(fn($item) => $item['MaThucPham'], $bookingData['ThucPhams']));
        $combos = ComboService::getComboByIds(array_map(fn($item) => $item['MaCombo'], $bookingData['Combos']));
        $orderId = $bookingData['id'];
        $bookingData['payment_method'] = PaymentType::Momo->value;
        OrderService::saveOrder($bookingData);
        if (isset($bookingData['promotion_code']))
            PromotionService::usePromotion($bookingData['promotion_code']);
        return [
            "show" => $show,
            "bookingData" => $bookingData,
            "tickets" => $tickets,
            "seats" => $seats,
            "foods" => $foods,
            "combos" => $combos,
            "orderId" => $orderId,
        ];
    }

    #[Route("/pay/callback/mock_succeed", "GET")]
    public static function mockSucceedCallback()
    {
        $bookingData = isset($_SESSION['bookingData']) ? $_SESSION['bookingData'] : null;
        if ($bookingData == null) {
            return redirect("");
        }
        $orderModel = self::createOrderModel();

        $orderModel['paymentType'] = "Thanh toán thử nghiệm";
        $bookingData['payment_method'] = PaymentType::Momo->value;
        OrderService::saveOrder($bookingData);


        return view("checkout-success", $orderModel);
    }
    #[Route("/pay/callback/mock_failed", "GET")]
    public static function mockFailedCallback()
    {
        $bookingData = isset($_SESSION['bookingData']) ? $_SESSION['bookingData'] : null;
        if ($bookingData == null) {
            return redirect("");
        }
        $_SESSION['bookingData'] = null;
        return view("checkout-failed");
    }

    #[Route("/pay/test", "GET")]
    public static function test()
    {
        $bookingData = isset($_SESSION['bookingData']) ? $_SESSION['bookingData'] : null;
        if ($bookingData == null) {
            echo "Không tìm thấy thông tin đặt vé";
            die();
        }
        $show = ShowService::getShowInfoById($bookingData['MaXuatChieu']);
        $tickets = TicketService::getTicketOfSeats(array_map(fn($item) => $item['MaGhe'], $bookingData['DanhSachVe']), $bookingData['MaXuatChieu']);
        $seats = SeatService::getSeatByIds(array_map(fn($item) => $item['MaGhe'], $bookingData['DanhSachVe']));
        $foods = ComboService::getFoodByIds(array_map(fn($item) => $item['MaThucPham'], $bookingData['ThucPhams']));
        $combos = ComboService::getComboByIds(array_map(fn($item) => $item['MaCombo'], $bookingData['Combos']));
        $orderId = $_SESSION['bookingData']['id'];
        $barCodeUrl = "https://barcode.orcascan.com/?type=code128&format=jpg&data=" . $orderId;
        $barCodeContent = file_get_contents($barCodeUrl);
        $base64 = "data:image/png;base64," . base64_encode($barCodeContent);
        extract([
            "show" => $show,
            "bookingData" => $bookingData,
            "tickets" => $tickets,
            "seats" => $seats,
            "foods" => $foods,
            "combos" => $combos,
            "orderId" => $orderId,
            "paymentType" => "Ví điện tử Momo",
            "barCodeUrl" => $base64,
        ]);
        $ticketFile = 'app/views/partials/ticket.php';
        ob_start();
        require_once ($ticketFile);
        $ticketHtml = ob_get_clean();
        file_put_contents('ticket.html', $ticketHtml);
        $options = new Options();
        $dompdf = new Dompdf\Dompdf($options);
        $dompdf->loadHtml($ticketHtml);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream();
    }

    #[Route("/pay/callback/zalopay", "GET")]
    public static function zalopayCallback()
    {
        $paymentStrategy = getPaymentStrategy(PaymentType::ZaloPay);
        $paymentStatus = $paymentStrategy->callback($_GET);
        if ($paymentStatus == PaymentStatus::Failed) {
            echo "Thanh toán thất bại";
            $_SESSION['bookingData'] = null;
            return;
        }
        $bookingData = isset($_SESSION['bookingData']) ? $_SESSION['bookingData'] : null;
        if ($bookingData == null) {
            return redirect("");
        }
        $orderModel = self::createOrderModel();
        $orderModel['paymentType'] = "Ví điện tử ZaloPay";
        return view("checkout-success", $orderModel);
    }

}