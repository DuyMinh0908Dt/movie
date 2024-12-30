<?php
namespace App\Services;

use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Core\Logger;
use App\Core\Request;
use App\Dtos\JsonResponse;
use App\Dtos\TrangThaiRap;

class CinemaService
{
    public static function getCinemasInIds($ids)
    {
        $sql = "SELECT MaRapChieu, TenRapChieu FROM RapChieu WHERE MaRapChieu IN (" . implode(",", $ids) . ")";
        $cinemas = Database::query($sql, []);
        return $cinemas;
    }


    public static function getAllCinemas($query = [])
    {

        $queryBuilder = new QueryBuilder();
        $keyword = getArrayValueSafe($query, 'tu-khoa');
        $statuses = getArrayValueSafe($query, 'trang-thais');
        $page = ifNullOrEmptyString(getArrayValueSafe($query, 'trang'), 1);
        $limit = ifNullOrEmptyString(getArrayValueSafe($query, 'limit'), 100);
        $order = getArrayValueSafe($query, 'sap-xep', 'MaRapChieu');
        $sort = getArrayValueSafe($query, 'thu-tu', 'ASC');
        $offset = ($page - 1) * $limit;
        $queryBuilder->select(['RapChieu.*'])
            ->from('RapChieu')
            ->where('1', '=', '1');
        if (!isNullOrEmptyString($keyword)) {
            $queryBuilder->and();
            $queryBuilder->startGroup();
            $queryBuilder->where('TenRapChieu', 'LIKE', "%$keyword%");
            $queryBuilder->orWhere('DiaChi', 'LIKE', "%$keyword%");
            $queryBuilder->endGroup();
        }
        if (!isNullOrEmptyArray($statuses)) {
            $queryBuilder->and();
            $queryBuilder->where('TrangThai', 'IN', $statuses);
        }
        $total = $queryBuilder->count();
        $queryBuilder->orderBy($order, $sort);
        $queryBuilder->limit($limit, $offset);
        $cinemas = $queryBuilder->get();
        Request::setQueryCount($total);
        return $cinemas;

    }
    public static function getCinemaById($id)
    {
        $sql = "SELECT * FROM RapChieu WHERE MaRapChieu = ?";
        $cinema = Database::queryOne($sql, [$id]);
        return $cinema;
    }
    public static function getCinemaByRoomId($roomId)
    {
        $sql = "SELECT RapChieu.* FROM RapChieu JOIN PhongChieu ON RapChieu.MaRapChieu = PhongChieu.MaRapChieu WHERE PhongChieu.MaPhongChieu = ?";
        $cinema = Database::queryOne($sql, [$roomId]);
        return $cinema;
    }
    public static function getCinemasByRoomIds($roomIds)
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->select(
            [
                'PhongChieu.MaPhongChieu' => 'PhongChieu.MaPhongChieu',
                'PhongChieu.TenPhongChieu' => 'PhongChieu.TenPhongChieu',
                'PhongChieu.ManHinh' => 'PhongChieu.ManHinh',
                'PhongChieu.ChieuDai' => 'PhongChieu.ChieuDai',
                'PhongChieu.ChieuRong' => 'PhongChieu.ChieuRong',
                'RapChieu.MaRapChieu' => 'RapChieu.MaRapChieu',
                'RapChieu.TenRapChieu' => 'RapChieu.TenRapChieu',
                'RapChieu.DiaChi' => 'RapChieu.DiaChi',
                'RapChieu.TrangThai' => 'RapChieu.TrangThai'
            ]
        )->from('RapChieu')
            ->join('PhongChieu', 'RapChieu.MaRapChieu = PhongChieu.MaRapChieu')
            ->where('PhongChieu.MaPhongChieu', 'IN', $roomIds);
        $data = $queryBuilder->get();
        return $queryBuilder->parseMany($data, [
            'root' => 'RapChieu',
            'embed' => ['PhongChieu']
        ]);

    }

    public static function createNewCinema($data)
    {
        $params = [
            'TenRapChieu' => $data['TenRapChieu'],
            'DiaChi' => $data['DiaChi'],
            'HinhAnh' => $data['HinhAnh'],
            'MoTa' => $data['MoTa'],
            'TrangThai' => $data['TrangThai'] ?? TrangThaiRap::Hien->value
        ];
        $result = Database::insert('RapChieu', $params);
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Thêm mới thất bại', 500);
    }

    public static function updateCinema($data, $id)
    {
        $params = [
            'TenRapChieu' => $data['TenRapChieu'],
            'DiaChi' => $data['DiaChi'],
            'HinhAnh' => $data['HinhAnh'],
            'MoTa' => $data['MoTa'],
            'TrangThai' => $data['TrangThai']
        ];
        $result = Database::update('RapChieu', $params, "MaRapChieu=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Cập nhật thất bại', 500);
    }
    public static function toggleHideCinema($id)
    {
        // kiểm tra xem có phải đang ẩn hay không
        $sql = "SELECT TrangThai FROM RapChieu WHERE MaRapChieu = ?";
        $status = Database::queryOne($sql, [$id]);
        if ($status['TrangThai'] == TrangThaiRap::An->value) {
            $result = Database::update('RapChieu', ['TrangThai' => TrangThaiRap::Hien->value], "MaRapChieu=$id");
        } else {
            // kiểm tra xem có xoá được hay không
            $sql = "SELECT COUNT(*) as count FROM PhongChieu WHERE MaRapChieu = ?";
            $count = Database::queryOne($sql, [$id]);
            if ($count['count'] > 0) {
                $result = Database::update('RapChieu', ['TrangThai' => TrangThaiRap::An->value], "MaRapChieu=$id");
            } else {
                return self::deleteCinema($id);
            }
        }
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Cập nhật thất bại', 500);
    }
    public static function deleteCinema($id)
    {
        $result = Database::delete('RapChieu', "MaRapChieu=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Xóa thất bại', 500);
    }
}