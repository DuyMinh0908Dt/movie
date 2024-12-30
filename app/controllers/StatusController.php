<?php

use App\Core\App;
use App\Dtos\JsonResponse;
use App\Services\StatusService;
use Core\Attributes\Controller;
use Core\Attributes\Route;


class StatusController
{
    #[Route("/api/trang-thai", "GET")]
    public static function index()
    {
        if (!isset($_GET['table'])) {
            return json(new JsonResponse(400, "Missing table parameter"));
        }
        return json(JsonResponse::ok(StatusService::getAllStatus($_GET['table'])));
    }
    #[Route("/api/trang-thai/ids", "POST")]
    public static function getStatusByIds()
    {
        if (!isset(request_body()['ids'])) {
            return json(new JsonResponse(400, "Missing ids parameter"));
        }
        return json(JsonResponse::ok(StatusService::getStatusByIds(request_body()['ids'])));
    }
}