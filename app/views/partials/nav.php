<?php
use App\Core\Request;

$webConfig = $GLOBALS['config']['Website'];
if (empty($webConfig['logo'])) {
    die("Error: Logo path not defined in configuration.");
}
?>

<div class="header ">
    <!-- <div class="header__logo-wrapper " onclick="window.location.href='/trang-chu'">
        <img class="" src="<?= $webConfig['logo'] ?>" alt="" />
    </div> -->
    
    <div class="header__logo-wrapper" onclick="window.location.href='/trang-chu'" style="margin-left: 20px;">
    <img class="header__logo" 
         src="<?= $webConfig['logo'] ?>" 
         alt="Logo <?= htmlspecialchars($webConfig['name']) ?>" 
         title="<?= htmlspecialchars($webConfig['name']) ?>" 
         onerror="this.onerror=null; this.src='/public/assets/img/default-logo.png';" 
         style="width: <?= $webConfig['logo_width'] ?? 235 ?>px; height: auto;" />
</div>

    <i class="icon-triangle fa-solid fa-caret-right"></i>
    <div class="container">
        <div class="header__wrapper row d-flex align-items-center">
            <div class="col-3 d-flex align-items-center">

            </div>
            <div class="col-9">
                <div class="d-flex justify-content-around align-items-center nav-tool-wrapper">
                    <form action="/trang-chu/tim-kiem" method="GET"
                        class="my-2 form-search form-search--focus d-my-mobile-none d-flex my-lg-0">
                        <input name="tu-khoa" e class="form-control search-input" type="search" x-init="
                             const queryParam = new URLSearchParams(window.location.search);
                                const keyword = queryParam.get('tu-khoa');
                                $el.value = keyword||'';
                            " placeholder="Nhập từ khoá" aria-label="Search">
                        <button class="my-2 btn search-btn my-sm-0" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                    <div class="nav-item dropdown nav-item-dropdown d-none d-my-ml-block">
                        <?php


                        if (Request::isAuthenicated()): ?>


                        <div class='relative'>
                            <div data-bs-toggle="dropdown" aria-expanded="false"
                                class='dropdown-toggle tw-flex tw-gap-x-1 tw-items-center tw-ml-8 tw-text-white tw-cursor-pointer'>
                                <span
                                    class="tw-inline-flex tw-items-center tw-justify-center tw-size-[35px] tw-text-sm tw-font-semibold tw-leading-none tw-rounded-full tw-bg-blue-100 tw-text-blue-800">
                                    <?php
                                        $splitedName = explode(' ', $_SESSION['user']['TenNguoiDung']);
                                        $firstChar = '';
                                        foreach ($splitedName as $name) {
                                            $firstChar .= $name[0];
                                        }
                                        $lastChar = substr($firstChar, -2);
                                        echo $lastChar;
                                        ?>
                                </span>
                                <span>
                                    <?= $_SESSION['user']['TenNguoiDung'] ?>
                                </span>

                            </div>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list "
                                x-placement="bottom-end"
                                style="position: absolute; transform: translate3d(4px, 62px, 0px); top: 10px; left: 0px; will-change: transform;">
                                <a class="dropdown-item !tw-flex tw-gap-x-2 !tw-items-center "
                                    href="/nguoi-dung/thong-tin"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    </svg>
                                    Thông tin
                                </a>
                                <?php if ($_SESSION['user']['TaiKhoan']['LoaiTaiKhoan'] == 1): ?>
                                <a class="dropdown-item !tw-flex tw-gap-x-2 !tw-items-center " href="/admin"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-dashboard">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M13.45 11.55l2.05 -2.05" />
                                        <path d="M6.4 20a9 9 0 1 1 11.2 0z" />
                                    </svg>Quản lý
                                </a>
                                <?php endif; ?>
                                <a class="dropdown-item !tw-flex tw-gap-x-2 !tw-items-center " href="/dang-xuat"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-logout">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                        <path d="M9 12h12l-3 -3" />
                                        <path d="M18 15l3 -3" />
                                    </svg> Đăng xuất</a>
                            </div>
                        </div>
                        <?php else: ?>

                        <a style="margin-right: 4px;" class="login-item btn-login btn" href="/dang-nhap">
                            <i class="fa-solid fa-right-to-bracket navbar-list__icon"></i>
                            <span class="ms-3 ">Đăng Nhập</span>
                        </a>
                        <?php endif; ?>
                    </div>


                    <label onclick="" for="checkShowMenu" class="menu-mobile-wrapper d-my-mobile-flex d-none">
                        <i class="fa-solid fa-bars"></i>
                    </label>

                    <input type="checkbox" id="checkShowMenu" />

                    <div class="nav-menu-mobile">
                        <form class="nav-menu-mobile__item" action="/trang-chu/tim-kiem" method="GET"
                            class="my-2 form-search form-search--focus d-my-mobile-none d-flex my-lg-0">
                            <input name="tu-khoa" e class="form-control search-input" type="search" x-init="
                             const queryParam = new URLSearchParams(window.location.search);
                                const keyword = queryParam.get('tu-khoa');
                                    $el.value = keyword||'';
                            " placeholder="Nhập từ khoá" aria-label="Search">
                            <button class="my-2 btn search-btn my-sm-0" type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </form>
                        <a class="nav-menu-mobile__item" href="#">
                            <i class="fa-solid fa-house navbar-list__icon"></i>
                            Trang chủ
                        </a>
                        <a class="nav-menu-mobile__item" href="#">
                            <i class="fa-regular fa-calendar-days nav-menu-mobile__icon"></i>
                            Lịch Chiếu
                        </a>
                        <a class="nav-menu-mobile__item" href="#">
                            <i class="fa-solid fa-clapperboard nav-menu-mobile__icon"></i>
                            Phim
                        </a>
                        <a class="nav-menu-mobile__item" href="#">
                            <i class="fa-solid fa-phone nav-menu-mobile__icon"></i>
                            Liên Hệ
                        </a>
                        <a class="nav-menu-mobile__item" href="#">
                            <i class="fa-solid fa-truck nav-menu-mobile__icon"></i>
                            Dịch Vụ
                        </a>
                    </div>

                    <div class="login-wrapper d-my-ml-none">
                        <?php


                        if (Request::isAuthenicated()): ?>

                        <div class='relative'>
                            <div data-bs-toggle="dropdown" aria-expanded="false"
                                class='dropdown-toggle tw-flex tw-gap-x-1 tw-items-center tw-ml-8 tw-text-white tw-cursor-pointer'>
                                <span
                                    class="tw-inline-flex tw-items-center tw-justify-center tw-size-[35px] tw-text-sm tw-font-semibold tw-leading-none tw-rounded-full tw-bg-blue-100 tw-text-blue-800">
                                    <?php
                                        $splitedName = explode(' ', $_SESSION['user']['TenNguoiDung']);
                                        $firstChar = '';
                                        foreach ($splitedName as $name) {
                                            $firstChar .= $name[0];
                                        }
                                        $lastChar = substr($firstChar, -2);
                                        echo $lastChar;
                                        ?>
                                </span>
                                <span>
                                    <?= $_SESSION['user']['TenNguoiDung'] ?>
                                </span>

                            </div>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list "
                                x-placement="bottom-end"
                                style="position: absolute; transform: translate3d(4px, 62px, 0px); top: 10px; left: 0px; will-change: transform;">
                                <a class="dropdown-item !tw-flex tw-gap-x-2 !tw-items-center "
                                    href="/nguoi-dung/thong-tin"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    </svg>
                                    Thông tin
                                </a>
                                <?php if ($_SESSION['user']['TaiKhoan']['LoaiTaiKhoan'] == 1): ?>
                                <a class="dropdown-item !tw-flex tw-gap-x-2 !tw-items-center " href="/admin"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-dashboard">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M13.45 11.55l2.05 -2.05" />
                                        <path d="M6.4 20a9 9 0 1 1 11.2 0z" />
                                    </svg>Quản lý
                                </a>
                                <?php endif; ?>
                                <a class="dropdown-item !tw-flex tw-gap-x-2 !tw-items-center " href="/dang-xuat"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-logout">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                        <path d="M9 12h12l-3 -3" />
                                        <path d="M18 15l3 -3" />
                                    </svg> Đăng
                                    xuất</a>
                            </div>
                        </div>

                        <?php else: ?>
                        <a style="margin-right: 4px;" class="login-item btn-login btn" href="/dang-nhap">
                            <i class="fa-solid fa-right-to-bracket navbar-list__icon"></i>
                            <span class="ms-3 ">Đăng Nhập</span>
                        </a>

                        <a class="login-item btn-login btn" href="/dang-nhap?action=dang-ky">
                            <i class="fa-solid navbar-list__icon fa-user-plus"></i>
                            <span class="ms-3 ">Đăng Ký</span>
                        </a>
                        <?php endif; ?>
                    </div>

                </div>


            </div>


            <!-- <nav class="navbar">
            <div class="col-3"></div>
            <div class="col-9 d-flex justify-content-center">
              
              
            </div>
          </nav>

          <nav></nav>
          <div class="header__wrap-content">
            <ul id="nav"></ul>
          </div>
        </div>
      </div> -->

        </div>

    </div>
    <div class="row nav-row">
        <div class="col-3"></div>
        <div class="col-9 d-flex align-items-center justify-content-center">
            <nav class="navbar-list d-mobile-none">
                <a class="navbar-item d-my-lg-none" href="tim-kiem-nang-cao">
                    <i class="fa-solid fa-search navbar-list__icon"></i>
                    Tìm kiếm nâng cao
                </a>
                <a class="navbar-item d-my-lg-none" href="#">
                    <i class="fa-solid fa-phone navbar-list__icon"></i>
                    Liên Hệ
                </a>
                <a class="navbar-item d-my-mobile-none" href="#">
                    <i class="fa-regular fa-calendar-days navbar-list__icon"></i>
                    Lịch Chiếu
                </a>
                <a class="navbar-item d-my-md-none" href="#">
                    <i class="fa-solid fa-clapperboard navbar-list__icon"></i>
                    Phim
                </a>
                <a class="navbar-item d-my-ml-none" href="#">
                    <i class="fa-solid fa-star navbar-list__icon"></i>
                    Rạp Và Giá
                </a>
                <a class="navbar-item d-my-mobile-none" href="#">
                    <i class="fa-solid fa-circle-check navbar-list__icon"></i>Ưu Đãi
                </a>

                <div class="nav-item d-my-mobile-none dropdown nav-item-dropdown d-my-lg-block">
                    <a class="navbar-item dropdown-toggle ms-4" href="#" role="button" id="dropdownMenuLink"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Khác
                    </a>

                    <ul class="dropdown-menu d-my-mobile-none bg-menu"" aria-labelledby="">
              <a class=" dropdown-item menu-item" href="#">
                        <i class="fa-solid fa-truck navbar-list__icon"></i>
                        Dịch Vụ
                        </a>
                        <!-- <div class="dropdown-divider"></div> -->
                        <a class="dropdown-item menu-item" href="#">
                            <i class="fa-solid fa-phone navbar-list__icon"></i>
                            Liên Hệ
                        </a>
                        <a class="dropdown-item menu-item d-my-ml-block d-none" href="#">
                            <i class="fa-solid fa-star navbar-list__icon"></i>
                            Rạp Và Giá
                        </a>
                        <a class="dropdown-item menu-item d-my-md-block d-none" href="#">
                            <i class="fa-solid fa-clapperboard navbar-list__icon"></i>
                            Phim
                        </a>
                    </ul>
                </div>

            </nav>
        </div>
    </div>
</div>