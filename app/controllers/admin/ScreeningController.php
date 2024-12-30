<?php

use Core\Attributes\Route;

class ScreeningController {
    #[Route(path:'/api/suat-chieu', method:'GET')]
    public static function abc() {
        return view('admin/suat-chieu/index');
    }
}