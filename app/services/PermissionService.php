<?php

namespace App\Services;

use App\Core\Database\Database;
use App\Dtos\JsonDataErrorRespose;
use App\Dtos\JsonResponse;


// enum Permission: string
// {
//     case CREATE_COMBO = "create:combo";
//     case READ_COMBO = "read:combo";
//     case UPDATE_COMBO = "update:combo";
//     case DELETE_COMBO = "delete:combo";
//     case CREATE_GHE = "create:ghe";
//     case READ_GHE = "read:ghe";
//     case UPDATE_GHE = "update:ghe";
//     case DELETE_GHE = "delete:ghe";
//     case CREATE_HOANDON = "create:hoandon";
//     case READ_HOANDON = "read:hoandon";
//     case UPDATE_HOANDON = "update:hoandon";
//     case DELETE_HOANDON = "delete:hoandon";
//     case CREATE_KHUYENMAI = "create:khuyenmai";
//     case READ_KHUYENMAI = "read:khuyenmai";
//     case UPDATE_KHUYENMAI = "update:khuyenmai";
//     case DELETE_KHUYENMAI = "delete:khuyenmai";
//     case CREATE_LOAIGHE = "create:loaighe";
//     case READ_LOAIGHE = "read:loaighe";
//     case UPDATE_LOAIGHE = "update:loaighe";
//     case DELETE_LOAIGHE = "delete:loaighe";
//     case CREATE_LOAIVE = "create:loaive";
//     case READ_LOAIVE = "read:loaive";
//     case UPDATE_LOAIVE = "update:loaive";
//     case DELETE_LOAIVE = "delete:loaive";
//     case CREATE_NGUOIDUNG = "create:nguoidung";
//     case READ_NGUOIDUNG = "read:nguoidung";
//     case UPDATE_NGUOIDUNG = "update:nguoidung";
//     case DELETE_NGUOIDUNG = "delete:nguoidung";
//     case CREATE_NHOMQUYEN = "create:nhomquyen";
//     case READ_NHOMQUYEN = "read:nhomquyen";
//     case UPDATE_NHOMQUYEN = "update:nhomquyen";
//     case DELETE_NHOMQUYEN = "delete:nhomquyen";
//     case CREATE_PHIM = "create:phim";
//     case READ_PHIM = "read:phim";
//     case UPDATE_PHIM = "update:phim";
//     case DELETE_PHIM = "delete:phim";
//     case CREATE_PHONGCHIEU = "create:phongchieu";
//     case READ_PHONGCHIEU = "read:phongchieu";
//     case UPDATE_PHONGCHIEU = "update:phongchieu";
//     case DELETE_PHONGCHIEU = "delete:phongchieu";
//     case CREATE_QUYEN = "create:quyen";
//     case READ_QUYEN = "read:quyen";
//     case UPDATE_QUYEN = "update:quyen";
//     case DELETE_QUYEN = "delete:quyen";
//     case CREATE_RAPCHIEU = "create:rapchieu";
//     case READ_RAPCHIEU = "read:rapchieu";
//     case UPDATE_RAPCHIEU = "update:rapchieu";
//     case DELETE_RAPCHIEU = "delete:rapchieu";
//     case CREATE_SUATCHIEU = "create:suatchieu";
//     case READ_SUATCHIEU = "read:suatchieu";
//     case UPDATE_SUATCHIEU = "update:suatchieu";
//     case DELETE_SUATCHIEU = "delete:suatchieu";
//     case CREATE_TAIKHOAN = "create:taikhoan";
//     case READ_TAIKHOAN = "read:taikhoan";
//     case UPDATE_TAIKHOAN = "update:taikhoan";
//     case DELETE_TAIKHOAN = "delete:taikhoan";
//     case CREATE_THELOAI = "create:theloai";
//     case READ_THELOAI = "read:theloai";
//     case UPDATE_THELOAI = "update:theloai";
//     case DELETE_THELOAI = "delete:theloai";
//     case CREATE_THUCPHAM = "create:thucpham";
//     case READ_THUCPHAM = "read:thucpham";
//     case UPDATE_THUCPHAM = "update:thucpham";
//     case DELETE_THUCPHAM = "delete:thucpham";
//     case CREATE_VE = "create:ve";
//     case READ_VE = "read:ve";
//     case UPDATE_VE = "update:ve";
//     case DELETE_VE = "delete:ve";
// }


class PermissionService
{
    private static $RESOURCE = [
        "combo" => "Combo",
        "ghe" => "Ghế",
        "hoandon" => "Hóa đơn",
        "khuyenmai" => "Khuyến mãi",
        "loaighe" => "Loại ghế",
        "loaive" => "Loại vé",
        "nguoidung" => "Người dùng",
        "nhomquyen" => "Nhóm quyền",
        "phim" => "Phim",
        "phongchieu" => "Phòng chiếu",
        "quyen" => "Quyền",
        "rapchieu" => "Rạp chiếu",
        "suatchieu" => "Suất chiếu",
        "taikhoan" => "Tài khoản",
        "theloai" => "Thể loại",
        "thucpham" => "Thực phẩm",
        "ve" => "Vé"
    ];
    private static $ACTION = [
        "create" => "Tạo",
        "read" => "Xem",
        "update" => "Sửa",
        "delete" => "Xóa"
    ];
    public static function getAllPermissions()
    {
        $permissions = Database::findAll("Quyen");
        $grouped = [];
        foreach ($permissions as $permission) {
            $split = explode(":", $permission['TenQuyen']);
            if (count($split) != 2) {
                $grouped[$permission['TenQuyen']] = [
                    "permission" => $permission['TenQuyen'],
                    "description" => $permission['MoTa'],
                    "MaQuyen" => $permission['MaQuyen'],
                ];
                continue;
            }
            $resource = $split[1];
            $action = $split[0];
            if (!array_key_exists($resource, $grouped)) {
                $grouped[$resource] = [
                    "resource" => $resource,
                    "resource_name" => self::$RESOURCE[$resource],
                    "actions" => []
                ];
            }
            $grouped[$resource]["actions"][] = [
                "action" => $action,
                "resource" => $resource,
                "MaQuyen" => $permission['MaQuyen'],
                "TenQuyen" => $permission['TenQuyen'],
                "TrangThai" => $permission['TrangThai'],
                "MoTa" => $permission['MoTa'],
                "action_name" => self::$ACTION[$action]
            ];
        }
        return $grouped;
    }
    public static function getPermissionByRole($roleId)
    {
        $permissions = Database::query("SELECT Quyen.MaQuyen, Quyen.TenQuyen
         FROM CT_NhomQuyen_Quyen JOIN Quyen ON CT_NhomQuyen_Quyen.MaQuyen = Quyen.MaQuyen WHERE MaNhomQuyen = ?", [$roleId]);
        return $permissions;
    }
}