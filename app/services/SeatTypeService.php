<?php

namespace App\Services;

use App\Core\Database\Database;
use App\Dtos\JsonDataErrorRespose;
use App\Dtos\JsonResponse;
use App\Dtos\TrangThaiLoaiGhe;


class SeatTypeService
{
    public static function getAllSeatType()
    {
        $sql = "SELECT * FROM LoaiGhe";
        $seatType = Database::query($sql, []);
        return $seatType;
    }
    public static function getSeatTypeById($id)
    {
        $sql = "SELECT * FROM LoaiGhe WHERE MaLoaiGhe=$id";
        $seatType = Database::queryOne($sql, []);
        return $seatType;
    }
    public static function getSeatTypeByIds($ids)
    {
        $sql = "SELECT * FROM LoaiGhe WHERE MaLoaiGhe IN (" . implode(",", $ids) . ")";
        $seatType = Database::query($sql, []);
        return $seatType;
    }
    public static function createNewSeatType($data)
    {
        $params = [
            'TenLoaiGhe' => $data['TenLoaiGhe'],
            'MoTa' => $data['MoTa'],
            'GiaVe' => $data['GiaVe'],
            'Rong' => $data['Rong'],
            'Mau' => $data['Mau'],
            'TrangThai' => $data['TrangThai'] ?? TrangThaiLoaiGhe::Hien->value
        ];
        $result = Database::insert('LoaiGhe', $params);
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Tạo loại ghế thất bại', 500);
    }

    public static function toggleHideSeatType($id)
    {
        $seatType = Database::queryOne("SELECT * FROM LoaiGhe WHERE MaLoaiGhe = ?", [$id]);
        $trangThai = $seatType['TrangThai'];
        if ($trangThai == TrangThaiLoaiGhe::Hien->value) {
            $countSeats = Database::queryOne("SELECT COUNT(*) as count FROM Ghe WHERE MaLoaiGhe = ?", [$id]);
            if ($countSeats['count'] > 0) {
                $trangThai = TrangThaiLoaiGhe::An->value;
            } else {
                return self::deleteSeatType($id);
            }
        } else {
            $trangThai = TrangThaiLoaiGhe::Hien->value;
        }
        $result = Database::update('LoaiGhe', ['TrangThai' => $trangThai], "MaLoaiGhe=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Cập nhật thất bại', 500);
    }
    public static function updateSeatType($data, $id)
    {
        $params = [
            'TenLoaiGhe' => $data['TenLoaiGhe'],
            'MoTa' => $data['MoTa'],
            'GiaVe' => $data['GiaVe'],
            'Rong' => $data['Rong'],
            'Mau' => $data['Mau'],
        ];
        $result = Database::update('LoaiGhe', $params, "MaLoaiGhe=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Cập nhật thất bại', 500);
    }

    public static function deleteSeatType($id)
    {
        $result = Database::delete('LoaiGhe', "MaLoaiGhe=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error("Xóa thất bại", 500);
    }

}