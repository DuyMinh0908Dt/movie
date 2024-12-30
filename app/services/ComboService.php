<?php

namespace App\Services;

use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Core\Request;
use App\Dtos\JsonResponse;
use App\Dtos\TrangThaiCombo;
use App\Dtos\TrangThaiThucPham;

class ComboService
{
    public static function getAllFoodnDrink($querys = [])
    {
        $keyword = getArrayValueSafe($querys, 'tu-khoa');
        $priceFrom = getArrayValueSafe($querys, 'gia-tu');
        $priceTo = getArrayValueSafe($querys, 'gia-den');
        $sortDir = ifNullOrEmptyString(getArrayValueSafe($querys, 'thu-tu'), 'ASC');
        $sortBy = ifNullOrEmptyString(getArrayValueSafe($querys, 'sap-xep'), 'MaThucPham');
        $page = ifNullOrEmptyString(getArrayValueSafe($querys, 'trang'), 1);
        $limit = ifNullOrEmptyString(getArrayValueSafe($querys, 'limit'), 100);
        $trangThais = getArrayValueSafe($querys, 'trang-thais', [TrangThaiThucPham::Hien->value]);
        $offset = ($page - 1) * $limit;
        $queryBuilder = new QueryBuilder();
        $queryBuilder->select(
            [
                'ThucPham.*'
            ]
        )->from('ThucPham')
            ->where('1', '=', '1');
        if (!isNullOrEmptyString($keyword)) {
            $queryBuilder->and();
            $queryBuilder->startGroup();
            $queryBuilder->where('TenThucPham', 'LIKE', "%$keyword%");
            $queryBuilder->orWhere('LoaiThucPham', 'LIKE', "%$keyword%");
            $queryBuilder->endGroup();
        }
        if (!isNullOrEmptyArray($trangThais)) {
            $queryBuilder->and();
            $queryBuilder->where('TrangThai', 'IN', $trangThais);
        }
        if (!isNullOrEmptyString($priceFrom)) {
            $queryBuilder->and();
            $queryBuilder->where('GiaThucPham', '>=', $priceFrom);
        }
        if (!isNullOrEmptyString($priceTo)) {
            $queryBuilder->and();
            $queryBuilder->where('GiaThucPham', '<=', $priceTo);
        }
        Request::setQueryCount($queryBuilder->count());
        $queryBuilder->orderBy($sortBy, $sortDir);
        $queryBuilder->limit($limit, $offset);

        $data = $queryBuilder->get();
        return $data;
    }
    public static function getFoodnDrinkById($id)
    {
        $sql = "SELECT ThucPham.* FROM ThucPham WHERE MaThucPham=?;";
        $foodndrink = Database::queryOne($sql, [$id]);
        return $foodndrink;
    }
    public static function createNewFoodnDrink($data)
    {
        $params = [
            'TenThucPham' => $data['TenThucPham'],
            'LoaiThucPham' => $data['LoaiThucPham'],
            'GiaThucPham' => $data['GiaThucPham'],
            'MoTa' => $data['MoTa'],
            'HinhAnh' => $data['HinhAnh'],
            'TrangThai' => $data['TrangThai'] ?? TrangThaiThucPham::Hien->value
        ];
        $result = Database::insert('ThucPham', $params);
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Thêm mới thất bại', 500);
    }
    public static function updateFoodnDrink($data, $id)
    {
        $params = [
            'TenThucPham' => $data['TenThucPham'],
            'LoaiThucPham' => $data['LoaiThucPham'],
            'GiaThucPham' => $data['GiaThucPham'],
            'MoTa' => $data['MoTa'],
            'TrangThai' => $data['TrangThai'],
            'HinhAnh' => $data['HinhAnh'],
        ];
        $result = Database::update('ThucPham', $params, "MaThucPham=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Cập nhật thất bại', 500);
    }
    public static function toggleHideFoodnDrink($id)
    {
        $exist = Database::queryOne("SELECT MaThucPham FROM ThucPham WHERE MaThucPham=$id AND TrangThai=" . TrangThaiThucPham::An->value, []);
        if ($exist) {
            $result = Database::update('ThucPham', ['TrangThai' => TrangThaiThucPham::Hien->value], "MaThucPham=$id");
        } else {
            $result = Database::update('ThucPham', ['TrangThai' => TrangThaiThucPham::An->value], "MaThucPham=$id");
        }
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Ẩn thất bại', 500);
    }
    public static function deleteFoodnDrink($id)
    {
        $sql = "SELECT * FROM CT_Combo_ThucPham WHERE MaThucPham=?;";
        $result = Database::queryOne($sql, [$id]);
        if (isset($result)) {
            return self::toggleHideFoodnDrink($id);
        }
        $sql = "SELECT * FROM CT_HoaDon_ThucPham WHERE MaThucPham=?;";
        $result = Database::queryOne($sql, [$id]);
        if (isset($result)) {
            return self::toggleHideFoodnDrink($id);
        }
        $result = Database::delete('ThucPham', "MaThucPham=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Xóa thất bại', 500);

    }


    public static function getAllCombo($query = [])
    {
        $keyword = getArrayValueSafe($query, 'tu-khoa');
        $priceFrom = getArrayValueSafe($query, 'gia-tu');
        $priceTo = getArrayValueSafe($query, 'gia-den');
        $sortDir = ifNullOrEmptyString(getArrayValueSafe($query, 'thu-tu'), 'ASC');
        $sortBy = ifNullOrEmptyString(getArrayValueSafe($query, 'sap-xep'), 'MaCombo');
        $page = ifNullOrEmptyString(getArrayValueSafe($query, 'trang'), 1);
        $limit = ifNullOrEmptyString(getArrayValueSafe($query, 'limit'), 100);
        $trangThais = getArrayValueSafe($query, 'trang-thais', [TrangThaiCombo::DangBan->value]);
        $offset = ($page - 1) * $limit;
        $thucPhamIds = getArrayValueSafe($query, 'thuc-phams');
        $queryBuilder = new QueryBuilder();
        $sortBy = 'Combo.' . $sortBy;
        $queryBuilder->selectDistinct(
            [
                'Combo.*',
            ]
        )->from('Combo')
            ->join('CT_Combo_ThucPham', 'Combo.MaCombo = CT_Combo_ThucPham.MaCombo')
            ->join('ThucPham', 'CT_Combo_ThucPham.MaThucPham = ThucPham.MaThucPham')
            ->where('1', '=', '1');
        if (!isNullOrEmptyArray($trangThais)) {
            $queryBuilder->andWhere('Combo.TrangThai', 'IN', $trangThais);
        }
        if (!isNullOrEmptyString($keyword)) {
            $queryBuilder->and();
            $queryBuilder->startGroup();
            $queryBuilder->where('TenCombo', 'LIKE', "%$keyword%");
            $queryBuilder->orWhere('ThucPham.TenThucPham', 'LIKE', "%$keyword%");
            $queryBuilder->endGroup();
        }
        if (!isNullOrEmptyString($priceFrom)) {
            $queryBuilder->andWhere('GiaCombo', '>=', $priceFrom);
        }
        if (!isNullOrEmptyString($priceTo)) {
            $queryBuilder->andWhere('GiaCombo', '<=', $priceTo);
        }
        if (!isNullOrEmptyArray($thucPhamIds)) {
            $queryBuilder->andWhere('CT_Combo_ThucPham.MaThucPham', 'IN', $thucPhamIds);
        }
        Request::setQueryCount($queryBuilder->count());
        $queryBuilder->orderBy($sortBy, $sortDir);
        $queryBuilder->limit($limit, $offset);
        $data = $queryBuilder->get();
        return $data;
    }
    public static function getComboById($id)
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->select(
            [
                'Combo.MaCombo' => 'MaCombo',
                'Combo.GiaCombo' => 'GiaCombo',
                'Combo.TenCombo' => 'TenCombo',
                'Combo.MoTa' => 'MoTa',
                'Combo.TrangThai' => 'TrangThai',
                'Combo.HinhAnh' => 'HinhAnh',
                'CT_Combo_ThucPham.MaThucPham' => 'MaThucPham',
                'ThucPham.TenThucPham' => 'TenThucPham',
                'ThucPham.GiaThucPham' => 'GiaThucPham',
                'CT_Combo_ThucPham.SoLuong' => 'SoLuong'
            ]
        )->from('Combo')
            ->join('CT_Combo_ThucPham', 'Combo.MaCombo = CT_Combo_ThucPham.MaCombo')
            ->join('ThucPham', 'CT_Combo_ThucPham.MaThucPham = ThucPham.MaThucPham')
            ->where('Combo.MaCombo', '=', $id);
        $data = $queryBuilder->get();
        // nhóm dữ liệu theo MaCombo
        if (empty($data)) {
            return JsonResponse::error('Không tìm thấy combo', 404);
        }
        $combo = [
            'MaCombo' => $data[0]['MaCombo'],
            'GiaCombo' => $data[0]['GiaCombo'],
            'TenCombo' => $data[0]['TenCombo'],
            'MoTa' => $data[0]['MoTa'],
            'TrangThai' => $data[0]['TrangThai'],
            'HinhAnh' => $data[0]['HinhAnh'],
            'ThucPham' => []
        ];
        foreach ($data as $item) {
            $combo['ThucPham'][] = [
                'MaThucPham' => $item['MaThucPham'],
                'TenThucPham' => $item['TenThucPham'],
                'GiaThucPham' => $item['GiaThucPham'],
                'SoLuong' => $item['SoLuong']
            ];
        }
        return $combo;
    }

    public static function getComboByIds($ids)
    {
        if (empty($ids))
            return [];
        $ids = implode(",", $ids);
        return Database::query("SELECT * FROM Combo WHERE MaCombo IN ($ids)", []);
    }
    public static function getFoodByIds($ids)
    {
        if (empty($ids))
            return [];
        $ids = implode(",", $ids);
        return Database::query("SELECT * FROM ThucPham WHERE MaThucPham IN ($ids)", []);
    }

    public static function calCombosPrice($data) // {comboId:, quantity:}
    {
        $combos = self::getComboByIds(array_map(fn($item) => $item['MaCombo'], $data));
        $total = 0;
        foreach ($combos as $combo) {
            $total += $combo['GiaCombo'] * $data[array_search($combo['MaCombo'], array_column($data, 'MaCombo'))]['SoLuong'];
        }
        return $total;
    }
    public static function calFoodsPrice($data) // {foodId:, quantity:}
    {
        $foods = self::getFoodByIds(array_map(fn($item) => $item['MaThucPham'], $data));
        $total = 0;
        foreach ($foods as $food) {
            $total += $food['GiaThucPham'] * $data[array_search($food['MaThucPham'], array_column($data, 'MaThucPham'))]['SoLuong'];
        }
        return $total;
    }

    public static function createNewCombo($data)
    {

        $params = [
            'GiaCombo' => $data['GiaCombo'],
            'TenCombo' => $data['TenCombo'],
            'TrangThai' => $data['TrangThai'] ?? TrangThaiCombo::DangBan->value,
            'MoTa' => $data['MoTa'],
            'HinhAnh' => $data['HinhAnh']
        ];

        $result = Database::insert('Combo', $params);
        if (!$result) {
            return JsonResponse::error('Thêm mới combo thất bại', 500);
        }
        $foods = $data['ThucPhams'];
        $comboId = $result;
        return self::addFoodsToCombo($comboId, $foods);

    }
    public static function removeAllFoodInCombo($id)
    {
        $result = Database::delete('CT_Combo_ThucPham', "MaCombo=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Xóa thất bại', 500);
    }
    public static function addFoodsToCombo($comboId, $foods)
    {

        Database::beginTransaction();
        foreach ($foods as $food) {
            $result = Database::insert('CT_Combo_ThucPham', [
                'MaCombo' => $comboId,
                'MaThucPham' => $food['MaThucPham'],
                'SoLuong' => $food['SoLuong']
            ]);
            if (!isset($result)) {
                Database::rollBack();
                return JsonResponse::error('Thêm thất bại', 500);
            }
        }

        Database::commit();
        return JsonResponse::ok();
    }
    public static function updateCombo($data, $id)
    {
        $params = [
            'GiaCombo' => $data['GiaCombo'],
            'TenCombo' => $data['TenCombo'],
            'TrangThai' => $data['TrangThai'],
            'MoTa' => $data['MoTa'],
            'HinhAnh' => $data['HinhAnh']
        ];
        $result = Database::update('Combo', $params, "MaCombo=$id");
        self::removeAllFoodInCombo($id);
        return self::addFoodsToCombo($id, $data['ThucPhams']);

    }

    public static function toggleHideCombo($id)
    {
        $exist = Database::queryOne("SELECT MaCombo FROM Combo WHERE MaCombo=$id AND TrangThai=" . TrangThaiCombo::An->value, []);
        if ($exist) {
            $result = Database::update('Combo', ['TrangThai' => TrangThaiCombo::DangBan->value], "MaCombo=$id");
        } else {
            $result = Database::update('Combo', ['TrangThai' => TrangThaiCombo::An->value], "MaCombo=$id");
        }
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Ẩn thất bại', 500);
    }
    public static function deleteCombo($id)
    {
        $sql = "SELECT * FROM CT_HoaDon_Combo WHERE MaCombo=?;";
        $result = Database::queryOne($sql, [$id]);
        if (isset($result)) {
            return self::toggleHideCombo($id);
        }
        $result = Database::delete('Combo', "MaCombo=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Xóa thất bại', 500);
    }
}