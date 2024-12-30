<?php
title("Admin");
require ('app/views/admin/header.php');

?>
<link rel="stylesheet" href="/public/overview.css">
<div style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="p-5 wrapper">
    <div class="p-0 analytics container-fluid" x-data="{
                    weekData: [],
                }">
        <div class="mb-4 row">
            <div class="col-4 " x-data="{
                                    sumOfBill: 0,
                                    changePercent: 0,
                                 
                                    isLoaded: false,
                                }" x-init="
                                const today = dayjs();
                                // tìm thứ 2 của tuần chứa ngày hôm nay
                                const startOfWeek = today.startOf('week');
                                const lastWeek = startOfWeek.subtract(1, 'week');
                                const endOfLastWeek = lastWeek.endOf('week');

                                const totalBillAllTimePromise = axios.get('/api/tong-quan/hoa-don');
                                const totalBillLastWeekPromise = axios.get('/api/tong-quan/hoa-don', {
                                    params: {
                                        'tu-ngay': lastWeek.format('YYYY-MM-DD'),
                                        'den-ngay': endOfLastWeek.format('YYYY-MM-DD')
                                    }
                                });
                                const totalBillCurrentWeekPromise = axios.get('/api/tong-quan/hoa-don', {
                                    params: {
                                        'tu-ngay': startOfWeek.format('YYYY-MM-DD'),
                                        'den-ngay': today.format('YYYY-MM-DD')
                                    }
                                });
                                const billStatisticWeekPromise = axios.get('/api/tong-quan/hoa-don/chi-tiet', {
                                    params: {
                                        'tu-ngay': lastWeek.format('YYYY-MM-DD'),
                                        'den-ngay': today.format('YYYY-MM-DD')
                                    }
                                });
                                const [totalBillAllTime, totalBillLastWeek, totalBillCurrentWeek, billStatisticWeek] = await Promise.all([
                                    totalBillAllTimePromise,
                                    totalBillLastWeekPromise,
                                    totalBillCurrentWeekPromise,
                                    billStatisticWeekPromise
                                ]);

                                weekData = [];
                                for (let i= lastWeek; i.isBefore(today); i = i.add(1, 'day')) {
                                    
                                    const date = i.format('YYYY-MM-DD');
                                    const data = billStatisticWeek.data.data.find(item => item.date === date);
                                    if (data) {
                                        weekData.push({
                                            date: data.date,
                                            total: data.total,
                                            totalMoney: data.totalMoney
                                        });
                                    } else {
                                        weekData.push({
                                            date: date,
                                            total: 0,
                                            totalMoney: 0
                                        });
                                    }
                                }
                                drawMiniChart(weekData.map(item => item.total));
                                drawProfitChart(weekData.map(item => item.totalMoney));
                                console.log(weekData);
                                sumOfBill = totalBillAllTime.data.data[0].total;
                                const sumOfBillLastWeek = totalBillLastWeek.data.data[0].total;
                                const sumOfBillCurrentWeek = totalBillCurrentWeek.data.data[0].total;
                                changePercent = ((sumOfBillCurrentWeek - sumOfBillLastWeek) / sumOfBillLastWeek * 100).toFixed(1);
                                
                                isLoaded = true;
                                ">

                <div class="p-3 bg-white shadow d-flex flex-nowrap border_radius-16 ">
                    <div class="col-8">
                        <h6 class="fw-semibold">Tổng số hóa đơn</h6>
                        <h3 class="fw-semibold" x-text="sumOfBill"></h3>

                        <div class="text-body-secondary fw-medium tw-flex tw-items-center tw-gap-x-2 tw-mt-2"
                            style="font-size: 14px;">
                            <svg x-show="changePercent < 0" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                class="css-jc110d" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M21 12a1 1 0 0 0-2 0v2.3l-4.24-5a1 1 0 0 0-1.27-.21L9.22 11.7L4.77 6.36a1 1 0 1 0-1.54 1.28l5 6a1 1 0 0 0 1.28.22l4.28-2.57l4 4.71H15a1 1 0 0 0 0 2h5a1.1 1.1 0 0 0 .36-.07l.14-.08a1.19 1.19 0 0 0 .15-.09a.75.75 0 0 0 .14-.17a1.1 1.1 0 0 0 .09-.14a.64.64 0 0 0 .05-.17A.78.78 0 0 0 21 17Z">
                                </path>
                            </svg>


                            <svg x-show="changePercent > 0" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                class="m-0 css-tec1m3" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M21 7a.78.78 0 0 0 0-.21a.64.64 0 0 0-.05-.17a1.1 1.1 0 0 0-.09-.14a.75.75 0 0 0-.14-.17l-.12-.07a.69.69 0 0 0-.19-.1h-.2A.7.7 0 0 0 20 6h-5a1 1 0 0 0 0 2h2.83l-4 4.71l-4.32-2.57a1 1 0 0 0-1.28.22l-5 6a1 1 0 0 0 .13 1.41A1 1 0 0 0 4 18a1 1 0 0 0 .77-.36l4.45-5.34l4.27 2.56a1 1 0 0 0 1.27-.21L19 9.7V12a1 1 0 0 0 2 0z">
                                </path>
                            </svg>
                            <strong class="text-dark"
                                x-text="(changePercent>0 ? '+'+changePercent : changePercent) + '%'"></strong>
                            so với tuần trước
                        </div>
                    </div>

                    <!-- biểu đồ mini -->
                    <div class="col-4">
                        <div id="mini_bill_chart">
                        </div>
                    </div>
                    <!-- hết biểu đồ mini -->
                </div>
            </div>

            <!-- tổng số dư -->
            <div class="col-4" x-data="{
                            sumOfBalance: 0,
                            changePercent: 0,
                            isLoaded: false,
                
                        }" x-init="
                            const today = dayjs();
                            // tìm thứ 2 của tuần chứa ngày hôm nay
                            const startOfWeek = today.startOf('week');
                            const lastWeek = startOfWeek.subtract(1, 'week');
                            const endOfLastWeek = lastWeek.endOf('week');

                            const totalBalanceAllTimePromise = axios.get('/api/tong-quan/ve');
                            const totalBalanceLastWeekPromise = axios.get('/api/tong-quan/ve', {
                                params: {
                                    'tu-ngay': lastWeek.format('YYYY-MM-DD'),
                                    'den-ngay': endOfLastWeek.format('YYYY-MM-DD')
                                }
                            });
                            const totalBalanceCurrentWeekPromise = axios.get('/api/tong-quan/ve', {
                                params: {
                                    'tu-ngay': startOfWeek.format('YYYY-MM-DD'),
                                    'den-ngay': today.format('YYYY-MM-DD')
                                }
                            });
                            const totalBalanceWeekPromise = axios.get('/api/tong-quan/ve/chi-tiet', {
                                params: {
                                    'tu-ngay': lastWeek.format('YYYY-MM-DD'),
                                    'den-ngay': today.format('YYYY-MM-DD')
                                }
                            });
                            const [totalBalanceAllTime, totalBalanceLastWeek, totalBalanceCurrentWeek,totalBalanceWeek] = await Promise.all([
                                totalBalanceAllTimePromise,
                                totalBalanceLastWeekPromise,
                                totalBalanceCurrentWeekPromise,
                                totalBalanceWeekPromise
                            ]);

                            weekData = [];
                            for (let i= lastWeek; i.isBefore(today); i = i.add(1, 'day')) {
                                
                                const date = i.format('YYYY-MM-DD');
                                const data = totalBalanceWeek.data.data.find(item => item.date === date);
                                if (data) {
                                    weekData.push({
                                        date: data.date,
                                        total: data.total,
                                        totalMoney: data.totalMoney
                                    });
                                } else {
                                    weekData.push({
                                        date: date,
                                        total: 0,
                                        totalMoney: 0
                                    });
                                }
                            }
                
                            drawTicketMiniChart(weekData.map(item => item.total));

                            sumOfBalance = totalBalanceAllTime.data.data[0].total;
                            const sumOfBalanceLastWeek = totalBalanceLastWeek.data.data[0].total;
                            const sumOfBalanceCurrentWeek = totalBalanceCurrentWeek.data.data[0].total;
                            changePercent = ((sumOfBalanceCurrentWeek - sumOfBalanceLastWeek) / sumOfBalanceLastWeek * 100).toFixed(1);
                            isLoaded = true;
                            ">
                <div class="p-3 bg-white shadow d-flex flex-nowrap border_radius-16">
                    <div class="col-8">
                        <h6 class="fw-semibold">Tổng số vé bán</h6>
                        <h3 class="fw-semibold" x-text="sumOfBalance">

                        </h3>
                        <div class="text-body-secondary fw-medium tw-flex tw-items-center tw-gap-x-2 tw-mt-2 "
                            style="font-size: 14px;">
                            <svg x-show="changePercent < 0" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                class="css-jc110d" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M21 12a1 1 0 0 0-2 0v2.3l-4.24-5a1 1 0 0 0-1.27-.21L9.22 11.7L4.77 6.36a1 1 0 1 0-1.54 1.28l5 6a1 1 0 0 0 1.28.22l4.28-2.57l4 4.71H15a1 1 0 0 0 0 2h5a1.1 1.1 0 0 0 .36-.07l.14-.08a1.19 1.19 0 0 0 .15-.09a.75.75 0 0 0 .14-.17a1.1 1.1 0 0 0 .09-.14a.64.64 0 0 0 .05-.17A.78.78 0 0 0 21 17Z">
                                </path>
                            </svg>


                            <svg x-show="changePercent > 0" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                class="m-0 css-tec1m3" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M21 7a.78.78 0 0 0 0-.21a.64.64 0 0 0-.05-.17a1.1 1.1 0 0 0-.09-.14a.75.75 0 0 0-.14-.17l-.12-.07a.69.69 0 0 0-.19-.1h-.2A.7.7 0 0 0 20 6h-5a1 1 0 0 0 0 2h2.83l-4 4.71l-4.32-2.57a1 1 0 0 0-1.28.22l-5 6a1 1 0 0 0 .13 1.41A1 1 0 0 0 4 18a1 1 0 0 0 .77-.36l4.45-5.34l4.27 2.56a1 1 0 0 0 1.27-.21L19 9.7V12a1 1 0 0 0 2 0z">
                                </path>
                            </svg>

                            <strong class="text-dark"
                                x-text="(changePercent>0 ? '+'+changePercent : changePercent) + '%'">

                            </strong>
                            so với tuần trước
                        </div>
                    </div>

                    <!-- biểu đồ mini -->
                    <div class="col-4">
                        <div id="mini_balance_chart">
                        </div>
                    </div>
                    <!-- hết biểu đồ mini -->
                </div>
            </div>

            <!-- tổng lợi nhuận -->
            <div class="col-4" x-data="{
                            sumOfProfit: 0,
                            changePercent: 0,
                            isLoaded: false,
                        }" x-init="
                            const today = dayjs();
                            // tìm thứ 2 của tuần chứa ngày hôm nay
                            const startOfWeek = today.startOf('week');
                            const lastWeek = startOfWeek.subtract(1, 'week');
                            const endOfLastWeek = lastWeek.endOf('week');

                            const totalProfitAllTimePromise = axios.get('/api/tong-quan/hoa-don/tong-tien');
                            const totalProfitLastWeekPromise = axios.get('/api/tong-quan/hoa-don/tong-tien', {
                                params: {
                                    'tu-ngay': lastWeek.format('YYYY-MM-DD'),
                                    'den-ngay': endOfLastWeek.format('YYYY-MM-DD')
                                }
                            });
                            const totalProfitCurrentWeekPromise = axios.get('/api/tong-quan/hoa-don/tong-tien', {
                                params: {
                                    'tu-ngay': startOfWeek.format('YYYY-MM-DD'),
                                    'den-ngay': today.format('YYYY-MM-DD')
                                }
                            });
                            const [totalProfitAllTime, totalProfitLastWeek, totalProfitCurrentWeek] = await Promise.all([
                                totalProfitAllTimePromise,
                                totalProfitLastWeekPromise,
                                totalProfitCurrentWeekPromise,
                            ]);

                            sumOfProfit = totalProfitAllTime.data.data[0].total;
                            const sumOfProfitLastWeek = totalProfitLastWeek.data.data[0].total;
                            const sumOfProfitCurrentWeek = totalProfitCurrentWeek.data.data[0].total;
                            changePercent = ((sumOfProfitCurrentWeek - sumOfProfitLastWeek) / sumOfProfitLastWeek * 100).toFixed(1);
                            isLoaded = true;
                            
                            ">
                <div class="p-3 bg-white shadow d-flex flex-nowrap border_radius-16">
                    <div class="col-8">
                        <h6 class="fw-semibold">Tổng lợi nhuận</h6>
                        <h3 class="fw-semibold" x-text="toVnd(sumOfProfit)"></h3>

                        <div class="text-body-secondary fw-medium tw-flex tw-items-center tw-gap-x-2 tw-mt-2"
                            style="font-size: 14px;">
                            <svg x-show="changePercent < 0" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                class="css-jc110d" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M21 12a1 1 0 0 0-2 0v2.3l-4.24-5a1 1 0 0 0-1.27-.21L9.22 11.7L4.77 6.36a1 1 0 1 0-1.54 1.28l5 6a1 1 0 0 0 1.28.22l4.28-2.57l4 4.71H15a1 1 0 0 0 0 2h5a1.1 1.1 0 0 0 .36-.07l.14-.08a1.19 1.19 0 0 0 .15-.09a.75.75 0 0 0 .14-.17a1.1 1.1 0 0 0 .09-.14a.64.64 0 0 0 .05-.17A.78.78 0 0 0 21 17Z">
                                </path>
                            </svg>


                            <svg x-show="changePercent > 0" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                class="m-0 css-tec1m3" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M21 7a.78.78 0 0 0 0-.21a.64.64 0 0 0-.05-.17a1.1 1.1 0 0 0-.09-.14a.75.75 0 0 0-.14-.17l-.12-.07a.69.69 0 0 0-.19-.1h-.2A.7.7 0 0 0 20 6h-5a1 1 0 0 0 0 2h2.83l-4 4.71l-4.32-2.57a1 1 0 0 0-1.28.22l-5 6a1 1 0 0 0 .13 1.41A1 1 0 0 0 4 18a1 1 0 0 0 .77-.36l4.45-5.34l4.27 2.56a1 1 0 0 0 1.27-.21L19 9.7V12a1 1 0 0 0 2 0z">
                                </path>
                            </svg>
                            <strong class="text-dark"
                                x-text="(changePercent>0 ? '+'+changePercent : changePercent )+ '%'"></strong>
                            so với tuần trước
                        </div>
                    </div>

                    <!-- biểu đồ mini -->
                    <div class="col-4">
                        <div id="mini_sales_profit_chart"></div>
                    </div>
                    <!-- biểu đồ mini -->
                </div>
            </div>
        </div>
        <!-- hết các chỉ số tổng quát -->

        <!-- chứa các biểu đồ tổng quát -->
        <div class="mb-4 row">
            <div class=" col-8">
                <div id="pie-root" class="p-4 bg-white shadow border_radius-16">
                    <div class="d-flex flex-column">
                        <span class="fs-5 fw-semibold">Tổng doanh
                            thu</span>
                        <span class="text-body-secondary" style="font-size: 0.875rem">
                            (<?= $growth > 0 ? '+' : '' ?><?= $growth ?> %) so với năm
                            ngoái</span>
                    </div>
                    <!-- chứa biểu đồ kết hợp -->
                    <div id="mixed_chart">
                    </div>
                    <!-- hết chứa biểu đồ kết hợp -->
                </div>
            </div>

            <div class=" col-4">
                <div class="bg-white shadow border_radius-16 tw-flex tw-flex-col" style="height: 100%;">
                    <div class="px-4 pt-4 fs-5 fw-semibold">Phân khúc phim</div>
                    <!-- chứa biểu đồ tròn -->
                    <div id="pie_chart" class="p-0 tw-flex-1">
                    </div>
                    <!-- hết chứa biểu đồ tròn -->
                </div>
            </div>
        </div>
        <!-- hết chứa các biểu đồ tổng quát -->

        <!-- biểu đồ thu nhập các rạp -->
        <div class="row">
            <div class="col-12">
                <div class="p-4 bg-white shadow border_radius-16 ">
                    <div class="d-flex flex-column">
                        <span class="fs-5 fw-semibold">Tổng doanh thu các rạp chiếu</span>
                        <!-- // <span class="text-body-secondary" style="font-size: 0.875rem">(+45%) so với năm ngoái</span> -->
                    </div>

                    <!-- chứa biểu đồ cột -->
                    <div id="bar_chart">

                    </div>
                    <!-- hết biểu đồ cột -->
                </div>
            </div>
        </div>
        <!-- hết biểu đồ thu nhập của các rạp -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!-- biểu đồ kết hợp -->
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const startMonth = '01';
    const currentMonth = dayjs().format('MM');
    const currentYear = dayjs().format('YYYY');
    const currentYearTicketRevenue = _.groupBy(<?= json_encode($currentYearTicketRevenue) ?>, (item) =>
        dayjs(item.date, 'YYYY-MM-DD').format('MM'));
    const currentYearFoodRevenue = _.groupBy(<?= json_encode($currentYearFoodRevenue) ?>, (item) => dayjs(
        item.date, 'YYYY-MM-DD').format('MM'));
    const currentYearTotalRevenue = _.groupBy(<?= json_encode($currentYearTotalRevenue) ?>, (item) => dayjs(
        item.date, 'YYYY-MM-DD').format('MM'));

    const currentYearTicketRevenueGroupByMonth = _.range(1, parseInt(currentMonth) + 1).map((month) => {
        const key = month.toString().padStart(2, '0');
        return currentYearTicketRevenue[key] || [{
            date: `${currentYear}-${key}-01`,
            total: 0,
            totalMoney: 0
        }];
    });

    const currentYearFoodRevenueGroupByMonth = _.range(1, parseInt(currentMonth) + 1).map((month) => {
        const key = month.toString().padStart(2, '0');
        return currentYearFoodRevenue[key] || [{
            date: `${currentYear}-${key}-01`,
            total: 0,
            totalMoney: 0
        }];
    });

    const currentYearTotalRevenueGroupByMonth = _.range(1, parseInt(currentMonth) + 1).map((month) => {
        const key = month.toString().padStart(2, '0');
        return currentYearTotalRevenue[key] || [{
            date: `${currentYear}-${key}-01`,
            total: 0,
            totalMoney: 0
        }];
    });

    console.log(currentYearTicketRevenueGroupByMonth);




    var options = {
        series: [{
            name: 'Tổng doanh thu',
            type: 'column',
            data: currentYearTotalRevenueGroupByMonth.map(item => item.reduce((acc, cur) =>
                Number(acc) + Number(cur.totalMoney), 0))
        }, {
            name: 'Tổng doanh thu vé',
            type: 'area',
            data: currentYearTicketRevenueGroupByMonth.map(item => item.reduce((acc, cur) =>
                Number(acc) + Number(cur.totalMoney), 0))
        }, {
            name: 'Tổng doanh thu thức ăn',
            type: 'line',
            data: currentYearFoodRevenueGroupByMonth.map(item => item.reduce((acc, cur) =>
                Number(acc) +
                Number(cur.totalMoney), 0))
        }],
        chart: {
            height: 350,
            type: 'line',
            stacked: false,
        },
        stroke: {
            width: [0, 2, 5],
            curve: 'smooth'
        },
        plotOptions: {
            bar: {
                columnWidth: '50%'
            }
        },

        fill: {
            opacity: [0.85, 0.25, 1],
            gradient: {
                inverseColors: false,
                shade: 'light',
                type: "vertical",
                opacityFrom: 0.85,
                opacityTo: 0.55,
                stops: [0, 100, 100, 100]
            }
        },
        labels: currentYearTicketRevenueGroupByMonth.map((item => dayjs(item[0].date, 'YYYY-MM-DD')
            .format(
                'MM/YYYY'))),
        markers: {
            size: 0
        },
        xaxis: {
            type: 'category'
        },
        yaxis: [{
            title: {
                text: 'Doanh thu (VND)',
            },
            labels: {
                formatter: function(value) {
                    return toVnd(value);
                }
            }
        }],
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function(y) {
                    return toVnd(y);
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#mixed_chart"), options);
    chart.render();
});
</script>
<!-- hết biểu đồ kết hợp -->

