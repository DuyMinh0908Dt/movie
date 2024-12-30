<?php

namespace App\Services;

use App\Core\Database\Database;
use App\Dtos\JsonDataErrorRespose;
use App\Dtos\JsonResponse;


class StatusService
{
    public static function getAllStatus(string $table)
    {
        $sql = "SELECT * FROM TrangThai WHERE ApDungChoBang = ?";
        $status = Database::query($sql, [$table]);
        return $status;
    }
    public static function getStatusByIds($ids)
    {
        $sql = "SELECT * FROM TrangThai WHERE MaTrangThai IN (" . implode(",", $ids) . ")";
        $status = Database::query($sql, []);
        return $status;
    }

}