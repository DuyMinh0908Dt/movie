<?php
title("Thống kê chi tiết phim");
require ("app/views/admin/header.php");
?>
<link rel="stylesheet" href="/public/thong-ke/detail-movie/home.css">
<link rel="stylesheet" href="/public/thong-ke/detail-movie/detailsAnalyticsMovies.css">
<div style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="wrapper p-5">
    <div class="detail-analytics-movies container-fluid bg-white border_radius-16">
        <!-- poster và tên phim -->
        <div class="row">
            <div class="col-12 p-0">
                <div class="container-fluid d-flex flex-grow-1 bg_header p-4">
                    <div>
                        <img src="https://cinestar.com.vn/pictures/Cinestar/02-2024/thanh-guom-diet-quy.jpg" alt
                            width="200">
                    </div>
                    <div class="p-4 ">
                        <h3 class="fw-medium fs-4">THANH GƯƠM DIỆT
                            QUỶ: PHÉP MÀU TÌNH THÂN, CHO ĐẾN CHUYẾN
                            ĐẶC HUẤN CỦA ĐẠI TRỤ</h3>
                        <div>
                            <span class="pe-3 border-end">121
                                phút</span>
                            <span class="ps-2">05-04-2023</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- hết poster và tên phim -->

        <!-- các thông số của phim -->
        <div class="movie-analytics row my-4 px-4">
            <div class="col-4 ps-0">
                <div class="container shadow border_radius-16 p-4">
                    <h6 class="mb-4">Doanh thu</h6>
                    <h4>704.344.245.000</h4>
                </div>
            </div>

            <div class="col-4 ">
                <div class="container shadow border_radius-16 p-4">
                    <h6 class="mb-4">Suất chiếu</h6>
                    <h4>7124</h4>
                </div>
            </div>

            <div class="col-4 pe-0">
                <div class="container shadow border_radius-16 p-4">
                    <h6 class="mb-4">Số vé bán ra</h6>
                    <h4>1.345</h4>
                </div>
            </div>
        </div>
        <!-- hết các thông số của phim -->

        <!-- biểu đồ doanh thu tại các rạp -->
        <div class="chart row">
            <div class="container px-4">
                <h4 class="fw-semibold m-0">Doanh thu tại các
                    rạp</h4>
                <div id="chart"></div>
            </div>
        </div>
        <!-- hết biểu đồ doanh thu tại các rạp -->

        <!-- thông tin phim -->
        <div class="movie-info row">
            <div class="container px-4 pb-4">
                <h4 class="fw-semibold mb-2">Thông tin phim</h4>
                <div class="fs-6 fw-normal">
                    <span><strong class="fw-semibold">Đạo
                            diễn:</strong> Haruo
                        Sotozaki</span> <br>
                    <span><strong class="fw-semibold">Thể
                            loại:</strong> Gia đình, Tâm
                        lý</span><br>
                    <span><strong class="fw-semibold">Khởi
                            chiếu:</strong>
                        23-02-2023</span><br>
                    <span><strong class="fw-semibold">Thời
                            lượng:</strong> 123 phút</span><br>
                    <span><strong class="fw-semibold">Ngôn
                            ngữ:</strong> Tiếng Việt - Phụ đề
                        tiếng
                        Anh</span><br>
                    <span><strong class="fw-semibold">Rated:</strong>
                        K</span><br>
                    <span><strong class="fw-semibold">Mô tả:
                        </strong> Phim lấy bối cảnh ở làng thợ
                        rèn, kể về hồi kết của trận ác chiến
                        giữa Tanjiro với Thượng Huyền Tứ
                        Hantengu, và việc Nezuko khắc chế được
                        mặt trời. Thêm vào đó, tập đầu tiên của
                        phần "Đại Trụ Đặc Huấn", mở màn cho khóa
                        đặc huấn của các Đại Trụ cho cuộc quyết
                        chiến với Kibutsuji Muzan</span>
                </div>
            </div>
        </div>
        <!-- hết thông tin phim -->
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<?php
script("/public/thong-ke/detail-movie/detailsAnalyticsMovies.js");
require ("app/views/admin/footer.php");
?>