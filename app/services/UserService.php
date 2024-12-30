<?php

namespace App\Services;

use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Core\Logger;
use App\Core\Request;
use App\Dtos\JsonDataErrorRespose;
use App\Dtos\JsonResponse;
use App\Dtos\Permission;
use App\Dtos\TrangThaiTaiKhoan;

enum AccountType: int
{
    case Employee = 1;
    case Customer = 2;
}
class UserService
{
    public static function isMailExist($email)
    {
        $query = "SELECT * FROM NguoiDung WHERE Email = ?;";
        $user = Database::queryOne($query, [$email]);
        if (!isset($user)) {
            return [
                "isExist" => false
            ];
        }
        $account = AccountService::getUserAccount($user['MaNguoiDung']);
        return [
            "isExist" => isset($user) ? true : false,
            "HoTen" => $user['TenNguoiDung'] ?? '',
            "SoDienThoai" => $user['SoDienThoai'] ?? '',
            "HasAccount" => isset($account) ? true : false
        ];
    }


    public static function updateUser($userId, $newInfo)
    {
        $result = null;
        $params = [
            "TenNguoiDung" => $newInfo["TenNguoiDung"] ?? "",
            "SoDienThoai" => $newInfo["SoDienThoai"] ?? "",
            "DiaChi" => $newInfo["DiaChi"] ?? "",
            "NgaySinh" => $newInfo["NgaySinh"] ?? "",
        ];
        $result = Database::update("NguoiDung", $params, "MaNguoiDung = $userId");
        return $result;
    }
    public static function plusPoint($userId, $point)
    {
        $query = "UPDATE NguoiDung SET DiemTichLuy = DiemTichLuy + ? WHERE MaNguoiDung = ?;";
        $result = Database::execute($query, [$point, $userId]);
        return $result;
    }

    public static function delete($userId)
    {

        $isUserOrdered = OrderService::isUserOrdered($userId);
        if ($isUserOrdered) {
            return new JsonResponse(400, "Người dùng đã đặt vé không thể xóa");
        }
        $result = Database::delete("NguoiDung", "MaNguoiDung = $userId");
        if (!$result) {
            return new JsonResponse(500, "Xóa thất bại");
        }
        return new JsonResponse(200, "Xóa thành công");
    }

    public static function createNewUser($data)
    {
        $params = [
            'TenNguoiDung' => $data['TenNguoiDung'],
            'SoDienThoai' => ($data['SoDienThoai']),
            'Email' => $data['Email'],
            'DiaChi' => $data['DiaChi'],
            'NgaySinh' => $data['NgaySinh'],
            'TrangThai' => getArrayValueSafe($data, 'TrangThai', TrangThaiTaiKhoan::DangHoatDong->value),

        ];
        error_log(print_r($params, true));
        if (self::isMailExist($params['Email'])['isExist']) {
            return JsonResponse::error('Email đã tồn tại', 403);
        }
        $result = Database::insert('NguoiDung', $params);
        if ($result) {
            return JsonResponse::ok();
        }
        return JsonResponse::error('Tạo người dùng thất bại', 500);

    }
    public static function getUserInfo($userId)
    {
        $query = "SELECT * FROM NguoiDung WHERE MaNguoiDung = ?;";
        $user = Database::queryOne($query, [$userId]);
        return $user;
    }

    public static function getUserByEmail($email)
    {
        $query = "SELECT * FROM NguoiDung WHERE Email = ?;";
        $user = Database::queryOne($query, [$email]);
        return $user;
    }



