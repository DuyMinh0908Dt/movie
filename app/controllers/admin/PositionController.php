<?php
use Core\Attributes\Route;

class PositionController {
    #[Route("/api/khuyen-mai", "GET")]
    public function abc () { 
        return view("admin/khuyen-mai/index");
    }
}