<?php
use Core\Attributes\Route;

class PageErrorController {
    #[Route("/page-error/404", "GET")]
    public function abc() {
        return view("/app/views/loi/404");
    }
    #[Route("/page-error/403", "GET")]
    public function abd() {
        return view("/app/views/loi/403");
    }
}