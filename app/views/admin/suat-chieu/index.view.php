<?php 
title("Quản lý suất chiếu");
require("app/views/header.php");
?>
<link rel="stylesheet" href="/public/suat-chieu/showtime.css">
            <div
                style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;"
                class="wrapper p-5">
                <div class="showtime container-fluid  shadow">
                    <!-- thanh phân loại phim -->
                    <div class="border-bottom mb-4">
                        <div>
                            <input type="button" name id="closing"
                                class="btn button button-nav-active fw-semibold"
                                value="Chưa mở bán"
                                onclick="optionOfList(this)">
                            <input type="button" name id="opening"
                                class="btn button fw-semibold"
                                value="Đang mở bán"
                                onclick="optionOfList(this)">
                            <input type="button" name id="soldout"
                                class="btn button fw-semibold" value="Hết vé"
                                onclick="optionOfList(this)">
                            <input type="button" name id="cancel"
                                class="btn button fw-semibold" value="Đã hủy"
                                onclick="optionOfList(this)">
                        </div>
                    </div>
                    <!-- hết thanh phân loại phim -->

                    <!-- thanh tìm kiếm và nút thêm phim mới -->
                    <div class="row justify-content-between px-5">
                        <div class="col-6">
                            <div class="input-group">
                                <input type="text" name id="searchMovie"
                                    placeholder="Nhập thông tin cần tìm"
                                    class="form-control">
                                <button
                                    class="btn btn-outline-secondary align-items-center"
                                    type="button" id="searchMovie">
                                    <i class="fa-solid fa-magnifying-glass"
                                        style="display: flex;"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-6">
                            <div
                                class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn btn-primary me-md-2"
                                    type="button">Thêm suất chiếu mới</button>
                            </div>
                        </div>
                    </div>
                    <!-- hết thanh tìm kiếm và nút thêm phim mới -->

                    <!-- danh sách phim -->
                    <div class="row m-3 table-responsive" style="flex: 1;">
                        <table class="table table-hover align-middle"
                            style="height: 100%;">
                            <!-- header của table -->
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">
                                        <div class="col-name">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-arrows-sort"
                                                width="16" height="16"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="#2c3e50" fill="none"
                                                stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none"
                                                    d="M0 0h24v24H0z"
                                                    fill="none" />
                                                <path
                                                    d="M3 9l4 -4l4 4m-4 -4v14" />
                                                <path
                                                    d="M21 15l-4 4l-4 -4m4 4v-14" />
                                            </svg>
                                            Mã suất chiếu
                                        </div>
                                    </th>
                                    <th scope="col">
                                        <div class="col-name">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-arrows-sort"
                                                width="16" height="16"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="#2c3e50" fill="none"
                                                stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none"
                                                    d="M0 0h24v24H0z"
                                                    fill="none" />
                                                <path
                                                    d="M3 9l4 -4l4 4m-4 -4v14" />
                                                <path
                                                    d="M21 15l-4 4l-4 -4m4 4v-14" />
                                            </svg>
                                            Rạp
                                        </div>
                                    </th>
                                    <th scope="col">
                                        <div class="col-name">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-arrows-sort"
                                                width="16" height="16"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="#2c3e50" fill="none"
                                                stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none"
                                                    d="M0 0h24v24H0z"
                                                    fill="none" />
                                                <path
                                                    d="M3 9l4 -4l4 4m-4 -4v14" />
                                                <path
                                                    d="M21 15l-4 4l-4 -4m4 4v-14" />
                                            </svg>
                                            Phòng
                                        </div>
                                    </th>
                                    <th scope="col">
                                        <div class="col-name">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-arrows-sort"
                                                width="16" height="16"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="#2c3e50" fill="none"
                                                stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none"
                                                    d="M0 0h24v24H0z"
                                                    fill="none" />
                                                <path
                                                    d="M3 9l4 -4l4 4m-4 -4v14" />
                                                <path
                                                    d="M21 15l-4 4l-4 -4m4 4v-14" />
                                            </svg>
                                            Bắt đầu
                                        </div>
                                    </th>
                                    <th scope="col">
                                        <div class="col-name">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-arrows-sort"
                                                width="16" height="16"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="#2c3e50" fill="none"
                                                stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none"
                                                    d="M0 0h24v24H0z"
                                                    fill="none" />
                                                <path
                                                    d="M3 9l4 -4l4 4m-4 -4v14" />
                                                <path
                                                    d="M21 15l-4 4l-4 -4m4 4v-14" />
                                            </svg>
                                            Kết thúc
                                        </div>
                                    </th>
                                    <th scope="col">Phim</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <!-- hết header của table -->

                            <tbody>
                                <tr>
                                    <th scope="row">127</th>
                                    <td> Hai Bà Trưng</td>
                                    <td>23</td>
                                    <td>2024-03-28 13:00:00</td>
                                    <td>2024-03-28 16:15:00</td>
                                    <td>HÀNH TINH CÁT PHẦN 2 (T16)</td>
                                    <td>1</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button "
                                                class="btn btn-light btn-icon rounded-circle"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="16" height="16"
                                                    fill="currentColor"
                                                    class="bi bi-three-dots-vertical"
                                                    viewBox="0 0 16 16" class="icon">
                                                    <path
                                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                                </svg>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <div class="dropdown-item">
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            width="16"
                                                            height="16"
                                                            fill="currentColor"
                                                            class="bi bi-eye-fill"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                            <path
                                                                d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                                        </svg>
                                                        <span
                                                            class="px-xl-3 ">Xem</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="dropdown-item">
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            width="16"
                                                            height="16"
                                                            fill="currentColor"
                                                            class="bi bi-pencil-square"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                            <path
                                                                fill-rule="evenodd"
                                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                        </svg>
                                                        <span
                                                            class="px-xl-3 ">Sửa</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="dropdown-item ">
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            width="16"
                                                            height="16"
                                                            fill="currentColor"
                                                            class="bi bi-trash3-fill"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                                        </svg>
                                                        <span
                                                            class="px-xl-3 ">Xóa</span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- hết danh sách phim -->

                    <!-- thanh phan trang -->
                    <div class="d-flex justify-content-end column-gap-3">
                        <div class="d-flex input-group h-50 w-25">
                            <label class="input-group-text border-0 bg-white "
                                for="inputGroupSelect01">Rows per
                                page</label>
                            <select class="form-select rounded"
                                id="inputGroupSelect01">
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
                                        <a class="page-link" href="#"
                                            aria-label="Previous">
                                            <span
                                                aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link"
                                            href="#">1</a></li>
                                    <li class="page-item"><a class="page-link"
                                            href="#">2</a></li>
                                    <li class="page-item"><a class="page-link"
                                            href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#"
                                            aria-label="Next">
                                            <span
                                                aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <!-- het thanh phan trang -->
                </div>
            </div>
        </div>
<?php 
script("/public/suat-chieu/showtime.js");
require("app/views/admin/footer.php");
?>