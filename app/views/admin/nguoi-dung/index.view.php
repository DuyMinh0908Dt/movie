<?php
use App\Dtos\LoaiTaiKhoan;

title("Quản lý người dùng");
require ('app/views/admin/header.php');
?>

<script>
</script>


<link rel="stylesheet" href="/public/tiendat/showtime.css">

<div x-data="dataTable({
    endpoint:'/api/nguoi-dung',
    initialQuery :{
        'trang': 1,
        'limit': 10,
    }
})" style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="p-5 wrapper">
    <div x-data="{
        selected:null,
        onApllyFilter(){
            console.log(query);
            refresh({
                   resetPage: true,
                });
        },
        onClearFilter(){
            query={};
            $nextTick(()=>{
                refresh();
            })
        },
       

}" class="shadow showtime container-fluid">
        <dialog id="delete_modal" class="tw-modal">
            <div class="tw-modal-box">
                <h3 class="tw-font-bold tw-text-lg">
                    Cảnh báo
                </h3>
                <p class="tw-py-4 tw-text-lg">
                    Bạn có chắc chắn muốn xoá người dùng #<span class='tw-font-bold'
                        x-text="selected?.MaNguoiDung"></span> không?
                </p>

                <div class="modal-action">
                    <form method="dialog" class='tw-flex tw-justify-end tw-gap-x-1'>
                        <button class="tw-btn tw-px-4">
                            Huỷ
                        </button>
                        <button class="tw-btn tw-btn-error tw-px-4 tw-text-white" x-on:click="
                            let id=selected.MaNguoiDung;
                            axios.delete('/api/nguoi-dung/'+id).then(()=>
                        {
                            toast('Xóa thành công', {
                            position: 'bottom-center',
                            type: 'success'
                        });
                        refresh();
                        return;
                        }).catch((e)=>{
                            toast('Thất bại', {
                            position: 'bottom-center',
                            type: 'danger',
                            description: e.response.data.message
                        });
                        return;}).finally(()=>{
                            window['delete_modal'].close();
                        })

                        ">
                            Xoá
                        </button>
                    </form>
                </div>
            </div>
        </dialog>
        <div class='tw-mt-3'></div>
        <!-- thanh tìm kiếm và nút thêm phim mới -->
        <div class="px-5 row justify-content-between">
            <div class="col-6 tw-flex tw-items-center">
                <div class="input-group">
                    <input x-on:keyup.enter="onApllyFilter()" x-model.debounce.500ms="query['tu-khoa']" type="text" name
                        id="searchMovie" placeholder="Nhập thông tin cần tìm" class="form-control">
                    <button x-on:click="onApllyFilter()" class="btn btn-outline-secondary align-items-center"
                        type="button" id="searchMovie">
                        <i class="fa-solid fa-magnifying-glass" style="display: flex;"></i>
                    </button>

                </div>

                <div class="gap-2 mx-2 d-grid d-md-flex justify-content-md-end tw-shrink-0">
                    <div class="dropdown">
                        <button data-bs-auto-close="outside" class="border-0 btn fw-medium " data-bs-toggle="dropdown"
                            aria-expanded="false">
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
                        <ul class="dropdown-menu tw-min-w-80">
                            <li>
                                <div class="px-2 pb-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <label class="fw-semibold" for>Lọc</label>
                                    </div>

                                    <div class="d-flex flex-nowrap">
                                        <button x-on:click="onClearFilter()" class="mx-2 btn btn-light">Xóa
                                            lọc</button>
                                        <button x-on:click="onApllyFilter()" class="btn btn-primary">Áp
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
                                        <label class="form-label">Điểm tích luỹ từ</label>
                                    </div>

                                    <div class="row d-flex tw-items-center flex-nowrap">
                                        <div class="col tw-grow">
                                            <input class="form-control tw-w-full" x-model="query['diem-tich-luy-tu']"
                                                type="number">
                                        </div>
                                        <span class='col tw-grow-0'>đến</span>
                                        <div class="col tw-grow">
                                            <input class="form-control tw-w-full" x-model="query['diem-tich-luy-den']"
                                                type="number">
                                        </div>
                                    </div>
                                </form>
                            </li>

                            <li>
                                <hr class="m-0 dropdown-divider">
                            </li>


                            <li>
                                <div class="p-2 d-flex tw-flex-col">
                                    <div class="row">
                                        <label class="form-label" for>Loại người dùng</label>
                                    </div>
                                    <select class="selectpicker !tw-w-full" x-model="query['loai-tai-khoan']">
                                        <option value="">Tất cả</option>
                                        <option value="NULL">Chưa xác định</option>
                                        <option value="<?= LoaiTaiKhoan::NhanVien->value ?>">
                                            Nhân viên</option>
                                        <option value="<?= LoaiTaiKhoan::KhachHang->value ?>">
                                            Khách hàng</option>

                                    </select>
                                </div>
                            </li>
                            <li>
                                <hr class="m-0 dropdown-divider">
                            </li>

                        </ul>
                    </div>
                </div>

            </div>

            <div class="col-6">
                <div class="gap-2 d-grid d-md-flex justify-content-md-end">
                    <a href="/admin/nguoi-dung/them" class="btn btn-primary me-md-2" type="button">Thêm người dùng
                        mới</a>
                </div>
            </div>
        </div>
        <!-- hết thanh tìm kiếm và nút thêm phim mới -->

        <!-- danh sách phim -->
        <div class="m-3 row table-responsive" style="flex: 1;">
            <table class="table align-middle table-hover" style="height: 100%;">
                <!-- header của table -->
                <thead class="table-light">
                    <tr>
                        <th scope="col">
                            <div class="col-name ">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Mã người dùng
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
                                Tên người dùng
                            </div>
                        </th>
                        <th scope="col">

                            Email
                        </th>

                        <th scope="col">
                            Ngày sinh
                        </th>
                        <th scope="col">
                            Điểm tích lũy
                        </th>
                        <th scope="col">
                            Mã tài khoản
                        </th>
                        <th scope="col">

                        </th>

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

                    <template x-for="item in data">
                        <tr>
                            <td x-text="item.MaNguoiDung"></td>
                            <td x-text="item.TenNguoiDung"></td>
                            <td x-text="item.Email"></td>
                            <td x-text="item.NgaySinh"></td>
                            <td x-text="item.DiemTichLuy"></td>
                            <td x-text="item.MaTaiKhoan"></td>
                            <td>


                                <div class="tw-p-2 tw-border-b tw-border-gray-50 tw-flex">
                                    <a tabindex="0" role="button" :href="`/admin/nguoi-dung/${item.MaNguoiDung}/sua`"
                                        class=" p-1 tw-btn tw-btn-sm tw-btn-warning tw-text-warning tw-aspect-square
                                        tw-btn-ghost" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                            <path
                                                d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                            <path d="M16 5l3 3" />
                                        </svg>
                                    </a>
                                    <button tabindex="0" role="button"
                                        x-on:click="selected = item; window['delete_modal'].showModal()"
                                        class="p-1 tw-btn tw-btn-sm tw-btn-warning tw-text-danger tw-aspect-square tw-btn-ghost">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 7l16 0" />
                                            <path d="M10 11l0 6" />
                                            <path d="M14 11l0 6" />
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                        </svg>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <!-- hết danh sách phim -->

        <!-- thanh phan trang -->
        <div class="d-flex justify-content-end column-gap-3">
            <div class="d-flex input-group h-50 w-25">
                <label class="bg-white border-0 input-group-text " for="inputGroupSelect01">Hiển thị</label>
                <select class="rounded form-select" id="inputGroupSelect01" x-model="query.limit">
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                </select>
            </div>

            <div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item" x-on:click="
                            query.trang=Number(query.trang)-1;
                            refresh();
                        ">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <!-- <template x-for="item in getArrayPages()" :key="item"> -->
                        <!-- <li x-on:click="query['trang']=item;refresh()" class="page-item"
                                :class="{'active': query['trang']==item}">
                                <a class="page-link" href="#" x-text="item"></a>
                            </li> -->
                        <!-- </template> -->
                        <li class="page-item" x-on:click="
                            query.trang=Number(query.trang)+1;
                            refresh();
                        ">
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
</div>

