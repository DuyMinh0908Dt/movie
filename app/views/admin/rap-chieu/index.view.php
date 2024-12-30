<?php
title("Quản lý rạp chiếu");
require ('app/views/admin/header.php')
    ?>

<link rel="stylesheet" href="/public/rap-chieu/home.css">

<div style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="wrapper p-5">
    <div class="access container-fluid  shadow">
        <!-- thanh tiềm kiếm và nút lọc dữ liệu  -->
        <div class="row justify-content-between px-5 mt-4">
            <div class="col-6">
                <div class="input-group">
                    <input type="text" id="searchMovie" placeholder="Nhập tên rạp cần tìm" class="form-control">
                    <button class="btn btn-outline-secondary align-items-center" type="button" id="searchMovieBtn">
                        <i class="fa-solid fa-magnifying-glass" style="display: flex;"></i>
                    </button>
                </div>
            </div>

            <!-- filter -->
            <div class="col-6 d-flex flex-nowrap justify-content-end">


                <div>
                    <button type="button" class="btn btn-secondary" id="btn-create" data-bs-toggle="modal"
                        data-bs-target="#cinema-detail-modal" type="button">Thêm mới
                    </button>
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
                            <div class="col-name" onclick="onOrderChange('MaRapChieu')">

                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Mã rạp chiếu
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name" onclick="onOrderChange('TenRapChieu')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Tên rạp chiếu
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name">
                                Địa chỉ
                            </div>
                        </th>
                        <th scope="col" onclick="onOrderChange('TrangThai')">Trạng thái</th>

                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody id="cinemaList">
                    <tr>
                        <th scope="row" class="col-id table-plus">1</th>
                        <td class="col-name ps-0">
                            <div class="d-flex align-items-center">
                                <div class="tb-theater-product mr-2 flex-shrink-0">
                                    <img src="" alt="" width="40" height="40">
                                </div>
                                <div>
                                    <span class="">Cinestar Quốc Thanh</span>
                                </div>
                            </div>
                        </td>
                        <td class="col-address">271 Nguyễn Trãi, Phường Nguyễn Cư Trinh, Quận 1, Thành Phố Hồ Chí Minh
                        </td>
                        <td class="col-stt">Trạng thái rạp</td>
                        <td class="col-des">
                            Cinestar Quốc Thanh
                        </td>
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
        <div class="d-flex justify-content-end column-gap-3 tw-mb-4">
            <div class="d-flex input-group h-50 w-25">
                <label class="input-group-text border-0 bg-white " for="inputGroupSelect01">Hiển thị</label>
                <select class="form-select rounded" id="inputGroupSelect01">
                    <option value="1">5</option>
                    <option value="2">10</option>
                    <option value="3">15</option>
                    <option value="3">20</option>
                </select>
                <label class="input-group-text border-0 bg-white " for="inputGroupSelect01">hóa đơn</label>
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
        <!-- hết thanh phân trang và số dòng hiển thị -->
    </div>

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
                        Bạn có chắc chắn muốn xóa rạp chiếu này không?
                    </p>
                    <p>
                        Rạp đã có phòng sẽ bị ẩn thay vì xóa
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
    <!-- Type ticket Details Modal -->
    <div class="modal fade bs-example-modal-lg" id="cinema-detail-modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content" id="cinema-form">
                <div class="modal-header">
                    <h4 class="modal-title" id="input-cinema-title">
                        Tạo rạp chiếu mới
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btn-close-cinema">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="create-cinema container bg-white">
                        <div class="row d-flex mt-2">
                            <label for="cinema-id" class="col-xl-2">
                                Mã rạp chiếu
                            </label>
                            <div class="input-group has-validation col-xl-10 p-0">
                                <input disabled type="text" class="form-control " id="cinema-id" required>
                                <div class="invalid-feedback">

                                </div>
                            </div>
                        </div>

                        <div class="row d-flex mt-2">
                            <label for="cinema-name" class="col-xl-2">
                                Tên rạp chiếu
                            </label>
                            <div class="input-group has-validation col-xl-10 p-0">
                                <input type="text" class="form-control" id="cinema-name" required>
                                <div class="invalid-feedback">

                                </div>
                            </div>
                        </div>

                        <div class="row d-flex mt-2">
                            <label class="col-xl-2">Hình
                                ảnh</label>
                            <div class="input-group col-xl-10 tw-p-0">
                                <input type="text" class="form-control" placeholder="Link hình ảnh"
                                    pattern=".+\.(png|jpe?g|gif|svg|webp)$" title="Link hình ảnh không hợp lệ"
                                    :class="{'is-invalid': errors.HinhAnh}" aria-label="Recipient's username"
                                    aria-describedby="button-addon2" id="HinhAnh" x-model="data.HinhAnh" required>
                                <div class="invalid-feedback" x-text="errors.HinhAnh"></div>
                                <label class="btn btn-outline-secondary">Chọn
                                    <input type="file" hidden accept="image/*" id="cinema-image-file">
                                    <span hidden class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </label>
                                <div id="cinema-image-error" class="invalid-feedback">
                                </div>
                            </div>
                        </div>

                        <div class="row d-flex mt-2">
                            <label for="cinema-address" class="col-xl-2">
                                Địa chỉ
                            </label>
                            <div class="input-group has-validation col-xl-10 p-0">
                                <input type="text" class="form-control" id="cinema-address" required>
                                <div class="invalid-feedback">

                                </div>
                            </div>
                        </div>

                        <div class="row d-flex mt-2">
                            <label for="cinema-status" class="col-xl-2">
                                Trạng thái
                            </label>
                            <div class="col-xl-10 p-0">
                                <select class="form-select " id="cinema-status" required>
                                    <option selected disabled value="">chọn...</option>
                                    <?php foreach ($cinemaStatuses as $status): ?>
                                        <option value="<?= $status['MaTrangThai'] ?>"><?= $status['Ten'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                </div>
                            </div>
                        </div>

                        <div class="row d-flex mt-2">
                            <label for="cinema-des" class="col-xl-2">
                                Mô tả
                            </label>
                            <div class="input-group has-validation col-xl-10 p-0">
                                <textarea id="cinema-des" required class="form-control "></textarea>
                                <div class="invalid-feedback">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancel-cinema">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-primary" id="btn-save-cinema">
                        Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php
script('/public/rap-chieu/cinema_details.js');
require ('app/views/admin/footer.php');
?>