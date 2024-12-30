<?php
use App\Core\Database\Database;
use App\Services\FileService;

require 'vendor/autoload.php';
require_once 'core/Preload.php';
$movieImgs = Database::query("SELECT HinhAnh FROM Phim", []);
$foodImgs = Database::query("SELECT HinhAnh FROM ThucPham", []);
$comboImgs = Database::query("SELECT HinhAnh FROM Combo", []);
$cinemaImgs = Database::query("SELECT HinhAnh FROM RapChieu", []);
$banners = array_map(function ($banner) {
    return $banner['image'];
}, $GLOBALS['config']['Banners']);
$imgs = array_merge($movieImgs, $foodImgs, $comboImgs, $cinemaImgs, $banners);
FileService::removeUnuseFile($imgs);