<?php
namespace App\Services;

use App\Core\Database\Database;
use App\Dtos\TrangThaiPhim;


class StatisticService
{
    public static function countCustomer($params)
    {
        $ngayBatDau = getArrayValueSafe($params, 'tu-ngay', '');
        $ngayKetThuc = getArrayValueSafe($params, 'den-ngay', '');
        $rapChieu = getArrayValueSafe($params, 'rap-chieu', '');
        $subSql = " SELECT DISTINCT HoaDon.MaNguoiDung FROM HoaDon
        JOIN Ve ON HoaDon.MaHoaDon = Ve.MaHoaDon
        JOIN SuatChieu ON Ve.MaSuatChieu = SuatChieu.MaXuatChieu
        JOIN PhongChieu ON SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu
        WHERE 1=1";
        if ($ngayBatDau) {
            $subSql .= " AND  DATE(HoaDon.NgayGioThanhToan) >= DATE('$ngayBatDau')";
        }
        if ($ngayKetThuc) {
            $subSql .= " AND DATE(HoaDon.NgayGioThanhToan) <= DATE('$ngayKetThuc')";
        }
        if ($rapChieu) {
            $subSql .= " AND PhongChieu.MaRapChieu = $rapChieu";
        }
        $sql = "SELECT COUNT(*) as total FROM ($subSql) as subSql";
        $total = Database::query($sql, []);
        return $total;
    }
    public static function countTotalBill($params)
    {
        $ngayBatDau = getArrayValueSafe($params, 'tu-ngay', '');
        $ngayKetThuc = getArrayValueSafe($params, 'den-ngay', '');
        $rapChieu = getArrayValueSafe($params, 'rap-chieu', '');
        $sql = "SELECT COUNT(DISTINCT HoaDon.MaHoaDon) as total FROM HoaDon
        JOIN Ve ON HoaDon.MaHoaDon = Ve.MaHoaDon
        JOIN SuatChieu ON Ve.MaSuatChieu = SuatChieu.MaXuatChieu
        JOIN PhongChieu ON SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu
        WHERE 1=1";
        if ($ngayBatDau) {
            $sql .= " AND DATE(NgayGioThanhToan) >= DATE('$ngayBatDau')";
        }
        if ($ngayKetThuc) {
            $sql .= " AND DATE(NgayGioThanhToan) <= DATE('$ngayKetThuc')";
        }
        if ($rapChieu) {
            $sql .= " AND PhongChieu.MaRapChieu = $rapChieu";
        }
        $total = Database::query($sql, []);
        return $total;
    }
    public static function sumTotalBill($params)
    {
        $ngayBatDau = getArrayValueSafe($params, 'tu-ngay', '');
        $ngayKetThuc = getArrayValueSafe($params, 'den-ngay', '');
        $rapChieu = getArrayValueSafe($params, 'rap-chieu', '');
        $sql = "SELECT DISTINCT HoaDon.MaHoaDon, HoaDon.TongTien FROM HoaDon
        JOIN Ve ON HoaDon.MaHoaDon = Ve.MaHoaDon
        JOIN SuatChieu ON Ve.MaSuatChieu = SuatChieu.MaXuatChieu
        JOIN PhongChieu ON SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu
         where 1=1 ";
        if ($ngayBatDau) {
            $sql .= " AND DATE(NgayGioThanhToan) >= DATE('$ngayBatDau')";
        }
        if ($ngayKetThuc) {
            $sql .= " AND DATE(NgayGioThanhToan) <= DATE('$ngayKetThuc')";
        }
        if ($rapChieu) {
            $sql .= " AND PhongChieu.MaRapChieu = $rapChieu";
        }
        $finalSql = "SELECT SUM(TongTien) as total FROM ($sql) as subSql";
        $total = Database::query($finalSql, []);
        return $total;
    }
    public static function billStatistic($params)
    {
        $ngayBatDau = getArrayValueSafe($params, 'tu-ngay', '');
        $ngayKetThuc = getArrayValueSafe($params, 'den-ngay', '');
        $rapChieu = getArrayValueSafe($params, 'rap-chieu', '');
        $sql = "SELECT  DISTINCT HoaDon.MaHoaDon,HoaDon.TongTien, DATE(NgayGioThanhToan) as `date` FROM HoaDon
            JOIN Ve ON HoaDon.MaHoaDon = Ve.MaHoaDon
            JOIN SuatChieu ON Ve.MaSuatChieu = SuatChieu.MaXuatChieu
            JOIN PhongChieu ON SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu
             where 1=1 ";
        if ($ngayBatDau) {
            $sql .= " AND DATE(NgayGioThanhToan) >= DATE('$ngayBatDau')";
        }
        if ($ngayKetThuc) {
            $sql .= " AND DATE(NgayGioThanhToan) <= DATE('$ngayKetThuc')";
        }
        if ($rapChieu) {
            $sql .= " AND PhongChieu.MaRapChieu = $rapChieu";
        }
        $finalSql = "SELECT COUNT(*) as total, SUM(TongTien) as totalMoney, `date` FROM ($sql) as subSql GROUP BY `date`";
        $total = Database::query($finalSql, []);
        return $total;
    }
    public static function sumOfEachFilmTag()
    {
        $sumEachFilmTagQuery = "SELECT COUNT(*) as total, HanCheDoTuoi FROM Phim GROUP BY HanCheDoTuoi";
        $result = Database::query($sumEachFilmTagQuery, []);
        return $result;
    }
    public static function ticketStatistic($params)
    {
        $ngayBatDau = getArrayValueSafe($params, 'tu-ngay', '');
        $ngayKetThuc = getArrayValueSafe($params, 'den-ngay', '');
        $rapChieu = getArrayValueSafe($params, 'rap-chieu', '');
        $groupBy = getArrayValueSafe($params, 'group-by', 'DATE(HoaDon.NgayGioThanhToan)');
        $sql = "SELECT COUNT(Ve.MaVe) as total,PhongChieu.MaRapChieu, DATE(HoaDon.NgayGioThanhToan) as `date`, SUM(Ve.GiaVe) as totalMoney FROM Ve 
                JOIN HoaDon ON Ve.MaHoaDon = HoaDon.MaHoaDon
                JOIN SuatChieu ON Ve.MaSuatChieu = SuatChieu.MaXuatChieu
                JOIN PhongChieu ON SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu
                WHERE 1=1 ";

        if ($ngayBatDau) {
            $sql .= " AND DATE(HoaDon.NgayGioThanhToan) >= DATE('$ngayBatDau')";
        }
        if ($ngayKetThuc) {
            $sql .= " AND DATE(HoaDon.NgayGioThanhToan) <= DATE('$ngayKetThuc')";
        }
        if ($rapChieu) {
            $sql .= " AND PhongChieu.MaRapChieu = $rapChieu";
        }
        $sql .= " GROUP BY $groupBy";
        $total = Database::query($sql, []);
        return $total;
    }

