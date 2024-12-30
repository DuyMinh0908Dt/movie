<?php
title("Quản lý combo");
require ('app/views/admin/header.php');

?>


<link rel="stylesheet" href="/public/san-pham/home.css">

<div style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="wrapper p-5">
    <div class="access container-fluid  shadow">
        <!-- thanh tiềm kiếm và nút lọc dữ liệu  -->
        <div class="row justify-content-between px-5 mt-4">
            <div class="col-6">
                <div class="input-group">
                    <input type="text" id="searchMovie" placeholder="Nhập tên sản phẩm cần tìm" class="form-control">
                    <button class="btn btn-outline-secondary align-items-center" type="button" id="searchMovieBtn">
                        <i class="fa-solid fa-magnifying-glass" style="display: flex;"></i>
                    </button>
                </div>
            </div>

            <!-- filter -->
            <div class="col-6 d-flex flex-nowrap justify-content-end">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mx-2">
                    <div class="dropdown">
                        <button class="btn border-0 fw-medium" data-bs-toggle="dropdown" aria-expanded="false">
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
                        <ul class="dropdown-menu tw-min-w-72">
                            <li>
                                <div class="d-flex justify-content-between align-items-center px-2 pb-2">
                                    <div>
                                        <label class="fw-semibold" for>Lọc</label>
                                    </div>

                                    <div class="d-flex flex-nowrap">
                                        <button class="btn btn-light mx-2" id="clear-filter">Xóa
                                            lọc</button>
                                        <button class="btn btn-primary" id="apply-filter">Áp
                                            dụng</button>
                                    </div>
                                </div>

                            </li>


                            <li>
                                <hr class="dropdown-divider m-0">
                            </li>

                            <li>
                                <form class="d-flex flex-wrap p-2">
                                    <div class="row">
                                        <label class="form-label" for>Khoảng tiền</label>
                                    </div>

                                    <div class="row d-flex flex-nowrap">
                                        <div class="col">
                                            <input class="form-control" type="number" name id='gia-tien-tu'>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="number" name id='gia-tien-den'>
                                        </div>
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <div>
                    <a type="button" class="btn btn-secondary" href="/admin/combo/them-moi" id="btn-create">Thêm mới
                    </a>
                </div>
            </div>
        </div>
        <!-- hết thanh tìm kiếm và nút lọc dữ liệu -->

        <!-- bảng dữ liệu hóa đơn -->
        <div class="row m-3 table-responsive" style="flex: 1;">
            <table class="table table-hover align-middle" style="height: 100%;" id="">
                <thead class="table-light">
                    <tr>
                        <th scope="col">
                            <div class="col-name" onclick="createSort('MaCombo')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Mã sản phẩm
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name" onclick="createSort('TenCombo')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Tên sản phẩm
                            </div>
                        </th>

                        <th scope="col">
                            <div class="col-name" onclick="createSort('GiaCombo')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Giá tiền
                            </div>
                        </th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <tr>
                        <th scope="row" class="col-id table-plus">1</th>
                        <td class="col-name ps-0">
                            <div class="d-flex align-items-center">
                                <div class="tb-img-product mr-2 flex-shrink-0">
                                    <img src="./assets/img/pepsi.jpg" alt="Ly nước pepsi" width="40" height="40">
                                </div>
                                <div>
                                    <span class="">Pepsi</span>
                                </div>
                            </div>
                        </td>
                        <td class="col-des">Nước ngọt Pepsi</td>
                        <td class="col-type">nước ngọt</td>
                        <td class="col-price">5</td>
                        <td class="col-status">còn hàng</td>
                        <td class="col-crud">
                            <div class="dropdown position-relative">
                                <span href="" class="btn menu-crud">
                                    <i class="menu-crud-icon fa-solid fa-ellipsis"></i>
                                    <div class="list-item-crud position-absolute">
                                        <a href="" class="item-crud">
                                            <i class="fa-regular fa-eye"></i>
                                            <span>Xem</span>
                                        </a>
                                        <a href="" class="item-crud">
                                            <i class="fa-solid fa-eye-dropper"></i>
                                            <span>Sửa</span>
                                        </a>
                                        <a href="" class="item-crud">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>Xóa</span>
                                        </a>
                                    </div>
                                </span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- hết bảng dữ liệu hóa đơn -->

        <!-- thanh phân trang và số dòng hiển thị -->
        <div class="d-flex justify-content-end tw-mb-3 column-gap-3">
            <div class="d-flex input-group h-50 w-25">
                <label class="input-group-text border-0 bg-white " for="limit-select">Hiển thị</label>
                <select class="form-select rounded" id="limit-select">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                </select>
                <label class="input-group-text border-0 bg-white ">hóa đơn</label>
            </div>

            <div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination" id="page-root">
                        <li class="page-item">
                            <a class="page-link" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item"><a class="page-link">1</a></li>
                        <li class="page-item"><a class="page-link">2</a></li>
                        <li class="page-item"><a class="page-link">3</a></li>
                        <li class="page-item">
                            <a class="page-link" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- hết thanh phân trang và số dòng hiển thị -->
    </div>

    <!-- delete modal -->
    <div class="modal" tabindex="-1" role="dialog" id="delete-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Bạn có chắc chắn muốn xóa sản phẩm này không?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btn-delete">
                        Xóa
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="
                    $('#delete-modal').modal('hide');
                    ">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
script('/public/san-pham/combo.js');
require ('app/views/admin/footer.php');
?>