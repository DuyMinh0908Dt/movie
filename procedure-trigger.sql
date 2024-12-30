DELIMITER / /

CREATE PROCEDURE CapNhatTrangThaiPhim() 
BEGIN 
DECLARE
	ngayHienTai DATE;
	-- Lấy ngày hiện tại
	SET ngayHienTai = CURDATE();
	-- Cập nhật trạng thái của phim dựa trên NgayPhatHanh so với ngày hiện tại, nếu trạng thái hiện tại không là 3
	UPDATE Phim
	SET
	    TrangThai = CASE
	        WHEN NgayPhatHanh > ngayHienTai THEN 2
	        -- Ngày phát hành sau ngày hiện tại, trạng thái là 2
	        ELSE 1
	        -- Ngày phát hành trước hoặc bằng ngày hiện tại, trạng thái là 1
	    END
	WHERE
	    TrangThai ! = 3;
	-- Chỉ cập nhật nếu TrangThai không phải là 3
END
// 

DELIMITER;

DELIMITER / /

CREATE TRIGGER CapNhatTrangThaiPhim_Insert AFTER INSERT 
ON Phim FOR EACH ROW 
BEGIN 
	-- Gọi thủ tục CapNhatTrangThaiPhim
	CALL CapNhatTrangThaiPhim ();
END
// 

DELIMITER;

DELIMITER $$

CREATE PROCEDURE update_ve_gia_trang_thai_5() 
BEGIN 
DECLARE
	loai_ve_gia INT;
DECLARE
	loai_ghe_gia INT;
DECLARE
	suat_chieu_gia INT;
DECLARE
	done INT DEFAULT FALSE;
DECLARE
	ma_ve INT;
DECLARE
	ma_loai_ve INT;
DECLARE
	ma_ghe INT;
DECLARE
	ma_suat_chieu INT;
DECLARE
	cur CURSOR FOR
	SELECT v.MaVe, v.MaLoaiVe, v.MaGhe, v.MaSuatChieu
	FROM Ve v
	WHERE
	    v.TrangThai = 5;
DECLARE
	CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	OPEN cur;
	 read_loop : LOOP FETCH cur INTO ma_ve , ma_loai_ve , ma_ghe , ma_suat_chieu;
	IF done THEN LEAVE read_loop;
END
	IF;
	-- Lấy giá vé từ bảng LoaiVe
	SELECT GiaVe INTO loai_ve_gia
	FROM LoaiVe
	WHERE
	    MaLoaiVe = ma_loai_ve;
	-- Lấy giá ghế từ bảng Ghe và LoaiGhe
	SELECT lg.GiaVe INTO loai_ghe_gia
	FROM Ghe g
	    JOIN LoaiGhe lg ON g.MaLoaiGhe = lg.MaLoaiGhe
	WHERE
	    g.MaGhe = ma_ghe;
	-- Lấy giá vé từ bảng SuatChieu
	SELECT GiaVe INTO suat_chieu_gia
	FROM SuatChieu
	WHERE
	    MaXuatChieu = ma_suat_chieu;
	-- Tính tổng giá vé
	UPDATE Ve
	SET
	    GiaVe = loai_ve_gia + loai_ghe_gia + suat_chieu_gia
	WHERE
	    MaVe = ma_ve;
END
	LOOP read_loop;
	CLOSE cur;
END$$ 

DELIMITER;

DELIMITER $$

CREATE TRIGGER update_ve_gia_khi_cap_nhat_hoa_don BEFORE 
UPDATE ON Ve FOR EACH ROW 
BEGIN 
DECLARE
	loai_ve_gia INT;
DECLARE
	loai_ghe_gia INT;
DECLARE
	suat_chieu_gia INT;
	IF NEW.TrangThai <> OLD.TrangThai
	AND NEW.TrangThai = 5 THEN
	-- Lấy giá vé từ bảng LoaiVe
	SELECT GiaVe INTO loai_ve_gia
	FROM LoaiVe
	WHERE
	    MaLoaiVe = NEW.MaLoaiVe;
	-- Lấy giá ghế từ bảng Ghe và LoaiGhe
	SELECT lg.GiaVe INTO loai_ghe_gia
	FROM Ghe g
	    JOIN LoaiGhe lg ON g.MaLoaiGhe = lg.MaLoaiGhe
	WHERE
	    g.MaGhe = NEW.MaGhe;
	-- Lấy giá vé từ bảng SuatChieu
	SELECT GiaVe INTO suat_chieu_gia
	FROM SuatChieu
	WHERE
	    MaXuatChieu = NEW.MaSuatChieu;
	-- Tính tổng giá vé
	SET NEW.GiaVe = loai_ve_gia + loai_ghe_gia + suat_chieu_gia;
