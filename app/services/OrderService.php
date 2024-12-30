<?php
namespace App\Services;

use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Core\Logger;
use App\Core\Request;
use App\Dtos\JsonResponse;
use App\Dtos\TrangThaiHoaDon;
use App\Dtos\TrangThaiVe;

class OrderService
{

    public static function startCheckout($data)
    {
        Database::beginTransaction();
        $tempId = uid();
        $DanhSachVe = $data['DanhSachVe'];
        $SeatIds = array_map(function ($item) {
            return $item['MaGhe'];
        }, $DanhSachVe);
        if (TicketService::isAnySeatsLocked($SeatIds, $data['MaXuatChieu'])) {
            return JsonResponse::error("Có vé đã bị đặt trong lúc bạn chọn, vui lòng chọn lại", 409);
        }

        $lockToTime = TicketService::lockSeats($SeatIds, $data['MaXuatChieu']);
        // tiền vé = tiền xuất chiếu + tiền loại ghế + tiền loại vé
        // tiền combo = tiền combo * số lượng
        // tiền thức ăn = tiền thức ăn * số lượng
        // tổng tiền = tiền vé + tiền combo + tiền thức ăn
        // tính tiền vé 
        foreach ($DanhSachVe as $ve) {
            Database::execute("UPDATE Ve SET MaLoaiVe = ?
             WHERE MaGhe = ? AND MaSuatChieu = ?",
                [$ve['MaLoaiVe'], $ve['MaGhe'], $data['MaXuatChieu']]
            );
        }
        $SeatIdsIn = implode(",", $SeatIds);
        $sql = "SELECT 
        LoaiVe.GiaVe+LoaiGhe.GiaVe+SuatChieu.GiaVe as GiaVe
         FROM Ve JOIN LoaiVe ON Ve.MaLoaiVe = LoaiVe.MaLoaiVe 
        JOIN Ghe ON Ve.MaGhe = Ghe.MaGhe 
        JOIN LoaiGhe ON Ghe.MaLoaiGhe = LoaiGhe.MaLoaiGhe
        JOIN SuatChieu ON Ve.MaSuatChieu = SuatChieu.MaXuatChieu
        WHERE Ve.MaSuatChieu = ? and Ve.MaGhe IN ($SeatIdsIn)";
        $ticketPrice = Database::query($sql, [$data['MaXuatChieu']]);

        $ticketPrice = array_reduce($ticketPrice, function ($acc, $item) {
            return $acc + $item['GiaVe'];
        }, 0);

        $comboPrice = ComboService::calCombosPrice($data['Combos']);
        $foodPrice = ComboService::calFoodsPrice($data['ThucPhams'] ?? []);
        $totalPrice = $ticketPrice + $comboPrice + $foodPrice;
        Database::commit();
        // set session 
        $bookingData = [
            'MaXuatChieu' => $data['MaXuatChieu'],
            'TongTien' => $totalPrice,
            "DanhSachVe" => $DanhSachVe,
            "ThucPhams" => $data['ThucPhams'],
            "Combos" => $data['Combos'],
            "lockTo" => $lockToTime,
            'userId' => isset($_SESSION['user']) ? $_SESSION['user']['MaNguoiDung'] : null,
            'id' => $tempId
        ];
        $_SESSION['bookingData'] = $bookingData;
        return JsonResponse::ok($bookingData);
    }
    public static function getOrderById($id)
    {
        $sql = "SELECT * FROM HoaDon WHERE MaHoaDon = ?";
        $order = Database::queryOne($sql, [$id]);
        if (!$order) {
            return null;
        }
        $sql = "SELECT Combo.*, CT_HoaDon_Combo.SoLuong, CT_HoaDon_Combo.ThanhTien FROM Combo JOIN CT_HoaDon_Combo ON Combo.MaCombo = CT_HoaDon_Combo.MaCombo WHERE CT_HoaDon_Combo.MaHoaDon = ?";
        $combos = Database::query($sql, [$id]);
        $sql = "SELECT ThucPham.*, CT_HoaDon_ThucPham.SoLuong, CT_HoaDon_ThucPham.ThanhTien FROM ThucPham JOIN CT_HoaDon_ThucPham ON ThucPham.MaThucPham = CT_HoaDon_ThucPham.MaThucPham WHERE CT_HoaDon_ThucPham.MaHoaDon = ?";
        $thucPhams = Database::query($sql, [$id]);
        $ve = TicketService::getTicketByOrderId($id);
        $suatChieu = ShowService::getShowById($ve[0]['MaSuatChieu']);
        $order['Combos'] = $combos;
        $order['ThucPhams'] = $thucPhams;
        $order['Ve'] = $ve;
        $order['SuatChieu'] = $suatChieu;
        $order['NguoiDung'] = UserService::getUserById($order['MaNguoiDung']);
        if (isset($order['MaKhuyenMai'])) {
            $order['KhuyenMai'] = PromotionService::getPromotionByCode($order['MaKhuyenMai']);
        }

        return $order;
    }
    public static function saveOrder($data)
    {
        $uid = $data['id'];
        // check if order exists
        $order = Database::queryOne("SELECT * FROM HoaDon WHERE MaHoaDon = ?", [$uid]);
        if ($order) {
            return $uid;
        }
        $TongTien = $data['TongTien'];
        $NgayGioThanhToan = date('Y-m-d H:i:s');
        $Thue = 0;
        $PhuongThucThanhToan = $data['payment_method'];
        $MaNguoiDung = null;
        if (isset($_SESSION['user'])) {
            $MaNguoiDung = $_SESSION['user']['MaNguoiDung'];
        } else {
            $MaNguoiDung = $data['userId'];
        }
        $MaKhuyenMai = null;
        if (isset($data['promotion_code'])) {
            $MaKhuyenMai = $data['promotion_code'];
        }
        $TrangThai = TrangThaiHoaDon::ThanhCong->value;
        Database::insert("HoaDon", [
            'MaHoaDon' => $uid,
            'TongTien' => $TongTien,
            'NgayGioThanhToan' => $NgayGioThanhToan,
            'Thue' => $Thue,
            'PhuongThucThanhToan' => $PhuongThucThanhToan,
            'MaNguoiDung' => $MaNguoiDung,
            'MaKhuyenMai' => $MaKhuyenMai,
            'TrangThai' => $TrangThai
        ]);
        $danhSachVe = array_map(fn($item) => $item['MaGhe'], $data['DanhSachVe']);
        foreach ($danhSachVe as $maGhe) {
            Database::execute("UPDATE Ve SET MaHoaDon = ? , TrangThai = ? WHERE MaGhe = ? AND MaSuatChieu = ?", [$uid, TrangThaiVe::DaDat->value, $maGhe, $data['MaXuatChieu']]);
        }
        $combos = $data['Combos'];
        foreach ($combos as $combo) {
            Database::insert("CT_HoaDon_Combo", [
                'MaHoaDon' => $uid,
                'MaCombo' => $combo['MaCombo'],
                'SoLuong' => $combo['SoLuong']
            ]);
        }
        $thucPhams = $data['ThucPhams'];
        foreach ($thucPhams as $thucPham) {
            Database::insert("CT_HoaDon_ThucPham", [
                'MaHoaDon' => $uid,
                'MaThucPham' => $thucPham['MaThucPham'],
                'SoLuong' => $thucPham['SoLuong']
            ]);
        }
        // 1k = 1 point
        $point = floor($TongTien / 1000);
        UserService::plusPoint($MaNguoiDung, $point);
        return $uid;
    }
    public static function searchOrder($query = [])
    {
        $keyword = getArrayValueSafe($query, 'tu-khoa', '');
        $MaNguoiDung = getArrayValueSafe($query, 'ma-nguoi-dung', '');
        $tongTienTu = getArrayValueSafe($query, 'tong-tien-tu', '');
        $tongTienDen = getArrayValueSafe($query, 'tong-tien-den', '');
        $tuNgay = getArrayValueSafe($query, 'tu-ngay', '');
        $denNgay = getArrayValueSafe($query, 'den-ngay', '');
        $phuongThucThanhToan = getArrayValueSafe($query, 'phuong-thuc-thanh-toan', '');
        $page = getArrayValueSafe($query, 'trang', 1);
        $pageSize = getArrayValueSafe($query, 'limit', 20);
        $offset = ($page - 1) * $pageSize;
        $orderBy = getArrayValueSafe($query, 'sap-xep', 'MaHoaDon');
        $orderType = getArrayValueSafe($query, 'thu-tu', 'desc');

        $queryBuilder = new QueryBuilder();
        $queryBuilder->select(["HoaDon.*", "NguoiDung.TenNguoiDung"])
            ->from("HoaDon")
            ->join("NguoiDung", "HoaDon.MaNguoiDung = NguoiDung.MaNguoiDung", "left")
            ->where("1", "=", "1");
        if ($keyword) {
            $queryBuilder->and();
            $queryBuilder->startGroup();
            $queryBuilder->where('HoaDon.MaHoaDon', 'like', "%$keyword%");
            $queryBuilder->orWhere('NguoiDung.TenNguoiDung', 'like', "%$keyword%");
            $queryBuilder->orWhere('NguoiDung.Email', 'like', "%$keyword%");
            $queryBuilder->orWhere('NguoiDung.SoDienThoai', 'like', "%$keyword%");
            $queryBuilder->orWhere('HoaDon.MaKhuyenMai', 'like', "%$keyword%");
            $queryBuilder->endGroup();
        }
        if ($MaNguoiDung) {
            $queryBuilder->andWhere('HoaDon.MaNguoiDung', '=', $MaNguoiDung);
        }
        if ($tongTienTu) {
            $queryBuilder->andWhere('HoaDon.TongTien', '>=', $tongTienTu);
        }
        if ($tongTienDen) {
            $queryBuilder->andWhere('HoaDon.TongTien', '<=', $tongTienDen);
        }
        if ($tuNgay) {
            $queryBuilder->andWhere('HoaDon.NgayGioThanhToan', '>=', $tuNgay);
        }
        if ($denNgay) {
            $queryBuilder->andWhere('HoaDon.NgayGioThanhToan', '<=', $denNgay);
        }
        if ($phuongThucThanhToan) {
            $queryBuilder->andWhere('HoaDon.PhuongThucThanhToan', '=', $phuongThucThanhToan);
        }
        $count = $queryBuilder->count();
        $queryBuilder->orderBy('HoaDon.' . $orderBy, $orderType);
        $queryBuilder->limit($pageSize, $offset);
        $result = $queryBuilder->get();
        Request::setQueryCount($count);
        return $result;
    }
    public static function isUserOrdered($MaNguoiDung)
    {
        $sql = "SELECT * FROM HoaDon WHERE MaNguoiDung = ? ";
        $order = Database::queryOne($sql, [$MaNguoiDung]);
        if (isset($order)) {
            return true;
        }
        return false;
    }
}