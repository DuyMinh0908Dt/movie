<?php
title("Quản lý Khuyến mãi");
require('app/views/admin/header.php');
?>
<link rel="stylesheet" href="/public/khuyen-mai/home.css">
<div style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="wrapper p-5">
    <div class="promotion container shadow">
        <!--  -->
        <div class="row mt-2 d-flex pb-3">
            <div class="tab_box">
                <button class="tab_btn">Tất cả</button>
                <button class="tab_btn">Trong thời gian</button>
                <button class="tab_btn">Qua thời hạn</button>
            </div>
            <div class="line"></div>
        </div>
        <!--  -->


        <!-- thanh tim kiem va nut them phim moi -->
        <div class="row justify-content-between px-5">
            <div class="col-6">
                <div class="input-group">
                    <input type="text" name id="searchMovie" placeholder="Nhập tên khuyến mãi cần tìm" class="form-control">
                    <button class="btn btn-outline-secondary align-items-center" type="button" id="searchMovie">
                        <i class="fa-solid fa-magnifying-glass" style="display: flex;"></i>
                    </button>
                </div>
            </div>

            <div class="col-6">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary me-md-2" type="button" data-bs-toggle="modal" data-bs-target="#position-modal">Thêm khuyến mãi mới</button>
                </div>
            </div>
        </div>


        <!-- chua bang phim -->
        <div class="row m-3 table-responsive" style="flex: 1;">
            <table class="table table-hover align-middle" style="height: 100%;">
                <thead class="table-light">
                    <tr>
                        <th scope="col">
                            <div class="col-name" onclick="sortByIdMovie()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-numeric-down m-2" viewBox="0 0 16 16" id="sortNumDown_icon">
                                    <path d="M12.438 1.668V7H11.39V2.684h-.051l-1.211.859v-.969l1.262-.906h1.046z" />
                                    <path fill-rule="evenodd" d="M11.36 14.098c-1.137 0-1.708-.657-1.762-1.278h1.004c.058.223.343.45.773.45.824 0 1.164-.829 1.133-1.856h-.059c-.148.39-.57.742-1.261.742-.91 0-1.72-.613-1.72-1.758 0-1.148.848-1.835 1.973-1.835 1.09 0 2.063.636 2.063 2.687 0 1.867-.723 2.848-2.145 2.848zm.062-2.735c.504 0 .933-.336.933-.972 0-.633-.398-1.008-.94-1.008-.52 0-.927.375-.927 1 0 .64.418.98.934.98" />
                                    <path d="M4.5 2.5a.5.5 0 0 0-1 0v9.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L4.5 12.293z" />
                                </svg>

                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-numeric-up m-2 d-none " viewBox="0 0 16 16" id="sortNumUp_icon">
                                    <path d="M12.438 1.668V7H11.39V2.684h-.051l-1.211.859v-.969l1.262-.906h1.046z" />
                                    <path fill-rule="evenodd" d="M11.36 14.098c-1.137 0-1.708-.657-1.762-1.278h1.004c.058.223.343.45.773.45.824 0 1.164-.829 1.133-1.856h-.059c-.148.39-.57.742-1.261.742-.91 0-1.72-.613-1.72-1.758 0-1.148.848-1.835 1.973-1.835 1.09 0 2.063.636 2.063 2.687 0 1.867-.723 2.848-2.145 2.848zm.062-2.735c.504 0 .933-.336.933-.972 0-.633-.398-1.008-.94-1.008-.52 0-.927.375-.927 1 0 .64.418.98.934.98" />
                                    <path d="M4.5 13.5a.5.5 0 0 1-1 0V3.707L2.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.5.5 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L4.5 3.707z" />
                                </svg>
                                Mã khuyến mãi
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name" onclick="sortByNameMovie()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-alpha-down m-2" viewBox="0 0 16 16" id="sortAlphaDown_icon">
                                    <path fill-rule="evenodd" d="M10.082 5.629 9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371zm1.57-.785L11 2.687h-.047l-.652 2.157z" />
                                    <path d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645zM4.5 2.5a.5.5 0 0 0-1 0v9.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L4.5 12.293z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-alpha-up m-2 d-none" viewBox="0 0 16 16" id="sortAlphaUp_icon">
                                    <path fill-rule="evenodd" d="M10.082 5.629 9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371zm1.57-.785L11 2.687h-.047l-.652 2.157z" />
                                    <path d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645zm-8.46-.5a.5.5 0 0 1-1 0V3.707L2.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.5.5 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L4.5 3.707z" />
                                </svg>
                                Tên khuyến mãi
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name" onclick="sortByNameMovie()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-alpha-down m-2" viewBox="0 0 16 16" id="sortAlphaDown_icon">
                                    <path fill-rule="evenodd" d="M10.082 5.629 9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371zm1.57-.785L11 2.687h-.047l-.652 2.157z" />
                                    <path d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645zM4.5 2.5a.5.5 0 0 0-1 0v9.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L4.5 12.293z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-alpha-up m-2 d-none" viewBox="0 0 16 16" id="sortAlphaUp_icon">
                                    <path fill-rule="evenodd" d="M10.082 5.629 9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371zm1.57-.785L11 2.687h-.047l-.652 2.157z" />
                                    <path d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645zm-8.46-.5a.5.5 0 0 1-1 0V3.707L2.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.5.5 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L4.5 3.707z" />
                                </svg>
                                Ngày bắt đầu
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name" onclick="sortByNameMovie()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-alpha-down m-2" viewBox="0 0 16 16" id="sortAlphaDown_icon">
                                    <path fill-rule="evenodd" d="M10.082 5.629 9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371zm1.57-.785L11 2.687h-.047l-.652 2.157z" />
                                    <path d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645zM4.5 2.5a.5.5 0 0 0-1 0v9.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L4.5 12.293z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-alpha-up m-2 d-none" viewBox="0 0 16 16" id="sortAlphaUp_icon">
                                    <path fill-rule="evenodd" d="M10.082 5.629 9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371zm1.57-.785L11 2.687h-.047l-.652 2.157z" />
                                    <path d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645zm-8.46-.5a.5.5 0 0 1-1 0V3.707L2.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.5.5 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L4.5 3.707z" />
                                </svg>
                                Ngày kết thúc
                            </div>
                        </th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">127</th>
                        <td>HÀNH TINH CÁT PHẦN 2 (T16)</td>
                        <td>
                            <a href="https://cinestar.com.vn/pictures/Cinestar/03-2024/hanh-tinh-cat-2.jpg">Poster</a>
                        </td>
                        <td>
                            <a href="https://www.youtube.com/watch?v=0ZqTYVYcx4k">Trailer</a>
                        </td>
                        <td>1</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-light btn-icon rounded-circle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                    </svg>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <div class="dropdown-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                            </svg>
                                            <button class="px-xl-3 btn-tb" data-bs-target="#position-detail-modal" data-bs-toggle="modal">Xem</button>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                            </svg>
                                            <button class="px-xl-3 btn-tb" data-bs-target="#position-detail-modal" data-bs-toggle="modal">Sửa</button>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-item ">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                            </svg>
                                            <span class="px-xl-3 ">Xóa</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- het chua bang phim -->

        <!-- thanh phan trang -->
        <div class="d-flex justify-content-end column-gap-3">
            <div class="d-flex h-50 w-25">
                <label class="input-group-text border-0 bg-white " for="inputGroupSelect01">Rows per
                    page</label>
                <select class="form-select rounded" id="inputGroupSelect01">
                    <option value="1">5</option>
                    <option value="2">10</option>
                    <option value="3">15</option>
                    <option value="3">20</option>
                </select>
            </div>

            <div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- het thanh phan trang -->
    </div>

    <!-- Modal create new position -->
    <div class="modal fade bs-example-modal-lg" id="position-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header position-relative">
                    <h4 class="modal-title" id="myLargeModalLabel">Thông tin Khuyến mãi</h4>
                    <button type="button" class="close close-modal position-absolute" data-dismiss="modal" aria-hidden="true" id="btn-close-modal-position">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="create-position container bg-white">

                        <!-- Tên Khuyến mãi -->
                        <div class="row d-flex mt-2">
                            <label for="POSITION_NAME" class="col-xl-2 fs-admin pe-0 ps-3">
                                Tên Khuyến mãi
                            </label>
                            <div class="has-validation col-xl-10 p-0">
                                <input type="text" class="form-control is-invalid" id="POSITION_NAME" name="POSITION_NAME" aria-describedby="POSITION_NAME-feedback" required>
                                <div id="POSITION_NAME-feedback" class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>

                        <!-- Mô tả Khuyến mãi -->
                        <div class="row d-flex mt-2">
                            <label for="POSITION_DESCRIPTION" class="col-xl-2 fs-admin pe-0 ps-3">
                                Mô tả Khuyến mãi
                            </label>
                            <div class="has-validation col-xl-10 p-0">
                                <textarea id="POSITION_DESCRIPTION" name="POSITION_DESCRIPTION" required class="form-control is-invalid" aria-describedby="POSITION_DESCRIPTION-feedback"></textarea>
                                <div id="POSITION_DESCRIPTION-feedback" class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>

                        <div class="row form-group mt-2">
                            <!-- Ngày bắt đầu khuyến mãi -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Ngày bắt đầu
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="date" name="START_DAY" id="START_DAY" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="START_DAY-feedback" class="invalid-feedback">
                                        Please choose a day
                                    </div>
                                </div>
                            </div>

                            <!-- Ngày kết thúc khuyến mãi -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Ngày kết thúc
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="date" name="END_DAY" id="END_DAY" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="END_DAY-feedback" class="invalid-feedback">
                                        Please choose a day
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group mt-2">
                            <!-- Giảm Tối đa -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Giảm Tối đa
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="number" id="PROMO_Max_Discount" name="PROMO_Max_Discount" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="PROMO_Max_Discount-feedback" class="invalid-feedback">
                                        Please choose a minutes
                                    </div>
                                </div>
                            </div>

                            <!-- Gía trị giảm -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Giá trị giảm
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="number" id="PROMO_Discount_Value" name="PROMO_Discount_Value" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="PROMO_Discount_Value-feedback" class="invalid-feedback">
                                        Please choose a minutes
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group mt-2">
                            <!-- Giới hạn sử dụng -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Giới hạn sử dụng
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="number" id="PROMO_USAGE_LIMIT" name="PROMO_USAGE_LIMIT" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="PROMO_USAGE_LIMIT-feedback" class="invalid-feedback">
                                        Please choose a minutes
                                    </div>
                                </div>
                            </div>

                            <!-- Giới hạn trên khuyến mãi -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Giới hạn trên khuyến mãi
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="number" id="PROMO_LIMIT" name="PROMO_LIMIT" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="PROMO_LIMIT-feedback" class="invalid-feedback">
                                        Please choose a minutes
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row form-group mt-2">
                            <!-- Giá trị tối thiểu -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Giá trị tối thiểu
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="number" id="PROMO_MIN_VALUE" name="PROMO_MIN_VALUE" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="PROMO_MIN_VALUE-feedback" class="invalid-feedback">
                                        Please choose a minutes
                                    </div>
                                </div>
                            </div>

                            <!-- Điểm tối thiểu -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Điểm tối thiểu
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="number" id="Min_Point" name="Min_Point" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="Min_Point-feedback" class="invalid-feedback">
                                        Please choose a minutes
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row d-flex mt-2">
                            <!-- Trạng thái -->
                            <label for="Promo-Status" class="col-xl-2">
                                Trạng thái
                            </label>
                            <div class="col-xl-4 p-0">
                                <select class="form-select is-invalid text-danger" id="Promo-Status" aria-describedby="Promo-Status-feedback" required>
                                    <option selected disabled value="">Choose...</option>
                                    <option>...</option>
                                </select>
                                <div id="Promo-Status-feedback" class="invalid-feedback">
                                    Please select a valid state.
                                </div>
                            </div>

                            <!-- Loại Vé -->
                            <label for="TICKET-TYPE" class="col-xl-2">
                                Loại vé
                            </label>
                            <div class="col-xl-4 p-0">
                                <select class="form-select is-invalid text-danger" id="TICKET-TYPE" aria-describedby="TICKET-TYPE-feedback" required>
                                    <option selected disabled value="">Choose...</option>
                                    <option>...</option>
                                </select>
                                <div id="TICKET-TYPE-feedback" class="invalid-feedback">
                                    Please select a valid state.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancel-mcp">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-save-mcp">
                        Save
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal update new position -->
    <div class="modal fade bs-example-modal-lg" id="position-detail-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header position-relative">
                    <h4 class="modal-title" id="myLargeModalLabel">Thông tin Khuyến mãi</h4>
                    <button type="button" class="close close-modal position-absolute" data-dismiss="modal" aria-hidden="true" id="btn-close-modal-position-detail">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="update-position container bg-white">

                        <!-- Tên Khuyến mãi -->
                        <div class="row d-flex mt-2">
                            <label for="POSITION_NAME" class="col-xl-2 fs-admin pe-0 ps-3">
                                Tên Khuyến mãi
                            </label>
                            <div class="has-validation col-xl-10 p-0">
                                <input type="text" class="form-control is-invalid" id="POSITION_NAME" name="POSITION_NAME" aria-describedby="POSITION_NAME-feedback" required>
                                <div id="POSITION_NAME-feedback" class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>

                        <!-- Mô tả Khuyến mãi -->
                        <div class="row d-flex mt-2">
                            <label for="POSITION_DESCRIPTION" class="col-xl-2 fs-admin pe-0 ps-3">
                                Mô tả Khuyến mãi
                            </label>
                            <div class="has-validation col-xl-10 p-0">
                                <textarea id="POSITION_DESCRIPTION" name="POSITION_DESCRIPTION" required class="form-control is-invalid" aria-describedby="POSITION_DESCRIPTION-feedback"></textarea>
                                <div id="POSITION_DESCRIPTION-feedback" class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>

                        <div class="row form-group mt-2">
                            <!-- Ngày bắt đầu khuyến mãi -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Ngày bắt đầu
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="date" name="START_DAY" id="START_DAY" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="START_DAY-feedback" class="invalid-feedback">
                                        Please choose a day
                                    </div>
                                </div>
                            </div>

                            <!-- Ngày kết thúc khuyến mãi -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Ngày kết thúc
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="date" name="END_DAY" id="END_DAY" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="END_DAY-feedback" class="invalid-feedback">
                                        Please choose a day
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group mt-2">
                            <!-- Giảm Tối đa -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Giảm Tối đa
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="number" id="PROMO_Max_Discount" name="PROMO_Max_Discount" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="PROMO_Max_Discount-feedback" class="invalid-feedback">
                                        Please choose a minutes
                                    </div>
                                </div>
                            </div>

                            <!-- Gía trị giảm -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Giá trị giảm
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="number" id="PROMO_Discount_Value" name="PROMO_Discount_Value" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="PROMO_Discount_Value-feedback" class="invalid-feedback">
                                        Please choose a minutes
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group mt-2">
                            <!-- Giới hạn sử dụng -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Giới hạn sử dụng
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="number" id="PROMO_USAGE_LIMIT" name="PROMO_USAGE_LIMIT" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="PROMO_USAGE_LIMIT-feedback" class="invalid-feedback">
                                        Please choose a minutes
                                    </div>
                                </div>
                            </div>

                            <!-- Giới hạn trên khuyến mãi -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Giới hạn trên khuyến mãi
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="number" id="PROMO_LIMIT" name="PROMO_LIMIT" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="PROMO_LIMIT-feedback" class="invalid-feedback">
                                        Please choose a minutes
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row form-group mt-2">
                            <!-- Giá trị tối thiểu -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Giá trị tối thiểu
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="number" id="PROMO_MIN_VALUE" name="PROMO_MIN_VALUE" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="PROMO_MIN_VALUE-feedback" class="invalid-feedback">
                                        Please choose a minutes
                                    </div>
                                </div>
                            </div>

                            <!-- Điểm tối thiểu -->
                            <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                                Điểm tối thiểu
                            </label>
                            <div class="col-4 p-0">
                                <div class="date">
                                    <input type="number" id="Min_Point" name="Min_Point" class="form-control is-invalid" required pattern="\d{1,2}/\d{1,2}/\d{4}" title="Vui lòng nhập đúng định dạng dd/mm/yyyy">
                                    <div id="Min_Point-feedback" class="invalid-feedback">
                                        Please choose a minutes
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row d-flex mt-2">
                            <!-- Trạng thái -->
                            <label for="Promo-Status" class="col-xl-2">
                                Trạng thái
                            </label>
                            <div class="col-xl-4 p-0">
                                <select class="form-select is-invalid text-danger" id="Promo-Status" aria-describedby="Promo-Status-feedback" required>
                                    <option selected disabled value="">Choose...</option>
                                    <option>...</option>
                                </select>
                                <div id="Promo-Status-feedback" class="invalid-feedback">
                                    Please select a valid state.
                                </div>
                            </div>

                            <!-- Loại Vé -->
                            <label for="TICKET-TYPE" class="col-xl-2">
                                Loại vé
                            </label>
                            <div class="col-xl-4 p-0">
                                <select class="form-select is-invalid text-danger" id="TICKET-TYPE" aria-describedby="TICKET-TYPE-feedback" required>
                                    <option selected disabled value="">Choose...</option>
                                    <option>...</option>
                                </select>
                                <div id="TICKET-TYPE-feedback" class="invalid-feedback">
                                    Please select a valid state.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancel-position-detail">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-save-position-detail">
                        Save
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
</div>

<?php
script('/public/khuyen-mai/main.js');
script('/public/khuyen-mai/tab.js');
require('app/views/footer.php');
?>