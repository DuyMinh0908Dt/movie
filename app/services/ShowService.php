<?php
namespace App\Services;

use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Core\Logger;
use App\Core\Request;
use App\Dtos\JsonDataErrorRespose;
use App\Dtos\JsonResponse;
use App\Dtos\TrangThaiPhim;
use App\Dtos\TrangThaiSuatChieu;
use App\Dtos\TrangThaiVe;

class ShowService
{
    public static function getUpcomingShowsOfMovie($movieId)
    {
        $now = date('Y-m-d H:i:s');
        $next7Days = date('Y-m-d H:i:s', strtotime('+7 days', strtotime($now)));
        $sql = "SELECT * FROM SuatChieu WHERE MaPhim = ? AND NgayGioChieu BETWEEN ? AND ? AND TrangThai != " . TrangThaiSuatChieu::Hidden->value;
        $shows = Database::query($sql, [$movieId, $now, $next7Days]);
        return $shows;
    }
    // trả về danh sách phim
    // lọc theo các tiêu chí
    //      1. Keyword => tên phim, mã phim, tên đạo diễn, 
    //      2. Thể loại -> select
    //      3. Quốc gia -> select
    //      4. Khoảng thời gian -> min: now, max: user input  -> tìm suất chiếu phim đó trong ngày đó
    //      5. Thời lượng -> min: 0, max: user input
    //      7. Định dạng -> select
    //      8. Rạp -> select -> rạp nào có suất chiếu phim đó
    //      9.  Sắp xếp -> select theo tên, ngày chiếu, thời lượng,
    //  {
    //             'tu-khoa': keyword,
    //             'the-loais': theloais,
    //             'raps': rapchieus,
    //             'thoi-gian-tu': showFrom,
    //             'thoi-gian-den': showTo,
    //             'thoi-luong-tu': durrationFrom,
    //             'thoi-luong-den': durrationTo,
    //             'sap-xep': sortBy,
    //             'thu-tu': sort
    //         }

