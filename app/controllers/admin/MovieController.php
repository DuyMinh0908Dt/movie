<?php

use App\Core\Logger;
use App\Dtos\JsonResponse;
use App\Dtos\Permission;
use App\Services\CategoryService;
use App\Services\PhimService;
use App\Services\ShowService;
use App\Services\StatusService;
use Core\Attributes\Route;

class MovieController
{

    #[Route("/api/phim/tim-kiem-nang-cao", "POST")]
    public static function advancedSearch()
    {
        $data = request_body();
        $phims = ShowService::advanceSearch($data) ?? [];
        return json(JsonResponse::ok($phims));
    }
    #[Route("/api/phim", "GET")]
    public static function search()
    {
        $query = $_GET;
        $phims = PhimService::getTatCaPhim($query);
        return json(JsonResponse::ok($phims));
    }
    #[Route(path: '/admin/phim', method: 'GET')]
    public static function index()
    {

        needAnyPermissionOrDie([Permission::READ_PHIM, Permission::UPDATE_PHIM, Permission::DELETE_PHIM, Permission::CREATE_PHIM]);
        $phimStatuses = StatusService::getAllStatus("Phim");
        $categories = CategoryService::getAllCategories();
        return view(
            'admin/movie/index',
            ['phimStatuses' => $phimStatuses, 'categories' => $categories]
        );
    }
    #[Route(path: '/admin/phim/them', method: 'GET')]
    public static function add()
    {
        needAnyPermissionOrDie([Permission::CREATE_PHIM]);
        $phimStatuses = StatusService::getAllStatus("Phim");
        $categories = CategoryService::getAllCategories();
        return view('admin/movie/add', ['phimStatuses' => $phimStatuses, 'categories' => $categories]);
    }
    #[Route(path: '/admin/phim/them', method: 'POST')]
    public static function save()
    {
        needAnyPermissionOrDie([Permission::CREATE_PHIM]);
        $data = request_body();
        $result = PhimService::createMovie($data);
        if ($result) {
            return json(JsonResponse::ok([
                "MaPhim" => $result
            ]));
        } else {
            return json(JsonResponse::error("Thêm phim thất bại"));
        }
    }
    #[Route(path: '/admin/phim/{id}/sua', method: 'GET')]
    public static function edit($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_PHIM]);
        $phim = PhimService::getPhimById($id);
        $phimStatuses = StatusService::getAllStatus("Phim");
        $categories = CategoryService::getAllCategories();
        return view('admin/movie/edit', ['phim' => $phim, 'phimStatuses' => $phimStatuses, 'categories' => $categories]);
    }
    #[Route(path: '/admin/phim/{id}/sua', method: 'PUT')]
    public static function update($id)
    {
        needAnyPermissionOrDie([Permission::UPDATE_PHIM]);
        $data = request_body();
        $result = PhimService::updateMovie($id, $data);
        if ($result) {
            return json(JsonResponse::ok());
        } else {
            return json(JsonResponse::error("Cập nhật phim thất bại"));
        }
    }

    #[Route(path: '/api/phim/{id}/can-delete', method: 'GET')]
    public static function checkCanDelete($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_PHIM]);
        $result = ShowService::isMovieHasAnyShow($id);
        return json(JsonResponse::ok([
            "canDelete" => !$result
        ]));
    }
    #[Route(path: '/api/phim/{id}/xoa', method: 'DELETE')]
    public static function delete($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_PHIM]);
        $result = PhimService::deleteMovie($id);
        if ($result) {
            return json(JsonResponse::ok());
        } else {
            return json(JsonResponse::error("Không thể xóa phim này"));
        }
    }
    #[Route(path: '/api/phim/{id}/toggle-hidden', method: 'PATCH')]
    public static function toggleHide($id)
    {
        needAnyPermissionOrDie([Permission::DELETE_PHIM]);
        $result = PhimService::toggleHide($id);

        return json(JsonResponse::ok([
            'status' => $result
        ]));

    }
}