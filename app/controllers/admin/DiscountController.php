<?php

use App\Dtos\Permission;
use App\Services\PromotionService;
use App\Services\StatusService;
use App\Services\TicketService;
use Core\Attributes\Route;

class DiscountController
{
    #[Route(path: '/admin/khuyen-mai', method: 'GET')]
    public static function index()
    {
        needAnyPermissionOrDie([Permission::READ_KHUYENMAI, Permission::UPDATE_KHUYENMAI, Permission::DELETE_KHUYENMAI, Permission::CREATE_KHUYENMAI]);
        $statuses = StatusService::getAllStatus('KhuyenMai');
        return view('admin/discount/index', ['statuses' => $statuses]);
    }
    #[Route(path: '/admin/khuyen-mai/them', method: 'GET')]
    public static function add()
    {
        needAnyPermissionOrDie([Permission::CREATE_KHUYENMAI]);
        $allTicketTypes = TicketService::getTicketTypes();
        $statuses = StatusService::getAllStatus('KhuyenMai');
        return view('admin/discount/add', ['allTicketTypes' => $allTicketTypes, 'statuses' => $statuses]);
    }
    #[Route(path: '/api/khuyen-mai/{id}', method: 'GET')]
    public static function getById($id)
    {

        $promotion = PromotionService::getPromotionByCode($id);
        return json($promotion);
    }
    #[Route(path: '/admin/khuyen-mai/{id}/sua', method: 'GET')]
    public static function edit($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_KHUYENMAI]);
        $promotion = PromotionService::getPromotionByCode($id);
        $allTicketTypes = TicketService::getTicketTypes();
        $statuses = StatusService::getAllStatus('KhuyenMai');
        return view('admin/discount/edit', ['promotion' => $promotion, 'allTicketTypes' => $allTicketTypes, 'statuses' => $statuses]);
    }
    #[Route(path: '/api/khuyen-mai/{id}', method: 'PUT')]
    public static function update($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_KHUYENMAI]);
        $result = PromotionService::updateDiscount(request_body(), $id);
        return json($result);
    }
    #[Route(path: '/api/khuyen-mai/{id}', method: 'DELETE')]
    public static function delete($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_KHUYENMAI]);
        $result = PromotionService::deleteDiscount($id);
        return json($result);
    }
    #[Route(path: '/api/khuyen-mai', method: 'POST')]
    public static function store()
    {
        needAnyPermissionOrDie([Permission::CREATE_KHUYENMAI]);
        $result = PromotionService::createNewDiscount(request_body());
        return json($result);
    }
    #[Route(path: '/api/khuyen-mai', method: 'GET')]
    public static function getAll()
    {
        needAnyPermissionOrDie([Permission::READ_KHUYENMAI, Permission::UPDATE_KHUYENMAI, Permission::DELETE_KHUYENMAI, Permission::CREATE_KHUYENMAI]);

        $promotions = PromotionService::getAllPromotions($_GET);
        return json($promotions);
    }
    #[Route(path: '/api/khuyen-mai/{id}/chuyen-trang-thai', method: 'PATCH')]
    public static function toggleHide($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_KHUYENMAI]);
        $result = PromotionService::toggleHidePromotion($id);
        return json($result);
    }

}