    public static function countTotalTicket($params)
    {
        $ngayBatDau = getArrayValueSafe($params, 'tu-ngay', '');
        $ngayKetThuc = getArrayValueSafe($params, 'den-ngay', '');
        $rapChieu = getArrayValueSafe($params, 'rap-chieu', '');
        $sql = "SELECT COUNT(*) as total FROM Ve JOIN HoaDon ON Ve.MaHoaDon = HoaDon.MaHoaDon
                JOIN SuatChieu ON Ve.MaSuatChieu = SuatChieu.MaXuatChieu
                JOIN PhongChieu ON SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu
                WHERE 1=1 ";
        if ($ngayBatDau) {
            $sql .= " AND DATE(HoaDon.NgayGioThanhToan) >= DATE('$ngayBatDau')";
        }
        if ($ngayKetThuc) {
            $sql .= " AND DATE(HoaDon.NgayGioThanhToan) <= DATE('$ngayKetThuc')";
        }
        if ($rapChieu) {
            $sql .= " AND PhongChieu.MaRapChieu = $rapChieu";
        }
        $total = Database::query($sql, []);
        return $total;
    }

    // Tổng doanh thu các rạp chiếu

    public static function sumTotalRevenueEachCinema($params)
    {
        $ngayBatDau = getArrayValueSafe($params, 'tu-ngay', '');
        $ngayKetThuc = getArrayValueSafe($params, 'den-ngay', '');
        $subSql = "SELECT distinct HoaDon.MaHoaDon , HoaDon.TongTien, RapChieu.MaRapChieu  From HoaDon
                    join Ve on Ve.MaHoaDon = HoaDon.MaHoaDon
                    join SuatChieu on Ve.MaSuatChieu = SuatChieu.MaXuatChieu
                    join PhongChieu on PhongChieu.MaPhongChieu = SuatChieu.MaPhongChieu
                    join RapChieu on RapChieu.MaRapChieu = PhongChieu.MaRapChieu
                    where 1=1";
        if ($ngayBatDau) {
            $subSql .= " AND DATE(HoaDon.NgayGioThanhToan) >= DATE('$ngayBatDau')";
        }
        if ($ngayKetThuc) {
            $subSql .= " AND DATE(HoaDon.NgayGioThanhToan) <= DATE('$ngayKetThuc')";
        }
        $subSql .= " GROUP BY HoaDon.MaHoaDon ";

        $sql = "SELECT SUM(TongTien) as total, RapChieu.TenRapChieu, RapChieu.MaRapChieu FROM ($subSql) as subSql
               RIGHT JOIN RapChieu ON subSql.MaRapChieu = RapChieu.MaRapChieu
                WHERE 1=1 ";

        $sql .= " GROUP BY RapChieu.MaRapChieu";
        $total = Database::query($sql, []);
        return $total;
    }

