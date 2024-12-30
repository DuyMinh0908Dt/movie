<?php
title("Thêm tài khoản");
require ('app/views/admin/header.php');
?>

<link rel="stylesheet" href="/public/tiendat/infoAccount.css">

<div x-data="{}" x-on:submit.prevent="
        const tendangnhap = $el.querySelector('#tendangnhap').value;
        const matkhau = $el.querySelector('#matkhau').value;
        const trangthai = $el.querySelector('#trangthai').value;
        const maNguoiDung = $el.querySelector('#maNguoiDung').value;
        const phanquyen = $el.querySelector('#phanquyen').value;
       
        const data = {
            TenDangNhap: tendangnhap,
            MatKhau: matkhau,
            TrangThai: trangthai,
            LoaiTaiKhoan: 1,
            MaNguoiDung: maNguoiDung,
            MaNhomQuyen: phanquyen
        };
        axios.post('', data).then(res => {
             toast('Thành công', {
                position: 'bottom-center',
                type: 'success',
                description: 'Thêm tài khoản thành công'
            });
        }).catch(err => {
            errors = err.response.data.errors;
            toast('Thất bại', {
                position: 'bottom-center',
                type: 'error',
                description: errors
            });
        });
    
        " style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="wrapper p-5">
    <form class="info-account container-fluid p-4 shadow">

        <h4 class="mt-4 tw-text-xl tw-font-bold">THÔNG TIN TÀI KHOẢN</h4>
        <div>
            <div class="row mb-3">
                <div class="col">
                    <label for="tendangnhap" class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control" id="tendangnhap" required>
                </div>
                <div class="col tw-flex tw-flex-col">
                    <label for="tendangnhap" class="form-label">
                        Của người dùng
                    </label>
                    <style>
                    .dropdown.bootstrap-select {
                        width: 100% !important;
                    }
                    </style>
                    <select class='form-select' id="maNguoiDung">
                        <?php foreach ($employees as $nguoidung): ?>
                        <option data-tokens="<?= $nguoidung['TenNguoiDung'] ?> <?= $nguoidung['MaNguoiDung'] ?> "
                            value="<?= $nguoidung['MaNguoiDung'] ?>">
                            <?= $nguoidung['MaNguoiDung'] ?> - <?= $nguoidung['TenNguoiDung'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>
            <div class="row mb-3">


                <div class="col">
                    <label for="phanquyen" class="form-label" for>Phân quyền</label>
                    <select class="form-select" name id="phanquyen" required>

                        <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['MaNhomQuyen'] ?>">
                            <?= $role['TenNhomQuyen'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="matkhau" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="matkhau" required>
                </div>

                <div class="col">
                    <label for="trangthai" class="form-label">Trạng thái</label>
                    <select class="form-select" name id="trangthai" required>
                        <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status['MaTrangThai'] ?>">
                            <?= $status['Ten'] ?>
                        </option>
                        <?php endforeach; ?>

                    </select>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button class="btn btn-primary me-md-2" type="button">Reset</button>
            <button class="btn btn-primary" type="submit">Lưu</button>
        </div>
    </form>

</div>


<script>

</script>
<?php
require ('app/views/admin/footer.php');


?>