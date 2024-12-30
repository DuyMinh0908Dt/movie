<?php
namespace App\Services;

use App\Core\Database\Database;
use App\Dtos\JsonResponse;
use App\Dtos\TrangThaiLoaiVe;
use App\Dtos\TrangThaiVe;

class TicketService
{

    public static function getTicketTypeById($id)
    {
        return Database::queryOne("SELECT * FROM LoaiVe WHERE MaLoaiVe = ?", [$id]);
    }
    public static function isSeatLocked($seatId, $showId)
    {

        $ticket = Database::queryOne("SELECT * FROM Ve WHERE MaGhe = ? AND MaSuatChieu = ?", [$seatId, $showId]);
        // KhoaDen	type = timestamp	
        $trangThai = $ticket['TrangThai'];
        if ($trangThai == TrangThaiVe::DaDat) {
            return true;
        }
        if ($ticket['KhoaDen'] == null)
            return false;
        $now = time();
        $lockTime = strtotime($ticket['KhoaDen']);
        return $now < $lockTime;
    }

    public static function isAnySeatsLocked($seatIds, $showId)
    {
        $seatIds = implode(",", $seatIds);
        $tickets = Database::query("SELECT * FROM Ve WHERE MaGhe IN ($seatIds) AND MaSuatChieu = ?", [$showId]);
        $now = time();
        foreach ($tickets as $ticket) {
            // check if TrangThai = 4
            $trangThai = $ticket['TrangThai'];
            if ($trangThai == TrangThaiVe::DaDat) {
                return true;
            }
            if ($ticket['KhoaDen'] == null)
                continue;
            $lockTime = strtotime($ticket['KhoaDen']);
            if ($now < $lockTime) {
                return true;
            }
        }
        return false;
    }



    public static function lockTicket($ticketId)
    {
        $lockTime = time() + getArrayValueSafe($GLOBALS['config']['Website'], 'hold_time', 10) * 60;
        Database::execute("UPDATE Ve SET KhoaDen = ? WHERE id = ?", [date('Y-m-d H:i:s', $lockTime), $ticketId]);
        return $lockTime;
    }
    public static function lockSeats($seatIds, $showId)
    {
        $seatIds = implode(",", $seatIds);
        $lockTime = time() + getArrayValueSafe($GLOBALS['config']['Website'], 'hold_time', 10) * 60;
        Database::execute("UPDATE Ve SET KhoaDen = ? WHERE MaGhe IN ($seatIds) AND MaSuatChieu = ?", [date('Y-m-d H:i:s', $lockTime), $showId]);
        return $lockTime;
    }

    public static function getAllTicketsOfShow($showId)
    {
        return Database::query("SELECT * FROM Ve WHERE MaSuatChieu = ?", [$showId]);
    }

    public static function getTicketOfSeats($seatIds, $showId)
    {
        $seatIds = implode(",", $seatIds);
        $sql = "SELECT
        Ve.*,
        LoaiVe.TenLoaiVe
        FROM Ve JOIN LoaiVe ON Ve.MaLoaiVe = LoaiVe.MaLoaiVe
        WHERE Ve.MaSuatChieu = ? AND Ve.MaGhe IN ($seatIds)";
        return Database::query($sql, [$showId]);
    }


    public static function getTicketTypes()
    {
        return Database::query("SELECT * FROM LoaiVe", []);
    }

    public static function createNewTicketType($data)
    {
        $params = [
            'TenLoaiVe' => $data['TenLoaiVe'],
            'GiaVe' => $data['GiaVe'],
            'MoTa' => $data['MoTa'],
            'TrangThai' => $data['TrangThai'] ?? TrangThaiLoaiVe::DangHoatDong->value,
            'Rong' => $data['Rong'],
            'Mau' => $data['Mau']
        ];
        $result = Database::insert('LoaiVe', $params);
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Thêm mới thất bại', 500);
    }

    public static function updateTicketType($data, $id)
    {
        $params = [
            'TenLoaiVe' => $data['TenLoaiVe'],
            'GiaVe' => $data['GiaVe'],
            'MoTa' => $data['MoTa'],
            'Rong' => $data['Rong'],
            'Mau' => $data['Mau']
        ];
        $result = Database::update('LoaiVe', $params, "MaLoaiVe=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Cập nhật thất bại', 500);
    }

    public static function deleteTicketType($id)
    {
        $result = Database::delete('LoaiVe', "MaLoaiVe=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Xóa thất bại', 500);
    }

    public static function toggleHideTicketType($id)
    {
        $ticketType = Database::queryOne("SELECT * FROM LoaiVe WHERE MaLoaiVe = ?", [$id]);
        $trangThai = $ticketType['TrangThai'];
        if ($trangThai == TrangThaiLoaiVe::DangHoatDong->value) {
            $countTickets = Database::queryOne("SELECT COUNT(*) as count FROM Ve WHERE MaLoaiVe = ?", [$id]);
            if ($countTickets['count'] > 0) {
                $trangThai = TrangThaiLoaiVe::An->value;
            } else {
                return self::deleteTicketType($id);
            }
        } else {
            $trangThai = TrangThaiLoaiVe::DangHoatDong->value;
        }
        $result = Database::update('LoaiVe', ['TrangThai' => $trangThai], "MaLoaiVe=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Cập nhật thất bại', 500);
    }

    public static function createEmptyTickets($showId)
    {
        $show = ShowService::getShowById($showId);
        $roomId = $show['MaPhongChieu'];
        $seats = SeatService::getSeatsByRoomId($roomId);
        Database::beginTransaction();
        foreach ($seats as $seat) {
            $params = [
                'MaSuatChieu' => $showId,
                'MaGhe' => $seat['MaGhe'],
                'TrangThai' => TrangThaiVe::ChuaDat->value
            ];
            Database::insert('Ve', $params);
        }
        Database::commit();
        return true;
    }
    public static function getTicketByOrderId($orderId)
    {
        return Database::query("SELECT 
        Ve.*,
        LoaiVe.TenLoaiVe,
        Ghe.SoGhe,
        LoaiGhe.TenLoaiGhe,
        LoaiGhe.MaLoaiGhe

         FROM Ve
        JOIN LoaiVe ON Ve.MaLoaiVe = LoaiVe.MaLoaiVe
        JOIN Ghe ON Ve.MaGhe = Ghe.MaGhe
        Join LoaiGhe ON Ghe.MaLoaiGhe = LoaiGhe.MaLoaiGhe
        WHERE MaHoaDon = ?", [$orderId]);
    }
}