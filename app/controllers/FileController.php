<?php
use App\Dtos\JsonResponse;
use App\Services\FileService;
use Core\Attributes\Route;

class FileController
{
    #[Route("/api/file/upload", "POST")]
    public static function upload()
    {
        return json(
            FileService::upload($_FILES["file"])
        );
    }
}