<!-- biểu đồ tròn -->
<script>
const data = <?= json_encode($tagStatistics) ?>;
var options = {
    series: data.map(item => item.total),
    chart: {
        type: 'pie',
        height: '80%'
    },
    labels: data.map(item => item.HanCheDoTuoi),
    legend: {
        position: 'bottom'
    },
    dataLabels: {
        enabled: true,
        style: {
            fontSize: '12px',
            textAlign: 'center'
        }
    },
    plotOptions: {
        pie: {
            offsetY: 25
        }
    }
};

const piechart = new ApexCharts(document.querySelector("#pie_chart"), options);
piechart.render();
const root = document.querySelector("#pie_chart");
const resizeObserver = new ResizeObserver(() => {
    piechart.updateOptions({
        chart: {
            height: root.clientHeight
        }
    });
});
resizeObserver.observe(root);
</script>
<!-- hết biểu đồ tròn -->

<!-- biểu đồ mini hóa đơn -->
<script>
function drawMiniChart(data) {
    const options = {
        series: [{
            name: 'Số hóa đơn',
            data: data
        }],

        chart: {
            type: 'line',
            sparkline: {
                enabled: true // Hiển thị chỉ đường biểu đồ, không có các thành phần khác
            }
        },
        dataLabels: {
            enabled: false // Loại bỏ dữ liệu
        },
        stroke: {
            curve: 'straight'
        },
        grid: {
            show: false // Loại bỏ lưới
        },
        xaxis: {
            labels: {
                show: false // Loại bỏ nhãn trục X
            }
        },
        yaxis: {
            show: false // Loại bỏ nhãn trục Y
        }
    };
    const chart = new ApexCharts(document.querySelector("#mini_bill_chart"), options);
    chart.render();
}



