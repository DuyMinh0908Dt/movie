<?php

namespace App\Services;

use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Dtos\JsonResponse;

class FileService
{
    private static $UPLOAD_DIR = '/public/uploads/';
    private static function createUploadDir(): void
    {
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . self::$UPLOAD_DIR)) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . self::$UPLOAD_DIR, 0777, true);
        }
    }
    private static function isUsed(string $path, string $usedFile): bool
    {
        if ($path === $usedFile)
            return true;
        if (strlen($path) < strlen($usedFile))
            return false;
        return substr($path, -strlen($usedFile)) === $usedFile;

    }
    public static function removeUnuseFile(array $usedFiles): void
    {

        $uploadDir = getcwd() . self::$UPLOAD_DIR;
        $files = scandir($uploadDir);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $path = self::$UPLOAD_DIR . $file;
            $isUsed = false;
            foreach ($usedFiles as $usedFile) {
                if (self::isUsed($path, $usedFile)) {
                    $isUsed = true;
                    break;
                }
            }
            if (!$isUsed) {
                unlink($uploadDir . $file);
            }
        }
    }
    public static function upload(mixed $file): JsonResponse
    {
        self::createUploadDir();
        $name = $file["name"];
        $nameParts = explode(".", $name);
        $ext = end($nameParts);
        $uniquefileName = uniqid() . '.' . $ext;
        $path = self::$UPLOAD_DIR . $uniquefileName;
        if (move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $path)) {
            return JsonResponse::ok([
                'path' => $path
            ]);
        } else {
            return JsonResponse::error("Upload file thất bại");
        }
    }
    public static function delete(string $path): JsonResponse
    {
        $target_file = $_SERVER['DOCUMENT_ROOT'] . $path;
        if (file_exists($target_file)) {
            unlink($target_file);
            return JsonResponse::ok();
        } else {
            return JsonResponse::error("File không tồn tại");
        }
    }
}