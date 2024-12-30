<?php
title("Quản lý thể loại");
require("app/views/admin/header.php");
?>

<link rel="stylesheet" href="/public/the-loai/home.css">

<div x-data="dataTable({
    initialQuery:{
        'trang':1,
        'tu-khoa':''
    },
    endpoint:'/api/the-loai'})" x-init="
refresh()
">
    <div x-data="{
        state: 'create',
        selectedCategory: null,
        TenTheLoai: ''
    }" x-init="
    $watch('selectedCategory', value => {
        if (value) {
            TenTheLoai = value.TenTheLoai;
        }
    });
    " style="
          flex-grow: 1;
          flex-shrink: 1;
          overflow-y: auto;
          max-height: 100vh;
        " class="p-5 wrapper">
        <div class="container pt-3 shadow movie">
            <h3 class="mb-3 ms-3">Thể loại phim</h3>
            <!-- thanh tim kiem va nut them phim moi -->
            <div class="px-5 row justify-content-between">
                <div class="col-6">
                    <div class="input-group">
                        <input type="text" name id="searchMovie" placeholder="Nhập tên thể loại phim cần tìm" 
                        x-bind:value="query['tu-khoa']"
                        x-on:keydown.enter="
                                query['tu-khoa']= $event.target.value;
                                refresh({
                                    resetPage:true
                                });
                        "
                        class="form-control" />
                        <button
                        x-on:click="
                            const el = document.getElementById('searchMovie');
                            query['tu-khoa']=el.value;
                                refresh({
                                    resetPage:true
                                });
                        "
                        class="btn btn-outline-secondary align-items-center" type="button" id="searchMovie">
                            <i class="fa-solid fa-magnifying-glass" style="display: flex"></i>
                        </button>
                    </div>
                </div>

                <div class="col-6">
                    <div class="gap-2 d-grid d-md-flex justify-content-md-end">
                        <button x-on:click="
                    state = 'create';
                    selectedCategory = null;
                    " class="btn btn-primary me-md-2" type="button" data-bs-toggle="modal" data-bs-target="#type-of-film-modal">
                            Thêm thể loại phim mới
                        </button>
                    </div>
                </div>
            </div>

            <!-- chua bang phim -->
            <div class="m-3 row table-responsive" style="flex: 1">
                <table class="table align-middle table-hover" style="height: 100%">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">
                                <div class="col-name">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="m-2 bi bi-sort-numeric-down" viewBox="0 0 16 16" id="sortNumDown_icon">
                                        <path d="M12.438 1.668V7H11.39V2.684h-.051l-1.211.859v-.969l1.262-.906h1.046z" />
                                        <path fill-rule="evenodd" d="M11.36 14.098c-1.137 0-1.708-.657-1.762-1.278h1.004c.058.223.343.45.773.45.824 0 1.164-.829 1.133-1.856h-.059c-.148.39-.57.742-1.261.742-.91 0-1.72-.613-1.72-1.758 0-1.148.848-1.835 1.973-1.835 1.09 0 2.063.636 2.063 2.687 0 1.867-.723 2.848-2.145 2.848zm.062-2.735c.504 0 .933-.336.933-.972 0-.633-.398-1.008-.94-1.008-.52 0-.927.375-.927 1 0 .64.418.98.934.98" />
                                        <path d="M4.5 2.5a.5.5 0 0 0-1 0v9.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L4.5 12.293z" />
                                    </svg>

                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="m-2 bi bi-sort-numeric-up d-none" viewBox="0 0 16 16" id="sortNumUp_icon">
                                        <path d="M12.438 1.668V7H11.39V2.684h-.051l-1.211.859v-.969l1.262-.906h1.046z" />
                                        <path fill-rule="evenodd" d="M11.36 14.098c-1.137 0-1.708-.657-1.762-1.278h1.004c.058.223.343.45.773.45.824 0 1.164-.829 1.133-1.856h-.059c-.148.39-.57.742-1.261.742-.91 0-1.72-.613-1.72-1.758 0-1.148.848-1.835 1.973-1.835 1.09 0 2.063.636 2.063 2.687 0 1.867-.723 2.848-2.145 2.848zm.062-2.735c.504 0 .933-.336.933-.972 0-.633-.398-1.008-.94-1.008-.52 0-.927.375-.927 1 0 .64.418.98.934.98" />
                                        <path d="M4.5 13.5a.5.5 0 0 1-1 0V3.707L2.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.5.5 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L4.5 3.707z" />
                                    </svg>
                                    Mã thể loại phim
                                </div>
                            </th>
                            <th scope="col">
                                <div class="col-name">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="m-2 bi bi-sort-alpha-down" viewBox="0 0 16 16" id="sortAlphaDown_icon">
                                        <path fill-rule="evenodd" d="M10.082 5.629 9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371zm1.57-.785L11 2.687h-.047l-.652 2.157z" />
                                        <path d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645zM4.5 2.5a.5.5 0 0 0-1 0v9.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L4.5 12.293z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="m-2 bi bi-sort-alpha-up d-none" viewBox="0 0 16 16" id="sortAlphaUp_icon">
                                        <path fill-rule="evenodd" d="M10.082 5.629 9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371zm1.57-.785L11 2.687h-.047l-.652 2.157z" />
                                        <path d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645zm-8.46-.5a.5.5 0 0 1-1 0V3.707L2.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.5.5 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L4.5 3.707z" />
                                    </svg>
                                    Tên thể loại phim
                                </div>
                            </th>

                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                 
                        <template x-for="item in data">
                            <tr>
                                <th scope="row"
                                x-text="item.MaTheLoai"
                                >

                                </th>
                                <td
                                x-text="item.TenTheLoai"
                                >
                                    Hành động
                                </td>
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                    </svg>
                                                    <span class="px-xl-3" x-on:click="
                                            state = 'edit';
                                            // tuỳ chỉnh dữ liệu
                                            selectedCategory = {...item};
                                            $('#type-of-film-modal').modal('show');
                                            ">Sửa</span>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="dropdown-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                                    </svg>
                                                    <span class="px-xl-3" x-on:click="
                                                selectedCategory = {
                                                    ...item
                                                }
                                                window['deleteModal'].showModal();">
                                                        Xóa</span>
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
            <!-- het chua bang phim -->

            <!-- thanh phan trang -->
            <div class="d-flex justify-content-end column-gap-3 tw-mb-3">
                <div class="d-flex input-group h-50 w-25">
                    <label class="bg-white border-0 input-group-text " for="inputGroupSelect01">Hiển thị</label>
                    <select
                    x-model="query.limit"
                    class="rounded form-select" id="inputGroupSelect01">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>

                <div>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item"
                            x-on:click="
                            if(query.trang !='1'){
                                query.trang = Number(query.trang)-1;
                                refresh()
                            }
                            "
                            >
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#"
                            x-text="query['trang']||1"
                            >2</a></li>
                            <li class="page-item"
                            x-on:click="

                                query.trang = Number(query.trang)+1;
                                refresh()
                            "
                            >
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

        <!-- modal khoi tao the loai moi -->
        <div class="modal fade bs-example-modal-lg" id="type-of-film-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header position-relative">
                        <h4 class="modal-title" id="edit-category-title" x-text="state == 'create' ? 'Thêm thể loại phim mới' : 'Sửa thể loại phim #'+selectedCategory?.MaTheLoai">

                        </h4>
                        <button type="button" class="close close-modal position-absolute" data-dismiss="modal" aria-hidden="true" id="btn-close-modal-tof">
                            ×
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container bg-white create-tof">
                            <div class="mt-2 row d-flex">
                                <label for="tof-name" class="col-xl-2">
                                    Tên thể loại phim
                                </label>
                                <div class="p-0 input-group has-validation col-xl-10">
                                    <input type="text" x-model="TenTheLoai" class="form-control " id="tof-name" aria-describedby="tof-price-feedback" required />
                                    <div id="tof-name-feedback" class="invalid-feedback">
                                        Please choose a username.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancel-modal-tof">
                            Huỷ
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-save-modal-tof"
                        x-on:click="
                           let api ='/api/the-loai'
                           if(state!='create'){
                            api ='/api/the-loai'+'/'+selectedCategory.MaTheLoai;
                           }
                           console.log(api);
                           const data={
                            TenTheLoai:TenTheLoai
                           }
                           console.log(data)
                            const method = state =='create'?'post':'put';
                            axios[method](api,data)
                            .then(res=>{
                                toast('Thanh cong', {
                                    position: 'bottom-center',
                                    type: 'success'
                                });
                                refresh();
                            }).
                            catch(e=>{
                                toast('That Bai', {
                                    position: 'bottom-center',
                                    type: 'danger'
                                });
                            })
                        "
                        x-text="state=='create'?'Thêm':'Cập nhật'"
                        >
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <dialog id="deleteModal" class=" tw-modal">
            <div class="tw-modal-box">
                <h3 class="tw-font-bold tw-text-lg">
                    Bạn có chắc chắn muốn xóa thể loại phim #<span x-text="selectedCategory?.MaTheLoai"></span> không?
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
    </div>
</div>

<?php
script("/public/the-loai/main.js");
require("app/views/admin/footer.php");
?>