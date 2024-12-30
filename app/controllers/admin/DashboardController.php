<?php

use Core\Attributes\Controller;
use Core\Attributes\Route;

#[Controller(path: "/admin")]
class DashboardController
{
    #[Route("", "GET")]
    public function index()
    {
        echo "admin", "GET";
    }
    #[Route("/{id1}/check/{id2}", "GET")]
    public function product($params)
    {
        print_r($params);
        echo "/{id1}/check/{id2}", "GET";
    }
    #[Route("/{id1}", "GET")]
    public function edit($params)
    {
        print_r($params);
        echo "/{id1}", "GET";
    }

    #[Route("/website-settings/config", "POST")]
    public function config()
    {
//         <?php
// return [
//     "Auth" => [
//         "secret" => %AUTH_SECRET%,
//         "remember_time_in_days" => %REMEMBER_TIME_IN_DAYS%,
//     ],
//     "Website" => [
//         "name" => %WEBSITE_NAME%,
//         "logo" => %WEBSITE_LOGO%,
//         "phone" => %WEBSITE_PHONE%,
//         "email" => %WEBSITE_EMAIL%,
//         "description" => %WEBSITE_DESCRIPTION%,
//         "hold_time" => %TICKET_HOLD_TIME%,
//     ],
// ];
        echo "/website-settings/config", "POST";
    }
}