<?php

namespace App\Services;

use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Core\Logger;
use App\Core\Request;
use App\Dtos\JsonResponse;
use App\Dtos\TrangThaiGhe;
use App\Dtos\TrangThaiPhong;
use App\Dtos\TrangThaiVe;

class RoomService
{
    public static function getAllRooms($params)
    {
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $order = $params['order'] ?? 'ASC';
        $orderBy = $params['orderBy'] ?? 'MaPhongChieu';
        $keyword = $params['keyword'] ?? '';
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM PhongChieu WHERE TenPhongChieu LIKE " . "'%$keyword%'" . " ";
        if (isset($params['MaRapChieu']) && !empty($params['MaRapChieu'])) {
            $sql = $sql . "AND MaRapChieu = " . $params['MaRapChieu'] . " ";
        }

        $total = Database::count($sql, []);
        $sql = $sql . "ORDER BY $orderBy $order LIMIT $offset, $limit";
        $rooms = Database::query($sql, []);
        return [
            'total' => $total,
            'rooms' => $rooms
        ];
    }
    public static function updateRoom($id, $data)
    {
        $params = [
            'TenPhongChieu' => $data['TenPhongChieu'],
            'ManHinh' => $data['ManHinh'],
            'MaRapChieu' => $data['MaRapChieu'],
            'TrangThai' => $data['TrangThai'] ?? TrangThaiPhong::DangBaoTri->value
        ];
        $result = Database::update('PhongChieu', $params, "MaPhongChieu = $id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Cập nhật phòng chiếu thất bại', 500);
    }
    public static function setRoomStatus(int $id, int $status)
    {
        $sql = "UPDATE RapChieu SET TrangThai = ? WHERE MaRapChieu = ?";
        $result = Database::execute($sql, [$status, $id]);
        return $result;
    }
    public static function createRoom($data)
    {
        $params = [
            'TenPhongChieu' => $data['TenPhongChieu'],
            'ManHinh' => $data['ManHinh'],
            'ChieuDai' => $data['ChieuDai'],
            'ChieuRong' => $data['ChieuRong'],
            'MaRapChieu' => $data['MaRapChieu'],
            'TrangThai' => $data['TrangThai'] ?? TrangThaiPhong::DangHoatDong->value
        ];
        $result = Database::insert('PhongChieu', $params);
        if ($result) {
            return JsonResponse::ok([
                'MaPhongChieu' => $result
            ]);
        }
        return JsonResponse::error('Tạo phòng chiếu thất bại', 500);
    }
    public static function getRoomById($id)
    {
        $sql = "SELECT * FROM PhongChieu WHERE MaPhongChieu = ?";
        $room = Database::queryOne($sql, [$id]);
        $cinema = CinemaService::getCinemaById($room['MaRapChieu']);
        $room['RapChieu'] = $cinema;
        return $room;
    }
    public static function getRoomByIds($ids)
    {
        $sql = "SELECT * FROM PhongChieu WHERE MaPhongChieu IN (" . implode(',', $ids) . ")";
        $rooms = Database::query($sql, []);
        return $rooms;
    }

    public static function updateRoomSeatCount($roomId)
    {
        $sql = "SELECT COUNT(*) as count FROM Ghe WHERE MaPhongChieu = ? and TrangThai = ?";
        $count = Database::queryOne($sql, [$roomId, TrangThaiGhe::Hien->value]);
        $sql = "UPDATE PhongChieu SET SoGhe = ? WHERE MaPhongChieu = ?";
        $result = Database::execute($sql, [$count['count'], $roomId]);
        return $result;
    }
    public static function getAllRoomOfCinema($cinemaId, $query)
    {
        $queryBuilder = new QueryBuilder();
        $keyword = getArrayValueSafe($query, 'tu-khoa');
        $statuses = getArrayValueSafe($query, 'trang-thais', []);
        $page = ifNullOrEmptyString(getArrayValueSafe($query, 'trang'), 1);
        $limit = ifNullOrEmptyString(getArrayValueSafe($query, 'limit'), 10);

        $queryBuilder->select([
            'PhongChieu.*',
        ])->from('PhongChieu')
            ->where('MaRapChieu', '=', $cinemaId);
        if (!isNullOrEmptyArray($statuses)) {
            $statuses = implode(',', $statuses);
            $queryBuilder->andWhere('TrangThai', 'IN', $statuses);
        }
        if (!isNullOrEmptyString($keyword)) {
            $queryBuilder->and();
            $queryBuilder->startGroup();
            $queryBuilder->where('TenPhongChieu', 'LIKE', "%$keyword%");
            $queryBuilder->orWhere('ManHinh', 'LIKE', "%$keyword%");
            $queryBuilder->endGroup();
        }
        $total = $queryBuilder->count();

        $queryBuilder->orderBy('MaPhongChieu', 'ASC');
        Request::setQueryCount($total);
        $queryBuilder->limit($limit, ($page - 1) * $limit);
        $rooms = $queryBuilder->get();
        return $rooms;

    }


    public static function canEditRoom($roomId)
    {
        $sql = "SELECT COUNT(*) as count FROM SuatChieu WHERE MaPhongChieu = ? AND NgayGioChieu >= CURDATE()";
        $count = Database::queryOne($sql, [$roomId]);
        return $count['count'] == 0;
    }

    public static function deleteRoom($roomId)
    {
        $sql = "SELECT COUNT(*) as count FROM SuatChieu WHERE MaPhongChieu = ? ";
        $count = Database::queryOne($sql, [$roomId]);
        $canDelete = $count['count'] == 0;
        if ($canDelete) {
            $result = Database::delete('PhongChieu', "MaPhongChieu = $roomId");
            if ($result) {
                return JsonResponse::ok();
            }
            return JsonResponse::error('Xóa phòng chiếu thất bại', 500);
        }
        return JsonResponse::error('Không thể xóa phòng chiếu vì đã có suất chiếu', 400);
    }

    public static function toggleHideRoom($roomId)
    {
        $sql = "SELECT TrangThai FROM PhongChieu WHERE MaPhongChieu = ?";
        $status = Database::queryOne($sql, [$roomId]);
        $newStatus = $status['TrangThai'] == TrangThaiPhong::DangBaoTri->value ? TrangThaiPhong::DangHoatDong->value : TrangThaiPhong::DangBaoTri->value;
        $result = Database::update('PhongChieu', ['TrangThai' => $newStatus], "MaPhongChieu = $roomId");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Cập nhật trạng thái phòng chiếu thất bại', 500);
    }

}