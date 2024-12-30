// button close Modal
var modal = document.getElementById("type-seat-detail-modal");
var txtIdTypeSeat = document.getElementById("type-seat-id");
var txtNameTypeSeat = document.getElementById("type-seat-name");
var txtPriceTypeSeat = document.getElementById("type-seat-price");
var txtDesTypeSeat = document.getElementById("type-seat-des");
const colorInput = document.getElementById("type-seat-color");

var btnCloseTypeSeatDetail = document.getElementById(
  "btn-close-type-seat-detail"
);
btnCloseTypeSeatDetail.addEventListener("click", function () {
  if (modal) {
    $(modal).modal("hide");
  }
});
$(modal).on("hidden.bs.modal", function () {
  $("#type-seat-detail-form").trigger("reset");
  $("#type-seat-detail-form .is-invalid").removeClass("is-invalid");
});
// Button cancel loại vé
var btnCancelTypeSeatDetail = document.getElementById("btn-cancel-type-seat");
btnCancelTypeSeatDetail.addEventListener("click", function () {
  $(modal).modal("hide");
});

// Button save loại vé
var btnSaveTypeSeatDetail = document.getElementById("btn-save-type-seat");
btnSaveTypeSeatDetail.addEventListener("click", function () {});

function validateForm() {
  let isValid = true;

  if (Number($("#type-seat-price").val()) <= 0) {
    $("#type-seat-price").addClass("is-invalid");
    $("#type-seat-price-feedback").text("Giá vé phải lớn hơn 0");
    $("#type-seat-price").one("input", function () {
      $("#type-seat-price").removeClass("is-invalid");
    });
    isValid = false;
  }

  return isValid;
}
$("#type-seat-detail-form").on("submit", function (e) {
  e.preventDefault();
  if (!validateForm()) {
    return;
  }
  const id = txtIdTypeSeat.value;
  const name = txtNameTypeSeat.value;
  const price = txtPriceTypeSeat.value;
  const des = txtDesTypeSeat.value;
  const rong = $("#type-seat-seat").val();
  let url = "/api/loai-ghe";
  if (id) {
    url = `/api/loai-ghe/${id}/sua`;
  }
  const formData = new FormData();
  formData.append("TenLoaiGhe", name);
  formData.append("GiaVe", price);
  formData.append("MoTa", des);
  formData.append("Rong", rong);
  formData.append("Mau", colorInput.value);
  console.log({
    TenLoaiGhe: name,
    GiaVe: price,
    MoTa: des,
    Rong: rong,
    Mau: colorInput.value,
  });
  toast("Đang xử lý", {
    position: "bottom-center",
    type: "info",
  });
  disableAllButton();

  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (data) {
      refetchAjax();
      $(modal).modal("hide");
      toast("Thành công", {
        position: "bottom-center",
        type: "success",
      });
    },
    error: function (error) {
      console.log(error);
      toast("Có lỗi xảy ra, vui lòng thử lại sau", {
        position: "bottom-center",
        type: "danger",
      });
    },
    complete: function () {
      enableAllButton();
    },
  });
});

function showEditModal(id) {
  $.ajax({
    url: `/api/loai-ghe/${id}`,
    type: "GET",
    success: function (data) {
      const typeSeat = data.data;
      txtIdTypeSeat.value = id;
      txtNameTypeSeat.value = typeSeat.TenLoaiGhe;
      txtPriceTypeSeat.value = typeSeat.GiaVe;
      txtDesTypeSeat.value = typeSeat.MoTa;
      colorInput.value = typeSeat.Mau;
      $("#type-seat-seat").val(typeSeat.Rong);
      $(modal).modal("show");
    },
    error: function (error) {
      toast("Có lỗi xảy ra, vui lòng thử lại sau", {
        position: "bottom-center",
        type: "danger",
        description: error.responseJSON.message,
      });
    },
  });
}

let currentSelectedId = null;
function showDeleteModal(id) {
  currentSelectedId = id;
  $("#delete-modal").modal("show");
  $("#delete-modal .modal-title").text("Xóa loại vé #" + id);
}

function onRecoverLoaiGhe(id) {
  $.ajax({
    url: `/api/loai-ghe/${id}/toggleHienThi`,
    type: "POST",
    success: function (data) {
      refetchAjax();
      toast("Thành công", {
        position: "bottom-center",
        type: "success",
      });
    },
    error: function (error) {
      toast("Có lỗi xảy ra", {
        position: "bottom-center",
        type: "danger",
        description: error.responseJSON.message,
      });
    },
  });
}

$("#btn-delete").on("click", function () {
  $.ajax({
    url: `/api/loai-ghe/${currentSelectedId}/toggleHienThi`,
    type: "POST",
    success: function (data) {
      refetchAjax();
      $("#delete-modal").modal("hide");
      toast("Thành công", {
        position: "bottom-center",
        type: "success",
      });
    },
    error: function (error) {
      toast("Có lỗi xảy ra", {
        position: "bottom-center",
        type: "danger",
        description: error.responseJSON.message,
      });
    },
  });
});

function refetchAjax() {
  window.location.reload();
}
