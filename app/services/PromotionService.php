<?php

namespace App\Services;

use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Core\Logger;
use App\Core\Request;
use App\Dtos\JsonDataErrorRespose;
use App\Dtos\JsonResponse;
use App\Dtos\TrangThaiKhuyenMai;

class PromotionService
{

    public static function getPromotionByCode($code)
    {
        $query = "SELECT * FROM KhuyenMai WHERE BINARY MaKhuyenMai = ?";
        $promotion = Database::queryOne($query, [$code]);
        return $promotion;
    }
    public static function toggleHidePromotion($id)
    {
        $promotion = self::getPromotionByCode($id);
        if (!$promotion) {
            return JsonResponse::error("Không tìm thấy mã khuyến mãi");
        }
        $newStatus = $promotion['TrangThai'] == TrangThaiKhuyenMai::An->value ? TrangThaiKhuyenMai::DangHoatDong->value : TrangThaiKhuyenMai::An->value;
        $result = Database::update('KhuyenMai', ['TrangThai' => $newStatus], "MaKhuyenMai='$id'");
        if ($result) {
            return JsonResponse::ok();
        } else {
            return JsonResponse::error('Cập nhật thất bại', 500);
        }

    }
    public static function getAllDiscount()
    {
        $sql = "SELECT * FROM KhuyenMai";
        $discount = Database::query($sql, []);
        return $discount;
    }
    public static function getDiscountByDayEnd()
    {
        $sql = "SELECT * FROM KhuyenMai WHERE NgayKetThuc > NOW()";
        $discount = Database::query($sql, []);
        return $discount;
    }

    public static function createNewDiscount($data)
    {
        $params = [
            'MaKhuyenMai' => $data['MaKhuyenMai'],
            'TenKhuyenMai' => $data['TenKhuyenMai'],
            'MoTa' => $data['MoTa'],
            'NgayBatDau' => getArrayValueSafe($data, 'NgayBatDau', null),
            'NgayKetThuc' => getArrayValueSafe($data, 'NgayKetThuc', null),
            'GiamToiDa' => getArrayValueSafe($data, 'GiamToiDa', null),
            'GiaTriGiam' => $data['GiaTriGiam'],
            'GioiHanSuDung' => getArrayValueSafe($data, 'GioiHanSuDung', null),
            'GioiHanTrenKhachHang' => getArrayValueSafe($data, 'GioiHanTrenKhachHang', null),
            'GiaTriToiThieu' => getArrayValueSafe($data, 'GiaTriToiThieu', null),
            'TrangThai' => getArrayValueSafe($data, 'TrangThai', TrangThaiKhuyenMai::DangHoatDong->value),
            'MaLoaiVe' => getArrayValueSafe($data, 'MaLoaiVe', null),
            'DiemToiThieu' => getArrayValueSafe($data, 'DiemToiThieu', 0)
        ];
        $result = Database::insert('KhuyenMai', $params);
        if ($result) {
            return JsonResponse::ok([
                'MaKhuyenMai' => $data['MaKhuyenMai'],
            ]);
        }
        return JsonResponse::error('Thêm mới thất bại', 500);
    }

    public static function updateDiscount($data, $id)
    {
        $params = [
            'TenKhuyenMai' => $data['TenKhuyenMai'],
            'MoTa' => $data['MoTa'],
            'NgayBatDau' => getArrayValueSafe($data, 'NgayBatDau', null),
            'NgayKetThuc' => getArrayValueSafe($data, 'NgayKetThuc', null),
            'GiamToiDa' => getArrayValueSafe($data, 'GiamToiDa', null),
            'GiaTriGiam' => $data['GiaTriGiam'],
            'GioiHanSuDung' => getArrayValueSafe($data, 'GioiHanSuDung', null),
            'GioiHanTrenKhachHang' => getArrayValueSafe($data, 'GioiHanTrenKhachHang', null),
            'GiaTriToiThieu' => getArrayValueSafe($data, 'GiaTriToiThieu', null),
            'TrangThai' => getArrayValueSafe($data, 'TrangThai', TrangThaiKhuyenMai::DangHoatDong->value),
            'MaLoaiVe' => getArrayValueSafe($data, 'MaLoaiVe', null),
            'DiemToiThieu' => getArrayValueSafe($data, 'DiemToiThieu', 0)
        ];
        $result = Database::update('KhuyenMai', $params, "MaKhuyenMai='$id'");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Cập nhật thất bại', 500);
    }

