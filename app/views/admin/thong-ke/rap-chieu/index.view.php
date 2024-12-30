<?php
title("Thống kê theo rạp chiếu");
require ('app/views/admin/header.php');
?>
<link rel="stylesheet" href="/public/thong-ke/cinema/analyticsCinema.css">
<div x-data="cinema_statistical" style="
          flex-grow: 1;
          flex-shrink: 1;
          overflow-y: auto;
          max-height: 100vh;
        " class="p-5 wrapper">
    <div class="p-0 analytics-cinema container-fluid">
        <div class="p-3 mx-auto mb-4 bg-white shadow row border_radius-16">
            <form>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Rạp chiếu</label>
                            <div class="mb-3 input-group">
                                <select id="my_cinema" x-model="query['rap-chieu']" class="form-select"
                                    id="inputGroupSelect01">
                                    <option selected value="">Tất cả rạp chiếu</option>
                                    <?php foreach ($cinemas as $cinema): ?>
                                                <option value="<?= $cinema['MaRapChieu'] ?>"><?= $cinema['TenRapChieu'] ?></option>

                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="mb-3">
                            <label for="ngayBatDau" class="form-label">Từ ngày</label>
                            <input x-model="query['tu-ngay']" type="date" class="form-control" id="ngayBatDau"
                                aria-describedby="emailHelp" />
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="mb-3">
                            <label for="ngayKetThuc" class="form-label">Đến ngày</label>
                            <input x-model="query['den-ngay']" type="date" class="form-control" id="ngayKetThuc"
                                aria-describedby="emailHelp" />
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Thống kê theo dạng</label>
                            <div class="mb-3 input-group">
                                <select id="my_statistical_formats" x-model="query.type" class="form-select"
                                    id="inputGroupSelect01">
                                    <option selected value="chart">Biểu đồ</option>
                                    <option value="table">Bảng</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="mb-4 row">
            <div class="col-4">
                <div
                    class="py-4 text-center total_amount-container border_radius-16 tw-flex tw-items-center tw-justify-center tw-gap-y-2 tw-flex-col ">
                    <img alt="icon" src="https://minimals.cc/assets/icons/glass/ic_glass_bag.png" width="64px" />
                    <h3 class="fw-semibold" x-init="
                        const fetchTotalBillCount = async ()=> {
                            const { data } = await axios.get('/api/tong-quan/hoa-don/tong-tien', {
                                params: query
                            });
                            totalBillCount = data.data[0].total;
                            $el.innerHTML = toVnd(totalBillCount);
                        }
                        fetchTotalBillCount();
                        $watch('query', () => fetchTotalBillCount());
                    ">

                    </h3>
                    <h6 class="text-body-tertiary">Doanh thu </h6>
                </div>
            </div>

            <!-- 'totalRevenue' => $totalRevenue,
            'totalBillCount' => $totalBillCount,
            'totalCustomerCount' => $totalCustomerCount -->
            <div class="col-4">
                <div
                    class="py-4 text-center total_customers-container tw-flex-col border_radius-16 tw-flex tw-items-center tw-justify-center tw-gap-y-2">
                    <img alt="icon" src="https://minimals.cc/assets/icons/glass/ic_glass_users.png" width="64px" />
                    <h3 class="fw-semibold" x-init="
                        const fetchTotalCustomerCount = async ()=> {
                            const { data } = await axios.get('/api/tong-quan/khach-hang/count', {
                                params: query
                            });
                            totalCustomerCount = data.data[0].total;
                            $el.innerHTML = totalCustomerCount;
                        }
                        fetchTotalCustomerCount();
                        $watch('query', () => fetchTotalCustomerCount());
                    ">
                        <div class="tw-skeleton tw-h-4 tw-w-28"></div>
                    </h3>
                    <h6 class="text-body-tertiary">Lượt khách</h6>
                </div>
            </div>

            <div class="col-4">
                <div
                    class="py-4 text-center total_orders tw-flex-col border_radius-16 tw-flex tw-items-center tw-justify-center tw-gap-y-2">
                    <img alt="icon" src="https://minimals.cc/assets/icons/glass/ic_glass_buy.png" width="64px" />
                    <h3 class="fw-semibold" x-init="
                        const fetchTotalBillCount = async ()=> {
                            const { data } = await axios.get('/api/tong-quan/hoa-don', {
                                params: query
                            });
                            totalBillCount = data.data[0].total;
                            $el.innerHTML = totalBillCount;
                        }
                        fetchTotalBillCount();
                        $watch('query', () => fetchTotalBillCount());
                        ">
                        <div class="tw-skeleton tw-h-4 tw-w-28"></div>
                    </h3>
                    <h6 class="text-body-tertiary">Hóa đơn</h6>
                </div>
            </div>
        </div>
        <!-- hết các chỉ số tổng quát của rạp -->

        <!-- ======================Thống kê theo dạng biểu đồ============================= -->
        <!-- biểu đồ cột -->
        <template x-if="query.type === 'chart'">
            <div>
                <template x-if="query['rap-chieu']!=''">
                    <div id="column_chart" class="p-3 mx-auto mb-4 bg-white shadow row border_radius-16 ">
                        <div class="fw-semibold">Doanh thu theo thời gian</div>
                        <div id="columnChart"></div>
                    </div>
                </template>
                <!-- hết biểu đồ cột -->

                <!-- biểu đồ doanh thu tất cả các rạp -->
                <template x-if="query['rap-chieu']==''">
                    <div id="bar_chart" class="p-3 mx-auto mb-4 bg-white shadow row border_radius-16">
                        <div class="p-0">
                            <div class="d-flex flex-column">
                                <span class="fs-5 fw-semibold">Tổng doanh thu các rạp chiếu</span>
                                <!-- <span class="text-body-secondary" style="font-size: 0.875rem">(+45%) so với năm
                                    ngoái</span> -->
                            </div>

                            <!-- chứa biểu đồ cột -->
                            <div id="bar_chart">

                            </div>

                        </div>
                    </div>
                </template>
                <!-- hết biểu đồ doanh thu tất cả các rạp -->

                <div id="donut_chart_top_movies" class="row detail-chart_container">
                    <!-- biểu đồ tròn doanh số theo hàng hóa -->
                    <div class="col-6">
                        <div class="p-3 bg-white shadow border_radius-16" style="height: 100%">
                            <div class="mb-2">
                                <div class="fw-semibold">Doanh thu theo hàng hóa</div>
                                <div id="donutChart"></div>
                            </div>
                        </div>
                    </div>
                    <!-- hết biểu đồ tròn doanh số theo hàng hóa -->

                    <!-- top phim doanh thu cao -->
                    <div class="col-6">
                        <div class="p-3 bg-white shadow border_radius-16" style="height: 100%">
                            <div class="fw-semibold">Top phim có doanh thu cao</div>
                            <div class="mb-4 fw-semibold flex-nowrap">
                                <span>
                                    <span class="fs-4" x-text="toVnd(data.totalMovieRevenue)"></span>
                                </span>
                            </div>
                            <template x-for="item in data.movieRevenue" :key="item.MaPhim">
                                <div class="p-3 row">
                                    <div class="d-flex">
                                        <div class="me-auto">
                                            <div class="row">
                                                <div class="p-0 col-1">
                                                    <div class="text-center d-block">
                                                        <img :src="item.HinhAnh" class="img-fluid img-thumbnail" alt />
                                                    </div>
                                                </div>

                                                <div class="p-0 col-10 ps-2">
                                                    <div class="fw-semibold" x-text="item.TenPhim">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class>
                                            <div class="row">
                                                <p class="m-0 text-end" style="color: rgb(0, 167, 111)"
                                                    x-text="toVnd(item.total)">

                                                </p>
                                            </div>
                                            <div class="row">
                                                <p class="m-0 text-end"
                                                    x-init="console.log(Number(item.total), data.totalMovieRevenue)"
                                                    x-text="(Number(item.total) / data.totalMovieRevenue * 100).toFixed(1) + '%'">
                                                    15%</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </template>


                            <!-- hết danh sách phim -->
                        </div>
                    </div>
                    <!-- hết top phim doanh thu cao -->
                </div>
            </div>
        </template>
        <!-- =========================hết thống kê theo dạng biểu đồ========================= -->

        <!-- ========================thống kê theo dạng bảng======================== -->
        <!-- bảng của biểu đồ cột -->
        <template x-if="query.type === 'table'">
            <div>
                <div class="p-3 mx-auto mb-4 bg-white shadow row border_radius-16 tables">
                    <template x-if="query['rap-chieu']==''">
                        <div class="container">
                            <div class="mb-2 row">
                                <span class="fw-semibold">Doanh thu của các rạp</span>
                            </div>

                            <div class="row table_container">
                                <table class="table table-column-chart">
                                    <thead x-data="{
                                        currentSortDirection : 'asc',
                                        sortBy(getColumn) {
                                            if (this.currentSortDirection === 'asc') {
                                                this.currentSortDirection = 'desc';
                                                this.data.allRevenue = this.data.allRevenue.sort((a, b) => Number(getColumn(a)) > Number(getColumn(b)) ? 1 : -1);
                                            } else {
                                                this.currentSortDirection = 'asc';
                                                this.data.allRevenue = this.data.allRevenue.sort((a, b) => Number(getColumn(a)) < Number(getColumn(b)) ? 1 : -1);
                                            }
                                        }
                                    }" class="table-header">
                                        <tr>
                                            <th scope="col">Tên rạp</th>
                                            <th scope="col" x-on:click="sortBy(item => item.food?.totalAmount)">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-arrows-sort" width="16"
                                                    height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                                </svg>
                                                Số sản phẩm
                                            </th>
                                            <th scope="col" x-on:click="sortBy(item => item.food?.totalMoney)">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-arrows-sort" width="16"
                                                    height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                                </svg>
                                                Doanh thu sản phẩm
                                            </th>

                                            <th scope="col" x-on:click="sortBy(item => item.ticket?.total)">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-arrows-sort" width="16"
                                                    height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                                </svg>
                                                Số vé
                                            </th>
                                            <th scope="col" x-on:click="sortBy(item => item.ticket?.totalMoney)">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-arrows-sort" width="16"
                                                    height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                                </svg>
                                                Doanh thu phim
                                            </th>
                                            <th scope="col" x-on:click="sortBy(item => item.total)">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-arrows-sort" width="16"
                                                    height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                                </svg>
                                                Tổng doanh thu
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody x-init="
                                    fetchAllRevenueDetail();
                                    ">

                                        <template x-for="item in data.allRevenue" :key="item.MaRapChieu">
                                            <tr>
                                                <td scope="row" x-text="item.MaRapChieu+' - ' + item.TenRapChieu"></td>
                                                <td x-text="item.food?.totalAmount || 0"></td>
                                                <td x-text="toVnd(item.food?.totalMoney || 0)"></td>
                                                <td x-text="item.ticket?.total || 0"></td>
                                                <td x-text="toVnd(item.ticket?.totalMoney || 0)"></td>
                                                <td x-text="toVnd(item.total || 0)"></td>
                                            </tr>

                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                    <template x-if="query['rap-chieu']!=''">
                        <div class="container">
                            <div class="mb-2 row">
                                <span class="fw-semibold">Doanh thu theo thời gian</span>
                            </div>
                            <div class="row table_container">
                                <table class="table table-column-chart">
                                    <thead class="table-header" x-data="{
                                        currentSortDirection : 'asc',
                                        sortBy(getColumn) {
                                            if (this.currentSortDirection === 'asc') {
                                                this.currentSortDirection = 'desc';
                                                this.data.cinemaRevenue = this.data.cinemaRevenue.sort((a, b) => Number(getColumn(a)) > Number(getColumn(b)) ? 1 : -1);
                                            } else {
                                                this.currentSortDirection = 'asc';
                                                this.data.cinemaRevenue = this.data.cinemaRevenue.sort((a, b) => Number(getColumn(a)) < Number(getColumn(b)) ? 1 : -1);
                                            }
                                        }
                                    }">
                                        <tr>
                                            <th scope="col">Thời gian</th>
                                            <th scope="col" x-on:click="sortBy((item) => item.food?.totalMoney)">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-arrows-sort" width="16"
                                                    height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                                </svg>
                                                Số lượng sản phẩm
                                            </th>
                                            <th scope="col" x-on:click="sortBy((item) => item.food?.totalAmount)">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-arrows-sort" width="16"
                                                    height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                                </svg>
                                                Doanh thu sản phẩm
                                            </th>
                                            <th scope="col" x-on:click="sortBy((item) => item.ticket?.total)">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-arrows-sort" width="16"
                                                    height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                                </svg>
                                                Số vé
                                            </th>
                                            <th scope="col" x-on:click="sortBy((item) => item.ticket?.totalMoney)">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-arrows-sort" width="16"
                                                    height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                                </svg>
                                                Doanh thu phim
                                            </th>
                                            <th scope="col" x-on:click="sortBy((item) => item.total)">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-arrows-sort" width="16"
                                                    height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                                </svg>
                                                Tổng doanh thu
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <template x-for="item in data.cinemaRevenue" :key="item.date">
                                            <tr>
                                                <th scope="row" x-text="dayjs(item.date).format('DD/MM/YYYY')"></th>
                                                <td x-text="toVnd(item.food?.totalMoney || 0)"></td>
                                                <td x-text="item.food?.totalAmount || 0"></td>
                                                <td x-text="toVnd(item.ticket?.totalMoney || 0)"></td>
                                                <td x-text="item.ticket?.total || 0"></td>
                                                <td
                                                x-text="toVnd(item.totalMoney || 0)"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                </div>
                <!-- hết bảng của biểu đồ cột -->

                <div class="row tables " id="table">
                    <!-- bảng của biểu đồ tròn -->
                    <div class="col-6">
                        <div class="container p-3 bg-white border_radius-16">
                            <div class="fw-semibold">Doanh thu theo hàng hóa</div>
                            <div class="fw-semibold flex-nowrap">
                                <span class="fs-4"
                                    x-text="toVnd(data.productRevenue.reduce((acc, item) => acc + Number(item.totalMoney), 0))">

                                </span>

                            </div>
                            <div class="table_container">
                                <table class="table">
                                    <thead class="table-header" x-data="{
                                        currentSortDirection : 'asc',
                                        sortBy(column) {
                                            if (this.currentSortDirection === 'asc') {
                                                this.currentSortDirection = 'desc';
                                                this.data.productRevenue = this.data.productRevenue.sort((a, b) => a[column] > b[column] ? 1 : -1);
                                            } else {
                                                this.currentSortDirection = 'asc';
                                                this.data.productRevenue = this.data.productRevenue.sort((a, b) => a[column] < b[column] ? 1 : -1);
                                            }
                                        }
                                    }
                                    ">
                                        <tr>
                                            <th scope="col">Sản phẩm</th>
                                            <th scope="col" x-on:click="sortBy('totalAmount')">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-arrows-sort" width="16"
                                                    height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                                </svg>
                                                Số lượng
                                            </th>
                                            <th scope="col" x-on:click="sortBy('totalMoney')">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-arrows-sort" width="16"
                                                    height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                                </svg>
                                                Doanh thu
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="item in data.productRevenue" :key="item.name">
                                            <tr>
                                                <th scope="row" x-text="item.name"></th>
                                                <td x-text="item.totalAmount"></td>
                                                <td x-text="toVnd(item.totalMoney)"></td>
                                            </tr>
                                        </template>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- hết bảng của biểu đồ tròn -->

                    <!-- bảng doanh thu phim -->
                    <div class="col-6">
                        <div class="container p-3 bg-white border_radius-16">
                            <div class="fw-semibold">Doanh thu các phim</div>
                            <div class="fw-semibold flex-nowrap">
                                <span class="fs-4" x-text="toVnd(data.totalMovieRevenue)"></span>

                            </div>

                            <!-- danh sách phim -->
                            <div class="container pt-3 table_container">
                                <template x-for="item in data.movieRevenue" :key="item.MaPhim">
                                    <div class="p-3 row">
                                        <div class="d-flex">
                                            <div class="me-auto">
                                                <div class="row">
                                                    <div class="p-0 col-1">
                                                        <div class="text-center d-block">
                                                            <img :src="item.HinhAnh" class="img-fluid img-thumbnail"
                                                                alt />
                                                        </div>
                                                    </div>

                                                    <div class="p-0 col-10 ps-2">
                                                        <div class="fw-semibold" x-text="item.TenPhim">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class>
                                                <div class="row">
                                                    <p class="m-0 text-end" style="color: rgb(0, 167, 111)"
                                                        x-text="toVnd(item.total)">

                                                    </p>
                                                </div>
                                                <div class="row">
                                                    <p class="m-0 text-end"
                                                        x-text="(Number(item.total) / data.totalMovieRevenue * 100).toFixed(1) + '%'">
                                                        15%</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </template>
                                <!-- hết danh sách phim -->
                            </div>
                        </div>
                        <!-- hết bảng doanh thu phim -->
                    </div>
                </div>
        </template>
        <!-- ================hết thống kê theo dạng bảng====================== -->
    </div>
</div>
</div>


<?php
script('/public/bundle/thong-ke/cinema/index.js');

require ('app/views/admin/footer.php');
?>