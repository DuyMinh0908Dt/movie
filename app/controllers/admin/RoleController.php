<?php

use App\Core\Request;
use App\Dtos\JsonDataErrorRespose;
use App\Dtos\JsonResponse;
use App\Dtos\Permission;
use App\Dtos\TrangThaiNhomQuyen;
use App\Services\CinemaService;
use App\Services\PermissionService;
use App\Services\PhimService;
use App\Services\RoleService;
use App\Services\RoomService;
use App\Services\ShowService;
use Core\Attributes\Controller;
use Core\Attributes\Route;

class RoleController
{

    #[Route("/admin/nhom-quyen", "GET")]
    public static function index()
    {
        needAnyPermissionOrDie([Permission::READ_NHOMQUYEN, Permission::UPDATE_NHOMQUYEN, Permission::DELETE_NHOMQUYEN, Permission::CREATE_NHOMQUYEN]);
        $roles = RoleService::getAllRole([
            'trang-thais' => [
                TrangThaiNhomQuyen::Hien->value,
                TrangThaiNhomQuyen::An->value
            ]
        ]);
        return view('admin/role/index', ['roles' => $roles]);
    }
    #[Route("/admin/nhom-quyen/them", "GET")]
    public static function add()
    {
        needAnyPermissionOrDie([Permission::CREATE_NHOMQUYEN]);
        $permissions = PermissionService::getAllPermissions();
        return view('admin/role/add', ['permissions' => $permissions]);
    }
    #[Route("/admin/nhom-quyen/them", "POST")]
    public static function save()
    {
        needAnyPermissionOrDie([Permission::CREATE_NHOMQUYEN]);
        $data = request_body();
        $result = RoleService::addRole([
            "Quyen" => $data['permissions'],
            "TenNhomQuyen" => $data['tennhomquyen'],
            "MoTa" => $data['description']
        ]);
        if ($result) {
            return json(JsonResponse::ok([
                "MaNhomQuyen" => $result
            ]));
        } else {
            return json(JsonResponse::error("Thêm nhóm quyền thất bại"));
        }
    }
    #[Route("/admin/nhom-quyen/{id}/sua", "GET")]
    public static function edit($id)
    {
        error_log(print_r(Request::getUser(), true));
        needAnyPermissionOrDie([Permission::UPDATE_NHOMQUYEN]);
        $role = RoleService::getRoleById($id);
        $permissions = PermissionService::getAllPermissions();
        return view('admin/role/update', ['role' => $role, 'permissions' => $permissions]);
    }
    #[Route("/admin/nhom-quyen/{id}/sua", "PUT")]
    public static function update($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_NHOMQUYEN]);
        $data = request_body();
        RoleService::updateRole([
            "Quyen" => $data['permissions'],
            "MaNhomQuyen" => $id,
            "TenNhomQuyen" => $data['tennhomquyen'],
            "MoTa" => $data['description']
        ]);
        return json(JsonResponse::ok());

    }
    #[Route("/api/nhom-quyen/{id}", "DELETE")]
    public static function delete($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_NHOMQUYEN]);
        RoleService::deleteRole($id);
        return json(JsonResponse::ok());
    }

    #[Route("/api/nhom-quyen/{id}/trang-thai", "PUT")]
    public static function toggleStatus($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_NHOMQUYEN]);
        RoleService::toggleStatusRole($id);
        return json(JsonResponse::ok());
    }
}