    public static function getAllUser($params)
    {
        $queryBuilder = new QueryBuilder();
        $isHasAccount = getArrayValueSafe($params, 'co-tai-khoan', null);
        $page = getArrayValueSafe($params, 'trang', 1);
        $pageSize = getArrayValueSafe($params, 'limit', 20);
        $offset = ($page - 1) * $pageSize;
        $orderBy = getArrayValueSafe($params, 'sap-xep', 'MaNguoiDung');
        $orderType = getArrayValueSafe($params, 'thu-tu', 'desc');
        $keyword = getArrayValueSafe($params, 'tu-khoa', null);
        $diemTichLuyTu = getArrayValueSafe($params, 'diem-tich-luy-tu', null);
        $diemTichLuyDen = getArrayValueSafe($params, 'diem-tich-luy-den', null);
        $loaiTaiKhoan = getArrayValueSafe($params, 'loai-tai-khoan', null);
        $queryBuilder->select([
            'NguoiDung.MaNguoiDung',
            'NguoiDung.TenNguoiDung',
            'NguoiDung.Email',
            'NguoiDung.SoDienThoai',
            'NguoiDung.DiaChi',
            'NguoiDung.NgaySinh',
            'NguoiDung.DiemTichLuy',
            'TaiKhoan.MaTaiKhoan',
            'TaiKhoan.LoaiTaiKhoan',
        ]);
        $queryBuilder->from('NguoiDung');
        $queryBuilder->join('TaiKhoan', 'NguoiDung.MaNguoiDung = TaiKhoan.MaNguoiDung', 'left');
        $queryBuilder->where('NguoiDung.MaNguoiDung', '!=', 1); // ignore admin
        if ($isHasAccount !== null) {
            if ($isHasAccount == true)
                $queryBuilder->andWhere('TaiKhoan.MaTaiKhoan', 'is not', null);
            else {
                $queryBuilder->andWhere('TaiKhoan.MaTaiKhoan', 'is', null);
            }
        }
        error_log($queryBuilder->__toString());
        if ($keyword != null) {
            $queryBuilder->and();
            $queryBuilder->startGroup();
            $queryBuilder->where('NguoiDung.TenNguoiDung', 'like', "%$keyword%");
            $queryBuilder->orWhere('NguoiDung.Email', 'like', "%$keyword%");
            $queryBuilder->orWhere('NguoiDung.SoDienThoai', 'like', "%$keyword%");
            $queryBuilder->orWhere('NguoiDung.DiaChi', 'like', "%$keyword%");
            $queryBuilder->orWhere('NguoiDung.MaNguoiDung', 'like', "%$keyword%");
            $queryBuilder->endGroup();
        }
        if ($diemTichLuyTu) {
            $queryBuilder->andWhere('NguoiDung.DiemTichLuy', '>=', $diemTichLuyTu);
        }
        if ($diemTichLuyDen) {
            $queryBuilder->andWhere('NguoiDung.DiemTichLuy', '<=', $diemTichLuyDen);
        }
        if ($loaiTaiKhoan) {
            $queryBuilder->andWhere('TaiKhoan.LoaiTaiKhoan', $loaiTaiKhoan == 'NULL' ? 'is' : '=', $loaiTaiKhoan);
        }
        $count = $queryBuilder->count();
        $queryBuilder->orderBy('NguoiDung.' . $orderBy, $orderType);
        $queryBuilder->limit($pageSize, $offset);
        $result = $queryBuilder->get();
        Request::setQueryCount($count);
        return $result;
    }
    public static function getUserOrCreateIfNotExist($data)
    {
        $email = $data['email'];
        $user = self::getUserByEmail($email);
        if (!$user) {
            $id = Database::insert(
                "NguoiDung",
                [
                    "TenNguoiDung" => $data['name'],
                    "Email" => $data['email'],
                    "SoDienThoai" => $data['phone'],
                    'DiemTichLuy' => 0,
                ]
            );
            if (!$id) {
                return null;
            }
            return $id;
        } else {
            return $user['MaNguoiDung'];
        }
    }
    public static function register($data)
    {
        // nếu thông tin đã tồn tại mà tài khoản có thì tạo tài khoản
        $email = $data['email'];
        $existUser = self::getUserByEmail($email);
        if ($existUser) {
            $account = AccountService::getUserAccount($existUser['MaNguoiDung']);
            if (isset($account)) {
                return new JsonResponse(400, "Email đã tồn tại");
            }
        }
        $id = null;
        if (!isset($existUser)) {
            $id = Database::insert(
                "NguoiDung",
                [
                    "TenNguoiDung" => $data['fullname'],
                    "Email" => $data['email'],
                    "SoDienThoai" => $data['phone'],
                    "DiaChi" => $data['address'],
                    "NgaySinh" => getArrayValueSafe($data, 'dateOfBirth', null),
                    "DiemTichLuy" => 0,
                ]
            );
        } else {
            self::updateUser($existUser['MaNguoiDung'], [
                "TenNguoiDung" => $data['fullname'],
                "SoDienThoai" => $data['phone'],
                "DiaChi" => $data['address'],
                "NgaySinh" => getArrayValueSafe($data, 'dateOfBirth', null),
                "Email" => $data['email'],
            ]);
            $id = $existUser['MaNguoiDung'];
        }
        if (!$id) {
            return new JsonResponse(500, "Đăng ký thất bại");
        }
        $res = AccountService::createNewAccount([
            "TenDangNhap" => $data['email'],
            "MatKhau" => $data['password'],
            "TrangThai" => null,
            "LoaiTaiKhoan" => AccountType::Customer->value,
            "MaNguoiDung" => $id,
        ]);
        if (!$res->isSuccessful()) {
            return new JsonResponse(500, "Đăng ký thất bại");
        }
        return new JsonResponse(200, "Đăng ký thành công");
    }


