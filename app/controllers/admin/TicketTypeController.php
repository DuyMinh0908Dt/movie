<?php

use App\Dtos\Permission;
use App\Services\StatusService;
use App\Services\TicketService;
use Core\Attributes\Route;

class TicketTypeController
{


    #[Route("/admin/loai-ve", "GET")]
    public static function index()
    {
        needAnyPermissionOrDie([Permission::READ_LOAIVE, Permission::UPDATE_LOAIVE, Permission::DELETE_LOAIVE, Permission::CREATE_LOAIVE]);
        $ticketTypes = TicketService::getTicketTypes();
        $ticketTypesStatus = StatusService::getAllStatus('LoaiVe');
        return view('admin/loai-ve/index', [
            'ticketTypes' => $ticketTypes,
            'ticketTypesStatus' => $ticketTypesStatus
        ]);
    }
    #[Route("/api/loai-ve", "GET")]
    public static function getTicketTypes()
    {

        $ticketTypes = TicketService::getTicketTypes();
        return json($ticketTypes);
    }
    #[Route("/api/loai-ve/{id}", "GET")]
    public static function getTicketTypeById($id)
    {
        $ticketType = TicketService::getTicketTypeById($id);
        return json($ticketType);
    }


    #[Route("/api/loai-ve", "POST")]
    public static function create()
    {
        needAnyPermissionOrDie([Permission::CREATE_LOAIVE]);
        $result = TicketService::createNewTicketType($_POST);
        return json($result);
    }
    #[Route("/api/loai-ve/{id}/sua", "POST")]
    public static function update($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_LOAIVE]);
        $result = TicketService::updateTicketType($_POST, $id);
        return json($result);
    }
    #[Route("/api/loai-ve/{id}/toggleHienThi", "POST")]
    public static function toggleHienThi($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_LOAIVE]);
        $result = TicketService::toggleHideTicketType($id);
        return json($result);
    }

}