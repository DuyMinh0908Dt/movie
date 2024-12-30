<?php
use App\Core\Database\Database;

require 'vendor/autoload.php';
require_once 'core/Preload.php';
Database::execute('CALL CapNhatTrangThaiPhim()', []);