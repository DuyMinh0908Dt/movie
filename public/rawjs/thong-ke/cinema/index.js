import ApexCharts from "apexcharts";
window.ApexCharts = ApexCharts;
const Alphine = window.Alpine;
const dayjs = window.dayjs;
const _ = window._;
let axios = window.axios.create();

import { setupCache } from "axios-cache-interceptor/dev";
window.axios = setupCache(axios, {
  methods: ["get"],
  debug: console.log,
  ttl: 15 * 60 * 1000,
  headerInterpreter: function (headers) {
    return headers["can-use-cache"] !== "false";
  },
});
console.log("setup cache");
axios = window.axios;
async function getSumTotalRevenueEachCinema(params) {
  const res = await axios.get("/api/tong-quan/rap-chieu", {
    params: params,
  });
  return res.data.data;
}
async function getSumTotalRevenueFood(params) {
  const res = await axios.get("/api/tong-quan/san-pham/chi-tiet", {
    params: params,
  });
  return res.data.data;
}
async function getSumTotalRevenueMovie(params) {
  const res = await axios.get("/api/tong-quan/phim/chi-tiet", {
    params: params,
  });
  return res.data.data;
}
async function getStatisticOfCinema(params) {
  const mainPromise = axios.get("/api/tong-quan/hoa-don/chi-tiet", {
    params: params,
  });
  const foodPromise = axios.get("/api/tong-quan/san-pham", {
    params: params,
  });
  const ticketPromise = axios.get("/api/tong-quan/ve/chi-tiet", {
    params: params,
  });
  //
  const [mainRes, foodRes, ticketRes] = await Promise.all([
    mainPromise,
    foodPromise,
    ticketPromise,
  ]);
  const mainData = mainRes.data.data;
  const foodData = foodRes.data.data;
  const ticketData = ticketRes.data.data;
  return mainData.map((item) => {
    const food = foodData.find((f) => f.date === item.date);
    const ticket = ticketData.find((t) => t.date === item.date);
    return {
      ...item,
      food: food || { total: 0, totalMoney: 0 },
      ticket: ticket || { total: 0, totalMoney: 0 },
    };
  });
}

function renderColumnChartOfCinema(data, el) {
  // [
  //   {
  //     total: 1,
  //     totalMoney: "190000",
  //     date: "2024-04-30",
  //   },
  //   {
  //     total: 1,
  //     totalMoney: "270000",
  //     date: "2024-05-02",
  //     ticket: {
  //       total: 1,
  //       totalMoney: "190000",
  //     },
  //     food: {
  //       total: 1,
  //       totalMoney: "80000",
  //     },
  //   },
  // ];

  // const doanhThuVe = data.map((item) => Number(item.ticket.totalMoney));
  // const doanhThuSanPham = data.map((item) => Number(item.food.totalMoney));
  // ,
  //       {
  //         name: "Doanh thu tổng",
  //         data: [76, 85, 101, 98, 87, 105, 91, 114, 94],
  //       },
  //       {
  //         name: "Doanh thu sản phẩm",
  //         data: [35, 41, 36, 26, 45, 48, 52, 53, 41],
  //       },
  const options = {
    series: [
      {
        name: "Doanh tổng",
        data: data.map((item) => [
          dayjs(item.date).valueOf(),
          Number(item.totalMoney),
        ]),
      },
      {
        name: "Doanh thu vé",
        data: data.map((item) => [
          dayjs(item.date).valueOf(),
          Number(item.ticket.totalMoney),
        ]),
      },
      {
        name: "Doanh thu sản phẩm",
        data: data.map((item) => [
          dayjs(item.date).valueOf(),
          Number(item.food.totalMoney),
        ]),
      },
    ],
    chart: {
      type: "bar",
      height: 350,
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: "55%",
        endingShape: "rounded",
      },
    },
    dataLabels: {
      enabled: false,
    },
    stroke: {
      show: true,
      width: 2,
      colors: ["transparent"],
    },
    xaxis: {
      type: "datetime",
      min: _.minBy(data, (item) => dayjs(item.date).valueOf()).date,
      tickAmount: 6,
      max: _.maxBy(data, (item) => dayjs(item.date).valueOf()).date,
      labels: {
        formatter: function (value) {
          return dayjs(value).format("DD/MM/YYYY");
        },
      },
    },
    yaxis: {},
    fill: {
      opacity: 1,
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return toVnd(val);
        },
      },
    },
  };

  const chart = new ApexCharts(el, options);
  chart.render();
  return chart;
}

function renderTotalRenuveChart(data, el) {
  data = data.sort((a, b) => Number(b.total) - Number(a.total));
  const options = {
    series: [
      {
        data: data.map((item) => Number(item.total)),
      },
    ],
    chart: {
      type: "bar",
      height: 350,
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        horizontal: true,
      },
    },
    dataLabels: {
      enabled: false,
    },
    xaxis: {
      categories: data.map((item) => item.TenRapChieu),
    },
  };
  const chart = new ApexCharts(el, options);
  chart.render();
  return chart;
}
function renderProductChart(data, el) {
  data = data.sort((a, b) => Number(b.totalMoney) - Number(a.totalMoney));

  const options = {
    series: data.map((item) => Number(item.totalMoney)),
    chart: {
      type: "donut",
    },

    labels: data.map((item) => item.name),
    legend: {
      position: "bottom",
    },
    // format tiền
    tooltip: {
      y: {
        formatter: function (val) {
          return toVnd(val);
        },
      },
    },

    plotOptions: {
      pie: {
        donut: {
          labels: {
            show: true,
            total: {
              show: true,
              showAlways: true,
              label: "Tổng tiền",
              fontSize: "22px",
              fontFamily: "Helvetica, Arial, sans-serif",
              color: "#373d3f",
              formatter: function (w) {
                return toVnd(
                  w.globals.seriesTotals.reduce((a, b) => {
                    return a + b;
                  }, 0)
                );
              },
            },
          },
        },
      },
    },
    responsive: [
      {
        breakpoint: 480,
        options: {
          chart: {
            width: 200,
          },
          legend: {
            position: "bottom",
          },
        },
      },
    ],
  };

  const chart = new ApexCharts(el, options);
  chart.render();
  return chart;
}

