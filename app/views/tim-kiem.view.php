<?php
title("Kết quả tìm kiếm");
require ('partials/head.php');
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker-bs5.min.css">
<style>
:root {}

.header2 {
    background-color: rgb(4, 81, 116);
}

.heading {
    border: solid rgb(243, 235, 215);
    border-width: 5px 0 5px 0;
}

.product {}

.movie-item {
    width: 25%;
}

img {
    object-fit: cover;
    max-width: 100%;
}

.button {
    color: rgba(228, 140, 68);
    background-color: rgb(27, 45, 68);
}
</style>
<div x-data="{
    data: [],
    page: 1,
    limit: 12,
    total: 0,
    listPage: [],
    isLoading: false,
    isShowFilter: false,
    onClearFilter: function(){
        const elements = document.querySelectorAll('.theloai:checked');
        elements.forEach(e => {
            e.checked = false;
        });
        const elements2 = document.querySelectorAll('.rapchieu:checked');
        elements2.forEach(e => {
            e.checked = false;
        });
        $refs.keywordInput.value = '';
        $refs.showFromInput.value = '';
        $refs.showToInput.value = '';
        $refs.durrationFromInput.value = '';
        $refs.durrationToInput.value = '';
        $refs.sortByInput.value = 'Phim.TenPhim';
        $refs.sortInput.value = 'ASC';
        this.onSearch();
    },
    onSearch: async function(){
            this.isLoading = true;
            this.data = [];
            const keyword = $refs.keywordInput.value;
                const theloais = Array.from(document.querySelectorAll('.theloai:checked')).map(e => e.value);
                document.querySelectorAll('.search-input').forEach(e => {
                    e.value = keyword;
                });
                const rapchieus = Array.from(document.querySelectorAll('.rapchieu:checked')).map(e => e.value);

                const showFrom = $refs.showFromInput.value;
                const showTo = $refs.showToInput.value;
                const durrationFrom = $refs.durrationFromInput.value;
                const durrationTo = $refs.durrationToInput.value;
                const sortBy = $refs.sortByInput.value;
                const sort = $refs.sortInput.value;
                console.log(keyword, theloais, rapchieus, showFrom, showTo, durrationFrom, durrationTo, sortBy, sort);
                const queryParam = {
                    'tu-khoa': keyword,
                    'the-loais': theloais,
                    'raps': rapchieus,
                    'thoi-gian-tu': showFrom && dayjs(showFrom, 'DD/MM/YYYY').format('YYYY-MM-DD'),
                    'thoi-gian-den':showTo && dayjs(showTo, 'DD/MM/YYYY').format('YYYY-MM-DD'),
                    'thoi-luong-tu': durrationFrom,
                    'thoi-luong-den': durrationTo,
                    'sap-xep': sortBy,
                    'thu-tu': sort,
                    trang: this.page,
                    limit: this.limit
                }
                const query = window.queryString.stringify(queryParam);
                window.history.pushState({}, '', '?' + query);
                axios.post('/api/phim/tim-kiem-nang-cao', queryParam).then(res => {
                    console.log(res.data.data);
                    this.data = res.data.data;
                    this.total = res.headers['x-total-count'];  
                    const totalPage = Math.ceil(this.total / this.limit);
                    this.listPage = Array.from({length: totalPage}, (_, i) => i + 1);
                }).catch(err => {
                    console.log(err);
                    
                }).finally(() => {
                    this.isLoading = false;
                });
    },
}" x-init="
console.log('init');
const query = window.queryString.parse(window.location.search);
$refs.keywordInput.value = query['tu-khoa'] ?? '';
const theloais = query['the-loais'] ?? [];
let elements = document.querySelectorAll('.theloai');
elements.forEach(e => {
    if (theloais.includes(e.value)) {
        e.checked = true;
    }
});
const rapchieus = query['raps'] ?? [];
elements = document.querySelectorAll('.rapchieu');
elements.forEach(e => {
    if (rapchieus.includes(e.value)) {
        e.checked = true;
    }
});
$refs.showFromInput.value = dayjs(query['thoi-gian-tu']|| new Date()).format('DD/MM/YYYY');
$refs.showToInput.value = query['thoi-gian-den'] ? dayjs(query['thoi-gian-den']|| new Date()).format('DD/MM/YYYY') : '';
$refs.durrationFromInput.value = parseInt(query['thoi-luong-tu']) || '';
$refs.durrationToInput.value = parseInt(query['thoi-luong-den']) || '';

