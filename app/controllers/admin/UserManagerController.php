<?php

use App\Core\App;
use App\Core\Request;
use App\Dtos\JsonResponse;
use App\Dtos\Permission;
use App\Services\AccountService;
use App\Services\UserService;
use Core\Attributes\Controller;
use Core\Attributes\Route;

class UserManagerController
{


    #[Route('/admin/nguoi-dung', 'GET')]
    public static function quanLyNguoiDung()
    {
        return view('admin/nguoi-dung/index');
    }
    #[Route('/admin/nguoi-dung/them', 'GET')]
    public static function themNguoiDung()
    {

        return view('admin/nguoi-dung/add');
    }
    #[Route('/admin/nguoi-dung/{id}/sua', 'GET')]
    public static function suaNguoiDung($id)
    {
        $user = UserService::getUserById($id);
        return view('admin/nguoi-dung/edit', ['user' => $user]);
    }

    // #[Route('/api/nguoi-dung/{id}', 'GET')] -> lấy thông tin người dùng bằng id
    #[Route('/api/nguoi-dung/{id}', 'GET')]
    public static function layThongTin($id){
        $user=UserService::getUserInfo($id);
        return view('admin/nguoi-dung/index',['user'=>$user]);
    }
    // #[Route('/api/nguoi-dung/{id}', 'PUT')] -> cập nhật thông tin người dùng bằng id
    #[Route('/api/nguoi-dung/{id}','PUT')]
    public static function capnhatNguoiDung($id){
        needAnyPermissionOrDie([Permission::UPDATE_NGUOIDUNG]);
        $data = request_body();
        $result = UserService::updateUser($id, $data);
        if ($result) {
            return json(JsonResponse::ok());
        } else {
            return json(JsonResponse::error("Cập nhật người dùng thất bại"));
        }
    }
    // #[Route('/api/nguoi-dung/{id}', 'DELETE')] -> xóa người dùng bằng id
    #[Route('/api/nguoi-dung/{id}','DELETE')]
    public static function xoaNguoiDung($id){
        needAnyPermissionOrDie([Permission::DELETE_NGUOIDUNG]);
        $result=UserService::delete($id);
        return json($result);
    }
    // #[Route('/api/nguoi-dung', 'POST')] -> tạo mới người dùng
    #[Route('/api/nguoi-dung','POST')]
    public static function taoNguoiDung(){
        needAnyPermissionOrDie([Permission::CREATE_NGUOIDUNG]);
        $result = UserService::createNewUser(request_body());
        return json($result);
    }

    // #[Route('/api/nguoi-dung', 'GET')] -> lấy danh sách người dùng

    #[Route('/api/nguoi-dung','GET')]
    public static function layDSNguoiDung(){
        $query = $_GET;
        $user = UserService::getAllUser($query);
        return json(JsonResponse::ok($user));
    }

}