<?php
use App\Dtos\TrangThaiNhomQuyen;

title("Quản lý nhóm quyền");
require ('app/views/admin/header.php');



?>
<link rel="stylesheet" href="/public/css/role.css">
<div x-data="
{
    selectedId: null,
}
" style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="wrapper p-5">
    <div class="access container-fluid  shadow">
        <!-- thanh tim kiem va nut them nhom quyen moi -->
        <div class="row justify-content-between px-5 mt-4 mb-3">
            <div class="col-6">
                <div class="input-group">
                    <input type="text" name id="searchMovie" placeholder="Nhập thông tin cần tìm" class="form-control">
                    <button class="btn btn-outline-secondary align-items-center" type="button" id="searchTicketType">
                        <i class="fa-solid fa-magnifying-glass" style="display: flex;"></i>
                    </button>
                </div>
            </div>

            <div class="col-6">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/admin/nhom-quyen/them" class="btn btn-primary me-md-2" type="button">Thêm nhóm quyền</a>
                </div>
            </div>
        </div>
        <!-- hết thanh tìm kiếm và nút thêm nhóm quyền mới -->

        <!-- chứa bảng nhóm quyền -->
        <div class="row m-3 table-responsive" style="flex: 1;">
            <table class="table table-hover align-middle" style="height: 100%;">
                <thead class="table-light">
                    <tr>
                        <th scope="col">
                            <div class="col-name">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Mã nhóm quyền
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Tên nhóm quyền
                            </div>
                        </th>
                        <th scope="col">Mô tả</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($roles as $role): ?>
                        <tr>
                            <th scope="row"><?= $role['MaNhomQuyen'] ?></th>
                            <td><?= $role['TenNhomQuyen'] ?></td>
                            <td><?= $role['MoTa'] ?></td>
                            <td>
                                <?php
                                if ($role['TrangThai'] == TrangThaiNhomQuyen::An->value)
                                    echo 'Khóa';
                                else
                                    echo 'Đang hoạt động';
                                ?>

                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-light btn-icon rounded-circle"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="icon">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <div class="dropdown-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                    <path
                                                        d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                                </svg>
                                                <span class="px-xl-3 ">Xem</span>
                                            </div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="/admin/nhom-quyen/<?= $role['MaNhomQuyen'] ?>/sua">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                                <span class="px-xl-3 ">Sửa</span>
                                            </a>
                                        </li>
                                        <li>
                                            <div x-on:click="
                                        axios.put('/api/nhom-quyen/' + <?= intval($role['MaNhomQuyen']) ?> + '/trang-thai')
                                        .then(res => {
                                            window.location.reload();
                                        }).catch(err => {
                                            console.log(err);
                                        });
                                        " class="dropdown-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="currentColor"
                                                    class="icon icon-tabler icons-tabler-filled icon-tabler-lock">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M12 2a5 5 0 0 1 5 5v3a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-6a3 3 0 0 1 3 -3v-3a5 5 0 0 1 5 -5m0 12a2 2 0 0 0 -1.995 1.85l-.005 .15a2 2 0 1 0 2 -2m0 -10a3 3 0 0 0 -3 3v3h6v-3a3 3 0 0 0 -3 -3" />
                                                </svg>
                                                <span class="px-xl-3 ">
                                                    <?php
                                                    if ($role['TrangThai'] == TrangThaiNhomQuyen::An->value)
                                                        echo 'Mở khóa';
                                                    else
                                                        echo 'Khóa';
                                                    ?>

                                                </span>
                                            </div>
                                        </li>
                                        <li>
                                            <div x-on:click="
                                        selectedId = <?= $role['MaNhomQuyen'] ?>;
                                        window['delete_modal'].showModal();
                                        " class="dropdown-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                                </svg>
                                                <span class="px-xl-3 ">Xóa</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Open the modal using ID.showModal() method -->
    <dialog id="delete_modal" class="tw-modal">
        <div class="tw-modal-box">
            <h3 class="tw-font-bold tw-text-lg">
                Xác nhận xóa nhóm quyền
            </h3>
            <p class="tw-py-4">
                Bạn có chắc chắn muốn xóa nhóm quyền #<span x-text="selectedId"></span> không?
            </p>
            <div class="tw-modal-action">
                <form method="dialog">

                    <button class="tw-btn">Huỷ</button>
                    <button x-on:click="
                    axios.delete('/api/nhom-quyen/' + selectedId)
                    .then(res => {
                        window.location.reload();
                    }).catch(err => {
                        console.log(err);
                    });
                    " class="tw-btn tw-btn-error tw-text-white">Xoá</button>
                </form>
            </div>
        </div>
        <form method="tw-dialog" class="tw-modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</div>


<?php
require ('app/views/admin/footer.php');


?>