    public static function deleteDiscount($id)
    {
        // check if any bill use this discount
        $query = "SELECT COUNT(*) as count FROM HoaDon WHERE MaKhuyenMai = ?";
        $count = Database::queryOne($query, [$id]);
        if ($count['count'] > 0) {
            return JsonResponse::error("Không thể xóa khuyến mãi này vì đã có hóa đơn sử dụng", 409);
        }
        $result = Database::delete('KhuyenMai', "MaKhuyenMai=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Xóa thất bại', 500);
    }


    public static function checkPromotion($code, $ticketTypeIds, $totalPrice)
    {
        $promotion = self::getPromotionByCode($code);
        if (!$promotion) {
            return JsonResponse::error("Không tìm thấy mã khuyến mãi");
        }
        if ($promotion['TrangThai'] != null && $promotion['TrangThai'] == TrangThaiKhuyenMai::An->value) {
            return JsonResponse::error("Khuyến mãi không còn hoạt động");
        }
        if ($promotion['NgayBatDau'] != null && $promotion['NgayBatDau'] > date('Y-m-d H:i:s')) {
            return JsonResponse::error("Không tìm thấy mã khuyến mãi");
        }
        if ($promotion['NgayKetThuc'] != null && $promotion['NgayKetThuc'] < date('Y-m-d H:i:s')) {
            return JsonResponse::error("Khuyến mãi đã hết hạn");
        }
        if ($promotion['GioiHanSuDung'] != null && $promotion['GioiHanSuDung'] <= 0) {
            return JsonResponse::error("Số lượng khuyến mãi đã hết");
        }
        $applyForTicketType = $promotion['MaLoaiVe'];
        if ($applyForTicketType != null && !in_array($applyForTicketType, $ticketTypeIds)) {
            return JsonResponse::error("Khuyến mãi không áp dụng cho loại vé bạn chọn");
        }
        $userId = $_SESSION['user']['MaNguoiDung'];
        $usageCount = self::countPromotionUsedByUser($userId, $promotion['MaKhuyenMai']);
        if ($promotion['GioiHanTrenKhachHang'] != null && $usageCount >= $promotion['GioiHanTrenKhachHang']) {
            return JsonResponse::error("Bạn đã sử dụng hết số lần sử dụng khuyến mãi");
        }
        if ($promotion["GiaTriToiThieu"] != null && $totalPrice < $promotion["GiaTriToiThieu"]) {
            return JsonResponse::error("Tổng giá trị vé phải lớn hơn " . $promotion["GiaTriToiThieu"]);
        }
        $reducePrice = 0;
        $discountValue = intval($promotion['GiaTriGiam']);
        if ($discountValue > 100) {
            $reducePrice = $discountValue;
        } else {

            $reducePrice = $totalPrice * $discountValue / 100;
            $maxDiscount = $promotion['GiamToiDa'];
            if ($maxDiscount != null && $maxDiscount > 0) {
                $reducePrice = min($reducePrice, $maxDiscount);
            }
        }
        $finalReducePrice = min($reducePrice, $totalPrice);
        return JsonResponse::ok([
            "reducePrice" => $finalReducePrice,
            "promotion" => $promotion
        ]);
    }
    private static function countPromotionUsedByUser($userId, $promotionId)
    {
        $query = "SELECT COUNT(*) as count FROM HoaDon WHERE MaKhuyenMai = ? AND MaNguoiDung = ?";
        $count = Database::queryOne($query, [$promotionId, $userId]);
        return $count['count'];
    }
    public static function usePromotion($code)
    {

        $promotion = self::getPromotionByCode($code);
        if (!$promotion) {
            return JsonResponse::error("Không tìm thấy mã khuyến mãi");
        }
        if ($promotion['GioiHanSuDung'] == null) {
            return JsonResponse::ok();
        }
        Database::update("KhuyenMai", [
            "GioiHanSuDung" => $promotion['GioiHanSuDung'] - 1
        ], " MaKhuyenMai = '" . $promotion['MaKhuyenMai'] . "'");
        return JsonResponse::ok();

    }

    public static function getAllPromotions($query = [])
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(['*'])->from('KhuyenMai')
            ->where("1", '=', '1');
        $keyword = getArrayValueSafe($query, 'tu-khoa', '');
        $khoangThoiGianTu = getArrayValueSafe($query, 'khoang-thoi-gian-tu', '');
        $khoangThoiGianDen = getArrayValueSafe($query, 'khoang-thoi-gian-den', '');
        $trangThais = getArrayValueSafe($query, 'trang-thais', []);
        $page = getArrayValueSafe($query, 'trang', 1);
        $limit = getArrayValueSafe($query, 'limit', 20);
        $offset = ($page - 1) * $limit;
        $orderBy = getArrayValueSafe($query, 'sap-xep', 'MaKhuyenMai');
        $orderType = getArrayValueSafe($query, 'thu-tu', 'ASC');


        if ($keyword != '') {
            $queryBuilder->and();
            $queryBuilder->startGroup();
            $queryBuilder->where('MaKhuyenMai', 'LIKE', "%$keyword%");
            $queryBuilder->orWhere('TenKhuyenMai', 'LIKE', "%$keyword%");
            $queryBuilder->endGroup();
        }
        if ($khoangThoiGianTu != '') {
            $queryBuilder->andWhere('NgayBatDau', '>=', $khoangThoiGianTu);
        }
        if ($khoangThoiGianDen != '') {
            $queryBuilder->andWhere('NgayKetThuc', '<=', $khoangThoiGianDen);
        }
        if ($trangThais) {
            $queryBuilder->andWhere('TrangThai', 'IN', $trangThais);
        }
        Request::setQueryCount($queryBuilder->count());
        $queryBuilder->orderBy($orderBy, $orderType);
        $queryBuilder->limit($limit, $offset);
        return $queryBuilder->get();
    }
}