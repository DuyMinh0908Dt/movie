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

class ComboController
{
    #[Route("/admin/combo", "GET")]
    public static function index()
    {
        needAnyPermissionOrDie([Permission::READ_COMBO, Permission::UPDATE_COMBO, Permission::DELETE_COMBO, Permission::CREATE_COMBO]);

        $statuses = StatusService::getAllStatus('Combo');
        return view("admin/combo/index", ['statuses' => $statuses]);
    }

    #[Route("/admin/combo/{id}/sua", "GET")]
    public static function edit($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_COMBO]);
        $combo = ComboService::getComboById($id);
        $statuses = StatusService::getAllStatus('Combo');
        $thucAn = ComboService::getAllFoodnDrink();
        return view("admin/combo/edit", ['combo' => $combo, 'statuses' => $statuses, 'foods' => $thucAn]);
    }

    #[Route("/admin/combo/{id}/sua", "POST")]
    public static function updateView($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_COMBO]);
        $result = ComboService::updateCombo(request_body(), $id);
        return json($result);
    }
    #[Route("/admin/combo/them-moi", "GET")]
    public static function createView()
    {
        needAnyPermissionOrDie([Permission::CREATE_COMBO]);
        $statuses = StatusService::getAllStatus('Combo');
        $thucAn = ComboService::getAllFoodnDrink();
        return view("admin/combo/add", ['statuses' => $statuses, 'foods' => $thucAn]);
    }
    #[Route("/admin/combo/them-moi", "POST")]
    public static function create()
    {
        needAnyPermissionOrDie([Permission::CREATE_COMBO]);
        $result = ComboService::createNewCombo(request_body());
        return json($result);
    }
    #[Route("/api/combo/{id}/sua", "POST")]
    public static function update($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_COMBO]);
        $result = ComboService::updateCombo(request_body(), $id);
        return json($result);
    }
    #[Route("/api/combo/{id}/toggle-status", "POST")]
    public static function tryDelete($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_COMBO]);
        $result = ComboService::deleteCombo($id);
        return json($result);
    }
    #[Route("/api/combo", "GET")]
    public static function getProducts()
    {
        needAnyPermissionOrDie([Permission::READ_COMBO, Permission::UPDATE_COMBO, Permission::DELETE_COMBO, Permission::CREATE_COMBO]);
        $combos = ComboService::getAllCombo($_GET);
        $statuses = StatusService::getAllStatus('Combo');
        return ajax("admin/combo/table-row", ['combos' => $combos, 'statuses' => $statuses]);
    }

}