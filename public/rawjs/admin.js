import Alpine from "alpinejs";
import $ from "jquery";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";
import "dayjs/locale/vi";

dayjs.extend(customParseFormat);

dayjs.locale("vi");
import axios from "axios";
import lodash from "lodash";
import { focus } from "@alpinejs/focus";
import queryString from "query-string";
import * as bt from "bootstrap";
window.bootstrap = bt;
window.$ = window.jQuery = $;
require("bootstrap-select");
require("../js/validation");
window.queryString = queryString;
window._ = lodash;
window.axios = axios;
window.dayjs = dayjs;
Alpine.plugin(focus);
window.Alpine = Alpine;
window.toVnd = function (number) {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(number);
};
Alpine.data("dataTable", ({ initialQuery = {}, endpoint }) => ({
  data: [],
  selected: null,
  isFetching: false,
  query: {
    trang: 1,
    limit: 10,
    ...initialQuery,
  },
  totalItems: 0,
  init() {
    const queryStr = window.location.search;
    const query = queryString.parse(queryStr, {
      arrayFormat: "bracket",
    });
    this.query = {
      ...this.query,
      ...query,
    };
    this.$watch("query.limit", () => {
      this.refresh({
        resetPage: true,
      });
    });

    this.$nextTick(() => {
      this.refresh();
    });
  },
  getArrayPages() {
    console.log(this.totalItems, this.query["limit"]);
    const totalPage = Math.ceil(this.totalItems / this.query["limit"]);
    if (totalPage >= 8) {
      const begin = Math.max(1, this.query["trang"] - 2);
      const end = Math.min(totalPage, this.query["trang"] + 2);
      return Array.from(
        {
          length: end - begin + 1,
        },
        (_, i) => i + begin
      );
    }
    return Array.from(
      {
        length: totalPage,
      },
      (_, i) => i + 1
    );
  },

  createOrderFn: function (orderBy) {
    if (this.query["sap-xep"] === orderBy) {
      this.query["thu-tu"] = this.query["thu-tu"] === "ASC" ? "DESC" : "ASC";
    } else {
      this.query["thu-tu"] = "ASC";
      this.query["sap-xep"] = orderBy;
    }
    this.refresh({
      resetPage: true,
    });
  },
  refresh: function ({ resetPage = false } = {}) {
    if (resetPage) {
      this.query.trang = 1;
    }
    this.data = [];
    this.isFetching = true;

    this.$nextTick(() => {
      this.$el.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    });
    const queryStr = queryString.stringify(this.query, {
      arrayFormat: "bracket",
    });
    const url = `${endpoint}?${queryStr}`;

    axios
      .get(url)
      .then((response) => {
        this.data = response.data.data;
        this.totalItems = response.headers["x-total-count"];

        window.history.pushState(
          {},
          "",
          window.location.pathname + "?" + queryStr
        );
      })
      .finally(() => {
        this.isFetching = false;
      });
  },
}));
window.addEventListener("DOMContentLoaded", () => {
  Alpine.start();
  console.log("Alpine started");
  $(".selectpicker").selectpicker();
});
function useDisableAllInput() {
  let inputThatDisabled = [];
  function disableAllButton() {
    // get all button that not disabled
    inputThatDisabled.push(document.querySelectorAll("button:not([disabled])"));
    inputThatDisabled.push(document.querySelectorAll("input:not([disabled])"));
    inputThatDisabled.push(document.querySelectorAll("select:not([disabled])"));
    inputThatDisabled.push(
      document.querySelectorAll("textarea:not([disabled])")
    );
    inputThatDisabled.forEach((inputs) => {
      inputs.forEach((input) => {
        input.disabled = true;
      });
    });
  }
  function enableAllButton() {
    inputThatDisabled.forEach((inputs) => {
      inputs.forEach((input) => {
        input.disabled = false;
      });
    });
    inputThatDisabled = [];
  }
  return {
    disableAllButton,
    enableAllButton,
  };
}
const { disableAllButton, enableAllButton } = useDisableAllInput();
window.disableAllButton = disableAllButton;
window.enableAllButton = enableAllButton;

const {
  disableAllButton: axiosDisableAllButton,
  enableAllButton: axiosEnableAllButton,
} = useDisableAllInput();
axios.interceptors.request.use(function (config) {
  config.headers["X-Requested-With"] = "XMLHttpRequest";
  if (config.method !== "get") {
    axiosDisableAllButton();
  }
  return config;
});
axios.interceptors.response.use(
  function (response) {
    axiosEnableAllButton();
    return response;
  },
  function (error) {
    axiosEnableAllButton();
    return Promise.reject(error);
  }
);