// Lắng nghe sự kiện resize và cập nhật kích thước của biểu đồ
window.addEventListener('resize', function() {
    chart.updateOptions({
        chart: {
            height: document.querySelector("#mini_bill_chart").clientHeight,
            width: document.querySelector("#mini_bill_chart").clientWidth
        }
    });
});
</script>
<!-- hết biểu đồ mini hóa đơn -->

<!-- biểu đồ mini số dư -->
<script>
function drawTicketMiniChart(data) {
    const options = {
        series: [{
            name: 'Số vé',
            data: data
        }],
        chart: {
            type: 'line',
            sparkline: {
                enabled: true // Hiển thị chỉ đường biểu đồ, không có các thành phần khác
            }
        },
        dataLabels: {
            enabled: false // Loại bỏ dữ liệu
        },
        stroke: {
            curve: 'straight',
            colors: '#00e396' // Thay đổi màu của đường biểu đồ
        },
        grid: {
            show: false // Loại bỏ lưới
        },
        xaxis: {
            labels: {
                show: false // Loại bỏ nhãn trục X
            }
        },
        yaxis: {
            show: false // Loại bỏ nhãn trục Y
        }
    };
    const chart = new ApexCharts(document.querySelector("#mini_balance_chart"), options);
    chart.render();

}


