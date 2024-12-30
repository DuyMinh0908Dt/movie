<?php

use App\Dtos\JsonResponse;
use App\Dtos\Permission;
use App\Services\AccountService;
use App\Services\RoleService;
use App\Services\StatusService;
use App\Services\UserService;
use Core\Attributes\Route;

class AccountController
{

    #[Route(path: '/admin/tai-khoan', method: 'GET')]
    public static function index()
    {
        needAnyPermissionOrDie([Permission::READ_TAIKHOAN, Permission::UPDATE_TAIKHOAN, Permission::DELETE_TAIKHOAN, Permission::CREATE_TAIKHOAN]);
        $statuses = StatusService::getAllStatus('TaiKhoan');
        $roles = RoleService::getAllRole();
        return view('admin/account/index', ['statuses' => $statuses, 'roles' => $roles]);
    }
    #[Route(path: '/api/tai-khoan', method: 'GET')]
    public static function getAllAccount()
    {
        needAnyPermissionOrDie([Permission::READ_TAIKHOAN, Permission::UPDATE_TAIKHOAN, Permission::DELETE_TAIKHOAN, Permission::CREATE_TAIKHOAN]);
        return json(JsonResponse::ok(AccountService::getAllAccount($_GET)));
    }
    #[Route(path: '/admin/tai-khoan/them', method: 'GET')]
    public static function addView()
    {

        needAnyPermissionOrDie([Permission::CREATE_TAIKHOAN]);

        $employees = UserService::getAllUser([
            'co-tai-khoan' => false,
            'limit' => 10000,
            'diem-tich-luy-tu'=>0,
            'diem-tich-luy-den'=>1
        ]);
        $roles = RoleService::getAllRole();
        $statuses = StatusService::getAllStatus('TaiKhoan');
        return view('admin/account/add', ['employees' => $employees, 'roles' => $roles, 'statuses' => $statuses]);
    }
    #[Route(path: '/admin/tai-khoan/them', method: 'POST')]
    public static function add()
    {
        needAnyPermissionOrDie([Permission::CREATE_TAIKHOAN]);
        $data = request_body();

        $result = AccountService::createNewAccount($data);
        return json($result);
    }
    #[Route(path: '/api/tai-khoan/{id}/set-password', method: 'PATCH')]
    public static function setPassword($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_TAIKHOAN]);
        $data = request_body();
        return json(AccountService::setPassword($id, $data));
    }
    #[Route(path: '/api/tai-khoan/{id}/nhom-quyen', method: 'PATCH')]
    public static function setRole($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_TAIKHOAN]);
        $data = request_body();
        return json(AccountService::setRole($id, $data));
    }
    #[Route(path: '/api/tai-khoan/{id}', method: 'DELETE')]
    public static function delete($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_TAIKHOAN]);
        return json(AccountService::deleteAccount($id));
    }
    #[Route(path: '/api/tai-khoan/{id}/chuyen-trang-thai', method: 'PATCH')]
    public static function changeStatus($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_TAIKHOAN]);
        return json(AccountService::toggleLockAccount($id));
    }
}