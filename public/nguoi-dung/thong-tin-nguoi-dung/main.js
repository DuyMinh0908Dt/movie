function updatePassWord(formId, endpoint) {
    $('#' + formId).on('submit', function(){
        $('#showerror').html('');

        var matKhauCu = $('#' + formId + ' #matKhauCu').val();
        var matKhauMoi = $('#' + formId + ' #matKhauMoi').val();
        var xacThucMatKhauMoi = $('#' + formId + ' #xacThucMatKhauMoi').val();

        if (matKhauCu.trim() == '') {
            alert('Bạn chưa nhập mật khẩu cũ.');
            return false;
        }

        if (matKhauMoi.trim() == '') {
            alert('Bạn chưa nhập mật khẩu mới.');
            return false;
        }

        if (xacThucMatKhauMoi.trim() == '') {
            alert('Bạn chưa nhập xác thực mật khẩu.');
            return false;
        }

        if (matKhauMoi !== xacThucMatKhauMoi) {
            alert('Mật khẩu mới không trùng khớp.');
            return false;
        }

        $.ajax ({
            url : endpoint,
            type : 'POST',
            dataType : 'json',
            data : {
                matKhauCu : matKhauCu,
                matKhauMoi : matKhauMoi,
                xacThucMatKhauMoi : xacThucMatKhauMoi
            },
            success : function (result) {
                if (!result.hasOwnProperty('status') || result.status !== 'success') {
                    alert('Có lỗi xảy ra khi thay đổi mật khẩu');
                    return false;
                }

                alert('Thay đổi mật khẩu thành công.');
            },
            error : function () {
                alert('Đã xảy ra lỗi. Vui lòng thử lại sau.');
            }
        });

        return false;
    });
}