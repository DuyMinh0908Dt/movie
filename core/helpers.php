<?php

use App\Core\Database\Database;
use App\Core\Logger;
use App\Dtos\JsonResponse;

global $scripts;
global $inlineScripts;
$inlineScripts = [];
$scripts = [];
global $title;
$title = "";

function title(string $value)
{
    global $title;
    $title = $value;
}

function script(string $src)
{
    global $scripts;
    $scripts[] = $src;
}
function scripts(array $list)
{
    foreach ($list as $src) {
        script($src);
    }
}

function inlineScript(string $script)
{
    global $inlineScripts;
    $inlineScripts[] = $script;
}

/**
 *
 * @param  string $name
 * @param  array  $data
 */


function view($name, $data = [])
{
    extract($data);
    $name = str_replace('.', '/', $name);


    require "app/views/{$name}.view.php";
    die();
}
function ajax($name, $data = [])
{
    extract($data);
    $name = str_replace('.', '/', $name);
    return require "app/views/{$name}.ajax.php";
}
/**
 *
 * @param  string $path
 */
function redirect($path)
{
    header("Location: /{$path}");
    die();
}
use Hidehalo\Nanoid\Client;
use Hidehalo\Nanoid\GeneratorInterface;

function uid()
{
    $client = new Client();
    return $client->generateId($size = 21);

}
function isNullOrEmptyString($str)
{
    return (!isset($str) || trim($str) === '');
}
function ifNullOrEmptyString($str, $default)
{
    return isNullOrEmptyString($str) ? $default : $str;
}

function isNullOrEmptyArray($arr)
{
    return (!isset($arr) || count($arr) === 0);
}
function getArrayValueSafe($arr, $key, $default = null)
{
    // esce]
    $val = isset($arr[$key]) ? $arr[$key] : $default;
    return is_string($val) ? Database::real_escape_string($val) : $val;
}
function execPostRequest($url, array $data)
{
    $ch = curl_init($url);
    /*curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        )
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    //execute post
    $result = curl_exec($ch);
    //close connection
    curl_close($ch);
    return $result;*/


}


function json($data, $status = 200)
{
    header('Content-Type: application/json');
    if ($data instanceof JsonResponse) {
        $status = $data->status;
        http_response_code($status);
        echo json_encode($data);
        die($status);
    }
    http_response_code($status);
    if (is_object($data)) {
        $data = (array) $data;
    }
    $res = JsonResponse::ok($data);
    $res->status = $status;
    echo json_encode($res);
    die($status);
}

function request_body()
{
    //  check if content type is json
    if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
        return json(new JsonResponse(400, "Invalid content type"), 400);
    }
    return json_decode(file_get_contents('php://input'), true);
}

function isAjaxRequest()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}


function onUnauthorized()
{
    if (isAjaxRequest()) {
        return json(new JsonResponse(401, "Unauthorized"), 401);
    }
    // http_response_code(401);
    return redirect('dang-nhap' . '?returnUrl=' . $_SERVER['REQUEST_URI']);
}
function onForbidden()
{
    if (isAjaxRequest()) {
        return json(new JsonResponse(403, "
        Bạn không có quyền thực hiện hành động này
        "), 403);
    }
    // set 403 status code
    http_response_code(403);
    return view('404');
}

function needLogin()
{
    if (!isset($_SESSION['user'])) {
        return onUnauthorized();
    }
}
function needNotEmployee()
{
    if (!isset($_SESSION['user'])) {
        return;
    }
    $user = $_SESSION['user'];

    if ($user['TaiKhoan']['LoaiTaiKhoan'] == 1) {
        return onForbidden();
    }

}
function needEmployee()
{
    needLogin();
    if ($_SESSION['user']['TaiKhoan']['LoaiTaiKhoan'] != 1) {
        return onForbidden();
    }
}

function needAnyPermissionOrDie($permissions)
{
    needEmployee();
    $userPermissions = $_SESSION['user']['permissions'];
    $isHasPermission = false;
    foreach ($permissions as $permission) {
        foreach ($userPermissions as $userPermission) {
            if ($userPermission == $permission) {
                $isHasPermission = true;
                break;
            }
        }
    }
    if (!$isHasPermission) {
        return onForbidden();
    }
}