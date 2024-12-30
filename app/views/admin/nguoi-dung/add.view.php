<?php
title("Thêm tài khoản");
require ('app/views/admin/header.php');
?>

<link rel="stylesheet" href="/public/tiendat/infoAccount.css">
<style>
    h4 {
        font-size: 1.25rem;
        font-weight:600;
    }
</style>
<div x-data="" style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="p-5 wrapper">
    <form class="p-4 shadow info-account container-fluid"x-on:submit.prevent="
        let SDT=document.getElementById('sodienthoai').value;
        let ten=document.getElementById('tennguoidung').value;
        let ngaySinh=document.getElementById('ngaysinh').value;
        let diaChi=document.getElementById('diachi').value;
        let email=document.getElementById('email').value;
        let data={
            'TenNguoiDung' : ten ,
            'SoDienThoai' : SDT,
            'DiaChi' : diaChi,
            'NgaySinh' : ngaySinh,
            'Email': email
        };
        axios.post('/api/nguoi-dung',data).then(()=>{
            toast('Tạo thành công', {
                position: 'bottom-center',
                type: 'success'
            });
            return;
        }).catch((e)=>{
            toast('Thất bại', {
                position: 'bottom-center',
                type: 'danger',
                description: e.response.data.message
            });
            return;
        })">
          <div class='tw-flex tw-justify-between tw-items-center'>
        <h4>Thêm người dùng</h4>
        <a href="/admin/nguoi-dung" data-ripple-light="true" class=" tw-btn tw-btn-ghost" type="button">

                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-narrow-left">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 12l14 0" />
                    <path d="M5 12l4 4" />
                    <path d="M5 12l4 -4" />
                </svg>
                Quay lại
            </a>
</div>
        <div>
            <div class="mb-3">
                <label for="tennguoidung" class="form-label">Tên người dùng</label>
                <input type="text" class="form-control" id="tennguoidung" 
                minlength="3" maxlength="100" required>
            </div>
            <div class="mb-3 row">
                <div class="col">
                    <label for="sodienthoai" class="form-label">Số điện thoại</label>
                    <input type="text"
                   pattern="^((\+84|0)[1-9][0-9]{8})$" title="Số điện thoại không hợp lệ" class="form-control" id="sodienthoai" required>
                </div>

                <div class="col">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
            </div>
         <div class="mb-3 col ">
                    <label for="ngaysinh" class="form-label">Ngày sinh</label>
                    <input type="date" class="form-control" id="ngaysinh" required>
                </div>

         

            <div class="mb-3">
                <label for="diachi" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" id="diachi" >
            </div>
        </div>
        <div class="gap-2 d-grid d-md-flex justify-content-md-end">
            <button class="btn btn-primary" type="submit">Lưu</button>
        </div>
    </form>
</div>


<script>
function onTheLoaiChange(event, text) {
    var tenTheLoaiElement = document.getElementById('theloai');
    if (event.target.checked == true) {
        tenTheLoaiElement.value = tenTheLoaiElement.value + '  ' + text;
    } else {
        tenTheLoaiElement.value = tenTheLoaiElement.value.replace('  ' + text, '');
    }
}
</script>
<?php
require ('app/views/admin/footer.php');


?>