    public static function sumTotalRevenueTicket($params)
    {
        $ngayBatDau = getArrayValueSafe($params, 'tu-ngay', '');
        $ngayKetThuc = getArrayValueSafe($params, 'den-ngay', '');
        $rapChieu = getArrayValueSafe($params, 'rap-chieu', '');
        $sql = "SELECT SUM(Ve.GiaVe) as totalMoney, DATE(HoaDon.NgayGioThanhToan) as `date`
                FROM Ve
                JOIN HoaDon ON Ve.MaHoaDon = HoaDon.MaHoaDon
                JOIN SuatChieu ON Ve.MaSuatChieu = SuatChieu.MaXuatChieu
                JOIN PhongChieu ON SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu

                WHERE 1=1 ";
        if ($ngayBatDau) {
            $sql .= " AND DATE(HoaDon.NgayGioThanhToan) >= DATE('$ngayBatDau')";
        }
        if ($ngayKetThuc) {
            $sql .= " AND DATE(HoaDon.NgayGioThanhToan) <= DATE('$ngayKetThuc')";
        }
        if ($rapChieu) {
            $sql .= " AND PhongChieu.MaRapChieu = $rapChieu";
        }
        $sql .= " GROUP BY DATE(HoaDon.NgayGioThanhToan)";
        $total = Database::query($sql, []);
        return $total;
    }

