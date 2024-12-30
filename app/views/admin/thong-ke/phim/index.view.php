<?php
title("Thống kê theo phim");
require ("app/views/admin/header.php");
?>
<link rel="stylesheet" href="/public/thong-ke/movie/analyticsMovies.css">
<div x-data="
{
    data: [],
    query: {
        'tu-ngay': '',
        'den-ngay': '',
        'the-loais': [],
    },
    isLoaded: false,
    currentSortDirection: 'asc',
    currentSortColumn: 'totalMoney',
    sort(column) {
        if (this.currentSortColumn === column) {
            this.currentSortDirection = this.currentSortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.currentSortColumn = column;
            this.currentSortDirection = 'asc';
        }
        this.data.sort((a, b) => {
            if (this.currentSortDirection === 'asc') {
                return Number(a[column]) - Number(b[column]);
            } else {
                return Number(b[column]) - Number(a[column]);
            }
        });
    },
     getData() {
        this.isLoaded = false;
        const queryStr = '?'+window.queryString.stringify(this.query, {arrayFormat: 'bracket'});
        axios.get('/api/thong-ke/phim/tong-quan'+queryStr).then(response => {
            this.data = response.data.data;
            this.sort(this.currentSortColumn);
        }).finally(() => {
            this.isLoaded = true;
        });
    }
}
" x-init="
getData();
$watch('query', () => {
    getData();
}, {deep: true});
" style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="p-5 wrapper">
    <div class="p-3 bg-white analytics-movies container-fluid tw-min-h-screen d-flex flex-column border_radius-16">
        <!-- thanh lọc dữ liệu phim -->
        <div class="row">
            <form>
                <div class="flex-row d-flex">
                    <div class="me-3">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Thể
                                loại</label>
                            <div class="mb-3">
                                <select x-model="query['the-loais']" class="selectpicker" multiple
                                    data-live-search="true">
                                    <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['MaTheLoai'] ?>"><?= $category['TenTheLoai'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="me-3">
                        <div class="mb-3">
                            <label for="ngayBatDau" class="form-label">Từ
                                ngày</label>
                            <input x-model="query['tu-ngay']" type="date" class="form-control" id="ngayBatDau"
                                aria-describedby="emailHelp">
                        </div>
                    </div>

                    <div class>
                        <div class="mb-3">
                            <label for="ngayKetThuc" class="form-label">Đến
                                ngày</label>
                            <input x-model="query['den-ngay']" type="date" class="form-control" id="ngayKetThuc"
                                aria-describedby="emailHelp">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- hết thanh lọc dữ liệu phim -->

        <!-- bảng dữ liệu phim -->
        <div class="px-3 overflow-y-auto row table_container">
            <table class="table align-middle table-hover" style="height: 100%">
                <thead class="table-light table-header">
                    <tr>
                        <th scope="col-1">
                            <div class="col-name">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Mã phim
                            </div>
                        </th>
                        <th scope="col-7">
                            <div class="col-name">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                    width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                    <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                </svg>
                                Tên phim
                            </div>
                        </th>
                        <th scope="col-1" x-on:click="sort('totalVe')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                            </svg>
                            Vé bán
                        </th>
                        <th scope="col-1" x-on:click="sort('totalSuatChieu')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                            </svg>
                            Số suất chiếu
                        </th>
                        <th scope="col-2" x-on:click="sort('totalMoney')" style="cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                            </svg>
                            Tổng doanh thu vé
                        </th>
                    </tr>
                </thead>
                <tbody class="overflow-y-auto" style="height: 100%;">


                    <template x-if="!isLoaded">
                        <tr>
                            <td colspan="5">
                                <div class="d-flex justify-content-center tw-py-10">
                                    <span class="loading loading-bars loading-lg"></span>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-for="(item, index) in data" :key="index">
                        <tr x-on:click="window.location.href = '/admin/thong-ke/phim/' + item.MaPhim "
                            class="tw-cursor-pointer">
                            <td>
                                <div class="col-name">
                                    <span x-text="item.MaPhim"></span>
                                </div>
                            </td>
                            <td>
                                <div class="col-name">
                                    <span x-text="item.TenPhim"></span>
                                </div>
                            </td>
                            <td>
                                <div class="col-name">
                                    <span x-text="item.totalVe"></span>
                                </div>
                            </td>
                            <td>
                                <div class="col-name">
                                    <span x-text="item.totalSuatChieu"></span>
                                </div>
                            </td>
                            <td>
                                <div class="col-name">
                                    <span x-text="toVnd(item.totalMoney)"></span>
                            </td>
                        </tr>

                    </template>

                </tbody>
            </table>
        </div>
        <!-- hết bảng dữ liệu pim -->
    </div>
</div>
</div>
<?php
require ("app/views/admin/footer.php");
?>