<?php

use App\Core\Logger;
use App\Dtos\JsonResponse;
use App\Dtos\Permission;
use App\Services\ConfigService;
use App\Services\StatisticService;
use Core\Attributes\Route;
use Carbon\Carbon;

class AdminController
{
    #[Route(path: '/admin', method: 'GET')]
    public static function home()
    {
        needEmployee();
        return view('admin/index');
    }
    #[Route("/admin/cai-dat-website", "GET")]
    public static function setting()
    {
        needAnyPermissionOrDie([Permission::CONFIG_WEBSITE]);

        return view("admin/cai-dat-website/index");
    }
    #[Route("/api/cai-dat-website/banners", "POST")]
    public static function updateBanners()
    {
        needAnyPermissionOrDie([Permission::CONFIG_WEBSITE]);
        ConfigService::updateBanners(request_body());
        return json(JsonResponse::ok());
    }
    #[Route("/api/cai-dat-website/info", "POST")]
    public static function updateInfo()
    {
        needAnyPermissionOrDie([Permission::CONFIG_WEBSITE]);
        ConfigService::updateWebsiteConfig(request_body());
        return json(JsonResponse::ok());
    }
    #[Route("/admin/logs", "GET")]
    public static function getLogs()
    {
        needAnyPermissionOrDie([Permission::CONFIG_WEBSITE]);
        echo nl2br(Logger::getLogs());
        die(200);
    }
    #[Route("/admin/tong-quan", "GET")]
    public static function overview()
    {
        needAnyPermissionOrDie([Permission::THONG_KE]);
        $tagStatistics = StatisticService::sumOfEachFilmTag();
        $cinemaStatistics = StatisticService::sumTotalRevenueEachCinema([]);
        $today = Carbon::now()->format('Y-m-d');
        $startOfCurrentYear = Carbon::now()->startOfYear()->format('Y-m-d');
        $endOfLastYear = Carbon::now()->subYear()->endOfYear()->format('Y-m-d');
        $startOfLastYear = Carbon::now()->subYear()->startOfYear()->format('Y-m-d');
        $currentYearTicketRevenue = StatisticService::sumTotalRevenueTicket([
            'tu-ngay' => $startOfCurrentYear,
            'den-ngay' => $today
        ]);
        $currentYearFoodRevenue = StatisticService::sumTotalRevenueFood([
            'tu-ngay' => $startOfCurrentYear,
            'den-ngay' => $today
        ]);
        $currentYearTotalRevenue = StatisticService::billStatistic([
            'tu-ngay' => $startOfCurrentYear,
            'den-ngay' => $today
        ]);
        $lastYearTotalRevenue = StatisticService::billStatistic([
            'tu-ngay' => $startOfLastYear,
            'den-ngay' => $endOfLastYear
        ]);
        if (empty($lastYearTotalRevenue['totalMoney'])) {
            $growth = 100;
        } else {
            $growth = ($currentYearTotalRevenue['totalMoney'] - $lastYearTotalRevenue['totalMoney']) / $lastYearTotalRevenue['totalMoney'] * 100;
        }
        return view("admin/tong-quan", [
            'tagStatistics' => $tagStatistics,
            'cinemaStatistics' => $cinemaStatistics,
            'currentYearTicketRevenue' => $currentYearTicketRevenue,
            'currentYearFoodRevenue' => $currentYearFoodRevenue,
            'currentYearTotalRevenue' => $currentYearTotalRevenue,
            'lastYearTotalRevenue' => $lastYearTotalRevenue,
            'growth' => $growth
        ]);
    }

}