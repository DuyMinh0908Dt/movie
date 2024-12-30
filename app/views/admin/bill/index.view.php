<?php
title("Quản lý hóa đơn");
require ('app/views/admin/header.php');
?>

<link rel="stylesheet" href="/public/tiendat/bill.css">
<!-- End sidebar -->

<div x-data="dataTable({
    endpoint:'/api/hoa-don',
    initialQuery :{
        'trang': 1,
        'limit': 50,
    }
})" style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="p-5 wrapper">
    <div x-data="{
        onApllyFilter(){
            console.log(query);
            refresh();
        },
        onClearFilter(){
            query={};
            $nextTick(()=>{
                refresh();
            })
        }

    }
    " class="shadow bill container-fluid">
        <!-- thanh tiềm kiếm và nút lọc dữ liệu  -->
        <div class="px-5 mt-4 row justify-content-between">
            <div class="col-6">
                <div class="input-group">
                    <input x-model="query['tu-khoa']" x-on:keydown.enter="onApllyFilter" type="text" name
                        id="searchMovie" placeholder="Nhập từ khoá cần tìm" class="form-control">
                    <button x-on:click="onApllyFilter" class="btn btn-outline-secondary align-items-center"
                        type="button" id="searchMovie">
                        <i class="fa-solid fa-magnifying-glass" style="display: flex;"></i>
                    </button>
                </div>
            </div>

            <div class="col-6">
                <div class="gap-2 d-grid d-md-flex justify-content-md-end">
                    <div class="dropdown">
                        <button class="border-0 btn fw-medium" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter-filled"
                                width="18" height="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M20 3h-16a1 1 0 0 0 -1 1v2.227l.008 .223a3 3 0 0 0 .772 1.795l4.22 4.641v8.114a1 1 0 0 0 1.316 .949l6 -2l.108 -.043a1 1 0 0 0 .576 -.906v-6.586l4.121 -4.12a3 3 0 0 0 .879 -2.123v-2.171a1 1 0 0 0 -1 -1z"
                                    stroke-width="0" fill="currentColor" />
                            </svg>
                            Bộ lọc
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <div class="px-2 pb-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <label class="fw-semibold" for>Lọc</label>
                                    </div>

                                    <div class="d-flex flex-nowrap">
                                        <button x-on:click="onClearFilter" class="mx-2 btn btn-light">Xóa
                                            lọc</button>
                                        <button x-on:click="onApllyFilter" class="btn btn-primary">Áp
                                            dụng</button>
                                    </div>
                                </div>

                            </li>

                            <li>
                                <hr class="m-0 dropdown-divider">
                            </li>

                            <li>
                                <form class="flex-wrap p-2 d-flex">
                                    <div class="row">
                                        <label class="form-label" for>khoảng thời
                                            gian</label>
                                    </div>

                                    <div class="row d-flex flex-nowrap">
                                        <div class="col">
                                            <input x-model="query['tu-ngay']" class="form-control" type="date" name id>
                                        </div>
                                        <div class="col">
                                            <input x-model="query['den-ngay']" class="form-control" type="date" name id>
                                        </div>
                                    </div>
                                </form>
                            </li>

                            <li>
                                <hr class="m-0 dropdown-divider">
                            </li>

                            <li>
                                <form class="flex-wrap p-2 d-flex">
                                    <div class="row">
                                        <label class="form-label" for>Khoảng tiền</label>
                                    </div>

                                    <div class="row d-flex flex-nowrap">
                                        <div class="col">
                                            <input x-model="query['tong-tien-tu']" class="form-control" type="number"
                                                name id>
                                        </div>
                                        <div class="col">
                                            <input x-model="query['tong-tien-den']" class="form-control" type="number"
                                                name id>
                                        </div>
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- hết thanh tìm kiếm và nút lọc dữ liệu -->

        <!-- bảng dữ liệu hóa đơn -->
        <div class="m-3 row table-responsive" style="flex: 1;">
            <table class="table align-middle table-hover" style="height: 100%;">
                <thead class="table-light">
                    <tr>
                        <th scope="col">
                            <div class="col-name">

                                Mã hóa đơn
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name">

                                Khách hàng
                            </div>
                        </th>
                        <th scope="col" x-on:click="createOrderFn('NgayGioThanhToan')">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Thời gian
                            </div>


                        </th>
                        <th scope="col">Mã khuyến mãi</th>
                        <th scope="col" x-on:click="createOrderFn('TongTien')">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Tổng tiền
                            </div>
                        </th>
                        <th scope="col">Phương thức thanh toán</th>
                    </tr>
                </thead>
                <tbody>

                    <template x-if="isFetching">
                        <tr>
                            <td class=" tw-border-b tw-border-gray-50" colspan="6">
                                <div class='tw-w-full tw-flex tw-py-32 tw-items-center tw-justify-center'>
                                    <span class="tw-loading tw-loading-dots tw-loading-lg"></span>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-for="item in data" :key="item.MaHoaDon">
                        <tr x-on:click="window.location.href = '/admin/hoa-don/'+item.MaHoaDon" class="cursor-pointer">
                            <td x-text="item.MaHoaDon"></td>
                            <td x-text="item.TenNguoiDung"></td>
                            <td
                                x-text="dayjs(item.NgayGioThanhToan, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY HH:mm:ss')">
                            </td>
                            <td x-text="item.MaKhuyenMai||'Không có'"></td>
                            <td x-text="toVnd(item.TongTien)"></td>
                            <td class='tw-uppercase' x-text="item.PhuongThucThanhToan"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <!-- hết bảng dữ liệu hóa đơn -->

        <!-- thanh phân trang và số dòng hiển thị -->
        <div class="d-flex justify-content-end column-gap-3">
            <div class="d-flex input-group h-50 w-25">
                <label class="bg-white border-0 input-group-text " for="inputGroupSelect01">Hiển thị</label>
                <select x-model="query['limit']" class="rounded form-select" id="inputGroupSelect01">
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                </select>
                <label class="bg-white border-0 input-group-text " for="inputGroupSelect01">hóa đơn</label>
            </div>

            <div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li x-on:click="
                        if(query['trang']>1){
                            query['trang']--;
                            refresh();
                        }
                        " class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <template x-for="item in getArrayPages()" :key="item">
                            <li x-on:click="query['trang']=item;refresh()" class="page-item"
                                :class="{'active': query['trang']==item}">
                                <a class="page-link" href="#" x-text="item"></a>
                            </li>
                        </template>
                        <li class="page-item">
                            <a x-on:click="
                                let totalPages = Math.ceil(totalItems/query['limit']);
                                if(query['trang']<totalPages){
                                    query['trang']++;
                                    refresh();
                                }
                                " class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- hết thanh phân trang và số dòng hiển thị -->
    </div>
</div>


<?php
require ('app/views/admin/footer.php');


?>