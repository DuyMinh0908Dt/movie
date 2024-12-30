<?php

namespace App\Services;

use App\Core\Database\Database;
use App\Dtos\JsonDataErrorRespose;
use App\Dtos\JsonResponse;
use App\Dtos\TrangThaiKhuyenMai;
use App\Dtos\TrangThaiNhomQuyen;

class RoleService
{

    public static function getAllRole($query = [])
    {
        $query = "SELECT * FROM NhomQuyen where 1 = 1";
        $trangThai = getArrayValueSafe($query, 'trang-thai', [TrangThaiNhomQuyen::Hien->value]);
        $inStatus = implode(",", $trangThai);
        $query .= " AND TrangThai IN ($inStatus)";
        $roles = Database::query($query, []);
        return $roles;
    }

    public static function getRoleById($id)
    {
        $role = Database::queryOne("SELECT * FROM NhomQuyen WHERE MaNhomQuyen = ?", [$id]);
        $role['Quyen'] = PermissionService::getPermissionByRole($id) ?? [];
        return $role;
    }
    public static function addRole($role)
    {
        $permissionIds = $role['Quyen'];
        $id = Database::insert("NhomQuyen", [
            'TenNhomQuyen' => $role['TenNhomQuyen'],
            'MoTa' => $role['MoTa'],
            'TrangThai' => TrangThaiNhomQuyen::Hien->value
        ]);
        self::addListPermissionToRole($id, $permissionIds);
        return $id;
    }
    public static function addListPermissionToRole($roleId, $permissionIds)
    {
        foreach ($permissionIds as $permissionId) {
            Database::insert("CT_NhomQuyen_Quyen", [
                'MaNhomQuyen' => $roleId,
                'MaQuyen' => $permissionId
            ]);
        }
    }
    public static function updateRole($role)
    {
        $permissionIds = $role['Quyen'];
        Database::update(
            "NhomQuyen",
            [
                'TenNhomQuyen' => $role['TenNhomQuyen'],
                'MoTa' => $role['MoTa']
            ],
            "MaNhomQuyen = " . $role["MaNhomQuyen"]
        );
        Database::execute("DELETE FROM CT_NhomQuyen_Quyen WHERE MaNhomQuyen = ?", [$role["MaNhomQuyen"]]);
        self::addListPermissionToRole($role["MaNhomQuyen"], $permissionIds);
    }
    public static function deleteRole($id)
    {
        Database::execute("DELETE FROM NhomQuyen WHERE MaNhomQuyen = ?", [$id]);
    }
    public static function toggleStatusRole($id)
    {
        $role = Database::queryOne("SELECT * FROM NhomQuyen WHERE MaNhomQuyen = ?", [$id]);
        $newStatus = $role['TrangThai'] == TrangThaiNhomQuyen::Hien->value ? TrangThaiNhomQuyen::An->value : TrangThaiNhomQuyen::Hien->value;
        Database::update("NhomQuyen", ['TrangThai' => $newStatus], "MaNhomQuyen = $id");
        return $newStatus;
    }

}