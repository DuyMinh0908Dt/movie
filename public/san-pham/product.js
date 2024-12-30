// Hiển thị trang thêm thông tin sản phẩm
var btnShowCreateProduct = document.getElementById("btn-create");
var create_product = document.getElementById("create-product-id");
var table_product = document.getElementById("show-table-product");
var modalState = "create";
// Hiển thị nội dung lên combo phân loại
const itemsProductType = document.querySelectorAll(".item-product-type");
const comboButtonType = document.getElementById("combo-product-type");

itemsProductType.forEach((item) => {
  item.addEventListener("click", function () {
    const selectedProdutType = this.textContent;
    comboButtonType.textContent = selectedProdutType;
  });
});

// Hiển thị nội dung lên combo trạng thái
const itemsProductStatus = document.querySelectorAll(".item-product-status");
const comboButtonStatus = document.getElementById("combo-product-status");

itemsProductStatus.forEach((item) => {
  item.addEventListener("click", function () {
    const selectedProductStatus = this.textContent;
    comboButtonStatus.textContent = selectedProductStatus;
  });
});

var textIdProduct = document.getElementById("product-id");
var textNameProduct = document.getElementById("product-name");
var textDesProduct = document.getElementById("product-des");
// var textTypeProduct = document.getElementById('');
var textPriceProduct = document.getElementById("product-price");
// var textStatusProduct = document.getElementById('');
var modal = document.getElementById("product-detail-modal");

// Button đóng Modal
var btnCloseProductDetail = document.getElementById("btn-close-product-detail");
btnCloseProductDetail.addEventListener("click", function () {
  if (modal) {
    $("#MaThucPham").val("");
    $("#TenThucPham input").val("");
    $("#HinhAnh input[type='text']").val("");
    $("#MoTa textarea").val("");
    $("#LoaiThucPham select").val("");
    $("#GiaThucPham input").val("");
    $("#TrangThai select").val("");
    $(modal).modal("hide");
    $("#edit-title").text("Thêm sản phẩm");
    $("#btn-save").text("Thêm sản phẩm");
    modalState = "create";
  }
});

// Button clear
var btnClearDes = document.getElementById("btn-clear-ticket-detail");
btnClearDes.addEventListener("click", function () {
  $("#MaThucPham").val("");
  $("#TenThucPham input").val("");
  $("#HinhAnh input[type='text']").val("");
  $("#MoTa textarea").val("");
  $("#LoaiThucPham select").val("");
  $("#GiaThucPham input").val("");
  $("#TrangThai select").val("");
  $(modal).modal("hide");
  $("#edit-title").text("Thêm sản phẩm");
  $("#btn-save").text("Thêm sản phẩm");
  modalState = "create";
});

let queryObj = queryString.parse(window.location.search, {
  arrayFormat: "bracket",
});

// lấy các element cần thiết
const searchMovieInput = $("#searchMovie");
const searchMovieBtn = $("#searchMovieBtn");
const priceFromInput = $("#gia-tien-tu");
const priceToInput = $("#gia-tien-den");
const clearFilterBtn = $("#clear-filter");
const applyFilterBtn = $("#apply-filter");
const limitSelect = $("#limit-select");
// khởi tạo giá trị mặc định cho các input
searchMovieInput.val(queryObj["tu-khoa"] || "");
priceFromInput.val(queryObj["gia-tu"] || "");
priceToInput.val(queryObj["gia-den"] || "");
limitSelect.val(queryObj["limit"] || 10);
let page = queryObj["trang"] || 1;

const loadingRowHtml = `<tr>
                        <td class="   tw-border-b tw-border-gray-50" colspan="6">
                            <div class='tw-w-full tw-flex tw-py-32 tw-items-center tw-justify-center'>
                                <span class="tw-loading tw-loading-dots tw-loading-lg"></span>
                            </div>
                        </td>
                    </tr>
                    `;

// render page
function renderPage(totalItems) {
  const totalPages = Math.ceil(totalItems / queryObj["limit"]);
  var pageRoot = $("#page-root");
  pageRoot.html("");
  if (totalPages <= 1) {
    return;
  }
  if (page > 1) {
    pageRoot.append(`<li class="page-item">
                            <a class="page-link" href="javascript:void(0)" onclick="changePage(${
                              page - 1
                            })" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>`);
  }
  for (let i = 1; i <= totalPages; i++) {
    pageRoot.append(`<li class="page-item">
                            <a class="page-link ${
                              page == i ? "active" : ""
                            }" href="javascript:void(0)" onclick="changePage(${i})">${i}</a>
                        </li>`);
  }
  if (page < totalPages) {
    pageRoot.append(`<li class="page-item">
                            <a class="page-link" href="javascript:void(0)" onclick="changePage(${
                              page + 1
                            })" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>`);
  }
}