    public static function isMovieHasAnyShow($movieId)
    {
        $sql = "SELECT * FROM SuatChieu WHERE MaPhim = ? LIMIT 1";
        $result = Database::queryOne($sql, [$movieId]);
        return isNullOrEmptyArray($result) ? false : true;
    }
    public static function advanceSearch($queryInput)
    {
        $keyword = getArrayValueSafe($queryInput, 'tu-khoa');
        $genre = getArrayValueSafe($queryInput, 'the-loais', []);
        $timeRangeFrom = ifNullOrEmptyString(getArrayValueSafe($queryInput, 'thoi-gian-tu'), date('Y-m-d'));
        $timeRangeTo = getArrayValueSafe($queryInput, 'thoi-gian-den');
        $durationFrom = getArrayValueSafe($queryInput, 'thoi-luong-tu');
        $durationTo = getArrayValueSafe($queryInput, 'thoi-luong-den');
        $cinema = getArrayValueSafe($queryInput, 'raps', null);
        $sortDir = ifNullOrEmptyString(getArrayValueSafe($queryInput, 'thu-tu'), 'ASC');
        $sortBy = ifNullOrEmptyString(getArrayValueSafe($queryInput, 'sap-xep'), 'Phim.TenPhim');
        $page = ifNullOrEmptyString(getArrayValueSafe($queryInput, 'trang'), 1);
        $limit = ifNullOrEmptyString(getArrayValueSafe($queryInput, 'limit'), 10);
        // đầu tiên tìm suất chiếu phù hợp với các tiêu chí
        $sql = "SELECT DISTINCT Phim.MaPhim FROM SuatChieu 
                JOIN Phim ON SuatChieu.MaPhim = Phim.MaPhim
                JOIN PhongChieu ON SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu
                WHERE 1=1 ";
        if (!isNullOrEmptyString($keyword)) {
            $sql .= "AND (Phim.TenPhim LIKE '%$keyword%' OR Phim.DaoDien LIKE '%$keyword%') ";
        }
        // genre để cho lần query sau
        if (!isNullOrEmptyString($timeRangeFrom)) {
            $sql .= "AND DATE(SuatChieu.NgayGioChieu) >= DATE('$timeRangeFrom') ";
        }
        if (!isNullOrEmptyString($timeRangeTo)) {
            $sql .= "AND DATE(SuatChieu.NgayGioChieu) <= DATE('$timeRangeTo') ";
        }
        if (!empty($durationFrom))
            $sql .= "AND Phim.ThoiLuong >= $durationFrom ";
        if (!empty($durationTo)) {
            $sql .= "AND Phim.ThoiLuong <= $durationTo ";
        }
        if (!isNullOrEmptyArray($cinema)) {
            $sql .= "AND PhongChieu.MaRapChieu IN (" . implode(",", $cinema) . ") ";
        }
        $sql .= "AND Phim.TrangThai != " . TrangThaiPhim::NgungChieu->value . " ";
        $listPhimIds = array_map(function ($movie) {
            return $movie['MaPhim'];
        }, Database::query($sql, []));
        if (isNullOrEmptyArray($listPhimIds)) {
            Request::setQueryCount(0);
            return [];
        }
        $sql = "SELECT Phim.MaPhim,Phim.TenPhim,NgayPhatHanh,HanCheDoTuoi,HinhAnh,ThoiLuong,NgonNgu,DaoDien ,DinhDang,Trailer FROM Phim JOIN CT_Phim_TheLoai ON CT_Phim_TheLoai.MaPhim = Phim.MaPhim WHERE Phim.MaPhim IN (" . implode(",", $listPhimIds) . ") ";
        if (!isNullOrEmptyArray($genre)) {
            $sql .= "AND CT_Phim_TheLoai.MaTheLoai IN (" . implode(",", $genre) . ") ";
        }




        // $sql = "Select Phim.MaPhim from Phim ";
        // $sql .= "LEFT JOIN CT_Phim_TheLoai ON CT_Phim_TheLoai.MaPhim = Phim.MaPhim ";
        // $sql .= "WHERE 1=1 ";
        // if (!isNullOrEmptyString($keyword)) {
        //     $sql .= "AND (Phim.TenPhim LIKE '%$keyword%' OR Phim.DaoDien LIKE '%$keyword%') ";
        // }
        // if (!isNullOrEmptyArray($genre)) {
        //     $sql .= "AND CT_Phim_TheLoai.MaTheLoai IN (" . implode(",", $genre) . ") ";
        // }
        // if (!empty($durationFrom))
        //     $sql .= "AND Phim.ThoiLuong >= $durationFrom ";
        // if (!empty($durationTo)) {
        //     $sql .= "AND Phim.ThoiLuong <= $durationTo ";
        // }

        // if (!isNullOrEmptyArray($cinema)) {
        //     $sql .= "AND EXISTS (SELECT * FROM SuatChieu JOIN PhongChieu ON SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu WHERE SuatChieu.MaPhim = Phim.MaPhim AND PhongChieu.MaRapChieu in (" . implode(",", $cinema) . ")) ";
        // }
        // if (!isNullOrEmptyString($timeRangeFrom)) {
        //     $sql .= "AND EXISTS (SELECT * FROM SuatChieu WHERE SuatChieu.MaPhim = Phim.MaPhim AND DATE(SuatChieu.NgayGioChieu) >= DATE('$timeRangeFrom')) ";
        // }
        // if (!isNullOrEmptyString($timeRangeTo)) {
        //     $sql .= "AND EXISTS (SELECT * FROM SuatChieu WHERE SuatChieu.MaPhim = Phim.MaPhim AND DATE(SuatChieu.NgayGioChieu) <= DATE('$timeRangeTo')) ";
        // }
        // $sql .= "GROUP BY Phim.MaPhim ";
        //     	Select Phim.MaPhim from Phim 
        //  JOIN CT_Phim_TheLoai ON CT_Phim_TheLoai.MaPhim = Phim.MaPhim 
        //  WHERE CT_Phim_TheLoai.MaTheLoai IN (6, 7)
        // AND (Phim.TenPhim LIKE '%%' OR Phim.DaoDien LIKE '%%') 
        // AND Phim.ThoiLuong >= 120
        // AND EXISTS (SELECT * FROM SuatChieu Join PhongChieu on SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu WHERE SuatChieu.MaPhim = Phim.MaPhim AND PhongChieu.MaRapChieu in (1))
        // GROUP BY Phim.MaPhim
        // HAVING COUNT(DISTINCT CT_Phim_TheLoai.MaTheLoai) = 2;
        if (!isNullOrEmptyArray($genre)) {
            $count = count($genre);
            $sql .= "HAVING COUNT(DISTINCT CT_Phim_TheLoai.MaTheLoai) = $count ";
        }
        $movies = Database::query($sql, []);
        $ids = array_map(function ($movie) {
            return $movie['MaPhim'];
        }, $movies);
        if (isNullOrEmptyArray($ids)) {
            Request::setQueryCount(0);
            return [];
        }
        $sql = "SELECT MaPhim,TenPhim,NgayPhatHanh,HanCheDoTuoi,HinhAnh,ThoiLuong,NgonNgu,DaoDien ,DinhDang,Trailer FROM Phim WHERE MaPhim IN (" . implode(",", $ids) . ") and TrangThai != " . TrangThaiPhim::NgungChieu->value . " ";
        $count = Database::count($sql, []);
        Request::setQueryCount($count);
        if (!isNullOrEmptyString($sortBy)) {
            $sql .= "ORDER BY $sortBy $sortDir ";
        }
        $sql .= "LIMIT " . ($page - 1) * $limit . ", $limit";
        $movies = Database::query($sql, []);
        return $movies;
    }

