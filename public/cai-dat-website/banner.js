// Create new Banner
var modalCreateBanner = document.getElementById("banner-modal");
var txtChooseBanner = document.getElementById("new-banner-img");
var txtLinkBanner = document.getElementById("new-banner-link");

// btn close modal create new banner
var btnCloseModalBanner = document.getElementById("btn-close-modal-banner");
btnCloseModalBanner.addEventListener("click", function () {
  if (modalCreateBanner) {
    $(modalCreateBanner).modal("hide");
  }
});

// btn save modal banner
var btnSaveModalBanner = document.getElementById("btn-save-modal-banner");

// btn cancel modal banner
var btnCancelModalBanner = document.getElementById("btn-cancel-modal-banner");
btnCancelModalBanner.addEventListener("click", function () {
  txtChooseBanner.value = "";
  txtLinkBanner.value = "";
  $(modalCreateBanner).modal("hide");
});

// Banner detail
var modalDetailBanner = document.getElementById("banner-detail-modal");
var txtChooseDetailBanner = document.getElementById("detail-banner-img");
var txtLinkDetailBanner = document.getElementById("detail-banner-link");

// btn close modal detail banner
var btnCloseModalDetailBanner = document.getElementById(
  "btn-close-modal-detail-banner"
);
btnCloseModalDetailBanner.addEventListener("click", function () {
  if (modalDetailBanner) {
    $(modalDetailBanner).modal("hide");
  }
});

// btn delete detail banner
var btnDeleteDetailBanner = document.getElementById(
  "btn-delete-modal-detail-banner"
);
btnDeleteDetailBanner.addEventListener("click", function () {
  $(modalDetailBanner).modal("hide");
});

// btn cancel detail banner
var btnCancelDetailBanner = document.getElementById(
  "btn-cancel-modal-detail-banner"
);
btnCancelDetailBanner.addEventListener("click", function () {
  $(modalDetailBanner).modal("hide");
});
