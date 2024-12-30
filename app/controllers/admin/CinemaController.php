<?php

use App\Dtos\JsonDataErrorRespose;
use App\Dtos\JsonResponse;
use App\Dtos\Permission;
use App\Services\CinemaService;
use App\Services\RoomService;
use App\Services\StatusService;
use Core\Attributes\Controller;
use Core\Attributes\Route;

class CinemaController
{

    #[Route("/api/rap", "GET")]
    public static function getCinemas()
    {
        $results = CinemaService::getAllCinemas();
        return json(JsonResponse::ok($results));
    }
    #[Route("/api/rap/ids", "POST")]
    public static function getCinemasInIds()
    {
        if (!isset(request_body()["ids"])) {
            return json(new JsonResponse(400, "Missing ids"));
        }
        $results = CinemaService::getCinemasInIds(request_body()["ids"]);

        return json(JsonResponse::ok($results));
    }
    #[Route("/api/rap/{id}", "GET")]
    public static function getCinemaById($id)
    {
        $result = CinemaService::getCinemaById($id);
        if ($result == null) {
            return json(JsonResponse::error("Cinema not found", 404));
        }
        return json(JsonResponse::ok($result));
    }

    #[Route("/api/phong-chieu/ids/rap", "POST")]
    public static function getCinemasByRoomIds()
    {
        if (!isset(request_body()["roomIds"])) {
            return json(new JsonResponse(400, "Missing roomIds"));
        }
        $results = CinemaService::getCinemasByRoomIds(request_body()["roomIds"]);
        return json(JsonResponse::ok($results));
    }
    #[Route("/admin/rap-chieu", "GET")]
    public static function quanLyRap()
    {
        needAnyPermissionOrDie([Permission::READ_RAPCHIEU, Permission::UPDATE_RAPCHIEU, Permission::DELETE_RAPCHIEU, Permission::CREATE_RAPCHIEU]);

        $cinemaStatuses = StatusService::getAllStatus('RapChieu');
        return view("admin/rap-chieu/index", [

            'cinemaStatuses' => $cinemaStatuses
        ]);
    }

    #[Route("/api/rap-chieu", "GET")]
    public static function getTableRowAjax()
    {
        needAnyPermissionOrDie([Permission::READ_RAPCHIEU, Permission::UPDATE_RAPCHIEU, Permission::DELETE_RAPCHIEU, Permission::CREATE_RAPCHIEU]);
        $results = CinemaService::getAllCinemas($_GET);
        $cinemaStatuses = StatusService::getAllStatus('RapChieu');
        return ajax("admin/rap-chieu/table-row", [
            'cinemas' => $results,
            'cinemaStatuses' => $cinemaStatuses
        ]);
    }
    #[Route("/api/rap-chieu", "POST")]
    public static function createCinema()
    {
        needAnyPermissionOrDie([Permission::CREATE_RAPCHIEU]);
        $result = CinemaService::createNewCinema($_POST);
        return json($result);
    }
    #[Route("/api/rap-chieu/{id}/sua", "POST")]
    public static function editCinema($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_RAPCHIEU]);
        $result = CinemaService::updateCinema($_POST, $id);
        return json($result);
    }
    #[Route("/api/rap-chieu/{id}/xoa", "POST")]
    public static function toggleHideCinema($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_RAPCHIEU]);
        $result = CinemaService::toggleHideCinema($id);
        return json($result);
    }
}