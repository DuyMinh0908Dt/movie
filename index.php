<?php


use App\Services\UserService;

require 'vendor/autoload.php';

require_once 'core/Preload.php';
session_start();


$GLOBALS['config'] = require 'config.php';

UserService::recoverAuth();


Router::dispatch();