// Lắng nghe sự kiện resize và cập nhật kích thước của biểu đồ
window.addEventListener('resize', function() {
    chart.updateOptions({
        chart: {
            height: document.querySelector("#mini_balance_chart").clientHeight,
            width: document.querySelector("#mini_balance_chart").clientWidth
        }
    });
});
</script>
<!-- hết biểu đồ mini số dư -->

<!-- biểu đồ mini lợi nhuận -->
<script>
function drawProfitChart(data) {
    const options = {
        series: [{
            name: 'Doanh thu',
            data: data
        }],
        chart: {
            type: 'line',
            sparkline: {
                enabled: true // Hiển thị chỉ đường biểu đồ, không có các thành phần khác
            }
        },
        dataLabels: {
            enabled: false // Loại bỏ dữ liệu
        },
        stroke: {
            curve: 'straight',
            colors: '#FF5733' // Thay đổi màu của đường biểu đồ
        },
        grid: {
            show: false // Loại bỏ lưới
        },
        xaxis: {
            labels: {
                show: false // Loại bỏ nhãn trục X
            }
        },
        yaxis: {
            show: false // Loại bỏ nhãn trục Y
        }
    };
    const chart = new ApexCharts(document.querySelector("#mini_sales_profit_chart"), options);
    chart.render();
}
// Lắng nghe sự kiện resize và cập nhật kích thước của biểu đồ
window.addEventListener('resize', function() {
    chart.updateOptions({
        chart: {
            height: document.querySelector("#mini_sales_profit_chart").clientHeight,
            width: document.querySelector("#mini_sales_profit_chart").clientWidth
        }
    });
});
</script>
<!-- hết biểu đồ mini lợi nhuận -->

