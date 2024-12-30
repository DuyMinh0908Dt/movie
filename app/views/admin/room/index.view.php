<?php
title("Trang quản lý phòng chiếu");
require ('app/views/admin/header.php');


?>

<script>
const inititalTotalItems = <?= $total ?>;
const initial_data = <?= json_encode($rooms) ?>;
const cinemas = <?= json_encode($cinemas) ?>;
</script>
<main class=' tw-h-full tw-w-full tw-overflow-y-auto ' x-data="{
    data: initial_data,
    selected: null,
    showModal: false,
    totalItems: inititalTotalItems,
    query: {
        keyword: '',
        orderBy: 'MaPhongChieu',
        order: 'ASC',
        page: 1,
        limit: 20,
        MaRapChieu: null
    },
    isFetching: false,
    createOrderFn: function (orderBy) {
        if (this.query.orderBy === orderBy) {
            this.query.order = this.query.order === 'ASC' ? 'DESC' : 'ASC';
        } else {
            this.query.orderBy = orderBy;
            this.query.order = 'ASC';
        }
    },
    fetchRelatedData: async function () {
    
        // use lodash 
        const ids = _.uniq(this.data.map(item => item.MaRapChieu));
        const statusIds = _.uniq(this.data.map(item => item.TrangThai));
        console.log(this.data.map(item => item.TrangThai)   );
        const query = {
            ids: ids
        };
        let relatedData =  axios.post('/api/rap/ids',query);
        let statusData =  axios.post('/api/trang-thai/ids', {
            ids: statusIds
        });
        [relatedData, statusData] = await Promise.all([relatedData, statusData]);
        this.data = this.data.map(item => {
            item.TrangThaiObj = statusData.data.data.find(status => status.MaTrangThai === item.TrangThai)
            item.RapChieu = relatedData.data.data.find(relatedItem => relatedItem.MaRapChieu === item.MaRapChieu);
            return item;
        });
    },
    refetch: function(){
        this.data = [];
        const url ='/api/phong-chieu?' + queryString.stringify(this.query);
        this.isFetching = true;
        const queryRs=  axios.get(url).then(response => {
            this.data = response.data.data;
            this.totalItems = response.headers['x-total-count'];
            console.log(response.headers);
            this.fetchRelatedData();
        }).finally(() => {
            this.isFetching = false;
        });
    },
    onConfirmDelete: function (id) {
        console.log(id);
    }
}" x-init="
fetchRelatedData();
$watch('query',async (value) => {
    refetch();
        const queryStr = queryString.stringify(value);
        // set window history
        window.history.pushState({}, '', window.location.pathname + '?' + queryStr);
}, {
    deep: true
});