    public static function sumTotalRevenueFood($params)
    {
        $ngayBatDau = getArrayValueSafe($params, 'tu-ngay', '');
        $ngayKetThuc = getArrayValueSafe($params, 'den-ngay', '');
        $rapChieu = getArrayValueSafe($params, 'rap-chieu', '');
        $groupBy = getArrayValueSafe($params, 'group-by', 'ngay');
        $subGroupBy = $groupBy === 'ngay' ? 'DATE(SubQuery.NgayGioThanhToan)' : 'SubQuery.MaRapChieu';
        $mainGroupBy = $groupBy === 'ngay' ? 'date' : 'MaRapChieu';
        $sql1 = "SELECT SUM(CT_HoaDon_ThucPham.ThanhTien) as totalMoney,
         DATE(SubQuery.NgayGioThanhToan) as `date`,
        SUM(CT_HoaDon_ThucPham.SoLuong) as totalAmount,
       MaRapChieu

                 FROM
                 CT_HoaDon_ThucPham
                 JOIN (
                    SELECT DISTINCT HoaDon.MaHoaDon, NgayGioThanhToan,PhongChieu.MaRapChieu FROM
                        HoaDon 
                        JOIN Ve ON HoaDon.MaHoaDon = Ve.MaHoaDon
                        JOIN SuatChieu ON Ve.MaSuatChieu = SuatChieu.MaXuatChieu
                        JOIN PhongChieu ON SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu

                     ) as SubQuery ON CT_HoaDon_ThucPham.MaHoaDon = SubQuery.MaHoaDon
                    WHERE 1=1";
        if ($ngayBatDau) {
            $sql1 .= " AND DATE(SubQuery.NgayGioThanhToan) >= DATE('$ngayBatDau')";
        }
        if ($ngayKetThuc) {
            $sql1 .= " AND DATE(SubQuery.NgayGioThanhToan) <= DATE('$ngayKetThuc')";
        }
        if ($rapChieu) {
            $sql1 .= " AND SubQuery.MaRapChieu = $rapChieu";
        }
        $sql1 .= " GROUP BY $subGroupBy";

        $sql2 = "SELECT SUM(CT_HoaDon_Combo.ThanhTien) as totalMoney, DATE(SubQuery.NgayGioThanhToan) as `date`,
                SUM(CT_HoaDon_Combo.SoLuong) as totalAmount,
                MaRapChieu
                FROM CT_HoaDon_Combo
                JOIN (
                    SELECT DISTINCT HoaDon.MaHoaDon, NgayGioThanhToan,PhongChieu.MaRapChieu FROM
                        HoaDon 
                        JOIN Ve ON HoaDon.MaHoaDon = Ve.MaHoaDon
                        JOIN SuatChieu ON Ve.MaSuatChieu = SuatChieu.MaXuatChieu
                        JOIN PhongChieu ON SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu

                     ) as SubQuery ON CT_HoaDon_Combo.MaHoaDon = SubQuery.MaHoaDon
                WHERE 1=1";
        if ($ngayBatDau) {
            $sql2 .= " AND DATE(SubQuery.NgayGioThanhToan) >= DATE('$ngayBatDau')";
        }
        if ($ngayKetThuc) {
            $sql2 .= " AND DATE(SubQuery.NgayGioThanhToan) <= DATE('$ngayKetThuc')";
        }
        if ($rapChieu) {
            $sql2 .= " AND SubQuery.MaRapChieu = $rapChieu";
        }
        $sql2 .= " GROUP BY $subGroupBy";
        $sql = "SELECT  SUM(totalMoney) as totalMoney,`date` , SUM(totalAmount) as totalAmount ,MaRapChieu from
                ( ($sql1) UNION ALL ($sql2) ) as subSql
                GROUP BY `$mainGroupBy`";
        $total = Database::query($sql, []);
        return $total;
    }
    public static function sumTotalEachFood($params)
    {
        $ngayBatDau = getArrayValueSafe($params, 'tu-ngay', '');
        $ngayKetThuc = getArrayValueSafe($params, 'den-ngay', '');
        $rapChieu = getArrayValueSafe($params, 'rap-chieu', '');
        $loaiSanPham = getArrayValueSafe($params, 'loai-san-pham', '');
        // 1=> thức ăn, 2=> combo
        // lấy ra tổng tiền, ngày, tên,
        $sql1 = "SELECT CT_HoaDon_ThucPham.ThanhTien as totalMoney,
        CT_HoaDon_ThucPham.SoLuong totalAmount,
         DATE(SubQuery.NgayGioThanhToan) as `date`, ThucPham.TenThucPham as name
FROM
    CT_HoaDon_ThucPham
    JOIN (SELECT
        DISTINCT HoaDon.`MaHoaDon`,
        HoaDon.`NgayGioThanhToan`,
        PhongChieu.`MaRapChieu`
    FROM
        HoaDon
        JOIN Ve ON HoaDon.`MaHoaDon` = Ve.`MaHoaDon`
        JOIN SuatChieu ON Ve.`MaSuatChieu` = SuatChieu.`MaXuatChieu`
        JOIN PhongChieu ON SuatChieu.`MaPhongChieu` = PhongChieu.`MaPhongChieu`


    ) as SubQuery ON CT_HoaDon_ThucPham.`MaHoaDon` = SubQuery.`MaHoaDon`
    JOIN ThucPham ON CT_HoaDon_ThucPham.`MaThucPham` = ThucPham.`MaThucPham`
WHERE
    1 = 1";
        if ($ngayBatDau) {
            $sql1 .= " AND DATE(SubQuery.NgayGioThanhToan) >= DATE('$ngayBatDau')";
        }
        if ($ngayKetThuc) {
            $sql1 .= " AND DATE(SubQuery.NgayGioThanhToan) <= DATE('$ngayKetThuc')";
        }
        if ($rapChieu) {
            $sql1 .= " AND SubQuery.MaRapChieu = $rapChieu";
        }
        $sql2 = "SELECT CT_HoaDon_Combo.ThanhTien as totalMoney,
        CT_HoaDon_Combo.SoLuong as totalAmount,
         DATE(SubQuery.NgayGioThanhToan) as `date`, Combo.TenCombo as name
                FROM CT_HoaDon_Combo
                JOIN (SELECT
                    DISTINCT HoaDon.`MaHoaDon`,
                    HoaDon.`NgayGioThanhToan`,
                    PhongChieu.`MaRapChieu`
                FROM
                    HoaDon
                    JOIN Ve ON HoaDon.`MaHoaDon` = Ve.`MaHoaDon`
                    JOIN SuatChieu ON Ve.`MaSuatChieu` = SuatChieu.`MaXuatChieu`
                    JOIN PhongChieu ON SuatChieu.`MaPhongChieu` = PhongChieu.`MaPhongChieu`
                ) as SubQuery ON CT_HoaDon_Combo.`MaHoaDon` = SubQuery.`MaHoaDon`
                JOIN Combo ON CT_HoaDon_Combo.`MaCombo` = Combo.`MaCombo`
                WHERE 1=1";
        if ($ngayBatDau) {
            $sql2 .= " AND DATE(SubQuery.NgayGioThanhToan) >= DATE('$ngayBatDau')";
        }
        if ($ngayKetThuc) {
            $sql2 .= " AND DATE(SubQuery.NgayGioThanhToan) <= DATE('$ngayKetThuc')";
        }
        if ($rapChieu) {
            $sql2 .= " AND SubQuery.MaRapChieu = $rapChieu";
        }
        if ($loaiSanPham == 1) {
            $sql = $sql1 . " GROUP BY name";
        } else if ($loaiSanPham == 2) {
            $sql = $sql2 . " GROUP BY name";
        } else {
            $sql = "SELECT SUM(totalMoney) as totalMoney, name,  SUM(totalAmount) as totalAmount, `date`
                 from
                ( ($sql1) UNION ALL ($sql2) ) as subSql
                GROUP BY  name";
        }
        $total = Database::query($sql, []);
        return $total;



    }
    public static function movieStatistic($params)
    {
        $ngayBatDau = getArrayValueSafe($params, 'tu-ngay', '');
        $ngayKetThuc = getArrayValueSafe($params, 'den-ngay', '');
        $rapChieu = getArrayValueSafe($params, 'rap-chieu', '');
        $top = getArrayValueSafe($params, 'top', null);
        $subSql = "SELECT distinct HoaDon.MaHoaDon , HoaDon.TongTien, Phim.TenPhim, Phim.MaPhim  From HoaDon
                    join Ve on Ve.MaHoaDon = HoaDon.MaHoaDon
                    join SuatChieu on Ve.MaSuatChieu = SuatChieu.MaXuatChieu
                    join Phim on Phim.MaPhim = SuatChieu.MaPhim
                    join PhongChieu on PhongChieu.MaPhongChieu = SuatChieu.MaPhongChieu
                    where 1=1";

        if ($ngayBatDau) {
            $subSql .= " AND DATE(HoaDon.NgayGioThanhToan) >= DATE('$ngayBatDau')";
        }
        if ($ngayKetThuc) {
            $subSql .= " AND DATE(HoaDon.NgayGioThanhToan) <= DATE('$ngayKetThuc')";
        }
        if ($rapChieu) {
            $subSql .= " AND PhongChieu.MaRapChieu = $rapChieu";
        }

        $sql = "SELECT SUM(TongTien) as total,COUNT(DISTINCT MaHoaDon) as totalBill, Phim.HinhAnh,Phim.TenPhim, Phim.MaPhim FROM ($subSql) as subSql
                RIGHT JOIN Phim ON subSql.MaPhim = Phim.MaPhim
                WHERE 1=1 ";
        $sql .= " GROUP BY Phim.MaPhim ";
        if ($top) {
            $sql .= " ORDER BY total DESC LIMIT $top";
        }
        $total = Database::query($sql, []);
        return $total;

    }

