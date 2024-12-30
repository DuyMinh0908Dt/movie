<?php

use App\Dtos\JsonResponse;
use App\Dtos\Permission;
use App\Services\SeatService;
use App\Services\SeatTypeService;
use App\Services\StatusService;
use Core\Attributes\Route;


class SeatController
{
    #[Route("/api/ghe/tao-nhieu", "POST")]
    public static function createManySeats()
    {
        needAnyPermissionOrDie([Permission::CREATE_PHONGCHIEU]);
        $data = request_body();
        $result = SeatService::createSeats($data);
        return json($result);
    }
    #[Route("/api/ghe/cap-nhat-nhieu", "PUT")]
    public static function updateManySeats()
    {
        needAnyPermissionOrDie([Permission::UPDATE_PHONGCHIEU]);
        $data = request_body();
        $result = SeatService::updateOfRoom($data);
        return json($result);
    }
    #[Route("/api/loai-ghe", "GET")]
    public static function getAllSeatType()
    {

        return json(JsonResponse::ok(SeatTypeService::getAllSeatType()));
    }
    #[Route("/api/loai-ghe/ids", "POST")]
    public static function getSeatTypeByIds()
    {
        if (!isset(request_body()['ids'])) {
            return json(new JsonResponse(400, "Missing ids parameter"));
        }
        return json(JsonResponse::ok(SeatTypeService::getSeatTypeByIds(request_body()['ids'])));
    }
    #[Route("/api/suat-chieu/{id}/ghe", "GET")]
    public static function getSeatsByShowId($id)
    {
        return json(JsonResponse::ok(SeatService::getAllOfShow($id)));
    }

    #[Route("/admin/loai-ghe", "GET")]
    public static function index()
    {
        needAnyPermissionOrDie([Permission::READ_LOAIGHE, Permission::UPDATE_LOAIGHE, Permission::DELETE_LOAIGHE, Permission::CREATE_LOAIGHE]);
        $seatTypes = SeatTypeService::getAllSeatType();
        $seatTypesStatus = StatusService::getAllStatus('LoaiGhe');
        return view('admin/loai-ghe/index', [
            'seatTypes' => $seatTypes,
            'seatTypesStatus' => $seatTypesStatus
        ]);
    }

    #[Route("/api/loai-ghe/{id}", "GET")]
    public static function getSeatTypeById($id)
    {
        needAnyPermissionOrDie([Permission::READ_LOAIGHE, Permission::UPDATE_LOAIGHE, Permission::DELETE_LOAIGHE, Permission::CREATE_LOAIGHE]);

        $seatType = SeatTypeService::getSeatTypeById($id);
        return json($seatType);
    }


    #[Route("/api/loai-ghe", "POST")]
    public static function create()
    {
        needAnyPermissionOrDie([Permission::CREATE_LOAIGHE]);
        $result = SeatTypeService::createNewSeatType($_POST);
        return json($result);
    }
    #[Route("/api/loai-ghe/{id}/sua", "POST")]
    public static function update($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_LOAIGHE]);
        $result = SeatTypeService::updateSeatType($_POST, $id);
        return json($result);
    }
    #[Route("/api/loai-ghe/{id}/toggleHienThi", "POST")]
    public static function toggleHienThi($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_LOAIGHE]);
        $result = SeatTypeService::toggleHideSeatType($id);
        return json($result);
    }
}