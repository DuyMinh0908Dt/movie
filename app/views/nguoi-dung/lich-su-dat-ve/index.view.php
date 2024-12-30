<?php
use App\Core\Request;
use App\Dtos\LoaiTaiKhoan;

title("Lịch sử mua vé");
$isKhachHang = Request::getUser()['TaiKhoan']['LoaiTaiKhoan'] == LoaiTaiKhoan::KhachHang->value;
if (!$isKhachHang) {
    redirect('/trang-chu');
}
require ('app/views/partials/head.php'); ?>
<link rel="stylesheet" href="/public/tiendat/infoBill.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"
    integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="/public/nguoi-dung/main.css">

<div x-data="{
    selectedId: null,
    selectedOrder: null,
    groupByTicketType: null,
    groupBySeatType: null,
    tienBapNuoc: 0,
    tienThucPham: 0,
    tienVe: 0,
    tienKhuyenMai: 0,
}" x-init="
$watch('selectedId',async (value) => {
    if (value) {
       const response = await axios.get(`/api/hoa-don/${value}`);
        selectedOrder = response.data.data;

        groupByTicketType = _.groupBy(selectedOrder?.['Ve'], 'MaLoaiVe');
        groupBySeatType = _.groupBy(selectedOrder?.['Ve'], 'MaLoaiGhe');
        tienVe = selectedOrder?.['Ve'].reduce((acc, ve) => acc + ve['GiaVe'], 0);
        tienBapNuoc = selectedOrder?.['Combos'].reduce((acc, combo) => acc + combo['ThanhTien'], 0);
        tienThucPham = selectedOrder?.['ThucPhams'].reduce((acc, thucPham) => acc + thucPham['ThanhTien'], 0);
        tienKhuyenMai = selectedOrder?.['TongTien'] - tienVe - tienBapNuoc - tienThucPham;


    }else{
        selectedOrder = null;
    }
});
" class="acc-page container-fluid p-3">
    <div class="row lg:tw-m-5 m-sm-0 p-md-3 p-lg-5 mx-xxl-5">
        <!-- account sidebar -->
        <div class="col-sm-12 col-md-4 mb-sm-4">
            <div class="acc-sidebar container-fluid shadow p-3 rounded">
                <div class="row d-flex justify-content-center align-items-md-center text-center">
                    <div class>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle"
                            width="130" height="130" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                        </svg>
                    </div>

                    <div>
                        <span>Thành viên</span>
                    </div>

                    <div class="mb-3">
                        <span class="fw-semibold fs-3">
                            <?= $userif['TenNguoiDung'] ?>
                        </span>
                    </div>

                    <div class="acc-points text-start">
                        <div class="mb-2">
                            <span class="fw-semibold">Tích điểm thành
                                viên</span>
                        </div>

                        <div class="bock-bar mb-2">
                            <div class="curr-bar" style="width: 1.8%;"></div>
                        </div>

                        <div>
                            <span class="fw-semibold">
                                <?= $userif['DiemTichLuy'] ?? 0 ?>
                                điểm
                            </span>
                        </div>
                    </div>

                    <div>
                        <hr class="my-3">
                    </div>
                </div>

                <div class="row align-items-sm-center">
                    <div class="acc-sidebar__items mb-4 col-sm-6 mb-sm-0 col-md-12 mb-md-3">
                        <a href="/nguoi-dung/thong-tin"
                            class="d-flex align-items-center justify-content-sm-center justify-content-md-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle"
                                width="30" height="30" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                            </svg>
                            <span class="fs-5 fw-semibold px-2">Thông
                                tin
                                khách
                                hàng</span>
                        </a>
                    </div>

                    <div class="acc-sidebar__items col-sm-6 col-md-12">
                        <a href="/nguoi-dung/lich-su-dat-ve"
                            class="active  tw-flex tw-items-center tw-gap-x-2 align-items-center justify-content-sm-center justify-content-md-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-history"
                                width="30" height="30" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 8l0 4l2 2" />
                                <path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" />
                            </svg>
                            <span class="fs-5 fw-semibold px-2">Lịch sử
                                mua
                                hàng</span>
                        </a>
                    </div>

                    <div class="d-sm-none d-md-block">
                        <hr class="my-3">
                    </div>
                </div>

                <div class="row d-sm-none d-md-block">
                    <div class="acc-logout">
                        <a href="#" class="logout d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout"
                                width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                <path d="M9 12h12l-3 -3" />
                                <path d="M18 15l3 -3" />
                            </svg>

                            <span class="fs-5 fw-semibold px-2">Đăng
                                xuất</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <dialog id="my_modal_2" class="tw-modal ">
            <div class="tw-modal-box tw-w-11/12 tw-max-w-5xl ">
                <form method="dialog">
                    <button
                        class="tw-btn tw-btn-sm tw-btn-circle tw-btn-ghost tw-absolute tw-right-2 tw-top-2">✕</button>
                </form>
                <div class='tw-flex tw-flex-col tw-pl-6'>
                    <div
                        class='tw-flex tw-flex-col tw-gap-y-2 tw-pr-2 md:tw-flex-row tw-mb-3 tw-justify-between tw-items-center'>
                        <h3 class="tw-font-bold md:tw-text-lg tw-text-base" x-text="'Chi tiết hóa đơn #' + selectedId">
                        </h3>
                        <img :src="selectedId?`https://barcode.orcascan.com/?type=code128&format=svg&data=${selectedId}`:''"
                            class=" tw-h-14 tw-object-cover tw-rounded-md tw-border tw-border-gray-200" alt="">
                    </div>


                    <div class="row ">
                        <!-- chi tiết hóa đơn -->
                        <div class="detail-bill-container col-12 p-0 m-0">
                            <!-- chi tiết vé đã đặt -->
                            <div class="ticket-container row w-100 shadow p-3">
                                <div class="md:tw-text-lg fw-bold mb-3"
                                    x-text="selectedOrder?.['SuatChieu']['TenPhim']">
                                </div>
                                <div class="fw-semibold md:tw-text-lg"
                                    x-text="selectedOrder?.['SuatChieu']['TenRapChieu']">
                                </div>
                                <div class="fw-normal" x-text="selectedOrder?.['SuatChieu']['DiaChi']">
                                </div>

                                <div class="d-flex align-items-stretch mt-3">
                                    <div class="flex-column">
                                        <div class="fw-semibold">Thời
                                            gian</div>
                                        <div class="fs-5 fw-normal tw-capitalize"
                                            x-text="dayjs(selectedOrder?.['SuatChieu']['NgayGioChieu']).format('HH:mm dddd DD/MM/YYYY')">
                                            22:45 Thứ
                                            Hai
                                            08/04/2024</div>
                                    </div>

                                    <div class="flex-column px-4">
                                        <div class="fw-semibold">Phòng
                                            chiếu</div>
                                        <div class="fs-5 fw-normal"
                                            x-text="selectedOrder?.['SuatChieu']['TenPhongChieu']">

                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-stretch mt-3">

                                    <div class="tw-grid tw-gap-2  tw-gap-x-4 tw-grid-cols-2 tw-max-w-96">
                                        <div class="fw-semibold">Loại vé</div>

                                        <div class="fw-semibold">Số vé</div>
                                        <template x-for="([maLoaiVe, ve]) in Object.entries(groupByTicketType||[])">
                                            <div class="tw-flex tw-gap-x-2 tw-col-span-2 tw-items-center">
                                                <div class="fs-5 fw-normal" x-text="ve[0]['TenLoaiVe']"></div>
                                                <div class="fs-5 fw-normal" x-text="ve.length"></div>
                                            </div>
                                        </template>

                                    </div>

                                </div>
                                <div class="d-flex align-items-stretch mt-3">
                                    <div class="tw-grid tw-gap-2  tw-gap-x-4 tw-grid-cols-2 tw-max-w-96">
                                        <div class="fw-semibold">Loại Ghế</div>

                                        <div class="fw-semibold">Số Ghế</div>


                                        <template x-for="([maLoaiGhe, ve]) in Object.entries(groupBySeatType||[])">
                                            <div class="tw-flex tw-gap-x-2 tw-col-span-2 tw-items-center">
                                                <div class="fs-5 fw-normal" x-text="ve[0]['TenLoaiGhe']"></div>
                                                <div class="fs-5 fw-normal" x-text="ve.map(v => v['SoGhe']).join(', ')">
                                                </div>
                                            </div>
                                        </template>

                                    </div>
                                </div>
                            </div>

                            <div style="min-height: 0"
                                class="prucduct-container flex tw-flex-col -tw-ml-3 w-100 shadow p-3 tw-overflow-y-auto tw-max-h-[400px]">
                                <div class="md:tw-text-lg fw-semibold mb-3">Bắp
                                    nước</div>
                                <template
                                    x-if="selectedOrder?.['Combos'].length == 0 && selectedOrder?.['ThucPhams'].length == 0">
                                    <div class="text-body-secondary tw-w-full tw-text-center">
                                        Không có bắp nước
                                    </div>
                                </template>

                                <template x-for="combo in selectedOrder?.['Combos']">
                                    <div class="row align-items-center">
                                        <div class="col-1">
                                            <img :src="combo['HinhAnh']" alt width="48px">
                                        </div>
                                        <div class="col-7">
                                            <div class="fw-medium fs-5" x-text="combo['TenCombo']">
                                            </div>
                                            <div class="fs-6 tw-hidden md:tw-block fw-light" x-text="combo['MoTa']">
                                            </div>
                                        </div>
                                        <div class="col-1 text-end">
                                            x <span x-text="combo['SoLuong']"></span>
                                        </div>
                                        <div class="col-3 text-end tw-font-semibold tw-text-lg"
                                            x-text="toVnd(combo['ThanhTien'])">

                                        </div>
                                    </div>
                                    <hr class="my-4">

                                </template>
                                <template x-for="thucpham in selectedOrder?.['ThucPhams']">
                                    <div class="row align-items-center">
                                        <div class="col-1">
                                            <img :src="thucpham['HinhAnh']" alt width="48px">
                                        </div>
                                        <div class="col-7">
                                            <div class="fw-medium fs-5" x-text="thucpham['TenThucPham']">
                                                >
                                            </div>
                                            <div class="fs-6 fw-light" x-text="thucpham['MoTa']">
                                            </div>
                                        </div>
                                        <div class="col-1 text-end" x-text="'x' +thucpham['SoLuong']">

                                        </div>
                                        <div class="col-3 text-end tw-font-semibold tw-text-lg"
                                            x-text="toVnd(thucpham['ThanhTien'])">

                                        </div>
                                    </div>
                                    <hr class="my-4">
                                </template>
                            </div>
                            <!-- hết combo bắp nước -->
                            <div class=" prucduct-container row w-100 shadow p-3 !tw-min-h-0 !tw-mt-7">
                                <div class="">
                                    <p class="fs-4 m-0 fw-semibold p-0 md:tw-text-lg">Khuyến
                                        mãi</p>
                                </div>
                                <template x-if="selectedOrder?.['KhuyenMai']">

                                    <div class=" p-4 border-bottom tw-flex -tw-mt-4 tw-flex-col">
                                        <div x-text="selectedOrder?.['KhuyenMai']['MaKhuyenMai']"
                                            class=" p-0 tw-font-semibold ">
                                        </div>

                                        <div x-text="selectedOrder?.['KhuyenMai']['TenKhuyenMai']"
                                            class=" p-0 tw-font-semibold ">

                                        </div>

                                        <div x-text="selectedOrder?.['KhuyenMai']['MoTa']" class=" ">
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!selectedOrder?.['KhuyenMai']">
                                    <div class="row align-items-center px-4 py-2 border-bottom">
                                        <div class="col-6 p-0 ">
                                            Không sử dụng
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class=" prucduct-container row w-100 shadow p-3 !tw-min-h-0 !tw-mt-7">
                                <div class="row pt-4 px-4">
                                    <p class="fs-4 m-0 fw-semibold p-0 md:tw-text-lg">Thanh
                                        toán</p>
                                </div>

                                <div class="row align-items-center px-4 py-2  border-bottom">
                                    <div class="col-6 p-0 tw-font-semibold">
                                        Phương thức:
                                    </div>

                                    <div x-text="selectedOrder?.['PhuongThucThanhToan']"
                                        class="col-5 tw-uppercase text-end ">
                                    </div>

                                </div>


                                <div class="row justify-content-end p-4">
                                    <p class="fs-4 m-0 fw-semibold p-0 md:tw-text-lg">Thành tiền</p>

                                    <table class="table table-sm table-borderless text-end">
                                        <tr>
                                            <td class="text-dark-emphasis">
                                                Tiền vé
                                            </td>
                                            <td class="text-dark-emphasis" x-text="toVnd(tienVe)">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-dark-emphasis">Tiền bắp
                                                nước</td>
                                            <td class="text-dark-emphasis" x-text="toVnd(tienBapNuoc)">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-dark-emphasis">Khuyến
                                                mãi</td>
                                            <td class="text-danger" x-text="toVnd(tienKhuyenMai)">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="fw-semibold">Tổng tiền</td>
                                            <td class="fw-semibold" x-text="toVnd(selectedOrder?.['TongTien'])">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <form method="dialog" class="tw-modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
        <!-- lịch sử mua hàng -->
        <div class="col-sm-12 col-md-8">
            <div class="container-fluid p-0">
                <div class="fs-1 fw-bold text-white mb-3">Lịch sử mua
                    hàng</div>

                <div x-data="{
                    orders: [],
                    total: 0,
                    isFetching: false,
                    query: {
                        trang: 1,
                        'sap-xep': 'NgayGioThanhToan',
                        'thu-tu': 'DESC',
                        'limit': 10
                    },
                      createOrderFn: function (orderBy) {
                        if (this.query['sap-xep'] === orderBy) {
                            this.query['thu-tu'] = this.query['thu-tu'] === 'ASC' ? 'DESC' : 'ASC';
                        } else {
                            this.query['thu-tu'] = 'ASC';
                            this.query['sap-xep'] = orderBy;
                        }
                    },
                    fetchOrders: async function() {
                        this.isFetching = true;
                        let response = await axios.get('/api/nguoi-dung/hoa-don', {
                            params: this.query
                        });
                        this.isFetching = false;
                        let data = response.data

                        this.orders = data.data;
                        this.total = response.headers['x-total-count'];
                    },
                    getTotalPage: function() {
                        return Math.ceil(this.total / this.query.limit);
                    },
                }" x-init="fetchOrders" class="shadow bg-white rounded p-sm-0 p-md-4 tw-max-w-full tw-overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr class="table-primary">
                                <th scope="col">Mã hóa đơn</th>
                                <th scope="col">
                                    Ngày
                                </th>
                                <th scope="col">Tổng cộng</th>
                                <th scope="col">
                                    Điểm
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="isFetching">
                                <tr>
                                    <td class=" tw-border-b tw-border-gray-50" colspan="7">
                                        <div class='tw-w-full tw-flex tw-py-32 tw-items-center tw-justify-center'>
                                            <span class="tw-loading tw-loading-dots tw-loading-lg"></span>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="item in orders" :key="item.MaHoaDon">
                                <tr>
                                    <td>
                                        <span x-text="item.MaHoaDon"></span>
                                    </td>
                                    <td>
                                        <span x-text="dayjs(item.NgayGioThanhToan).format('HH:mm DD/MM/YYYY')"></span>
                                    </td>
                                    <td>
                                        <span x-text="toVnd(item.TongTien)"></span>
                                    </td>
                                    <td>
                                        <span x-text="Math.round(item.TongTien / 1000)">
                                        </span>
                                    </td>

                                    <td>
                                        <button class="btn btn-primary"
                                            x-on:click="selectedId = item.MaHoaDon; window['my_modal_2'].showModal()"
                                            class="">Xem chi
                                            tiết</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">

                                    <div class="tw-w-full tw-flex tw-items-center tw-justify-center">
                                        <div class="tw-join">
                                            <template x-for="(item, index) in Array.from({length: getTotalPage()})">
                                                <button x-on:click="query.trang = index + 1; fetchOrders()"
                                                    :class="{'tw-btn-active': query.trang == index + 1}"
                                                    class="tw-join-item tw-btn" x-text="index + 1"></button>

                                            </template>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

require ('app/views/partials/footer.php');
?>