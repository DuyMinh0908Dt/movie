var modalCreatePosition = document.getElementById('position-modal');
var txtPositionName = document.getElementById('POSITION_NAME');
var txtPositionDes = document.getElementById('POSITION_DESCRIPTION');
var txtPositionStartDay = document.getElementById('START_DAY');
var txtPositionEndDay = document.getElementById('END_DAY');
var txtProMaxDiscount = document.getElementById('PROMO_Max_Discount');
var txtPromoDiscountValue = document.getElementById('PROMO_Discount_Value');
var txtPromoUsageLimit = document.getElementById('PROMO_USAGE_LIMIT');
var txtPromoLimit = document.getElementById('PROMO_LIMIT');
var txtPromoMinValue = document.getElementById('PROMO_MIN_VALUE');
var txtMinPoint = document.getElementById('Min_Point');

// btn close modal create position
var btnCloseModalCreatePosition = document.getElementById('btn-close-modal-position');
btnCloseModalCreatePosition.addEventListener('click', function() {
    if (modalCreatePosition) {

        $(modalCreatePosition).modal('hide');
    }
});


// btn save modal create position
var btnSaveModalCreatePosition = document.getElementById('btn-save-mcp');
btnSaveModalCreatePosition.addEventListener('click', function() {
    $(modalCreatePosition).modal('hide');
});


// btn cancel modal create position
var btnCancelModalCreatePosition = document.getElementById('btn-cancel-mcp');
btnCancelModalCreatePosition.addEventListener('click', function() {
    txtPositionName.value = "";
    txtPositionDes.value = "";
    txtPositionStartDay.value = "";
    txtPositionEndDay.value = "";
    txtProMaxDiscount.value = "";
    txtPromoDiscountValue.value = "";
    txtPromoUsageLimit.value = "";
    txtPromoLimit.value = "";
    txtPromoMinValue.value = "";
    txtMinPoint.value = "";
    
    $(modalCreatePosition).modal('hide');
});


// modal update position
var modalPositionDetail = document.getElementById('position-detail-modal');
modalPositionDetail.addEventListener('click', function() {
    if (modalPositionDetail) {

        $(modalPositionDetail).modal('hide');
    }
});

var btnSavePositionDetail = document.getElementById('btn-save-position-detail');
btnSavePositionDetail.addEventListener('click', function() {

    $(modalPositionDetail).modal('hide');
})


const allMovie_btn = document.getElementById('all');
        const movieShowing_btn = document.getElementById('movieshowing');
        const upComingMovie_btn = document.getElementById('upcomingmovie');
        const movieShown_btn = document.getElementById('movieshown');
        const sortNumDown_icon = document.getElementById('sortNumDown_icon');
        const sortNumUp_icon = document.getElementById('sortNumUp_icon');
        const sortAlphaDown_icon = document.getElementById('sortAlphaDown_icon');
        const sortAlphaUp_icon = document.getElementById('sortAlphaUp_icon');

        function optionOfList(button) {
            button.remove
            if (button.id == 'all') {
                allMovie_btn.classList.add('button-nav-active');
                setupButtonInavActive(movieShowing_btn);
                setupButtonInavActive(upComingMovie_btn);
                setupButtonInavActive(movieShown_btn);
            } else if (button.id == 'movieshowing') {
                movieShowing_btn.classList.add('button-nav-active');
                setupButtonInavActive(allMovie_btn);
                setupButtonInavActive(upComingMovie_btn);
                setupButtonInavActive(movieShown_btn);
            } else if (button.id == 'upcomingmovie') {
                upComingMovie_btn.classList.add('button-nav-active');
                setupButtonInavActive(allMovie_btn);
                setupButtonInavActive(movieShowing_btn);
                setupButtonInavActive(movieShown_btn);
            } else {
                movieShown_btn.classList.add('button-nav-active');
                setupButtonInavActive(allMovie_btn);
                setupButtonInavActive(movieShowing_btn);
                setupButtonInavActive(upComingMovie_btn);
            }
        }

        function setupButtonInavActive(button) {
            button.classList.remove('button-nav-active');
        }

        function sortByIdMovie() {
            if (sortNumDown_icon.classList.contains('d-none')) {
                sortNumDown_icon.classList.remove('d-none');
                sortNumUp_icon.classList.add('d-none');
            } else {
                sortNumUp_icon.classList.remove('d-none');
                sortNumDown_icon.classList.add('d-none');
            }
        }

        function sortByNameMovie() {
            if (sortAlphaDown_icon.classList.contains('d-none')) {
                sortAlphaDown_icon.classList.remove('d-none');
                sortAlphaUp_icon.classList.add('d-none');
            } else {
                sortAlphaUp_icon.classList.remove('d-none');
                sortAlphaDown_icon.classList.add('d-none');
            }
        }