<?php

use App\Dtos\JsonDataErrorRespose;
use App\Dtos\JsonResponse;
use App\Dtos\Permission;
use App\Services\CinemaService;
use App\Services\ComboService;
use App\Services\RoomService;
use App\Services\StatusService;
use Core\Attributes\Controller;
use Core\Attributes\Route;

class ProductController
{
    #[Route("/admin/san-pham", "GET")]
    public static function index()
    {
        needAnyPermissionOrDie([Permission::READ_THUCPHAM, Permission::UPDATE_THUCPHAM, Permission::DELETE_THUCPHAM, Permission::CREATE_THUCPHAM]);
        $statuses = StatusService::getAllStatus('ThucPham');
        return view("admin/san-pham/index", ['statuses' => $statuses]);
    }
    #[Route("/api/san-pham", "POST")]
    public static function create()
    {
        needAnyPermissionOrDie([Permission::CREATE_THUCPHAM]);
        $result = ComboService::createNewFoodnDrink($_POST);
        return json($result);
    }
    #[Route("/api/san-pham/{id}", "GET")]
    public static function get($id)
    {
        needAnyPermissionOrDie([Permission::READ_THUCPHAM, Permission::UPDATE_THUCPHAM, Permission::DELETE_THUCPHAM, Permission::CREATE_THUCPHAM]);
        $product = ComboService::getFoodnDrinkById($id);
        return json(JsonResponse::ok($product));
    }

    #[Route("/api/san-pham/{id}", "POST")]
    public static function update($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_THUCPHAM]);
        $result = ComboService::updateFoodnDrink($_POST, $id);
        return json($result);
    }
    #[Route("/api/san-pham/{id}/delete", "POST")]
    public static function tryDelete($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_THUCPHAM]);
        $result = ComboService::deleteFoodnDrink($id);
        return json($result);
    }
    #[Route("/api/san-pham", "GET")]
    public static function getProducts()
    {
        needAnyPermissionOrDie([Permission::READ_THUCPHAM, Permission::UPDATE_THUCPHAM, Permission::DELETE_THUCPHAM, Permission::CREATE_THUCPHAM]);
        $products = ComboService::getAllFoodnDrink($_GET);
        $statuses = StatusService::getAllStatus('ThucPham');
        return ajax("admin/san-pham/table-row", ['products' => $products, 'statuses' => $statuses]);
    }

}