async function fetchAllRevenueDetaisl(params, allRevenue) {
  // Số lượng sản phẩm	Doanh thu sản phẩm	Số vé	Doanh thu phim	Tổng doanh thu
  // có allRevenue rồi giờ lấy chi tiết = Số lượng sản phẩm, Doanh thu sản phẩm, Số vé, Doanh thu vé
  // vé
  const ticketPromise = axios.get("/api/tong-quan/ve/chi-tiet", {
    params: {
      ...params,
      "group-by": "PhongChieu.MaRapChieu",
    },
  });
  const foodPromise = axios.get("/api/tong-quan/san-pham", {
    params: {
      ...params,
      "group-by": "MaRapChieu",
    },
  });
  const [ticketRes, foodRes] = await Promise.all([ticketPromise, foodPromise]);

  const ticketData = ticketRes.data.data;
  const foodData = foodRes.data.data;
  // join ticketData và foodData với allRevenue
  allRevenue = [...allRevenue].map((item) => {
    const ticket = ticketData.find((t) => t.MaRapChieu === item.MaRapChieu);
    const food = foodData.find((f) => f.MaRapChieu === item.MaRapChieu);
    return {
      ...item,
      ticket: ticket || { total: 0, totalMoney: 0 },
      food: food || { total: 0, totalMoney: 0 },
    };
  });
  return allRevenue;
}
document.addEventListener("alpine:init", () => {
  Alphine.data("cinema_statistical", () => ({
    query: {
      "rap-chieu": "",
      "tu-ngay": "",
      "den-ngay": "",
      type: "chart",
    },
    data: {
      cinemaRevenue: [],
      allRevenue: [],
      productRevenue: [],
      movieRevenue: [],
      totalMovieRevenue: 0,
    },
    listChart: [],
    async init() {
      const refetch = async () => {
        await Promise.all([
          this.fetchAllRevenue(),
          this.fetchCinemaRevenue(),
          this.fetchProductRevenue(),
          this.fetchMovieRevenue(),
        ]);

        if (this.query.type === "chart") {
          this.renderChart();
        } else {
          await this.fetchAllRevenueDetail();
        }
      };
      await refetch();
      this.$watch("query.type", (value) => {
        if (value === "chart") {
          this.$nextTick(() => {
            this.renderChart();
          });
        } else {
          this.listChart.forEach((chart) => {
            chart.destroy();
          });
          this.listChart = [];
        }
      });
      this.$watch("query['rap-chieu']", () => {
        refetch();
      });
      this.$watch("query['tu-ngay']", () => {
        refetch();
      });
      this.$watch("query['den-ngay']", () => {
        refetch();
      });
    },
    async fetchAllRevenueDetail() {
      const res = await fetchAllRevenueDetaisl(
        this.query,
        this.data.allRevenue
      );
      this.data.allRevenue = res;
    },

    async fetchAllRevenue() {
      if (!this.query["rap-chieu"]) {
        // nếu không chọn rạp chiếu thì lấy tất cả
        this.data.allRevenue = await getSumTotalRevenueEachCinema(this.query);
      }
    },
    async fetchCinemaRevenue() {
      if (this.query["rap-chieu"]) {
        // nếu chọn rạp chiếu thì lấy theo rạp chiếu
        this.data.cinemaRevenue = await getStatisticOfCinema(this.query);
      }
    },
    async fetchProductRevenue() {
      this.data.productRevenue = await getSumTotalRevenueFood(this.query);
    },
    async fetchMovieRevenue() {
      let temp = await getSumTotalRevenueMovie({
        ...this.query,
      });
      temp = temp.sort((a, b) => Number(b.total) - Number(a.total));
      this.data.totalMovieRevenue = temp.reduce((sum, item) => {
        return sum + Number(item.total);
      }, 0);
      //this.data.movieRevenue lấy top 5 phim có doanh thu cao nhất
      this.data.movieRevenue = temp.slice(0, 5);
    },
    renderChart() {
      this.listChart.forEach((chart) => {
        chart.destroy();
      });
      this.listChart = [];
      if (this.query["rap-chieu"] == "") {
        const char1 = renderTotalRenuveChart(
          this.data.allRevenue,
          document.querySelector("#bar_chart")
        );
        this.listChart.push(char1);
      } else {
        const char1 = renderColumnChartOfCinema(
          this.data.cinemaRevenue,
          document.querySelector("#columnChart")
        );
        this.listChart.push(char1);
      }

      console.log(this.data.productRevenue);

      const char2 = renderProductChart(
        this.data.productRevenue,
        document.querySelector("#donutChart")
      );
      this.listChart.push(char2);
    },
  }));
});
