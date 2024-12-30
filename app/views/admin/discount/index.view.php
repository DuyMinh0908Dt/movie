<?php
title("Quản lý khuyến mãi");
require ('app/views/admin/header.php');

?>

<script>
const trangthais = <?= json_encode($statuses) ?>;
</script>


<link rel="stylesheet" href="/public/tiendat/showtime.css">

<div x-data="dataTable({
    endpoint:'/api/khuyen-mai',
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

    }" class="shadow showtime container-fluid">
        <dialog id="delete_modal" class="tw-modal">
            <div class="tw-modal-box">
                <h3 class="tw-font-bold tw-text-lg">
                    Cảnh báo
                </h3>
                <p class="tw-py-4 tw-text-lg">
                    Bạn có chắc chắn muốn xoá khuyến mãi #<span class='tw-font-bold'
                        x-text="selected?.MaKhuyenMai"></span> không?
                </p>

                <div class="modal-action">
                    <form method="dialog" class='tw-flex tw-justify-end tw-gap-x-1'>
                        <button class="tw-btn tw-px-4">
                            Huỷ
                        </button>
                        <button x-on:click="
                        axios.delete(`/api/khuyen-mai/${selected?.MaKhuyenMai}`).then(res=>{
                            toast('Thành công', {
                                position: 'bottom-center',
                                type: 'success',
                                description: 'Xoá khuyến mãi thành công',
                            })
                            data = data.filter(x=>x.MaKhuyenMai!=selected.MaKhuyenMai);
                            window['delete_modal'].close();
                        }).catch(err=>{
                            toast('Thất bại', {
                                position: 'bottom-center',
                                type: 'danger',
                                description: err.response.data.message,
                            });
                            window['delete_modal'].close();
                        })
                        " class="tw-btn tw-btn-error tw-px-4 tw-text-white" x-on:click="deleteItem()">
                            Xoá
                        </button>
                    </form>
                </div>
            </div>
        </dialog>
        <!-- thanh phân loại phim -->
        <div class="mb-4 border-bottom">
            <div>
                <input type="button" class="btn button fw-semibold" value="Tất cả"
                    :class="{'button-nav-active': query['trang-thais']==undefined}"
                    x-on:click="query['trang-thais']=undefined;refresh()">
                <?php foreach ($statuses as $status): ?>
                <input type="button" class="btn button fw-semibold" value="<?= $status['Ten'] ?>"
                    :class="{'button-nav-active': query['trang-thais']?.includes('<?= $status['MaTrangThai'] ?>')}"
                    x-on:click="query['trang-thais']=['<?= $status['MaTrangThai'] ?>'];refresh()">
                <?php endforeach; ?>
            </div>
        </div>
        <!-- hết thanh phân loại phim -->

        <!-- thanh tìm kiếm và nút thêm phim mới -->
        <div class="px-5 row justify-content-between">
            <div class="col-6 tw-flex tw-items-center">
                <div class="input-group">
                    <input x-model="query['tu-khoa']" x-on:keydown.enter="refresh" type="text" name id="searchMovie"
                        placeholder="Nhập thông tin cần tìm" class="form-control">
                    <button x-on:click="refresh" class="btn btn-outline-secondary align-items-center" type="button"
                        id="searchMovie">
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
                        <ul class="dropdown-menu">
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
                                        <label class="form-label" for>Khoảng thời gian chiếu</label>
                                    </div>

                                    <div class="row d-flex tw-items-center flex-nowrap">
                                        <div class="col">
                                            <input x-model="query['khoang-thoi-gian-tu']" class="form-control"
                                                type="date">
                                        </div>
                                        <span class='col'>đến</span>
                                        <div class="col">
                                            <input x-model="query['khoang-thoi-gian-den']" class="form-control"
                                                type="date">
                                        </div>
                                    </div>
                                </form>
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
                    <a href="/admin/khuyen-mai/them" class="btn btn-primary me-md-2" type="button">Thêm khuyến mãi
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
                            <div class="col-name " x-on:click="createOrderFn('MaKhuyenMai')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Mã khuyến mãi
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name" x-on:click="createOrderFn('TenKhuyenMai')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Tên khuyến mãi
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name" x-on:click="createOrderFn('NgayBatDau')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Ngày bắt đầu
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name" x-on:click="createOrderFn('NgayKetThuc')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Ngày kết thúc
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name" x-on:click="createOrderFn('GiaTriGiam')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Giá trị
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name" x-on:click="createOrderFn('GioiHanSuDung')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Giới hạn sử dụng
                            </div>
                        </th>
                        <th scope="col">
                            <div class="col-name" x-on:click="createOrderFn('GioiHanTrenKhachHang')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Giới hạn trên khách hàng
                            </div>
                        </th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    <template x-if="isFetching">
                        <tr>
                            <td class=" tw-border-b tw-border-gray-50" colspan="9">
                                <div class='tw-w-full tw-flex tw-py-32 tw-items-center tw-justify-center'>
                                    <span class="tw-loading tw-loading-dots tw-loading-lg"></span>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-for="item in data" :key="item.MaKhuyenMai">
                        <tr>
                            <th scope="row">
                                <span x-text="item.MaKhuyenMai"></span>
                            </th>
                            <td>
                                <span x-text="item.TenKhuyenMai"></span>
                            </td>
                            <td>
                                <span
                                    x-text="item.NgayBatDau?dayjs(item.NgayBatDau).format('DD/MM/YYYY'):'Không có'"></span>
                            </td>
                            <td>
                                <span
                                    x-text="item.NgayKetThuc?dayjs(item.NgayKetThuc).format('DD/MM/YYYY'):'Không có'"></span>
                            </td>
                            <td>
                                <span x-text="item.GiaTriGiam<=100?item.GiaTriGiam+'%':toVnd(item.GiaTriGiam)"></span>
                            </td>
                            <td>
                                <span x-text="item.GioiHanSuDung||'Không giới hạn'"></span>
                            </td>
                            <td>
                                <span x-text="item.GioiHanTrenKhachHang||'Không giới hạn'"></span>
                            </td>
                            <td>
                                <span
                                    x-text="trangthais.find(x=>x.MaTrangThai==item.TrangThai)?.Ten||'Không xác định'"></span>
                            </td>
                            <td>
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
                                    <ul class="dropdown-menu" x-on:click="selected = item">
                                        <li>
                                            <a :href="'/admin/khuyen-mai/'+item.MaKhuyenMai+'/sua'"
                                                class="dropdown-item !tw-text-yellow-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                                <span class="px-xl-3 ">
                                                    Sửa
                                                </span>
                                            </a>
                                        </li>
                                        <li x-on:click="
                                        const res = await axios.patch(`/api/khuyen-mai/${item.MaKhuyenMai}/chuyen-trang-thai`);
                                            toast('Thành công', {
                                                position: 'bottom-center',
                                                type: 'success',
                                            });
                                            refresh();
                                        ">
                                            <div class="dropdown-item ">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="tw-w-6 tw-h-6">
                                                    <path fill-rule="evenodd"
                                                        d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z"
                                                        clip-rule="evenodd" />
                                                </svg>

                                                <span class="px-xl-3 "
                                                    x-text="item.TrangThai==12?'Dừng khuyến mãi':'Tiếp tục khuyến mãi'"></span>
                                            </div>
                                        </li>
                                        <li x-on:click="
                                        window['delete_modal'].showModal();
                                        ">
                                            <div class="dropdown-item !tw-text-red-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="tw-w-6 tw-h-6">
                                                    <path
                                                        d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z" />
                                                    <path fill-rule="evenodd"
                                                        d="m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.133 2.845a.75.75 0 0 1 1.06 0l1.72 1.72 1.72-1.72a.75.75 0 1 1 1.06 1.06l-1.72 1.72 1.72 1.72a.75.75 0 1 1-1.06 1.06L12 15.685l-1.72 1.72a.75.75 0 1 1-1.06-1.06l1.72-1.72-1.72-1.72a.75.75 0 0 1 0-1.06Z"
                                                        clip-rule="evenodd" />
                                                </svg>


                                                <span class="px-xl-3 ">
                                                    Xoá khuyến mãi
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
        <!-- hết danh sách phim -->

        <!-- thanh phan trang -->
        <div class="d-flex justify-content-end column-gap-3 tw-mb-3">
            <div class="d-flex input-group h-50 w-25">
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