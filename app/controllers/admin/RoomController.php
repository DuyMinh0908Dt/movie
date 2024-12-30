<?php

use App\Dtos\JsonResponse;
use App\Dtos\Permission;
use App\Services\CinemaService;
use App\Services\RoomService;
use App\Services\SeatService;
use App\Services\SeatTypeService;
use App\Services\StatusService;
use Core\Attributes\Controller;
use Core\Attributes\Route;

class RoomController
{
    #[Route("/admin/phong-chieu", "GET")]
    public static function index()
    {
        needAnyPermissionOrDie([Permission::READ_PHONGCHIEU, Permission::UPDATE_PHONGCHIEU, Permission::DELETE_PHONGCHIEU, Permission::CREATE_PHONGCHIEU]);
        $results = RoomService::getAllRooms($_GET);
        $cinemas = CinemaService::getAllCinemas();
        return view("admin/room/index", [
            'rooms' => $results['rooms'],
            'total' => $results['total'],
            'cinemas' => $cinemas
        ]);
    }
    #[Route("/api/phong-chieu", "GET")]
    public static function getRooms()
    {
        needAnyPermissionOrDie([Permission::READ_PHONGCHIEU, Permission::UPDATE_PHONGCHIEU, Permission::DELETE_PHONGCHIEU, Permission::CREATE_PHONGCHIEU]);
        $results = RoomService::getAllRooms($_GET);
        header("X-Total-Count: " . $results['total']);
        return json(JsonResponse::ok($results['rooms']));
    }

    #[Route("/admin/phong-chieu/them", "GET")]
    public static function create()
    {
        needAnyPermissionOrDie([Permission::CREATE_PHONGCHIEU]);
        $cinemas = CinemaService::getAllCinemas();
        $status = StatusService::getAllStatus('PhongChieu');
        $seatTypes = SeatTypeService::getAllSeatType();
        return view("admin/room/create", [
            'cinemas' => $cinemas,
            'statuses' => $status,
            'seatTypes' => $seatTypes
        ]);
    }
    #[Route("/api/phong-chieu", "POST")]
    public static function createRoom()
    {
        needAnyPermissionOrDie([Permission::CREATE_PHONGCHIEU]);
        $data = request_body();
        $result = RoomService::createRoom($data);
        return json($result);
    }
    #[Route("/admin/phong-chieu/{id}/sua", "GET")]
    public static function update($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_PHONGCHIEU]);
        $cinemas = CinemaService::getAllCinemas();
        $status = StatusService::getAllStatus('PhongChieu');
        $seatTypes = SeatTypeService::getAllSeatType();
        $seats = SeatService::getSeatsByRoomId(intval($id));
        $room = RoomService::getRoomById(intval($id));
        return view("admin/room/update", [
            'cinemas' => $cinemas,
            'statuses' => $status,
            'seatTypes' => $seatTypes,
            'seats' => $seats,
            'room' => $room
        ]);
    }
    #[Route("/admin/phong-chieu/{id}/sua", "POST")]
    public static function updateRoom($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_PHONGCHIEU]);
        $data = request_body();
        $result = RoomService::updateRoom(intval($id), $data);
        return json($result);
    }

    #[Route("/api/phong-chieu/ids", "POST")]
    public static function getRoomsByIds()
    {

        $data = request_body();
        $ids = $data['ids'];
        $rooms = RoomService::getRoomByIds($ids);
        return json(JsonResponse::ok($rooms));
    }
    #[Route("/api/rap-chieu/{id}/phong-chieu", "GET")]
    public static function getRoomsByCinemaId($id)
    {
        $rooms = RoomService::getAllRoomOfCinema(intval($id), $_GET);
        return json(JsonResponse::ok($rooms));
    }

    #[Route("/api/phong-chieu/{id}/can-edit", "GET")]
    public static function canEdit($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_PHONGCHIEU, Permission::DELETE_PHONGCHIEU, Permission::READ_PHONGCHIEU]);
        $can_edit = RoomService::canEditRoom(intval($id));
        return json(JsonResponse::ok([
            'can_edit' => $can_edit
        ]));
    }
    #[Route("/api/phong-chieu/{id}/toggle-status", "PATCH")]
    public static function toggleStatus($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_PHONGCHIEU]);
        $result = RoomService::toggleHideRoom(intval($id));
        return json($result);
    }
    #[Route("/api/phong-chieu/{id}", "DELETE")]
    public static function delete($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_PHONGCHIEU]);
        $result = RoomService::deleteRoom(intval($id));
        return json($result);
    }


}