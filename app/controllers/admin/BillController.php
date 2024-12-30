<?php

use App\Core\Request;
use App\Dtos\JsonResponse;
use App\Dtos\Permission;
use App\Services\OrderService;
use Core\Attributes\Route;

class BillController
{
    #[Route(path: '/admin/hoa-don', method: 'GET')]
    public static function index()
    {
        needAnyPermissionOrDie([Permission::READ_HOANDON]);

        return view('admin/bill/index');
    }
    #[Route(path: '/admin/hoa-don/{id}', method: 'GET')]
    public static function detail($id)
    {
        needAnyPermissionOrDie([Permission::READ_HOANDON]);
        $order = OrderService::getOrderById($id);
        return view('admin/bill/detail', ['order' => $order]);
    }
    #[Route("/api/hoa-don", "GET")]
    public static function getAllOrder()
    {
        needAnyPermissionOrDie([Permission::READ_HOANDON]);
        $orders = OrderService::searchOrder($_GET);
        return json($orders);
    }
    #[Route("/api/hoa-don/{id}", "GET")]
    public static function getOrder($id)
    {
        // needAnyPermissionOrDie([Permission::READ_HOANDON]);
        $order = OrderService::getOrderById($id);
        if (!$order)
            return json(JsonResponse::error('Không tìm thấy hóa đơn'));
        return json($order);
    }
    #[Route("/api/nguoi-dung/hoa-don", "GET")]

    public static function getMyOrder()
    {
        needLogin();
        $userId = Request::getUser()['MaNguoiDung'];
        $orders = OrderService::searchOrder([
            'ma-nguoi-dung' => $userId,
            ...$_GET,
        ]);

        return json($orders);
    }
}