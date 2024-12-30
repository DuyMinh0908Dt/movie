<?php

namespace App\Services;

use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Core\Request;
use App\Dtos\TrangThaiPhim;

class PhimService
{

    public static $MOVIE_TAGS = [
        "P" => "Thích hợp cho mọi độ tuổi",
        "K" => "Được phổ biến người xem dưới 13 tuổi với điều kiện xem cùng cha mẹ hoặc người giám hộ",
        "T13" => "cấm người dưới 13 tuổi",
        "T16" => "cấm người dưới 16 tuổi",
        "T18" => "cấm người dưới 18 tuổi"
    ];
    public static function createMovie($data)
    {
        $result = null;
        $params = [
            'TenPhim' => $data['TenPhim'],
            'NgayPhatHanh' => $data['NgayPhatHanh'],
            'HanCheDoTuoi' => $data['HanCheDoTuoi'],
            'HinhAnh' => $data['HinhAnh'],
            'ThoiLuong' => $data['ThoiLuong'],
            'NgonNgu' => $data['NgonNgu'],
            'MoTa' => $data['MoTa'],
            'DaoDien' => $data['DaoDien'],
            'TrangThai' => $data['TinhTrang'],
            'DinhDang' => $data['DinhDang'],
            'Trailer' => $data['Trailer'],
        ];
        $id = Database::insert("Phim", $params);
        if ($id) {
            $theLoais = $data['TheLoais'];
            foreach ($theLoais as $theLoai) {
                Database::insert("CT_Phim_TheLoai", [
                    'MaPhim' => $id,
                    'MaTheLoai' => $theLoai
                ]);
            }
            $result = $id;
        }
        return $result;
    }
    public static function toggleHide($id)
    {
        $movie = self::getPhimById($id);
        $status = $movie['TrangThai'] == TrangThaiPhim::NgungChieu->value ? null : TrangThaiPhim::NgungChieu->value;
        Database::update("Phim", ['TrangThai' => $status], "MaPhim = $id");

        return $status;
    }
    public static function getPhimDangChieu($page = 1, $limit = 40)
    {
        $query = "SELECT * FROM Phim WHERE TrangThai = ? LIMIT ? , ?;";
        $phims = Database::query($query, [TrangThaiPhim::DangChieu->value, ($page - 1) * $limit, $limit]);
        return $phims;
    }
    public static function getPhimSapChieu($page = 1, $limit = 40)
    {
        $query = "SELECT * FROM Phim WHERE TrangThai = ? LIMIT ? , ?;";
        $phims = Database::query($query, [TrangThaiPhim::SapChieu->value, ($page - 1) * $limit, $limit]);
        return $phims;
    }
    public static function getPublicMovies($page = 1, $limit = 20)
    {
        $query = "SELECT * FROM Phim WHERE TrangThai != ? LIMIT ? , ?;";
        $phims = Database::query($query, [TrangThaiPhim::NgungChieu->value, ($page - 1) * $limit, $limit]);
        return $phims;
    }