    private static function setSession($user)
    {
        if (isset($user['TaiKhoan']['MaNhomQuyen'])) {
            $role = RoleService::getRoleById($user['TaiKhoan']['MaNhomQuyen']);
            $permissions = array_map(function ($permission) {
                $rawPermission = $permission['TenQuyen'];
                $permission = Permission::from($rawPermission);
                return $permission;
            }, $role['Quyen']);
            $user['permissions'] = $permissions;

        } else {
            $user['permissions'] = [];
        }
        $_SESSION['user'] = $user;
    }
    private static function rememberLogin($user)
    {
        $secretKey = $GLOBALS['config']['Auth']['secret'];
        $rememberTime = $GLOBALS['config']['Auth']['remember_time_in_days'];
        $userId = $user['MaNguoiDung'];
        $password = $user['TaiKhoan']['MatKhau'];
        $rawHash = "$userId|$password"; // để tránh trường hợp người dùng đổi mật khẩu thì cookie vẫn còn
        $hash = hash_hmac('sha256', $rawHash, $secretKey);
        $cookieValue = "$userId|$hash";
        setcookie(
            "remember",
            $cookieValue,
            time() + 60 * 60 * 24 * $rememberTime
        );

    }
    public static function logout()
    {
        unset($_SESSION['user']);
        setcookie("remember", "", time() - 3600, '/');
    }

    public static function getUserById($id)
    {
        $query = "SELECT * FROM NguoiDung WHERE MaNguoiDung = ?;";
        $user = Database::queryOne($query, [$id]);
        return $user;
    }
    public static function login($username, $password, $rememberMe = false)
    {


        $account = AccountService::login($username, $password);
        if ($account == false) {
            return new JsonResponse(401, "Sai tên đăng nhập hoặc mật khẩu");
        }
        if ($account['TrangThai'] == TrangThaiTaiKhoan::DangBiKhoa->value) {
            return new JsonResponse(401, "Tài khoản đã bị khóa");
        }
        $userInfor = self::getUserById($account['MaNguoiDung']);
        $userInfor['TaiKhoan'] = $account;

        self::setSession($userInfor);
        if ($rememberMe) {
            self::rememberLogin($userInfor);
        }
        return new JsonResponse(200, "Đăng nhập thành công", $userInfor);
    }
    public static function recoverAuth()
    {
        if (isset($_SESSION['user'])) {
            return;
        }
        if (isset($_COOKIE['remember'])) {
            $cookieValue = $_COOKIE['remember'];
            $secretKey = $GLOBALS['config']['Auth']['secret'];
            $parts = explode('|', $cookieValue);
            if (count($parts) !== 2) {
                return;
            }
            $userId = $parts[0];

            $query = "SELECT * FROM NguoiDung WHERE MaNguoiDung = ?;";
            $user = Database::queryOne($query, [$userId]);

            if ($user) {
                $query = "SELECT MaTaiKhoan,MatKhau, TrangThai,LoaiTaiKhoan,MaNhomQuyen FROM TaiKhoan WHERE MaNguoiDung = ?;";
                $account = Database::queryOne($query, [$userId]);
                $trangThai = $account['TrangThai'];
                if ($trangThai == TrangThaiTaiKhoan::DangBiKhoa->value) {
                    self::logout();
                    return;
                }
                $user['TaiKhoan'] = $account;
            }
            $hash = $parts[1];
            $userPassword = $user['TaiKhoan']['MatKhau'];
            $rawHash = "$userId|$userPassword";
            $expectedHash = hash_hmac('sha256', $rawHash, $secretKey);
            if ($hash === $expectedHash) {
                if ($user) {
                    self::setSession($user);
                }
            } else {
                self::logout();
            }
        }
    }
}