<!-- biểu đồ lợi nhuận các rạp chiếu -->
<script>
let cinemaStatistics = <?= json_encode($cinemaStatistics) ?>;
cinemaStatistics = cinemaStatistics.map(item => {

    return {
        ...item,
        total: item.total != null ? Number(item.total) : 0
    }
}).sort((a, b) => a.total - b.total);
var options = {
    series: [{
        data: cinemaStatistics.map(item => item.total),
        name: 'Doanh thu',

    }],
    chart: {
        type: 'bar',
        height: 350
    },
    plotOptions: {
        bar: {
            borderRadius: 4,
            horizontal: true,
        }
    },
    dataLabels: {
        enabled: false
    },
    xaxis: {
        categories: cinemaStatistics.map(item => item.TenRapChieu),
        labels: {
            formatter: function(val) {
                return new Intl.NumberFormat("vi-VN", {
                    style: "currency",
                    currency: "VND",
                }).format(val);
            }
        }
    }
};

var chart = new ApexCharts(document.querySelector("#bar_chart"), options);
chart.render();
window.addEventListener('resize', function() {
    chart.updateOptions({
        chart: {
            height: document.querySelector("#bar_chart").clientHeight,
            width: document.querySelector("#bar_chart").clientWidth
        }
    });
});
</script>
<?php
require ('app/views/admin/footer.php');


?>