function refetchAjax() {
  // cập nhật queryParam
  const queryStr = queryString.stringify(queryObj, {
    arrayFormat: "bracket",
  });
  page = queryObj["trang"] || 1;
  window.history.pushState({}, "", window.location.pathname + "?" + queryStr);
  // gọi ajax để lấy dữ liệu
  $("#table-body").html(loadingRowHtml);
  $.ajax({
    url: "/api/san-pham",
    type: "GET",
    data: {
      ...queryObj,
      "trang-thais": [20, 21],
    },
    success: function (data, status, request) {
      const total = request.getResponseHeader("x-total-count");
      renderPage(total);
      $("#table-body").html(data);
    },
    error: function (error) {
      $("#table-body").html('<tr><td colspan="6">Có lỗi xảy ra</td></tr>');
    },
  });
}
// sự kiện khi click vào nút tìm kiếm
searchMovieBtn.click(function () {
  queryObj["tu-khoa"] = searchMovieInput.val();
  queryObj["trang"] = 1;
  refetchAjax();
});
// sự kiện khi click vào nút xóa lọc
clearFilterBtn.click(function () {
  searchMovieInput.val("");
  priceFromInput.val("");
  priceToInput.val("");
  queryObj = {
    trang: 1,
    limit: 10,
  };
  refetchAjax();
});
// sự kiện khi click vào nút áp dụng lọc
applyFilterBtn.click(function () {
  queryObj["gia-tu"] = priceFromInput.val();
  queryObj["gia-den"] = priceToInput.val();
  queryObj["trang"] = 1;
  refetchAjax();
});

// sự kiện khi thay đổi số lượng sản phẩm trên 1 trang
limitSelect.change(function () {
  queryObj["limit"] = limitSelect.val();
  queryObj["trang"] = 1;
  refetchAjax();
});
// input enter
searchMovieInput.keypress(function (e) {
  if (e.which == 13) {
    searchMovieBtn.click();
  }
});

// hàm gọi khi chuyển trang
function changePage(page) {
  queryObj["trang"] = page;
  refetchAjax();
}
function createSort(sortBy) {
  const currentSort = queryObj["sap-xep"];
  if (currentSort === sortBy) {
    queryObj["thu-tu"] = queryObj["thu-tu"] === "ASC" ? "DESC" : "ASC";
  } else {
    queryObj["sap-xep"] = sortBy;
    queryObj["thu-tu"] = "ASC";
  }
  refetchAjax();
}
refetchAjax();

//form-input-product
// add on submit event prevent default

$("input").on("focus", function (e) {
  $(this).removeClass("is-invalid");
});
$("select").on("focus", function (e) {
  $(this).removeClass("is-invalid");
});
$("textarea").on("focus", function (e) {
  $(this).removeClass("is-invalid");
});

