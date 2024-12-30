<?php
title("Quản lý loại vé");
require ('app/views/admin/header.php');


?>
<script>
const ticketTypes = <?= json_encode($ticketTypes) ?>;
const ticketTypeStatus = <?= json_encode($ticketTypesStatus) ?>;
</script>
<link rel="stylesheet" href="/public/loai-ve/home.css">

<div x-data="
{
    filteredTicketTypes: ticketTypes,
    currentOrderDirection: 'asc',
    currentOrderBy: 'MaLoaiVe',
    orderBy:function(column='MaLoaiVe') {
        if (column == this.currentOrderBy) {
            this.currentOrderDirection = this.currentOrderDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.currentOrderBy = column;
            this.currentOrderDirection = 'asc';
        }
        this.filteredTicketTypes = this.filteredTicketTypes.sort((a, b) => {
            if (this.currentOrderDirection === 'asc') {
                return a[column] > b[column] ? 1 : -1;
            } else {
                return a[column] < b[column] ? 1 : -1;
            }
        });
    },
    keyword: '',
}
" x-init="
$watch('keyword', value => {
    filteredTicketTypes = ticketTypes.filter(ticketType => {
        return ticketType.TenLoaiVe.toLowerCase().includes(value.toLowerCase());
    }).sort((a, b) => {
        if (currentOrderDirection === 'asc') {
            return a[currentOrderBy] > b[currentOrderBy] ? 1 : -1;
        } else {
            return a[currentOrderBy] < b[currentOrderBy] ? 1 : -1;
        }
    });
});
" style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="wrapper p-5">
    <div class="access container-fluid  shadow">
        <!-- thanh tiềm kiếm và nút lọc dữ liệu  -->
        <div class="row justify-content-between px-5 mt-4">
            <div class="col-6">
                <div class="input-group">
                    <input type="text" x-model.debounce.500ms="keyword" name id="searchMovie"
                        placeholder="Nhập từ khoá cần tìm" class="form-control">
                    <button class="btn btn-outline-secondary align-items-center" type="button" id="searchMovie">
                        <i class="fa-solid fa-magnifying-glass" style="display: flex;"></i>
                    </button>
                </div>
            </div>

            <!-- filter -->
            <div class="col-6 d-flex flex-nowrap justify-content-end">


                <div>
                    <button type="button" class="btn btn-secondary" id="btn-create" data-bs-toggle="modal"
                        data-bs-target="#type-ticket-detail-modal" type="button">Thêm mới
                    </button>
                </div>
            </div>
        </div>
        <!-- hết thanh tìm kiếm và nút lọc dữ liệu -->
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
                            Bạn có chắc chắn muốn xóa loại vé này không?
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
        <!-- bảng dữ liệu hóa đơn -->
        <div class="row m-3 table-responsive" style="flex: 1;">
            <table class="table table-hover align-middle" style="height: 100%;" id="">
                <thead class="table-light">
                    <tr>
                        <th scope="col">
                            <div class="col-name" x-on:click="orderBy('MaLoaiVe')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Mã loại vé
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name" x-on:click="orderBy('TenLoaiVe')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Tên loại vé
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name" x-on:click="orderBy('GiaVe')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Phụ thu
                            </div>
                        </th>
                        <th scope="col">Áp dụng cho ghế </th>
                        <th scope="col">
                            Trạng thái
                        </th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="ticketType in filteredTicketTypes" :key="ticketType.MaLoaiVe">
                        <tr>
                            <th scope="row" class="col-id table-plus">
                                <span x-text="ticketType.MaLoaiVe">

                                </span>
                            </th>
                            <td class=" ps-0">
                                <div class="d-flex align-items-center">
                                    <div class="col-name">
                                        <span x-text="ticketType.TenLoaiVe">

                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="col-price">
                                <span x-text="toVnd(ticketType.GiaVe)">

                                </span>
                            </td>
                            <td class="col-des">
                                <span x-text="ticketType.Rong+ ' Người'">

                                </span>
                            </td>
                            <td>
                                <span
                                    x-text="ticketTypeStatus.find(status => status.MaTrangThai == ticketType.TrangThai)?.Ten||'Hiện'">
                                </span>
                            </td>
                            <td class="col-crud">
                                <div class="dropdown">
                                    <button type="button " class="btn btn-light btn-icon rounded-circle"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16"
                                            class="icon">
                                            <path
                                                d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu">

                                        <li>
                                            <div x-on:click="showEditModal(ticketType.MaLoaiVe)"
                                                class="dropdown-item !tw-text-yellow-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                                <span class="px-xl-3 ">Sửa</span>
                                            </div>
                                        </li>

                                        <li>

                                            <div class="dropdown-item " x-init="
                                            console.log(ticketType.TrangThai);
                                            " :class="{'!tw-text-green-500': ticketType.TrangThai==13, '!tw-text-red-500': ticketType.TrangThai!=13}"
                                                x-on:click="ticketType.TrangThai!=13 ? showDeleteModal(ticketType.MaLoaiVe) : onRecoverLoaiVe(ticketType.MaLoaiVe)">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="tw-w-6 tw-h-6">
                                                    <path
                                                        d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z" />
                                                    <path fill-rule="evenodd"
                                                        d="m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.133 2.845a.75.75 0 0 1 1.06 0l1.72 1.72 1.72-1.72a.75.75 0 1 1 1.06 1.06l-1.72 1.72 1.72 1.72a.75.75 0 1 1-1.06 1.06L12 15.685l-1.72 1.72a.75.75 0 1 1-1.06-1.06l1.72-1.72-1.72-1.72a.75.75 0 0 1 0-1.06Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="px-xl-3 "
                                                    x-text="ticketType.TrangThai==13 ? 'Hiện' : 'Xoá/Ẩn'">
                                                </span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <!-- hết bảng dữ liệu hóa đơn -->

        <!-- thanh phân trang và số dòng hiển thị -->

        <!-- hết thanh phân trang và số dòng hiển thị -->
    </div>


    <!-- Type ticket Details Modal -->
    <div class="modal fade bs-example-modal-lg" id="type-ticket-detail-modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content" id="type-ticket-detail-form">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">
                        Thông tin
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        id="btn-close-type-ticket-detail">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="create-type-ticket container bg-white">
                        <div class="row d-flex mt-2">
                            <label for="type-ticket-id" class="col-xl-2">
                                Mã loại vé
                            </label>
                            <div class="input-group has-validation col-xl-10 p-0">
                                <input disabled type="text" class="form-control " id="type-ticket-id"
                                    aria-describedby="type-ticket-id-feedback" required>
                                <div id="type-ticket-id-feedback" class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>

                        <div class="row d-flex mt-2">
                            <label for="type-ticket-name" class="col-xl-2">
                                Tên loại vé
                            </label>
                            <div class="input-group has-validation col-xl-10 p-0">
                                <input type="text" class="form-control " id="type-ticket-name"
                                    aria-describedby="type-ticket-price-feedback" required>
                                <div id="type-ticket-name-feedback" class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>

                        <div class="row d-flex mt-2">
                            <label for="type-ticket-price" class="col-xl-2">
                                Giá tiền
                            </label>
                            <div class="input-group has-validation col-xl-10 p-0">
                                <input type="number" class="form-control " id="type-ticket-price"
                                    aria-describedby="type-ticket-price-feedback" required>
                                <div id="type-ticket-price-feedback" class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex mt-2">
                            <label for="cinema-status" class="col-xl-2">
                                Ghế áp dụng
                            </label>
                            <div class="col-xl-10 p-0">
                                <select class="form-select " id="type-ticket-seat" required>
                                    <option selected disabled value="">chọn...</option>
                                    <option value="1">Ghế đơn </option>
                                    <option value="2">Ghế đôi</option>
                                    <option value="3">Ghế 3 người</option>
                                    <option value="4">Ghế 4 người</option>
                                    <option value="5">Ghế 5 người</option>
                                </select>
                                <div class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex mt-2">
                            <label for="type-ticket-des" class="col-xl-2">
                                Mô tả
                            </label>
                            <div class="input-group has-validation col-xl-10 p-0">
                                <textarea id="type-ticket-des" required class="form-control "
                                    aria-describedby="type-ticket-des-feedback"></textarea>
                                <div id="type-ticket-des-feedback" class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancel-type-ticket">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-primary" id="btn-save-type-ticket">
                        Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php
script('/public/loai-ve/ticket_details.js');

require ('app/views/admin/footer.php');

?>