<script>
const closing_btn = document.getElementById('closing');
const opening_btn = document.getElementById('opening');
const soldout_btn = document.getElementById('soldout');
const cancel_btn = document.getElementById('cancel');
const sortNumDown_id_icon = document.getElementById('sortNumDown_id_icon');
const sortNumUp_id_icon = document.getElementById('sortNumUp_id_icon');
const sortAlphaDown_rap_icon = document.getElementById('sortAlphaDown_rap_icon');
const sortAlphaUp_rap_icon = document.getElementById('sortAlphaUp_rap_icon');

function optionOfList(button) {
    if (button.id == 'closing') {
        closing_btn.classList.add('button-nav-active');
        setupButtonInavActive(opening_btn);
        setupButtonInavActive(soldout_btn);
        setupButtonInavActive(cancel_btn);
    } else if (button.id == 'opening') {
        opening_btn.classList.add('button-nav-active');
        setupButtonInavActive(closing_btn);
        setupButtonInavActive(soldout_btn);
        setupButtonInavActive(cancel_btn);
    } else if (button.id == 'soldout') {
        soldout_btn.classList.add('button-nav-active');
        setupButtonInavActive(closing_btn);
        setupButtonInavActive(opening_btn);
        setupButtonInavActive(cancel_btn);
    } else {
        cancel_btn.classList.add('button-nav-active');
        setupButtonInavActive(closing_btn);
        setupButtonInavActive(opening_btn);
        setupButtonInavActive(soldout_btn);
    }
}

function setupButtonInavActive(button) {
    button.classList.remove('button-nav-active');
}
</script>
<?php
require ('app/views/admin/footer.php');


?>