    public static function overviewMovie(
        $params,
    ) {
        // mã phim,/ tên phim, số vé bán, số suất chiếu , tổng doanh thu
        $tuNgay = getArrayValueSafe($params, 'tu-ngay', '');
        $denNgay = getArrayValueSafe($params, 'den-ngay', '');
        $theLoais = getArrayValueSafe($params, 'the-loais', '');
        // đầu tiên tìm phim theo thể loại having count = count của thể loại
        $sql = "SELECT
        DISTINCT
         Phim.MaPhim, Phim.TenPhim, Phim.HinhAnh
            FROM Phim
            JOIN CT_Phim_TheLoai ON Phim.MaPhim = CT_Phim_TheLoai.MaPhim
            JOIN TheLoai ON CT_Phim_TheLoai.MaTheLoai = TheLoai.MaTheLoai
            WHERE Phim.TrangThai != " . TrangThaiPhim::SapChieu->value;
        if ($theLoais) {
            $theLoaisIn = implode(',', $theLoais);
            $sql .= " AND TheLoai.MaTheLoai IN ($theLoaisIn)";
            $sql .= " GROUP BY Phim.MaPhim HAVING COUNT(Phim.MaPhim) = " . count($theLoais);
        }

        $result = Database::query($sql, []);
        $phimIds = array_column($result, 'MaPhim');
        if (count($phimIds) === 0) {
            return [];
        }
        $phimIds = implode(',', $phimIds);
        $countSuatChieu = "SELECT COUNT(*) as total, Phim.MaPhim FROM Phim
        JOIN SuatChieu ON Phim.MaPhim = SuatChieu.MaPhim
        WHERE Phim.MaPhim IN ($phimIds)";
        if ($tuNgay) {
            $countSuatChieu .= " AND DATE(SuatChieu.NgayGioChieu) >= DATE('$tuNgay')";
        }
        if ($denNgay) {
            $countSuatChieu .= " AND DATE(SuatChieu.NgayGioChieu) <= DATE('$denNgay')";
        }
        $countSuatChieu .= " GROUP BY Phim.MaPhim";
        $countSuatChieuResult = Database::query($countSuatChieu, []);

        $countVe = "SELECT COUNT(*) as total, SUM(Ve.GiaVe) as totalMoney, Phim.MaPhim FROM Phim
        JOIN SuatChieu ON Phim.MaPhim = SuatChieu.MaPhim
        JOIN Ve ON Ve.MaSuatChieu = SuatChieu.MaXuatChieu
        JOIN HoaDon ON Ve.MaHoaDon = HoaDon.MaHoaDon
        WHERE Phim.MaPhim IN ($phimIds)
        AND Ve.MaHoaDon IS NOT NULL
        ";
        if ($tuNgay) {
            $countVe .= " AND DATE(HoaDon.NgayGioThanhToan) >= DATE('$tuNgay')";
        }
        if ($denNgay) {
            $countVe .= " AND DATE(HoaDon.NgayGioThanhToan) <= DATE('$denNgay')";
        }
        $countVe .= " GROUP BY Phim.MaPhim";
        $countVeResult = Database::query($countVe, []);

        // join bảng countSuatChieu vào countVe
        $result = array_map(function ($item) use ($countSuatChieuResult, $countVeResult) {
            $suatChieu = array_filter($countSuatChieuResult, function ($suatChieu) use ($item) {
                return $suatChieu['MaPhim'] == $item['MaPhim'];
            });
            $ve = array_filter($countVeResult, function ($ve) use ($item) {
                return $ve['MaPhim'] == $item['MaPhim'];
            });
            $suatChieu = array_values($suatChieu);
            $ve = array_values($ve);
            $item['totalSuatChieu'] = $suatChieu[0]['total'] ?? 0;
            $item['totalVe'] = $ve[0]['total'] ?? 0;
            $item['totalMoney'] = $ve[0]['totalMoney'] ?? 0;
            return $item;
        }, $result);

        return $result;

    }

}