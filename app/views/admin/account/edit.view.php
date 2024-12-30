<?php
title("Thêm tài khoản");
require ('app/views/admin/header.php');
?>

<link rel="stylesheet" href="/public/tiendat/infoAccount.css">

<div style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="wrapper p-5">
    <div class="info-account container-fluid p-4 shadow">

        <h4 class="mt-4 tw-text-xl tw-font-bold">THÔNG TIN TÀI KHOẢN</h4>
        <form>
            <div class="row mb-3">
                <div class="col">
                    <label for="tendangnhap" class="form-label">Tên đăng nhập</label>
                    <input value="<?= $account['TenDangNhap'] ?>" type="text" class="form-control" id="tendangnhap"
                        required>
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
                    <select class='form-select' disabled>
                        <?php foreach ($employees as $nguoidung): ?>
                        <option data-tokens="<?= $nguoidung['TenNguoiDung'] ?> <?= $nguoidung['MaNguoiDung'] ?> "
                            value="<?= $nguoidung['MaNguoiDung'] ?>" <?php if ($nguoidung['MaNguoiDung'] == $account['MaNguoiDung'])
                                      echo 'selected' ?>>
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
                        <option value="<?= $role['MaNhomQuyen'] ?>" <?php if ($role['MaNhomQuyen'] == $account['MaNhomQuyen'])
                                  echo 'selected' ?>>
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
        </form>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button class="btn btn-primary me-md-2" type="button">Reset</button>
            <button class="btn btn-primary" type="submit">Lưu</button>
        </div>
    </div>

</div>


<script>

</script>
<?php
require ('app/views/admin/footer.php');


?>