END
	IF;
END$$ 

DELIMITER;

DELIMITER $$

CREATE TRIGGER update_ct_hoadon_combo_thanhtien BEFORE 
INSERT ON CT_HoaDon_Combo FOR EACH ROW 
BEGIN 
DECLARE
	gia_combo INT;
	SELECT GiaCombo INTO gia_combo
	FROM Combo
	WHERE
	    MaCombo = NEW.MaCombo;
	SET NEW.ThanhTien = gia_combo * NEW.SoLuong;
END$$ 

DELIMITER;

DELIMITER $$

CREATE TRIGGER update_ct_hoadon_combo_thanhtien_after_update 
AFTER UPDATE ON CT_HoaDon_Combo FOR EACH ROW 
BEGIN 
DECLARE
	gia_combo INT;
	SELECT GiaCombo INTO gia_combo
	FROM Combo
	WHERE
	    MaCombo = NEW.MaCombo;
	SET NEW.ThanhTien = gia_combo * NEW.SoLuong;
END$$ 

DELIMITER;

DELIMITER $$

CREATE TRIGGER update_ct_hoadon_thucpham_thanhtien 
BEFORE INSERT ON CT_HoaDon_ThucPham FOR EACH ROW 
BEGIN 
DECLARE
	gia_thucpham INT;
	SELECT GiaThucPham INTO gia_thucpham
	FROM ThucPham
	WHERE
	    MaThucPham = NEW.MaThucPham;
	SET NEW.ThanhTien = gia_thucpham * NEW.SoLuong;
END$$ 

DELIMITER;

DELIMITER $$

CREATE TRIGGER update_ct_hoadon_thucpham_thanhtien_after_update 
AFTER UPDATE ON CT_HoaDon_ThucPham FOR EACH ROW 
BEGIN 
DECLARE
	gia_thucpham INT;
	SELECT GiaThucPham INTO gia_thucpham
	FROM ThucPham
	WHERE
	    MaThucPham = NEW.MaThucPham;
	SET NEW.ThanhTien = gia_thucpham * NEW.SoLuong;
END$$ 

DELIMITER;

DELIMITER $$

CREATE PROCEDURE update_thanhtien_ct_hoadon() 
BEGIN 
	-- Cập nhật thành tiền cho CT_HoaDon_Combo
DECLARE
	done INT DEFAULT FALSE;
DECLARE
	ma_hoa_don_combo CHAR(36);
DECLARE
	ma_combo_combo INT;
DECLARE
	so_luong_combo INT;
DECLARE
	gia_combo INT;
DECLARE
	ma_hoa_don_thucpham CHAR(36);
DECLARE
	ma_thucpham INT;
DECLARE
	so_luong_thucpham INT;
DECLARE
	gia_thucpham INT;
DECLARE
	cur_thucpham CURSOR FOR
	SELECT MaHoaDon, MaThucPham, SoLuong
	FROM CT_HoaDon_ThucPham;
DECLARE
	cur_combo CURSOR FOR
	SELECT MaHoaDon, MaCombo, SoLuong
	FROM CT_HoaDon_Combo;
DECLARE
	CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	OPEN cur_combo;
	 read_loop : LOOP FETCH cur_combo INTO ma_hoa_don_combo , ma_combo_combo , so_luong_combo;
	IF done THEN LEAVE read_loop;
END
	IF;
	SELECT GiaCombo INTO gia_combo
	FROM Combo
	WHERE
	    MaCombo = ma_combo_combo;
	UPDATE CT_HoaDon_Combo
	SET
	    ThanhTien = gia_combo * so_luong_combo
	WHERE
	    MaHoaDon = ma_hoa_don_combo
	    AND MaCombo = ma_combo_combo;
END
	LOOP read_loop;
	CLOSE cur_combo;
	-- Cập nhật thành tiền cho CT_HoaDon_ThucPham
	SET done = FALSE;
	OPEN cur_thucpham;
	 read_loop2 : LOOP FETCH cur_thucpham INTO ma_hoa_don_thucpham , ma_thucpham , so_luong_thucpham;
	IF done THEN LEAVE read_loop2;
END
	IF;
	SELECT GiaThucPham INTO gia_thucpham
	FROM ThucPham
	WHERE
	    MaThucPham = ma_thucpham;
	UPDATE CT_HoaDon_ThucPham
	SET
	    ThanhTien = gia_thucpham * so_luong_thucpham
	WHERE
	    MaHoaDon = ma_hoa_don_thucpham
	    AND MaThucPham = ma_thucpham;
END
	LOOP read_loop2;
	CLOSE cur_thucpham;
END$$ 

DELIMITER;