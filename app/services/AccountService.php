<?php
namespace App\Services;

use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Core\Logger;
use App\Core\Request;
use App\Dtos\JsonDataErrorRespose;
use App\Dtos\JsonResponse;
use App\Dtos\LoaiTaiKhoan;
use App\Dtos\TrangThaiTaiKhoan;

class AccountService
{
    private static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    public static function comparePassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
    public static function setRole($id, $data)
    {
        $role = $data['role'];
        $result = Database::update('TaiKhoan', ['MaNhomQuyen' => $role], "MaTaiKhoan=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Cập nhật thất bại', 500);
    }
    public static function setPassword($id, $data)
    {
        $password = self::hashPassword($data['password']);
        $result = Database::update('TaiKhoan', ['MatKhau' => $password], "MaTaiKhoan=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Cập nhật thất bại', 500);
    }

    public static function toggleLockAccount($id)
    {
        $account = Database::queryOne("SELECT * FROM TaiKhoan WHERE MaTaiKhoan = ?", [$id]);
        $trangThai = $account['TrangThai'];
        if ($trangThai == TrangThaiTaiKhoan::DangBiKhoa->value) {
            $trangThai = TrangThaiTaiKhoan::DangHoatDong->value;
        } else {
            $trangThai = TrangThaiTaiKhoan::DangBiKhoa->value;
        }
        $result = Database::update('TaiKhoan', ['TrangThai' => $trangThai], "MaTaiKhoan=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Cập nhật thất bại', 500);
    }
    public static function updateAccount($data, $id)
    {
        $params = [
            'TenDangNhap' => $data['TenDangNhap'],
            'TrangThai' => $data['TrangThai'],
            'LoaiTaiKhoan' => $data['LoaiTaiKhoan'],
            'MaNguoiDung' => $data['MaNguoiDung'],
            'MaNhomQuyen' => $data['MaNhomQuyen'],
        ];
        $result = Database::update('TaiKhoan', $params, "MaTaiKhoan=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Cập nhật thất bại', 500);
    }
    public static function getAllAccount($data)
    {
        $keyword = getArrayValueSafe($data, 'tu-khoa');
        $loaiTaiKhoan = getArrayValueSafe($data, 'loai-tai-khoan');
        $page = getArrayValueSafe($data, 'trang', 1);
        $pageSize = getArrayValueSafe($data, 'limit', 20);
        $offset = ($page - 1) * $pageSize;
        $orderBy = getArrayValueSafe($data, 'sap-xep', 'MaTaiKhoan');
        $orderType = getArrayValueSafe($data, 'thu-tu', 'desc');
        $queryBuilder = new QueryBuilder();
        $queryBuilder->select([
            'TaiKhoan.MaTaiKhoan',
            'TaiKhoan.TrangThai',
            'TaiKhoan.LoaiTaiKhoan',
            'TaiKhoan.MaNguoiDung',
            'TaiKhoan.TenDangNhap',
            'NguoiDung.TenNguoiDung',
            'NhomQuyen.TenNhomQuyen',
        ])
            ->from('TaiKhoan')
            ->join('NguoiDung', 'TaiKhoan.MaNguoiDung = NguoiDung.MaNguoiDung')
            ->join('NhomQuyen', 'TaiKhoan.MaNhomQuyen = NhomQuyen.MaNhomQuyen', 'left')
            ->where('TenDangNhap', '!=', 'admin');
        if ($loaiTaiKhoan) {
            $queryBuilder->andWhere('TaiKhoan.LoaiTaiKhoan', '=', $loaiTaiKhoan);
        }
        if ($keyword) {
            $queryBuilder->and();
            $queryBuilder->startGroup();
            $queryBuilder->where('TaiKhoan.TenDangNhap', 'like', "%$keyword%");
            $queryBuilder->orWhere('NguoiDung.TenNguoiDung', 'like', "%$keyword%");
            $queryBuilder->orWhere('NguoiDung.MaNguoiDung', 'like', "$keyword");
            $queryBuilder->orWhere('TaiKhoan.MaTaiKhoan', 'like', "$keyword");
            $queryBuilder->orWhere('NhomQuyen.TenNhomQuyen', 'like', "%$keyword%");
            $queryBuilder->endGroup();
        }
        $count = $queryBuilder->count();
        $queryBuilder->orderBy('TaiKhoan.' . $orderBy, $orderType);
        $queryBuilder->limit($pageSize, $offset);
        $result = $queryBuilder->get();
        Request::setQueryCount($count);
        return $result;
    }

    public static function getAccountById($id)
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->select([
            'TaiKhoan.MaTaiKhoan',
            'TaiKhoan.TrangThai',
            'TaiKhoan.LoaiTaiKhoan',
            'TaiKhoan.MaNguoiDung',
            'TaiKhoan.TenDangNhap',
            'TaiKhoan.MaNhomQuyen',
        ])
            ->from('TaiKhoan')
            ->where('TaiKhoan.MaTaiKhoan', '=', $id);
        return $queryBuilder->first();
    }

    public static function getUserAccount($userId)
    {
        $query = "SELECT * FROM TaiKhoan WHERE MaNguoiDung = ?;";
        $account = Database::queryOne($query, [$userId]);
        return $account;
    }
    public static function createNewAccount($data)
    {
        $params = [
            'TenDangNhap' => $data['TenDangNhap'],
            'MatKhau' => self::hashPassword($data['MatKhau']),
            'TrangThai' => getArrayValueSafe($data, 'TrangThai', TrangThaiTaiKhoan::DangHoatDong->value),
            'LoaiTaiKhoan' => $data['LoaiTaiKhoan'],
            'MaNguoiDung' => $data['MaNguoiDung'],
        ];
        $isExist = Database::queryOne("SELECT * FROM TaiKhoan WHERE TenDangNhap = ?", [$data['TenDangNhap']]);
        $isExist = isset($isExist) ? true : false;
        if ($isExist) {
            return JsonDataErrorRespose::create([
                'TenDangNhap' => 'Tên đăng nhập đã tồn tại'
            ]);
        }

        if ($data['LoaiTaiKhoan'] == LoaiTaiKhoan::NhanVien->value) {
            $params['MaNhomQuyen'] = $data['MaNhomQuyen'];
        }
        $result = Database::insert('TaiKhoan', $params);
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Tạo tài khoản thất bại', 500);
    }

    public static function deleteAccount($id)
    {
        $result = Database::delete('TaiKhoan', "MaTaiKhoan=$id");
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Xóa tài khoản thất bại', 500);
    }
    public static function login($username, $password)
    {
        $account = Database::queryOne("SELECT MaTaiKhoan,MaNguoiDung, MatKhau,TrangThai,LoaiTaiKhoan,MaNhomQuyen FROM TaiKhoan WHERE TenDangNhap = ?", [$username]);
        if ($account) {
            if (self::comparePassword($password, $account['MatKhau'])) {
                return $account;
            }
        }
        return false;
    }
    public static function getAccountByUserId($userId)
    {
        $query = "SELECT * FROM TaiKhoan WHERE MaNguoiDung = ?;";
        $account = Database::queryOne($query, [$userId]);
        return $account;
    }
    public static function updatePassword($userId, $oldPassword, $newPassword)
    {
        $account = self::getAccountByUserId($userId);
        if (isset($account)) {
            if (self::comparePassword($oldPassword, $account['MatKhau'])) {
                $newPassword = self::hashPassword($newPassword);
                $result = Database::update('TaiKhoan', ['MatKhau' => $newPassword], "MaNguoiDung=$userId");
                return JsonResponse::ok();
            } else {
                return JsonDataErrorRespose::create([
                    'MatKhauCu' => 'Mật khẩu cũ không đúng'
                ]);
            }
        }
        return JsonResponse::error('Người dùng không tồn tại', 404);
    }
}