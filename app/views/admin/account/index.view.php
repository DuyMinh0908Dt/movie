<?php
use App\Dtos\LoaiTaiKhoan;

title("Quản lý tài khoản");
require ('app/views/admin/header.php');
?>
<script>
const statuses = <?= json_encode($statuses) ?>;
</script>
<link rel="stylesheet" href="/public/tiendat/account.css">
<div x-data="dataTable({
    endpoint:'/api/tai-khoan',
    initialQuery :{
        'trang': 1,
        'limit': 50,
        'loai-tai-khoan': <?= LoaiTaiKhoan::KhachHang->value ?>,
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
    " class="shadow account container-fluid">
        <div class="mb-4 border-bottom">
            <div>
                <input type="button" id="customer" class="btn button button-nav-active fw-semibold" value="Khách hàng"
                    x-on:click="query['loai-tai-khoan'] = <?= LoaiTaiKhoan::KhachHang->value ?>; onApllyFilter();optionOfList($el)">
                <input type="button" id="staff" class="btn button fw-semibold" value="Nhân viên"
                    x-on:click="query['loai-tai-khoan'] = <?= LoaiTaiKhoan::NhanVien->value ?>; onApllyFilter();optionOfList($el)">
            </div>
        </div>

        <!-- thanh tim kiem va nut them phim moi -->
        <div class="px-5 row justify-content-between">
            <div class="col-6">
                <div class="input-group">
                    <input x-on:keydown.enter="query['tu-khoa'] = $event.target.value; onApllyFilter()" type="text"
                        placeholder="Nhập thông tin cần tìm" class="form-control">
                    <button x-on:click="query['tu-khoa'] = $event.target.previousElementSibling.value; onApllyFilter()"
                        class="btn btn-outline-secondary align-items-center" type="button" id="searchMovie">
                        <i class="fa-solid fa-magnifying-glass" style="display: flex;"></i>
                    </button>
                </div>
            </div>
            <template x-if="query['loai-tai-khoan']==<?= LoaiTaiKhoan::NhanVien->value ?>">

                <div class="col-6">
                    <a href="/admin/tai-khoan/them" class="gap-2 d-grid d-md-flex justify-content-md-end">
                        <button class="btn btn-primary me-md-2" type="button">Thêm tài khoản nhân viên</button>
                    </a>
                </div>

            </template>

        </div>
        <div class="m-3 row table-responsive" style="flex: 1;">
            <table class="table align-middle table-hover" style="height: 100%;">
                <thead class="table-light">
                    <tr>
                        <th scope="col">
                            <div class="col-name" x-on:click="createOrderFn('MaTaiKhoan')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Mã tài khoản
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name" x-on:click="createOrderFn('TenDangNhap')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Tên đăng nhập
                            </div>
                        </th>
                        <th scope="col" x-on:click="createOrderFn('MaNguoiDung')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                            </svg>
                            Mã người dùng
                        </th>
                        <th scope="col">

                            Tên người dùng
                        </th>
                        <th scope="col" x-show="query['loai-tai-khoan']==<?= LoaiTaiKhoan::NhanVien->value ?>">

                            Nhóm quyền
                        </th>
                        <th scope="col" x-on:click="createOrderFn('TrangThai')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                            </svg>
                            Trạng thái
                        </th>
                        <th scope="col"></th>
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
                    <template x-for="item in data" :key="item.MaTaiKhoan">
                        <tr>
                            <td x-text="item.MaTaiKhoan"></td>
                            <td x-text="item.TenDangNhap"></td>
                            <td x-text="item.MaNguoiDung"></td>
                            <td x-text="item.TenNguoiDung"></td>
                            <td x-show="query['loai-tai-khoan']==<?= LoaiTaiKhoan::NhanVien->value ?>"
                                x-text="item.TenNhomQuyen||'Chưa có'"></td>
                            <td x-text="statuses.find(status=>status.MaTrangThai==item.TrangThai)?.Ten">
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
                                            <a class="dropdown-item" :href="'/admin/tai-khoan/'+item.MaTaiKhoan+'/sua'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                    <path
                                                        d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                    <path d="M16 5l3 3" />
                                                </svg>
                                                <span class="px-xl-3 ">Sửa người dùng</span>
                                            </a>
                                        </li>

                                        <li x-show="query['loai-tai-khoan']==<?= LoaiTaiKhoan::NhanVien->value ?>">
                                            <div class="dropdown-item" x-on:click="
                                                selected ={...item};
                                                $nextTick(()=>{
                                                    window['setRoleModal'].showModal();
                                                })
                                            ">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-accessible">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                    <path d="M10 16.5l2 -3l2 3m-2 -3v-2l3 -1m-6 0l3 1" />
                                                    <circle cx="12" cy="7.5" r=".5" fill="currentColor" />
                                                </svg>
                                                <span class="px-xl-3 ">
                                                    Phân quyền
                                                </span>
                                            </div>
                                        </li>
                                        <li x-show="query['loai-tai-khoan']==<?= LoaiTaiKhoan::NhanVien->value ?>">
                                            <div class="dropdown-item" x-on:click="
                                                selected = {...item};
                                                $nextTick(()=>{
                                                    window['setPasswordModal'].showModal();
                                                })
                                            ">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-fingerprint">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M18.9 7a8 8 0 0 1 1.1 5v1a6 6 0 0 0 .8 3" />
                                                    <path d="M8 11a4 4 0 0 1 8 0v1a10 10 0 0 0 2 6" />
                                                    <path d="M12 11v2a14 14 0 0 0 2.5 8" />
                                                    <path d="M8 15a18 18 0 0 0 1.8 6" />
                                                    <path d="M4.9 19a22 22 0 0 1 -.9 -7v-1a8 8 0 0 1 12 -6.95" />
                                                </svg>
                                                <span class="px-xl-3 ">
                                                    Đặt mật khẩu
                                                </span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-item " x-on:click="
                                                selected = item;
                                                $nextTick(()=>{
                                                    window['deleteAccountModal'].showModal();
                                                })
                                            ">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                                </svg>
                                                <span class="px-xl-3 ">Xóa</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-item " x-on:click="
                                            axios.patch(`/api/tai-khoan/${item.MaTaiKhoan}/chuyen-trang-thai`)
                                            .then(()=>{
                                                refresh();
                                                toast('Thay đổi trạng thái thành công',{
                                                    position: 'bottom-center',
                                                    type: 'success'
                                                })
                                            })
                                            ">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="currentColor"
                                                    class="icon icon-tabler icons-tabler-filled icon-tabler-lock">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M12 2a5 5 0 0 1 5 5v3a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-6a3 3 0 0 1 3 -3v-3a5 5 0 0 1 5 -5m0 12a2 2 0 0 0 -1.995 1.85l-.005 .15a2 2 0 1 0 2 -2m0 -10a3 3 0 0 0 -3 3v3h6v-3a3 3 0 0 0 -3 -3" />
                                                </svg>
                                                <span class="px-xl-3 ">Khoá</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                    </template>

                </tbody>
            </table>
        </div>
        <dialog id="deleteAccountModal" class=" tw-modal">
            <div class="tw-modal-box">
                <h3 class="tw-font-bold tw-text-lg">
                    Bạn có chắc chắn muốn xóa tài khoản này không?
                </h3>
                <p class="tw-py-4">
                    Bạn sẽ không thể hoàn tác hành động này.
                </p>
                <div class="tw-modal-action">
                    <form method="dialog">
                        <button class="tw-btn">Huỷ</button>
                        <button class="tw-btn tw-btn-error tw-text-white">Xoá</button>
                    </form>
                </div>
            </div>
            <form method="dialog" class="tw-modal-backdrop">
                <button>close</button>
            </form>
        </dialog>

        <dialog id="setRoleModal" class=" tw-modal">
            <div class="tw-modal-box">
                <h3 class="tw-font-bold tw-text-lg">
                    Phân quyền cho tài khoản
                </h3>
                <div class="tw-py-4">
                    <select x-model="selected.role" class="form-select">
                        <option value="">Chọn nhóm quyền</option>

                        <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['MaNhomQuyen'] ?>">
                            <?= $role['TenNhomQuyen'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="tw-modal-action">
                    <form method="dialog">
                        <button class="tw-btn">Huỷ</button>
                        <button class="tw-btn tw-btn-primary" type="button" x-on:click="axios.patch(`/api/tai-khoan/${selected.MaTaiKhoan}/nhom-quyen`,{role:selected.role})
                    .then(()=>{
                        refresh();
                        toast('Phân quyền thành công',{
                            position: 'bottom-center',
                            type: 'success'
                        })
                    })">Lưu</button>
                    </form>
                </div>
            </div>
            <form method="dialog" class="tw-modal-backdrop">
                <button>close</button>

            </form>
        </dialog>

        <!-- thanh phan trang -->
        <div class="d-flex justify-content-end column-gap-3">
            <div class="d-flex input-group h-50 w-25 tw-mb-3">
                <label class="bg-white border-0 input-group-text " for="inputGroupSelect01">Hiển thị</label>
                <select x-model="query['limit']" class="rounded form-select" id="inputGroupSelect01">
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                </select>
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
        <!-- het thanh phan trang -->
    </div>
</div>
<!-- javascript -->

<script>
const customer_btn = document.getElementById('customer');
const staff_btn = document.getElementById('staff');

function optionOfList(button) {
    button.remove
    if (button.id == 'customer') {
        customer_btn.classList.add('button-nav-active');
        setupButtonInavActive(staff_btn);
    } else {
        staff_btn.classList.add('button-nav-active');
        setupButtonInavActive(customer_btn);
    }
}

function setupButtonInavActive(button) {
    button.classList.remove('button-nav-active');
}
</script>
<?php
require ('app/views/admin/footer.php');


?>