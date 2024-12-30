<?php

use App\Dtos\JsonResponse;

use App\Services\CategoryService;
use App\Services\CinemaService;
use App\Services\StatisticService;

use Core\Attributes\Route;

class StatisticController
{
    #[Route(path: '/api/tong-quan/hoa-don', method: 'GET')]
    public static function countTotalBill()
    {
        $params = $_GET;
        $total = StatisticService::countTotalBill($params);
        return json(JsonResponse::ok($total));
    }
    #[Route(path: '/api/tong-quan/hoa-don/chi-tiet', method: 'GET')]
    public static function billStatistic()
    {
        $params = $_GET;
        $total = StatisticService::billStatistic($params);
        return json(JsonResponse::ok($total));
    }
    #[Route(path: '/api/tong-quan/hoa-don/tong-tien', method: 'GET')]
    public static function sumTotalBill()
    {
        $params = $_GET;
        $total = StatisticService::sumTotalBill($params);
        return json(JsonResponse::ok($total));
    }
    #[Route(path: '/api/tong-quan/phim/theo-gioi-han-do-tuoi', method: 'GET')]
    public static function sumOfEachFilmTag()
    {
        $result = StatisticService::sumOfEachFilmTag();
        return json(JsonResponse::ok($result));
    }
    #[Route(path: '/api/tong-quan/ve', method: 'GET')]
    public static function countTotalTicket()
    {
        $params = $_GET;
        $total = StatisticService::countTotalTicket($params);
        return json(JsonResponse::ok($total));
    }
    #[Route(path: '/api/tong-quan/ve/chi-tiet', method: 'GET')]
    public static function ticketStatistic()
    {
        $params = $_GET;
        $total = StatisticService::ticketStatistic($params);
        return json(JsonResponse::ok($total));
    }
    #[Route(path: '/api/tong-quan/khach-hang', method: 'GET')]
    public static function countCustomer()
    {
        $params = $_GET;
        $total = StatisticService::countCustomer($params);
        return json(JsonResponse::ok($total));
    }
    #[Route(path: '/api/tong-quan/rap-chieu', method: 'GET')]
    public static function sumTotalRevenueEachCinema()
    {
        $params = $_GET;
        $total = StatisticService::sumTotalRevenueEachCinema($params);
        return json(JsonResponse::ok($total));
    }

    #[Route(path: '/api/tong-quan/san-pham', method: 'GET')]
    public static function sumTotalRevenueFood()
    {
        $params = $_GET;
        $total = StatisticService::sumTotalRevenueFood($params);
        return json(JsonResponse::ok($total));
    }
    #[Route(path: '/api/tong-quan/phim/chi-tiet', method: 'GET')]
    public static function movieStatistic()
    {
        $params = $_GET;
        $total = StatisticService::movieStatistic($params);
        return json(JsonResponse::ok($total));
    }
    #[Route(path: '/api/tong-quan/san-pham/chi-tiet', method: 'GET')]
    public static function foodStatistic()
    {
        $params = $_GET;
        $total = StatisticService::sumTotalEachFood($params);
        return json(JsonResponse::ok($total));
    }
    #[Route(path: '/api/tong-quan/khach-hang/count', method: 'GET')]
    public static function countCustomerByDate()
    {
        $params = $_GET;
        $total = StatisticService::countCustomer($params);
        return json(JsonResponse::ok($total));
    }

    #[Route(path: '/admin/thong-ke/rap-chieu', method: 'GET')]
    public static function cinema()
    {
        // tổng doanh thu, tổng lượt khách tổng, hoá đơn
        $totalRevenue = StatisticService::sumTotalBill([]);
        $totalBillCount = StatisticService::countTotalBill([]);
        $totalCustomerCount = StatisticService::countCustomer([]);
        $cinemas = CinemaService::getAllCinemas();
        return view('admin/thong-ke/rap-chieu/index', [
            'totalRevenue' => $totalRevenue[0],
            'totalBillCount' => $totalBillCount[0],
            'totalCustomerCount' => $totalCustomerCount[0],
            'cinemas' => $cinemas
        ]);
    }
    #[Route(path: '/admin/thong-ke/phim', method: 'GET')]
    public static function movie()
    {
        $categories = CategoryService::getAllCategories();

        return view('admin/thong-ke/phim/index', [
            'categories' => $categories
        ]);
    }
    #[Route(path: '/admin/thong-ke/san-pham', method: 'GET')]
    public static function movieDetail()
    {
        return view('admin/thong-ke/san-pham/index');
    }

    #[Route(path: '/api/thong-ke/phim/tong-quan', method: 'GET')]
    public static function movieGeneral()
    {
        return json(JsonResponse::ok(StatisticService::overviewMovie($_GET)));
    }


    #[Route(path: '/admin/thong-ke/phim/{id}', method: 'GET')]
    public static function detailStatistic($id)
    {
        return view('admin/thong-ke/phim/detail', [
            'id' => $id
        ]);
    }
}