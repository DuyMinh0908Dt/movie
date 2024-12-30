<?php
use App\Dtos\Permission;
use App\Services\CategoryService;
use Core\Attributes\Route;

class FilmCategoryController
{
    #[Route("/admin/the-loai", "GET")]
    public static function quanLyTheLoaiPhim()
    {
        needAnyPermissionOrDie([Permission::READ_THELOAI, Permission::UPDATE_THELOAI, Permission::DELETE_THELOAI, Permission::CREATE_THELOAI]);
        return view("admin/the-loai-phim/index");
    }
    #[Route("/api/the-loai", "GET")]
    public static function getApiTheLoai()
    {
        needAnyPermissionOrDie([Permission::READ_THELOAI, Permission::UPDATE_THELOAI, Permission::DELETE_THELOAI, Permission::CREATE_THELOAI]);
        $theLoai = CategoryService::getAllCategories($_GET);
        return json($theLoai);
    }
    #[Route("/api/the-loai", "POST")]
    public static function postApiTheLoai()
    {
        needAnyPermissionOrDie([Permission::CREATE_THELOAI]);
        $data = request_body();
        $result = CategoryService::createNewCategory($data);
        return json($result);
    }
    #[Route("/api/the-loai/{id}", "PUT")]
    public static function putApiTheLoai($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_THELOAI]);
        $data = request_body();
        $result = CategoryService::updateCategory( $data,$id);
        return json($result);
    }
    #[Route("/api/the-loai/{id}", "DELETE")]
    public static function deleteApiTheLoai($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_THELOAI]);
        $result = CategoryService::deleteCategory($id);
        return json($result);
    }

}