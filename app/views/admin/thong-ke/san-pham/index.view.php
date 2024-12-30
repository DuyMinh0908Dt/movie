<?php
title("Thống kê sản phẩm");
require ("app/views/admin/header.php");
?>
<link rel="stylesheet" href="/public/thong-ke/product/analyticsProducts.css">
<div x-data="
{
    data: [],
    query: {
        'tu-ngay': '',
        'den-ngay': '',
        'loai-san-pham': ''
    },
    isLoaded: false,
    currentSortDirection: 'desc',
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
        axios.get('/api/tong-quan/san-pham/chi-tiet'+queryStr).then(response => {
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
" style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="wrapper p-5">
    <div class="analytics-products container-fluid bg-white border_radius-16 shadow p-3 d-flex flex-column">
        <!-- thanh lọc dữ liệu -->
        <div class="row">
            <form>
                <div class="d-flex flex-row">
                    <div class="col-3 me-3">
                        <label class="form-label" for>Loại sản
                            phẩm</label>
                        <select x-model="query['loai-san-pham']" class="form-select" name id required>
                            <option value="tatCa">Tất cả</option>
                            <option value="1">Thực
                                phẩm</option>
                            <option value="2">Combo</option>
                        </select>
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
        <!-- hết thanh lọc dữ liệu -->

        <!-- bảng dữ liệu sản phẩm -->
        <div class="row px-3 overflow-y-auto table_container">
            <table class="table table-hover align-middle" style="height: 100%">
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
                                Mã
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
                                Tên sản phẩm
                            </div>
                        </th>
                        <th scope="col-1" x-on:click="sort('totalAmount')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                            </svg>
                            Số lượng
                        </th>
                        <th scope="col-2" x-on:click="sort('totalMoney')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort"
                                width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                            </svg>
                            Doanh thu
                        </th>
                    </tr>
                </thead>
                <tbody class="overflow-y-auto" style="height: 100%;">

                    <template x-for="(item, index) in data" :key="index">
                        <tr>
                            <td x-text="index + 1">
                            </td>
                            <td x-text="item.name">
                            </td>
                            <td x-text="item.totalAmount">
                            </td>
                            <td x-text="toVnd(item.totalMoney)">
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <!-- hết bảng dữ liệu sản phẩm -->
    </div>
</div>
</div>
<!-- javascript -->
<!-- <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script> -->
<?php
require ("app/views/admin/footer.php");
?>