$refs.sortByInput.value = query['sap-xep'] ?? 'Phim.TenPhim';
$refs.sortInput.value = query['thu-tu'] ?? 'ASC';
$watch('page', (value) => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
    onSearch();
});
 $nextTick(() => {
        onSearch();
 });
">
    <div class="header2 container-fluid ">
        <div class="mt-3 row justify-content-center heading">
            <div class="col-auto">
                <h1
                    class="m-1 text-center text-uppercase fw-medium tw-font-semibold tw-text-2xl tw-py-2 tw-text-white">
                    Kết quả tìm kiếm
                </h1>
            </div>
        </div>

    </div>
    <div class='container'>
        <div class="tw-collapse tw-collapse-arrow !tw-overflow-visible  tw-bg-white tw-rounded-md tw-shadow-sm">
            <input type="checkbox" x-model="isShowFilter" />
            <div class="tw-collapse-title tw-text-xl tw-font-medium">
                Bộ lọc
            </div>
            <div class="tw-collapse-content" style="visibility: hidden;"
                :style="isShowFilter ? 'visibility: visible; opacity: 1;' : 'visibility: hidden; opacity: 0;'">

                <div class="tw-collapse tw-collapse-arrow tw-border-b tw-bg-white tw-rounded-md tw-shadow-sm">
                    <input type="checkbox" />
                    <div class="tw-collapse-title tw-text-xl tw-font-medium">
                        Từ khóa
                    </div>
                    <div class="tw-collapse-content">
                        <input x-ref="keywordInput" type="text" class="tw-input tw-input-bordered tw-w-full"
                            placeholder="Nhập từ khóa">
                    </div>
                </div>
                <div
                    class="tw-collapse !tw-overflow-visible  tw-collapse-arrow tw-border-b  tw-bg-white tw-rounded-md tw-shadow-sm">
                    <input type="checkbox" />
                    <div class="tw-collapse-title tw-text-xl tw-font-medium">
                        Được chiếu ở một trong các rạp
                    </div>
                    <div class="tw-collapse-content tw-relative">
                        <div class='tw-relative'>
                            <ul
                                class="tw-menu tw-max-h-60 !tw-flex-nowrap tw-overflow-x-scroll tw-bg-base-200   tw-w-full tw-rounded-box">
                                <?php foreach ($cinemas as $cinema): ?>
                                            <li>
                                                <label role="button" class='flex tw-items-center tw-gap-x-2'>
                                                    <input type="checkbox" value="<?= $cinema['MaRapChieu'] ?>"
                                                        class="tw-checkbox tw-checkbox-sm rapchieu" />
                                                    <span class='tw-truncate'>
                                                        <?= $cinema['TenRapChieu'] ?>
                                                    </span>
                                                </label>
                                            </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                    </div>
                </div>
                <div
                    class="tw-collapse !tw-overflow-visible   tw-collapse-arrow tw-border-b  tw-bg-white tw-rounded-md tw-shadow-sm">
                    <input type="checkbox" />
                    <div class="tw-collapse-title tw-text-xl tw-font-medium">
                        Có các thể loại
                    </div>
                    <div class="tw-collapse-content tw-relative">
                        <div class='tw-relative'>
                            <ul
                                class="tw-menu tw-max-h-60 !tw-flex-nowrap tw-overflow-x-scroll tw-bg-base-200   tw-w-full tw-rounded-box">
                                <?php
                                uasort($categories, function ($a, $b) {
                                    return $a['TenTheLoai'] <=> $b['TenTheLoai'];
                                });
                                ?>
                                <?php foreach ($categories as $category): ?>
                                            <li>
                                                <label role="button" class='flex tw-items-center tw-gap-x-2'>
                                                    <input type="checkbox" value="<?= $category['MaTheLoai'] ?>"
                                                        class="tw-checkbox tw-checkbox-sm theloai" />
                                                    <span class='tw-truncate'>
                                                        <?= $category['TenTheLoai'] ?>
                                                    </span>
                                                </label>
                                            </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                    </div>
                </div>
                <div
                    class="tw-collapse !tw-overflow-visible tw-collapse-arrow tw-border-b  tw-bg-white tw-rounded-md tw-shadow-sm">
                    <input type="checkbox" />
                    <div class="tw-collapse-title tw-text-xl tw-font-medium">
                        Khoảng thời gian chiếu
                    </div>
                    <div class="tw-collapse-content" x-init="
                const elem = document.querySelector('#show-from');
                 datepickerFrom = new Datepicker(elem, {
                    language: 'vi',
                    minDate: new Date(),
                    format: 'dd/mm/yyyy',
                }); 
                const elem2 = document.querySelector('#show-to');
                datepickerTo = new Datepicker(elem2, {
                    language: 'vi',
                    minDate: new Date(),
                    format: 'dd/mm/yyyy',
                });
                ">
                        <div class="tw-flex tw-gap-x-4 tw-flex-wrap">
                            <label class="tw-form-control tw-border-none tw-w-full tw-max-w-xs">
                                <div class="tw-label">
                                    <span class="tw-label-text">Từ ngày</span>
                                </div>
                                <input x-ref="showFromInput" readonly id="show-from" type="text" placeholder="Type here"
                                    class="tw-input tw-input-bordered tw-w-full tw-max-w-xs" />
                            </label>
                            <label class="tw-form-control tw-border-none tw-w-full tw-max-w-xs">
                                <div class="tw-label">
                                    <span class="tw-label-text">Đến ngày
                                    </span>
                                </div>
                                <input x-ref="showToInput" readonly id="show-to" type="text" placeholder="Type here"
                                    class="tw-input tw-input-bordered tw-w-full tw-max-w-xs" />
                            </label>
                        </div>

                    </div>
                </div>
                <div class="tw-collapse tw-collapse-arrow tw-border-b tw-bg-white tw-rounded-md tw-shadow-sm">
                    <input type="checkbox" />
                    <div class="tw-collapse-title tw-text-xl tw-font-medium">
                        Thời lượng chiếu (phút)
                    </div>
                    <div class="tw-collapse-content">
                        <div class="tw-flex tw-gap-x-4 tw-flex-wrap">
                            <label class="tw-form-control tw-border-none tw-w-full tw-max-w-xs">
                                <div class="tw-label">
                                    <span class="tw-label-text">Thời lượng tối thiểu</span>
                                </div>
                                <input x-ref="durrationFromInput" id="durration-from" type="text"
                                    placeholder="Type here" class="tw-input tw-input-bordered tw-w-full tw-max-w-xs" />
                            </label>
                            <label class="tw-form-control tw-border-none tw-w-full tw-max-w-xs">
                                <div class="tw-label">
                                    <span class="tw-label-text">Thời lượng tối đa
                                    </span>
                                </div>
                                <input x-ref="durrationToInput" id="show-to" type="text" placeholder="Type here"
                                    class="tw-input tw-input-bordered tw-w-full tw-max-w-xs" />
                            </label>
                        </div>
                    </div>
                </div>
                <div class="tw-collapse tw-collapse-arrow tw-border-b tw-bg-white tw-rounded-md tw-shadow-sm">
                    <input type="checkbox" />
                    <div class="tw-collapse-title tw-text-xl tw-font-medium">
                        Sắp xếp kết quả
                    </div>
                    <div class="tw-collapse-content">
                        <div class="tw-flex tw-gap-x-4 tw-flex-wrap">
                            <label class="tw-form-control tw-border-none tw-w-full tw-max-w-xs">
                                <div class="tw-label">
                                    <span class="tw-label-text">
                                        Sắp xếp theo
                                    </span>
                                </div>
                                <select x-ref="sortByInput" class="tw-select tw-select-bordered" value="Phim.TenPhim">

                                    <option value="Phim.TenPhim">Tên phim</option>
                                    <option value="Phim.ThoiLuong">Thời lượng</option>
                                    <option value="Phim.NgayPhatHanh">Ngày phát hành</option>
                                </select>

                            </label>
                            <label class="tw-form-control tw-border-none tw-w-full tw-max-w-36">
                                <div class="tw-label">
                                    <span class="tw-label-text">
                                        Thứ tự
                                    </span>
                                </div>
                                <select class="tw-select tw-select-bordered">
                                    <option x-ref="sortInput" value="ASC">Tăng dần</option>
                                    <option value="DESC">Giảm dần</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
                <div class='tw-flex tw-justify-center tw-pt-8 tw-pb-3 tw-gap-2 '>
                    <button class="tw-btn tw-w-full tw-max-w-xs" x-on:click="onClearFilter">
                        Xoá bộ lọc
                    </button>
                    <button x-on:click="onSearch" class="tw-btn tw-text-white tw-btn-primary tw-w-full tw-max-w-xs">
                        Áp dụng
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="container product my-lg-4 " x-data="{}">
        <div class="row tw-gap-y-4 ">
            <template x-if="isLoading">
                <div class="text-center col-12 tw-justify-center tw-items-center tw-py-12">
                    <span class="tw-loading tw-loading-dots tw-loading-lg"></span>
                </div>
            </template>
            <template x-if="!isLoading && data.length == 0">
                <div class="text-center col-12 tw-justify-center tw-items-center tw-py-12">
                    <span class="tw-text-xl tw-font-semibold">Không có kết quả nào</span>
                </div>
            </template>
            <template x-for="item in data" :key="item.MaPhim">
                <div class="mb-4 col-xl-3 gx-5 mb-xl-0 col-md-4 col-6 col-lg-4 ">
                    <div class="tw-rounded-md tw-pb-2">
                        <div class="app-carousel-movie-item__img" x-on:mouseover="onItemMouseOver($event)"
                            x-on:mouseout="onItemMouseOut($event)">

                            <div class="p-4 text-white app-carousel-movie-item__info--hover text-start fs-5">
                                <h2 class="fs-4 fw-bold" x-text="item.TenPhim"></h2>

                                <div class="mt-5">
                                    <div>
                                        <i class="me-3 fa-solid fa-layer-group"></i>
                                        <span x-text="item.DinhDang"></span>
                                    </div>

                                    <div>
                                        <i class="mt-2 me-3 fa-solid fa-clock"></i>
                                        <span x-text="item.ThoiLuong"></span>
                                    </div>

                                    <div>
                                        <i class="mt-2 fa-solid fa-closed-captioning me-3"></i>
                                        <span x-text="item.NgonNgu"></span>
                                    </div>


                                </div>
                            </div>
                            <img :src="item.HinhAnh" class="d-block w-100" alt="...">
                        </div>
                        <h4 class="mt-3 app-carousel-movie-item__title" x-text="item.TenPhim"></h4>
                        <div class="mt-4 d-flex justify-content-around align-items-center">
                            <a style="color: var(--color1)" :href="item.Trailer">
                                <i class="fa-solid fa-star"></i>
                                <span>Xem Trailer</span>
                            </a>
                            <a style="margin-right: 4px;" class="login-item btn-login btn"
                                :href="'/phim/' + item.MaPhim">

                                <i class="fa-solid fa-star"></i>
                                <span class="ms-2">Đặt vé</span>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <nav aria-label="..." class='tw-flex tw-items-center tw-justify-center tw-py-5 tw-my-10'>
            <div class="tw-join">
                <template x-for="item in listPage" :key="item">

                    <input class="tw-join-item tw-btn tw-btn-square" type="radio" name="options" :aria-label="item"
                        :value="item" x-model="page" :checked="item == page" />

                </template>
            </div>

        </nav>
    </div>
</div>
<script>
function onItemMouseOver(e) {
    const target = e.currentTarget;
    const infoEle = target.getElementsByClassName(
        "app-carousel-movie-item__info--hover"
    )[0];
    infoEle.style.opacity = 1;
}

function onItemMouseOut(e) {
    const target = e.currentTarget;
    const infoEle = target.getElementsByClassName(
        "app-carousel-movie-item__info--hover"
    )[0];
    infoEle.style.opacity = 0;
}
</script>

<script type="module">
import queryString from 'https://cdn.jsdelivr.net/npm/query-string@9.0.0/+esm'
window.queryString = queryString
</script>

<?php
script("https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker.min.js");
script("https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/locales/vi.js");
require ('partials/footer.php'); ?>