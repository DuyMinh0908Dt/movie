//-----------------show navbar-mobile----------------
var checkbox = document.getElementById("checkShowMenu");

console.log(checkbox);

// Hủy chọn checkbox khi chiều rộng màn hình lớn hơn 768px
function uncheckCheckboxOnLargeScreen() {
  if (window.innerWidth > 768) {
    console.log("ff");
    checkbox.checked = false;
  }
}

// Gọi hàm khi trang được tải và khi cửa sổ được resize
window.onload = uncheckCheckboxOnLargeScreen;
window.onresize = uncheckCheckboxOnLargeScreen;