    public static function getShowInfoById($maSuatChieu)
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->select([
            'SuatChieu.MaXuatChieu',
            'SuatChieu.NgayGioChieu',
            'SuatChieu.NgayGioKetThuc',
            'Phim.TenPhim',
            'Phim.HinhAnh',
            'Phim.MaPhim',
            'PhongChieu.TenPhongChieu',
            'RapChieu.TenRapChieu',
            'RapChieu.DiaChi',
        ])->from('SuatChieu')
            ->join('Phim', 'SuatChieu.MaPhim = Phim.MaPhim')
            ->join('PhongChieu', 'SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu')
            ->join('RapChieu', 'PhongChieu.MaRapChieu = RapChieu.MaRapChieu')
            ->where('SuatChieu.MaXuatChieu', '=', $maSuatChieu);
        $show = $queryBuilder->first();
        return $show;
    }
    public static function getShowById($id)
    {
        $sql = "SELECT SuatChieu.*,
        Phim.TenPhim,
        PhongChieu.TenPhongChieu,
        RapChieu.TenRapChieu,
        RapChieu.DiaChi,
        RapChieu.MaRapChieu,
        Phim.MaPhim,
        Phim.HinhAnh
         FROM SuatChieu
        JOIN Phim ON SuatChieu.MaPhim = Phim.MaPhim
        JOIN PhongChieu ON SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu
        JOIN RapChieu ON PhongChieu.MaRapChieu = RapChieu.MaRapChieu
        WHERE MaXuatChieu = ?";
        $show = Database::queryOne($sql, [$id]);
        return $show;
    }

    public static function getAllShows($query)
    {
        $queryBuilder = new QueryBuilder();
        $keyword = getArrayValueSafe($query, 'tu-khoa');
        $status = getArrayValueSafe($query, 'trang-thai');
        $page = ifNullOrEmptyString(getArrayValueSafe($query, 'trang'), 1);
        $limit = ifNullOrEmptyString(getArrayValueSafe($query, 'limit'), 10);
        $cinemaIds = getArrayValueSafe($query, 'rapchieus', []);
        $sortDir = ifNullOrEmptyString(getArrayValueSafe($query, 'thu-tu'), 'ASC');
        $sortBy = ifNullOrEmptyString(getArrayValueSafe($query, 'sap-xep'), 'NgayGioChieu');
        $phimIds = getArrayValueSafe($query, 'phims', []);
        $tuNgay = getArrayValueSafe($query, 'tu-ngay');
        $denNgay = getArrayValueSafe($query, 'den-ngay');
        // select display fields MaXuatChieu, TenRap, Phong, BatDau, KetThuc, TenPhim, TrangThai

        $queryBuilder->select([
            'SuatChieu.MaXuatChieu',
            'RapChieu.TenRapChieu',
            'PhongChieu.TenPhongChieu',
            'SuatChieu.NgayGioChieu',
            'SuatChieu.NgayGioKetThuc',
            'Phim.TenPhim',
            'SuatChieu.GiaVe',
            'SuatChieu.TrangThai',
        ])->from('SuatChieu')
            ->join('Phim', 'SuatChieu.MaPhim = Phim.MaPhim')
            ->join('PhongChieu', 'SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu')
            ->join('RapChieu', 'PhongChieu.MaRapChieu = RapChieu.MaRapChieu')
            ->where('1', '=', '1');
        if (!isNullOrEmptyString($keyword)) {
            $queryBuilder->andWhere('Phim.TenPhim', 'LIKE', "%$keyword%");
        }
        if (!isNullOrEmptyString($status)) {
            $queryBuilder->andWhere('SuatChieu.TrangThai', '=', $status);
        }
        if (!isNullOrEmptyArray($cinemaIds)) {
            $queryBuilder->andWhere('RapChieu.MaRapChieu', 'IN', $cinemaIds);
        }
        if (!isNullOrEmptyArray($phimIds)) {
            $queryBuilder->andWhere('Phim.MaPhim', 'IN', $phimIds);
        }
        if (!isNullOrEmptyString($tuNgay)) {
            $queryBuilder->andWhere('DATE(SuatChieu.NgayGioChieu)', '>=', $tuNgay);
        }
        if (!isNullOrEmptyString($denNgay)) {
            $queryBuilder->andWhere('DATE(SuatChieu.NgayGioChieu)', '<=', $denNgay);
        }

        $count = $queryBuilder->count();
        Request::setQueryCount($count);
        if (!isNullOrEmptyString($sortBy)) {
            $queryBuilder->orderBy($sortBy, $sortDir);
        }

        $queryBuilder->limit($limit, ($page - 1) * $limit);
        $shows = $queryBuilder->get();
        return $shows;
    }
    private static function validateShowTime($show)
    {
        $showTime = $show['NgayGioBatDau'];
        $endTime = $show['NgayGioKetThuc'];
        $roomId = $show['MaPhongChieu'];
        $sql = "SELECT * FROM SuatChieu WHERE MaPhongChieu = ? AND ((NgayGioChieu BETWEEN ? AND ?) OR (NgayGioKetThuc BETWEEN ? AND ?) OR (NgayGioChieu < ? AND NgayGioKetThuc > ?))";
        $result = Database::queryOne($sql, [$roomId, $showTime, $endTime, $showTime, $endTime, $showTime, $endTime]);
        return $result;
    }

    public static function createShow($show)
    {

        $exists = self::validateShowTime($show);
        if ($exists) {
            return JsonDataErrorRespose::create()->addFieldError('NgayGioBatDau', 'Suất chiếu bị trùng giờ');
        }
        $params = [
            'MaPhim' => $show['MaPhim'],
            'MaPhongChieu' => $show['MaPhongChieu'],
            'NgayGioChieu' => $show['NgayGioBatDau'],
            'NgayGioKetThuc' => $show['NgayGioKetThuc'],
            'GiaVe' => $show['GiaVe'],
            'TrangThai' => $show['TrangThai'] ?? TrangThaiSuatChieu::Hidden->value
        ];
        $result = Database::insert('SuatChieu', $params);
        if (!$result) {
            return JsonResponse::error('Tạo suất chiếu thất bại', 500);
        }
        TicketService::createEmptyTickets($result);
        return JsonResponse::ok([
            'MaXuatChieu' => $result
        ]);
    }
    public static function canEditShow($showId)
    {
        $tickets = TicketService::getAllTicketsOfShow($showId);
        $isAnyTicketSold = false;
        foreach ($tickets as $ticket) {
            if ($ticket['TrangThai'] == TrangThaiVe::DaDat->value) {
                $isAnyTicketSold = true;
                break;
            }
        }
        return !$isAnyTicketSold;
    }
    public static function toggleSellTicked($showId)
    {
        $show = self::getShowById($showId);
        if (!$show) {
            return JsonResponse::error('Không tìm thấy suất chiếu', 404);
        }
        error_log(var_dump($show));
        $params = [
            'TrangThai' => $show['TrangThai'] == TrangThaiSuatChieu::Hidden->value ? TrangThaiSuatChieu::DangMoBan->value : TrangThaiSuatChieu::Hidden->value,
        ];
        $result = Database::update('SuatChieu', $params, "MaXuatChieu = $showId");
        return JsonResponse::ok();
    }

    public static function editShow($showId, $show)
    {
        if (!self::canEditShow($showId)) {
            return JsonResponse::error('Không thể chỉnh sửa suất chiếu vì đã có vé được bán', 400);
        }
        $exists = self::validateShowTime($show);
        if ($exists && $exists['MaXuatChieu'] != $showId) {
            return JsonDataErrorRespose::create()->addFieldError('NgayGioBatDau', 'Suất chiếu bị trùng giờ');
        }
        $params = [
            'MaPhim' => $show['MaPhim'],
            'MaPhongChieu' => $show['MaPhongChieu'],
            'NgayGioChieu' => $show['NgayGioBatDau'],
            'NgayGioKetThuc' => $show['NgayGioKetThuc'],
            'GiaVe' => $show['GiaVe']
        ];
        $result = Database::update('SuatChieu', $params, "MaXuatChieu = $showId");
        if (!$result) {
            return JsonResponse::error('Chỉnh sửa suất chiếu thất bại', 500);
        }
        return JsonResponse::ok();
    }
    public static function deleteShow($showId)
    {
        if (!self::canEditShow($showId)) {
            return JsonResponse::error('Không thể xóa suất chiếu vì đã có vé được bán', 400);
        }
        $result = Database::delete('SuatChieu', "MaXuatChieu = $showId");
        if (!$result) {
            return JsonResponse::error('Xóa suất chiếu thất bại', 500);
        }
        return JsonResponse::ok();
    }
}