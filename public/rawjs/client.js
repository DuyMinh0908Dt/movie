/*


</script>
<!-- from cdn -->
<script src="https://unpkg.com/@material-tailwind/html@latest/scripts/ripple.js"></script>

*/

import Alpine from "alpinejs";
import $ from "jquery";
import dayjs from "dayjs";
import customParseFormat from "dayjs/plugin/customParseFormat";
dayjs.extend(customParseFormat);
import "dayjs/locale/vi";
dayjs.locale("vi");
dayjs.prototype.getDayOfWeek = function () {
  const dayOfWeek = [
    "Chủ Nhật",
    "Thứ Hai",
    "Thứ Ba",
    "Thứ Tư",
    "Thứ Năm",
    "Thứ Sáu",
    "Thứ Bảy",
  ];
  return dayOfWeek[this.day()];
};
import axios from "axios";
import { focus } from "@alpinejs/focus";
import queryString from "query-string";
import * as bt from "bootstrap";
window.bootstrap = bt;
window.$ = window.jQuery = $;
require("../js/validation");
window.queryString = queryString;
window.axios = axios;
window.dayjs = dayjs;
Alpine.plugin(focus);
window.Alpine = Alpine;
window.addEventListener("DOMContentLoaded", () => {
  Alpine.start();
});
window.toVnd = function (number) {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(number);
};
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
