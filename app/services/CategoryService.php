<?php
namespace App\Services;

use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Dtos\JsonResponse;

class CategoryService
{
    public static function getAllCategories($query = [])
    {
        $queryBuilder = new QueryBuilder();
        $keywords = getArrayValueSafe($query, 'tu-khoa');
        $page = getArrayValueSafe($query, 'trang', 1);
        $limit = getArrayValueSafe($query, 'limit', 10000);
        $offset = ($page - 1) * $limit;
        $orderBy = getArrayValueSafe($query, 'sap-xep', 'MaTheLoai');
        $orderType = getArrayValueSafe($query, 'thu-tu', 'desc');
        $queryBuilder->select(['*'])
            ->from('TheLoai');
        if ($keywords) {
            $queryBuilder->where('TenTheLoai', 'like', "%$keywords%");
        }
        $queryBuilder->orderBy($orderBy, $orderType);
        $queryBuilder->limit($limit, $offset);
        $categories = $queryBuilder->get();
        return $categories;

    }

    public static function getCategoriesByMovieId($id)
    {
        $sql = "SELECT TheLoai.* FROM TheLoai JOIN CT_Phim_TheLoai ON TheLoai.MaTheLoai = CT_Phim_TheLoai.MaTheLoai WHERE CT_Phim_TheLoai.MaPhim = ?";
        $categories = Database::query($sql, [$id]);
        return $categories;
    }

    // insert
    public static function createNewCategory($data)
    {
        $params = [
            'TenTheLoai' => $data['TenTheLoai'],
        ];
        $result = Database::insert('TheLoai', $params);
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Thêm mới thất bại', 500);
    }


    public static function updateCategory($data, $id)
    {
        $params = [
            'TenTheLoai' => $data['TenTheLoai'],
        ];
        $result = Database::update('TheLoai', $params, "MaTheLoai=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Không thể cập nhật', 500);
    }

    public static function deleteCategory($id)
    {
        $result = Database::delete('TheLoai', "MaTheLoai=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Xóa thất bại', 500);
    }

}