">
    <dialog id="delete_modal" class="tw-modal">
        <div class="tw-modal-box">
            <h3 class="tw-font-bold tw-text-lg">
                Cảnh báo
            </h3>
            <p class="tw-py-4 tw-text-lg">
                Bạn có chắc chắn muốn xoá phòng #<span class='tw-font-bold' x-text="selected?.MaPhongChieu"></span>
                không?
            </p>

            <div class="modal-action">
                <form method="dialog" class='tw-flex tw-justify-end tw-gap-x-1'>
                    <button class="tw-btn tw-px-4">
                        Huỷ
                    </button>
                    <button x-on:click="
                        axios.delete(`/api/phong-chieu/${selected?.MaPhongChieu}`).then(res=>{
                            toast('Thành công', {
                                position: 'bottom-center',
                                type: 'success',
                                description: 'Xoá suất chiếu thành công',
                            });
                            data = data.filter(x=>x.MaXuatChieu!=selected.MaXuatChieu);
                           window['delete_modal'].close();
                        }).catch(err=>{
                            toast('Thất bại', {
                                position: 'bottom-center',
                                type: 'danger',
                                description: err.response.data.message,
                            });
                        })
                        " class="tw-btn tw-btn-error tw-px-4 tw-text-white" x-on:click="deleteItem()">
                        Xoá
                    </button>
                </form>
            </div>
        </div>
    </dialog>
    <div
        class='tw-m-10 tw-relative tw-flex tw-flex-col tw-bg-white tw-p-5 tw-rounded-2xl tw-shadow-md  tw-bg-clip-border'>

        <div
            class="tw-relative tw-mx-4 tw-mt-4 tw-overflow-hidden tw-text-gray-800 tw-bg-white tw-rounded-none tw-bg-clip-border">
            <div class="tw-flex tw-items-center tw-justify-between tw-gap-8 tw-mb-4">
                <div class='tw-flex tw-gap-x-6 tw-items-center'>
                    <h5
                        class="tw-block tw-font-sans tw-text-2xl tw-antialiased tw-font-semibold tw-leading-snug tw-tracking-normal text-blue-gray-900">
                        Quản lý phòng chiếu
                    </h5>
                    <div class="tw-relative tw-h-10 tw-w-48 tw-min-w-[200px]">
                        <select x-model="query.MaRapChieu"
                            class="tw-peer tw-h-full tw-w-full tw-rounded-[7px] tw-border tw-border-blue-gray-200 tw-border-t-transparent tw-bg-transparent tw-px-3 tw-py-2.5 tw-font-sans tw-text-sm tw-font-normal tw-text-blue-gray-700 tw-outline tw-outline-0 tw-transition-all placeholder-shown:tw-border placeholder-shown:tw-border-blue-gray-200 placeholder-shown:tw-border-t-blue-gray-200 empty:tw-!bg-gray-900 focus:tw-border-2 focus:tw-border-gray-900 focus:tw-border-t-transparent focus:tw-outline-0 disabled:tw-border-0 disabled:tw-bg-gray-50">
                            <option value="" selected>Chọn rạp</option>
                            <template x-for="item in cinemas" :key="item.MaRapChieu">
                                <option :value="item.MaRapChieu" x-text="item.TenRapChieu"></option>
                            </template>
                        </select>
                        <label
                            class="before:tw-content[' '] after:tw-content[' '] tw-pointer-events-none tw-absolute tw-left-0 tw--top-1.5 tw-flex tw-h-full tw-w-full tw-select-none tw-text-[11px] tw-font-normal tw-leading-tight tw-text-blue-gray-400 tw-transition-all before:tw-pointer-events-none before:tw-mt-[6.5px] before:tw-mr-1 before:tw-box-border before:tw-block before:tw-h-1.5 before:tw-w-2.5 before:tw-rounded-tl-md before:tw-border-t before:tw-border-l before:tw-border-blue-gray-200 before:tw-transition-all after:tw-pointer-events-none after:tw-mt-[6.5px] after:tw-ml-1 after:tw-box-border after:tw-block after:tw-h-1.5 after:tw-w-2.5 after:tw-flex-grow after:tw-rounded-tr-md after:tw-border-t after:tw-border-r after:tw-border-blue-gray-200 after:tw-transition-all peer-placeholder-shown:tw-text-sm peer-placeholder-shown:tw-leading-[3.75] peer-placeholder-shown:tw-text-gray-500 peer-placeholder-shown:tw-before peer-placeholder-shown:tw-after peer-focus:tw-text-[11px] peer-focus:tw-leading-tight peer-focus:tw-text-gray-900 peer-focus:tw-before peer-focus:tw-before peer-focus:tw-before peer-focus:tw-after peer-focus:tw-after peer-focus:tw-after peer-disabled:tw-text-transparent peer-disabled:tw-before peer-disabled:tw-after peer-disabled:tw-peer-placeholder-shown">
                            Chọn rạp
                        </label>
                    </div>
                </div>
                <div class="tw-flex tw-flex-col tw-gap-2 shrink-0 sm:tw-flex-row tw-my-2">

                    <a href="/admin/phong-chieu/them" data-ripple-light="true" class=" tw-flex tw-select-none tw-items-center tw-gap-3 tw-rounded-lg tw-bg-blue-700 tw-py-3
                            tw-px-4 tw-text-center tw-align-middle tw-font-sans tw-text-xs tw-font-bold tw-uppercase
                            tw-text-white tw-shadow-md shadow-gray-900/10 tw-transition-all hover:tw-shadow-lg
                            hover:shadow-gray-900/20 focus:tw-opacity-[0.85] focus:tw-shadow-none
                            active:tw-opacity-[0.85] active:tw-shadow-none disabled:tw-pointer-events-none
                            disabled:tw-opacity-50 disabled:tw-shadow-none" type="button">

                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Thêm phòng mới
                    </a>
                </div>
            </div>
            <div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-4 md:tw-flex-row tw-py-3">
                <div class="tw-w-full md:tw-w-72">
                    <div class="tw-relative tw-h-10 tw-w-full tw-min-w-[200px]">
                        <label class="tw-input tw-input-bordered tw-flex tw-items-center tw-gap-2">
                            <input type="text" class="tw-grow" placeholder="Từ khóa tìm kiếm"
                                x-model.debounce.500ms="query.keyword">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="tw-w-4 tw-h-4 tw-opacity-70">
                                <path fill-rule="evenodd"
                                    d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="tw-p-6 tw-px-0 tw-overflow-auto">
            <table class="tw-w-full tw-mt-4 tw-text-left tw-table-auto tw-min-w-max">
                <thead>
                    <tr class='tw-font-semibold tw-text-black'>
                        <th
                            class="tw-p-2 tw-transition-colors tw-cursor-pointer border-y tw-border-blue-gray-100 tw-bg-gray-50/50 hover:tw-bg-gray-50">
                            <p x-on:click="createOrderFn('MaPhongChieu')"
                                class="tw-flex tw-items-center  tw-gap-2  tw-antialiased  tw-leading-none tw-text-blue-gray-900 tw-opacity-70">
                                Mã phòng
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" aria-hidden="true" class="tw-w-4 tw-h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"></path>
                                </svg>
                            </p>
                        </th>
                        <th
                            class="tw-p-2 tw-transition-colors tw-cursor-pointer border-y tw-border-blue-gray-100 tw-bg-gray-50/50 hover:tw-bg-gray-50">
                            <p x-on:click="createOrderFn('TenPhongChieu')"
                                class="tw-flex tw-items-center  tw-gap-2  tw-antialiased  tw-leading-none text-blue-gray-900 tw-opacity-70">
                                Tên phòng
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" aria-hidden="true" class="tw-w-4 tw-h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"></path>
                                </svg>
                            </p>
                        </th>
                        <th
                            class="tw-p-2 tw-transition-colors tw-cursor-pointer border-y tw-border-blue-gray-100 tw-bg-gray-50/50 hover:tw-bg-gray-50">
                            <p x-on:click="createOrderFn('MaRapChieu')"
                                class="tw-flex tw-items-center  tw-gap-2  tw-antialiased  tw-leading-none text-blue-gray-900 tw-opacity-70">
                                Rạp
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" aria-hidden="true" class="tw-w-4 tw-h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"></path>
                                </svg>
                            </p>
                        </th>
                        <th
                            class="tw-p-2 tw-transition-colors tw-cursor-pointer border-y tw-border-blue-gray-100 tw-bg-gray-50/50 hover:tw-bg-gray-50">
                            <p x-on:click="createOrderFn('SoGhe')"
                                class="tw-flex tw-items-center tw-gap-2  tw-antialiased  tw-leading-none text-blue-gray-900 tw-opacity-70">
                                Số ghế
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" aria-hidden="true" class="tw-w-4 tw-h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"></path>
                                </svg>
                            </p>
                        </th>
                        <th
                            class="tw-p-2 tw-transition-colors tw-cursor-pointer border-y tw-border-blue-gray-100 tw-bg-gray-50/50 hover:tw-bg-gray-50">
                            <p
                                class="tw-flex tw-items-center  tw-gap-2  tw-antialiased  tw-leading-none text-blue-gray-900 tw-opacity-70">
                                Màn hình

                            </p>
                        </th>
                        <th
                            class="tw-p-2 tw-transition-colors tw-cursor-pointer border-y tw-border-blue-gray-100 tw-bg-gray-50/50 hover:tw-bg-gray-50">
                            <p x-on:click="createOrderFn('TrangThai')"
                                class="tw-flex tw-items-center  tw-gap-2  tw-antialiased  tw-leading-none text-blue-gray-900 tw-opacity-70">
                                Tình trạng
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" aria-hidden="true" class="tw-w-4 tw-h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"></path>
                                </svg>
                            </p>
                        </th>
                        <th
                            class="tw-p-4 tw-transition-colors tw-cursor-pointer border-y tw-border-blue-gray-100 tw-bg-gray-50/50 hover:tw-bg-gray-50">
                            <p
                                class="tw-flex tw-items-center tw-justify-between tw-gap-2 tw-font-sans tw-text-sm tw-antialiased tw-font-normal tw-leading-none text-blue-gray-900 tw-opacity-70">
                            </p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="isFetching">
                        <td class="   tw-border-b tw-border-gray-50" colspan="7">
                            <div class='tw-w-full tw-flex tw-py-32 tw-items-center tw-justify-center'>
                                <span class="tw-loading tw-loading-dots tw-loading-lg"></span>
                            </div>
                        </td>
                    </template>

                    <template x-for="item in data" :key="item.MaPhongChieu">
                        <tr class="hover:tw-bg-gray-50">
                            <td class="tw-p-2 tw-border-b tw-border-gray-50" x-text="item.MaPhongChieu">

                            </td>
                            <td class="tw-p-2 tw-border-b tw-border-gray-50" x-text="item.TenPhongChieu">
                            </td>
                            <td class="tw-p-2 tw-border-b tw-border-gray-50"
                                x-text="item.RapChieu?.TenRapChieu|| item.MaRapChieu">
                            <td class="tw-p-2 tw-border-b tw-border-gray-50" x-text="item.SoGhe">

                            </td>
                            <td class="tw-p-2 tw-border-b tw-border-gray-50" x-text="item.ManHinh">

                            </td>
                            <td class="tw-p-2 tw-border-b tw-border-gray-50 ">
                                <div class='tw-flex'>

                                    <span x-text="item.TrangThaiObj?.Ten|| item.TrangThai">
                                        Đang hoạt động
                                    </span>

                                </div>
                            </td>
                            <td>

                                <div class="tw-p-2 tw-border-b tw-border-gray-50 tw-flex">
                                    <button tabindex="0" role="button" x-on:click="
                                        const url = `/api/phong-chieu/${item.MaPhongChieu}/can-edit`;
                                        axios.get(url).then(res=>{
                                            if(res.data.data['can_edit']) {
                                                window.location.href = `/admin/phong-chieu/${item.MaPhongChieu}/sua`;
                                            }else{
                                                toast('Thất bại', {
                                                    position: 'bottom-center',
                                                    type: 'danger',
                                                    description: 'Không thể chỉnh sửa phòng chiếu này vì đang hoặc sắp có suất chiếu',
                                                });
                                            }
                                        }).catch(err=>{
                                            toast('Thất bại', {
                                                position: 'bottom-center',
                                                type: 'danger',
                                                description: err.response.data.message,
                                            });
                                        })
                                    " class="tw-btn  p-1 tw-btn-sm tw-btn-warning tw-text-warning tw-aspect-square  tw-btn-ghost"
                                        type="button">
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
                                    </button>
                                    <button tabindex="0" role="button"
                                        x-on:click="selected = item; window['delete_modal'].showModal()"
                                        class="tw-btn tw-btn-sm  p-1 tw-btn-warning tw-text-danger tw-aspect-square  tw-btn-ghost">
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
                                    <button tabindex="0" role="button" x-on:click="axios.patch(`/api/phong-chieu/${item.MaPhongChieu}/toggle-status`).then(res=>{
                                        refetch();
                                    }).catch(err=>{
                                        toast('Thất bại', {
                                            position: 'bottom-center',
                                            type: 'danger',
                                            description: err.response.data.message,
                                        });
                                    })"
                                        class="tw-btn tw-btn-sm  p-1 tw-btn-warning tw-text-base-content tw-aspect-square  tw-btn-ghost">
                                        <svg x-show="item.TrangThai == 7" xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye-off">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" />
                                            <path
                                                d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87" />
                                            <path d="M3 3l18 18" />
                                        </svg>
                                        <svg x-show="item.TrangThai ==6" xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path
                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                        </svg>
                                    </button>
                                </div>
                            </td>

                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="tw-flex tw-items-center tw-p-4 tw-border-t tw-border-gray-50">
            <div
                class="tw-flex tw-grow tw-gap-x-2 tw-items-center tw-font-sans tw-text-sm tw-antialiased tw-font-normal tw-leading-normal text-blue-gray-900">
                <span>
                    Hiển thị:
                </span>
                <script>
                const pageLimits = [10, 20, 30, 40, 50];
                </script>
                <select class="tw-select  tw-select-sm tw-select-bordered tw-w-full tw-max-w-20" x-model="query.limit">
                    <template x-for="limit in pageLimits">
                        <option x-text="limit" :value="limit" :selected="limit == query.limit"></option>
                    </template>
                </select>
                <span x-text="` trên ${totalItems} kết quả`">

                </span>
            </div>

            <div class="tw-flex tw-gap-2">

                <button x-bind:disabled="query.page == 1" x-on:click="query.page = query.page - 1"
                    class="tw-select-none tw-rounded-lg tw-border tw-border-gray-900 tw-py-2 tw-px-4 tw-text-center tw-align-middle tw-font-sans tw-text-xs tw-font-bold  tw-text-gray-900 tw-transition-all hover:tw-opacity-75 focus:tw-ring focus:tw-ring-gray-300 active:tw-opacity-[0.85] disabled:tw-pointer-events-none disabled:tw-opacity-50 disabled:tw-shadow-none"
                    type="button">
                    Trang trước
                </button>
                <div>
                    <div
                        class="tw-relative tw-h-10 tw-max-h-[40px] tw-w-10 tw-max-w-[40px] tw-select-none tw-rounded-lg tw-bg-gray-900 tw-text-center tw-align-middle tw-font-sans tw-text-xs tw-font-medium tw-uppercase tw-text-white tw-shadow-md tw-shadow-gray-900/10 tw-transition-all hover:tw-shadow-lg hover:tw-shadow-gray-900/20 focus:tw-opacity-[0.85] focus:tw-shadow-none active:tw-opacity-[0.85] active:tw-shadow-none disabled:tw-pointer-events-none disabled:tw-opacity-50 disabled:tw-shadow-none">
                        <span
                            class="tw-absolute tw-transform tw--translate-x-1/2 tw--translate-y-1/2 tw-top-1/2 tw-left-1/2"
                            x-text="query.page">1</span>
                    </div>
                </div>
                <button x-bind:disabled="query.page * query.limit >= totalItems"
                    x-on:click="query.page = query.page + 1"
                    class="tw-select-none tw-rounded-lg tw-border tw-border-gray-900 tw-py-2 tw-px-4 tw-text-center tw-align-middle tw-font-sans tw-text-xs tw-font-bold  tw-text-gray-900 tw-transition-all hover:tw-opacity-75 focus:tw-ring focus:tw-ring-gray-300 active:tw-opacity-[0.85] disabled:tw-pointer-events-none disabled:tw-opacity-50 disabled:tw-shadow-none"
                    type="button">
                    Trang sau
                </button>
            </div>
        </div>
    </div>
</main>

<?php
require ('app/views/admin/footer.php');


?>