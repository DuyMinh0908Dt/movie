<?php

use App\Core\Database\QueryBuilder;
use App\Core\Request;
use App\Services\AccountType;
use App\Services\CategoryService;
use App\Services\CinemaService;
use App\Services\ComboService;
use App\Services\PhimService;
use App\Services\SeatTypeService;
use App\Services\ShowService;
use App\Services\TicketService;
use App\Services\UserService;
use Core\Attributes\Route;


class HomeController
{
    #[Route("/", "GET")]
    public static function index()
    {
        $phims = PhimService::getPhimDangChieu();
        $commingMovies = PhimService::getPhimSapChieu();
        // dd($commingMovies);
        return view("home", ["phims" => $phims, "commingMovies" => $commingMovies]);
    }
    #[Route("/trang-chu", "GET")]
    public static function home()
    {
        $phims = PhimService::getPhimDangChieu();
        $commingMovies = PhimService::getPhimSapChieu();
        return view("home", ["phims" => $phims, "commingMovies" => $commingMovies]);
    }
    #[Route("/dang-nhap", "GET")]
    public static function login()
    {
        if (Request::isAuthenicated()) {

            return redirect("trang-chu");
        }
        return view("login");
    }

    #[Route("/tim-kiem-nang-cao", "GET")]
    public static function timKiemNangCao()
    {

        $cinemas = CinemaService::getAllCinemas();
        $categories = CategoryService::getAllCategories();
        return view("tim-kiem-nang-cao", ["cinemas" => $cinemas, "categories" => $categories]);
    }

    #[Route("/tim-kiem-nang-cao", "POST")]
    public static function ketQuaTimKiemNangCao()
    {
        $data = request_body();
        $cinemas = PhimService::searchImage($data);
        return view("tim-kiem-nang-cao", ["cinemas" => $cinemas]);
    }

    #[Route("/dang-nhap", "POST")]
    public static function loginPost()
    {
        $returnUrl = getArrayValueSafe($_GET, 'returnUrl', null);

        $username = $_POST['username'];
        $password = $_POST['password'];

        $loginResult = UserService::login($username, $password, getArrayValueSafe($_POST, 'remember', false));
        if ($loginResult->isSuccessful()) {
            if ($loginResult->data['TaiKhoan']['LoaiTaiKhoan'] == AccountType::Customer->value) {
                return redirect($returnUrl ?? "trang-chu?isloginSuccess=true");
            } else {
                return redirect("admin");
            }
        }
        return view("login", ["error" => $loginResult->message]);
    }
    #[Route("/phim/{id}", "GET")]
    public static function chiTietPhim($id)
    {
        $id = intval($id);
        $phim = PhimService::getPhimById($id);
        $categories = CategoryService::getCategoriesByMovieId($id);
        $upcomingShows = ShowService::getUpcomingShowsOfMovie($id);
        $ticketTypes = TicketService::getTicketTypes();
        $seatTypes = SeatTypeService::getAllSeatType();
        $foods = ComboService::getAllFoodnDrink();
        $combos = ComboService::getAllCombo();
        return view("chi-tiet", [
            "phim" => $phim,
            "categories" => $categories,
            "upcomingShows" => $upcomingShows,
            "ticketTypes" => $ticketTypes,
            "seatTypes" => $seatTypes,
            "foods" => $foods,
            "combos" => $combos
        ]);
    }

    #[Route("/dang-ky", "POST")]
    public static function register()
    {
        $body = request_body();

        return json(UserService::register($body));
    }
    #[Route("/trang-chu/lich-chieu", "GET")]
    public static function lichChieu()
    {
        return view("lich-chieu");
    }
    #[Route("/trang-chu/tim-kiem", "GET")]
    public static function search()
    {
        $cinemas = CinemaService::getAllCinemas();
        $categories = CategoryService::getAllCategories();
        return view("tim-kiem", ["cinemas" => $cinemas, "categories" => $categories]);
    }
    #[Route("/dang-xuat", "GET")]
    public static function logout()
    {
        UserService::logout();
        return redirect("trang-chu");
    }
    #[Route("/test", "GET")]
    public static function test()
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder->select(
            [
                'PhongChieu.MaPhongChieu' => 'PhongChieu.MaPhongChieu',
                'PhongChieu.TenPhongChieu' => 'PhongChieu.TenPhongChieu',
                'PhongChieu.ManHinh' => 'PhongChieu.ManHinh',
                'PhongChieu.ChieuDai' => 'PhongChieu.ChieuDai',
                'PhongChieu.ChieuRong' => 'PhongChieu.ChieuRong',
                'RapChieu.MaRapChieu' => 'RapChieu.MaRapChieu',
                'RapChieu.TenRapChieu' => 'RapChieu.TenRapChieu',
                'RapChieu.DiaChi' => 'RapChieu.DiaChi',
                'RapChieu.TrangThai' => 'RapChieu.TrangThai'
            ]
        )->from('PhongChieu')
            ->join('RapChieu', 'PhongChieu.MaRapChieu = RapChieu.MaRapChieu');
        $data = $queryBuilder->get();
        return json($queryBuilder->parseMany($data, [
            'root' => 'RapChieu',
            'embed' => ['PhongChieu'],
            "groupBy" => "MaRapChieu"
        ]));
    }

}