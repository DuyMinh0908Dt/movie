<?php

use App\Services\PromotionService;
use Core\Attributes\Route;

class PromotionController
{
    #[Route(path: '/api/promotions/{code}', method: 'GET')]
    public static function checkPromotion($code)
    {
        $bookingData = $_SESSION['bookingData'];
        $promotion = PromotionService::checkPromotion($code, array_map(fn($item) => $item['MaLoaiVe'], $bookingData['DanhSachVe']), $bookingData['TongTien']);
        return json($promotion);
    }
}