$("#form-input-product").submit(function (e) {
  e.preventDefault();
  // validate form
  const name = $("#TenThucPham input").val();
  const hinhanh = $("#HinhAnh input[type='text']").val();
  const mota = $("#MoTa textarea").val();
  const phanloai = $("#LoaiThucPham select").val();
  const gia = $("#GiaThucPham input").val();
  const trangthai = $("#TrangThai select").val();

  if (!name || name?.length < 3) {
    $("#TenThucPham input").addClass("is-invalid");
    $("#TenThucPham .invalid-feedback").text(
      "Tên thực phẩm có ít nhất 3 ký tự"
    );
    return;
  }
  if (!hinhanh) {
    $("#HinhAnh input[type='text']").addClass("is-invalid");
    $("#HinhAnh .invalid-feedback").text("Cần nhập hình ảnh");
    return;
  }
  if (!phanloai) {
    $("#LoaiThucPham select").addClass("is-invalid");
    $("#LoaiThucPham .invalid-feedback").text("Cần chọn phân loại");
    return;
  }
  if (!gia || isNaN(gia)) {
    if (isNaN(gia)) {
      $("#GiaThucPham input").addClass("is-invalid");
      $("#GiaThucPham .invalid-feedback").text("Giá tiền phải là số");
      return;
    }
    if (Number(gia) < 0) {
      $("#GiaThucPham input").addClass("is-invalid");
      $("#GiaThucPham .invalid-feedback").text("Giá tiền không được âm");
      return;
    }
  }
  if (!trangthai) {
    $("#TrangThai select").addClass("is-invalid");
    $("#TrangThai .invalid-feedback").text("Cần chọn trạng thái");
    return;
  }
  const formData = new FormData();
  formData.append("TenThucPham", name);
  formData.append("LoaiThucPham", phanloai);
  formData.append("GiaThucPham", gia);
  formData.append("MoTa", mota);
  formData.append("HinhAnh", hinhanh);
  formData.append("TrangThai", trangthai);

  $("#btn-save").prop("disabled", true);
  $("#btn-save span").hide();
  $("#btn-save .spinner-border").show();
  let url = "/api/san-pham";
  if (modalState == "edit") {
    url = "/api/san-pham/" + $("#MaThucPham").val().trim();
  }
  disableAllButton();

  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (res) {
      toast("Thành công", {
        position: "bottom-center",
        type: "success",
      });
      refetchAjax();
      $("#form-input-product").trigger("reset");
      $(modal).modal("hide");
    },
    error: function (error) {
      console.log(error);
      toast("Thất bại", {
        position: "bottom-center",
        type: "danger",
      });
    },
    complete: function () {
      $("#btn-save").prop("disabled", false);
      $("#btn-save span").show();
      $("#btn-save .spinner-border").hide();
      enableAllButton();
    },
  });
});

$("#HinhAnh input[type='file']").change(function (e) {
  $("#HinhAnh .spinner-border").show();
  const file = e.target.files[0];
  // upload file but use ajax from jquery
  const formData = new FormData();
  formData.append("file", file);
  $.ajax({
    url: "/api/file/upload",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (res) {
      $("#HinhAnh input[type='text']").val(res.data.path);
    },
    error: function (error) {
      toast("Upload file thất bại", {
        position: "bottom-center",
        type: "danger",
      });
    },
    complete: function () {
      $("#HinhAnh .spinner-border").hide();
    },
  });
});

function showEditModal(id) {
  $.ajax({
    url: "/api/san-pham/" + id,
    type: "GET",
    success: function (res) {
      $("#edit-title").text("Sửa sản phẩm #" + res.data.MaThucPham);
      $("#btn-save").text("Lưu thay đổi");
      modalState = "edit";
      const data = res.data;
      console.log(data.MaThucPham);
      $("#MaThucPham").val(data.MaThucPham);
      $("#TenThucPham input").val(data.TenThucPham);
      $("#HinhAnh input[type='text']").val(data.HinhAnh);
      $("#MoTa textarea").val(data.MoTa);
      console.log(data.LoaiThucPham);
      $("#LoaiThucPham select").val(data.LoaiThucPham);
      $("#GiaThucPham input").val(data.GiaThucPham);
      $("#TrangThai select").val(data.TrangThai);
      $(modal).modal("show");
    },
    error: function (error) {
      toast("Lỗi khi lấy dữ liệu", {
        position: "bottom-center",
        type: "danger",
      });
    },
  });
}
let selectedId = null;
function showDeleteModal(id) {
  selectedId = id;
  $("#delete-modal .modal-title").text("Xóa sản phẩm #" + id);
  $("#btn-delete").attr("data-id", id);
  $("#delete-modal").modal("show");
}

$("#btn-delete").click(function () {
  const id = selectedId;
  $.ajax({
    url: "/api/san-pham/" + id + "/delete",
    type: "POST",
    success: function (res) {
      toast("Thành công", {
        position: "bottom-center",
        type: "success",
      });
      refetchAjax();
      $("#delete-modal").modal("hide");
    },
    error: function (error) {
      toast("Thất bại", {
        position: "bottom-center",
        type: "danger",
      });
    },
  });
});

function onRecoverProduct(id) {
  $.ajax({
    url: "/api/san-pham/" + id + "/delete",
    type: "POST",
    success: function (res) {
      toast("Khôi phục thành công", {
        position: "bottom-center",
        type: "success",
      });
      refetchAjax();
    },
    error: function (error) {
      toast("Khôi phục thất bại", {
        position: "bottom-center",
        type: "danger",
      });
    },
  });
}
