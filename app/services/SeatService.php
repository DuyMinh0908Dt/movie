<?php


namespace App\Services;

use App\Core\Database\Database;
use App\Dtos\JsonDataErrorRespose;
use App\Dtos\JsonResponse;
use App\Dtos\TrangThaiGhe;
use App\Dtos\TrangThaiPhong;
use App\Dtos\TrangThaiVe;

class SeatService
{
    public static function createSeat($data)
    {
        $params = [
            'MaPhongChieu' => $data['MaPhongChieu'],
            'MaLoaiGhe' => $data['MaLoaiGhe'],
            'SoGhe' => $data['SoGhe'],
            'TrangThai' => $data['TrangThai'] ?? TrangThaiPhong::DangHoatDong->value
        ];
        $result = Database::insert('Ghe', $params);
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Tạo ghế thất bại', 500);
    }
    public static function createSeats($data)
    {
        Database::beginTransaction();
        $cleanData = array_map(function ($seat) {
            return [
                'MaPhongChieu' => intval($seat['MaPhongChieu']),
                'MaLoaiGhe' => intval($seat['MaLoaiGhe']),
                'SoGhe' => $seat['SoGhe'],
                'TrangThai' => $seat['TrangThai'] ?? TrangThaiPhong::DangHoatDong->value,
                "X" => intval($seat["X"]),
                "Y" => intval($seat["Y"])
            ];
        }, $data);
        $isSuccess = true;
        for ($i = 0; $i < count($cleanData); $i++) {
            $result = Database::insert('Ghe', $cleanData[$i]);
            if (!isset($result)) {
                $isSuccess = false;
                Database::rollBack();
                break;
            }
        }
        if ($isSuccess) {
            Database::commit();
            RoomService::updateRoomSeatCount($cleanData[0]['MaPhongChieu']);
            return JsonResponse::ok();
        }
        return JsonResponse::error('Tạo ghế thất bại', 500);
    }
    public static function getSeatsByRoomId($roomId)
    {
        $sql = "SELECT * FROM Ghe WHERE MaPhongChieu = ? AND TrangThai = ?";
        $seats = Database::query($sql, [$roomId, TrangThaiGhe::Hien->value]);
        return $seats;
    }
    public static function updateOfRoom($data)
    {

        $inputSeats = $data['inputSeats'];
        $roomID = $data['MaPhongChieu'];

        Database::beginTransaction();
        foreach ($inputSeats as $seat) {
            $params = [
                'MaLoaiGhe' => $seat['MaLoaiGhe'],
                'SoGhe' => $seat['SoGhe']
            ];
            if (!isset($seat['MaGhe'])) {
                $params['MaPhongChieu'] = $roomID;
                $params['TrangThai'] = TrangThaiGhe::Hien->value;
                $params['X'] = $seat['X'];
                $params['Y'] = $seat['Y'];
                $result = Database::insert('Ghe', $params);
                if (!$result) {
                    Database::rollBack();
                    return JsonResponse::error('Tạo ghế thất bại', 500);
                }
                continue;
            }
            $result = Database::update('Ghe', $params, "MaGhe = " . $seat['MaGhe']);
            if (!$result) {
                Database::rollBack();
                return JsonResponse::error('Cập nhật ghế thất bại', 500);
            }
        }
        $deleteSeats = $data['deleteSeats'];
        // just update trang thai to Hidden
        foreach ($deleteSeats as $seat) {
            $params = [
                'TrangThai' => TrangThaiGhe::An->value
            ];
            $result = Database::update('Ghe', $params, "MaGhe = " . $seat);
            if (!$result) {
                Database::rollBack();
                return JsonResponse::error('Cập nhật ghế thất bại', 500);
            }
        }
        Database::commit();
        RoomService::updateRoomSeatCount($data['MaPhongChieu']);
        return JsonResponse::ok();
    }
    public static function getSeatByIds($ids)
    {
        $sql = "SELECT * FROM Ghe JOIN LoaiGhe ON Ghe.MaLoaiGhe = LoaiGhe.MaLoaiGhe
        WHERE MaGhe IN  (" . implode(",", $ids) . ")";
        $seats = Database::query($sql, []);
        return $seats;
    }
    public static function getAllOfShow($showId)
    {
        $sql = "SELECT MaPhongChieu FROM SuatChieu WHERE MaXuatChieu = ?";
        $rooms = Database::queryOne($sql, [$showId]);
        $roomId = $rooms['MaPhongChieu'];

        $sql = "
        SELECT Ghe.*, Ve.MaVe from Ghe left join (
                        select * from Ve where Ve.MaSuatChieu = ?  and Ve.TrangThai = ? or (Ve.KhoaDen is not null and Ve.KhoaDen > ?)
                    ) as Ve on Ghe.MaGhe = Ve.MaGhe     
            where Ghe.MaPhongChieu = ?
            and Ghe.TrangThai = ?
        ";
        $seats = Database::query($sql, [$showId, TrangThaiVe::DaDat->value, date('Y-m-d H:i:s', time()), $roomId, TrangThaiGhe::Hien->value]);
        return $seats;
    }
}