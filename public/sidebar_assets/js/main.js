const bodyTag = document.querySelector("body");
const switchDark = bodyTag.querySelector(".toggle-color-bg__switch");
const closeSidebarBtn = bodyTag.querySelector(".toggle");
const sidebar = bodyTag.querySelector(".sidebar");
const darkText = bodyTag.querySelector(".dark-mode");

closeSidebarBtn.addEventListener("click", () => {
  sidebar.classList.toggle("close");
});
