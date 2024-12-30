var modalCreateTypeOfFilm = document.getElementById('type-of-film-modal');
var txtTenTheLoai = document.getElementById('tof-name');
// var txtTrangThaiTheLoai = document.getElementById('');

// btn close modal
var btnCloseModalTOF = document.getElementById('btn-close-modal-tof');
btnCloseModalTOF.addEventListener('click', function() {
    $(modalCreateTypeOfFilm).modal('hide');
});

// btn save modal
var btnSaveModalTOF = document.getElementById('btn-save-modal-tof');
btnSaveModalTOF.addEventListener('click', function() {
    txtTenTheLoai.value = "";

    $(modalCreateTypeOfFilm).modal('hide');
});

// btn cancel modal
var btnCanncelModalTOF = document.getElementById('btn-cancel-modal-tof');
btnCanncelModalTOF.addEventListener('click', function(){
    txtTenTheLoai.value = "";

    $(modalCreateTypeOfFilm).modal('hide');
})