    public static function getTatCaPhim($query = [])
    {
        $queryBuilder = new QueryBuilder();
        $keyword = getArrayValueSafe($query, 'tu-khoa');
        $statuses = getArrayValueSafe($query, 'trang-thais');
        $status = getArrayValueSafe($query, 'trang-thai');
        if ($status) {
            $statuses = [$status];
        }
        $ngayPhatHanhTu = getArrayValueSafe($query, 'ngay-phat-hanh-tu');
        $ngayPhatHanhDen = getArrayValueSafe($query, 'ngay-phat-hanh-den');
        $tags = getArrayValueSafe($query, 'tags');
        $durationFrom = getArrayValueSafe($query, 'thoi-luong-tu');
        $durationTo = getArrayValueSafe($query, 'thoi-luong-den');
        $orderBy = getArrayValueSafe($query, 'sap-xep', 'MaPhim');
        $orderType = getArrayValueSafe($query, 'thu-tu', 'ASC');
        $categoriesId = getArrayValueSafe($query, 'the-loais');
        $page = ifNullOrEmptyString(getArrayValueSafe($query, 'trang'), 1);
        $limit = ifNullOrEmptyString(getArrayValueSafe($query, 'limit'), 10);
        $offset = ($page - 1) * $limit;
        $queryBuilder->select(['Phim.*'])
            ->from('Phim')
            ->where('1', '=', '1');
        if (!isNullOrEmptyString($keyword)) {
            $queryBuilder->and();
            $queryBuilder->startGroup();
            $queryBuilder->where('TenPhim', 'LIKE', "%$keyword%");
            $queryBuilder->orWhere('DaoDien', 'LIKE', "%$keyword%");
            $queryBuilder->orWhere('NgonNgu', 'LIKE', "%$keyword%");
            $queryBuilder->endGroup();
        }
        if (!isNullOrEmptyArray($statuses)) {
            $queryBuilder->and();
            $queryBuilder->where('TrangThai', 'IN', $statuses);
        }
        if (!isNullOrEmptyArray($categoriesId)) {
            $queryBuilder->and();
            $queryBuilder->where('MaPhim', 'IN', "(SELECT MaPhim FROM CT_Phim_TheLoai WHERE MaTheLoai IN (" . implode(",", $categoriesId) . "))");
        }
        if (!isNullOrEmptyArray($tags)) {
            $queryBuilder->and();
            $queryBuilder->where('MaPhim', 'IN', $tags);
        }
        if (!isNullOrEmptyString($durationFrom)) {
            $queryBuilder->and();
            $queryBuilder->where('ThoiLuong', '>=', $durationFrom);
        }
        if (!isNullOrEmptyString($durationTo)) {
            $queryBuilder->and();
            $queryBuilder->where('ThoiLuong', '<=', $durationTo);
        }

        if (!isNullOrEmptyString($ngayPhatHanhTu)) {
            $queryBuilder->andWhere('NgayPhatHanh', '>=', $ngayPhatHanhTu);
        }
        if (!isNullOrEmptyString($ngayPhatHanhDen)) {
            $queryBuilder->andWhere('NgayPhatHanh', '<=', $ngayPhatHanhDen);
        }
        $total = $queryBuilder->count();
        $queryBuilder->orderBy($orderBy, $orderType);

        $queryBuilder->limit($limit, $offset);
        $phims = $queryBuilder->get();
        Request::setQueryCount($total);
        return $phims;
    }

    public static function getPhimById($id)
    {
        $query = "SELECT * FROM Phim WHERE MaPhim = ?;";
        $phim = Database::queryOne($query, [$id]);
        $categories = self::getCateGoriesByMovieId($id);
        $phim['TheLoais'] = $categories;
        return $phim;
    }
    public static function getPhimByIds($ids)
    {
        $query = "SELECT * FROM Phim WHERE MaPhim IN (" . implode(",", $ids) . ");";
        $phims = Database::query($query, []);
        return $phims;
    }
    public static function getCateGoriesByMovieId($id)
    {
        $query = "SELECT * FROM TheLoai WHERE MaTheLoai IN (SELECT MaTheLoai FROM CT_Phim_TheLoai WHERE MaPhim = ?);";
        $categories = Database::query($query, [$id]);
        return $categories;
    }
    public static function removeAllCategoriesOfMovie($id)
    {
        Database::execute("DELETE FROM CT_Phim_TheLoai WHERE MaPhim = ?", [$id]);
    }

    public static function updateMovie($id, $data)
    {
        $result = null;
        $params = [
            'TenPhim' => $data['TenPhim'],
            'NgayPhatHanh' => $data['NgayPhatHanh'],
            'HanCheDoTuoi' => $data['HanCheDoTuoi'],
            'HinhAnh' => $data['HinhAnh'],
            'ThoiLuong' => $data['ThoiLuong'],
            'NgonNgu' => $data['NgonNgu'],
            'MoTa' => $data['MoTa'],
            'DaoDien' => $data['DaoDien'],
            'TrangThai' => $data['TinhTrang'],
            'DinhDang' => $data['DinhDang'],
            'Trailer' => $data['Trailer'],
        ];
        Database::update("Phim", $params, "MaPhim = $id");
        self::removeAllCategoriesOfMovie($id);
        $theLoais = $data['TheLoais'];
        foreach ($theLoais as $theLoai) {
            Database::insert("CT_Phim_TheLoai", [
                'MaPhim' => $id,
                'MaTheLoai' => $theLoai
            ]);
        }
        $result = true;
        return $result;
    }
    public static function deleteMovie($id)
    {
        $isHasAnyShow = ShowService::isMovieHasAnyShow($id);
        if ($isHasAnyShow) {
            return false;
        }
        $result = null;
        self::removeAllCategoriesOfMovie($id);
        Database::delete("Phim", "MaPhim = $id");
        $result = true;
        return $result;
    }

    public static function searchImage($data)
    {


    }
}