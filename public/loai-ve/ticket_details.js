// button close Modal
var modal = document.getElementById("type-ticket-detail-modal");
var txtIdTypeTicket = document.getElementById("type-ticket-id");
var txtNameTypeTicket = document.getElementById("type-ticket-name");
var txtPriceTypeTicket = document.getElementById("type-ticket-price");
var txtDesTypeTicket = document.getElementById("type-ticket-des");

// Button đóng modal loại vé
var btnCloseTypeTicketDetail = document.getElementById(
  "btn-close-type-ticket-detail"
);
btnCloseTypeTicketDetail.addEventListener("click", function () {
  if (modal) {
    txtIdTypeTicket.value = "";
    txtNameTypeTicket.value = "";
    txtPriceTypeTicket.value = "";
    txtDesTypeTicket.value = "";
    $(modal).modal("hide");
  }
});

// Button cancel loại vé
var btnCancelTypeTicketDetail = document.getElementById(
  "btn-cancel-type-ticket"
);
btnCancelTypeTicketDetail.addEventListener("click", function () {
  txtIdTypeTicket.value = "";
  txtNameTypeTicket.value = "";
  txtPriceTypeTicket.value = "";
  txtDesTypeTicket.value = "";
  $(modal).modal("hide");
});

// Button save loại vé
var btnSaveTypeTicketDetail = document.getElementById("btn-save-type-ticket");
btnSaveTypeTicketDetail.addEventListener("click", function () {});

function validateForm() {
  let isValid = true;

  if (Number($("#type-ticket-price").val()) < 0) {
    $("#type-ticket-price").addClass("is-invalid");
    $("#type-ticket-price-feedback").text("Giá vé phải lớn hơn 0");
    $("#type-ticket-price").one("input", function () {
      $("#type-ticket-price").removeClass("is-invalid");
    });
    isValid = false;
  }

  return isValid;
}
$("#type-ticket-detail-form").on("submit", function (e) {
  e.preventDefault();

  if (!validateForm()) {
    return;
  }
  const id = txtIdTypeTicket.value;
  const name = txtNameTypeTicket.value;
  const price = txtPriceTypeTicket.value;
  const des = txtDesTypeTicket.value;
  const rong = $("#type-ticket-seat").val();
  let url = "/api/loai-ve";
  if (id) {
    url = `/api/loai-ve/${id}/sua`;
  }
  const formData = new FormData();
  formData.append("TenLoaiVe", name);
  formData.append("GiaVe", price);
  formData.append("MoTa", des);
  formData.append("Rong", rong);

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
    url: `/api/loai-ve/${id}`,
    type: "GET",
    success: function (data) {
      const typeTicket = data.data;
      console.log(typeTicket);
      txtIdTypeTicket.value = id;
      txtNameTypeTicket.value = typeTicket.TenLoaiVe;
      txtPriceTypeTicket.value = typeTicket.GiaVe;
      txtDesTypeTicket.value = typeTicket.MoTa;
      $("#type-ticket-seat").val(typeTicket.Rong);
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

function onRecoverLoaiVe(id) {
  $.ajax({
    url: `/api/loai-ve/${id}/toggleHienThi`,
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
    url: `/api/loai-ve/${currentSelectedId}/toggleHienThi`,
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
