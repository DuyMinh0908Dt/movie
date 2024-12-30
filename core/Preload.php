<?php

use App\Core\Database\Database;
use App\Core\Logger;
use App\Services\SeatService;

$GLOBALS['config'] = require 'config.php';

Database::init_db();
Router::load_from_class(HomeController::class);
Router::load_from_class(PayController::class);
Router::load_from_class(UserController::class);
Router::load_from_class(DashboardController::class);
Router::load_from_class(CheckoutController::class);
Router::load_from_class(RoomController::class);
Router::load_from_class(CinemaController::class);
Router::load_from_class(SeatController::class);
Router::load_from_class(StatusController::class);
Router::load_from_class(MovieController::class);
Router::load_from_class(PromotionController::class);
Router::load_from_class(RoleController::class);
Router::load_from_class(TicketTypeController::class);
Router::load_from_class(BillController::class);
Router::load_from_class(AccountController::class);
Router::load_from_class(ShowController::class);
Router::load_from_class(FileController::class);
Router::load_from_class(ProductController::class);
Router::load_from_class(ComboController::class);
Router::load_from_class(AdminController::class);
Router::load_from_class(UserManagerController::class);
Router::load_from_class(FilmCategoryController::class);
Router::load_from_class(OrderController::class);
Router::load_from_class(StatisticController::class);
Router::load_from_class(DiscountController::class);
Router::build();
function exception_handler(Throwable $exception)
{
    error_log($exception->getMessage());
    error_log($exception->getTraceAsString());
    Logger::error($exception->getMessage());
    Logger::error($exception->getTraceAsString());

}

set_exception_handler('exception_handler');