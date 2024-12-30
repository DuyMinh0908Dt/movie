<?php
use App\Core\Request;
use App\Dtos\LoaiTaiKhoan;

title("Well come cinestar");
$isKhachHang = Request::getUser()['TaiKhoan']['LoaiTaiKhoan'] == LoaiTaiKhoan::KhachHang->value;
require ('app/views/partials/head.php'); ?>
<link rel="stylesheet" href="/public/nguoi-dung/main.css">
<div class="p-3 acc-page container-fluid px-sm-0">
    <div class="m-1 row m-sm-0 p-md-3 p-lg-5 mx-xxl-5">
        <!-- account sidebar -->
        <div class="col-sm-12 col-md-4 mb-sm-4">
            <div class="p-3 rounded shadow acc-sidebar container-fluid">
                <div class="text-center row d-flex justify-content-center align-items-md-center">
                    <div class>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle"
                            width="130" height="130" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                        </svg>
                    </div>

                    <div>
                        <span>

                            <?php if ($isKhachHang) {
                                echo "Thành viên";
                            } else {
                                echo "Nhân viên";
                            } ?>
                        </span>
                    </div>

                    <div class="mb-3">
                        <span class="fw-semibold fs-3">
                            <?= $userif["TenNguoiDung"] ?>
                        </span>
                    </div>
                    <?php if ($isKhachHang): ?>
                        <div class="acc-points text-start">
                            <div class="mb-2">
                                <span class="fw-semibold">Tích điểm thành
                                    viên</span>
                            </div>

                            <div class="mb-2 bock-bar">
                                <div class="curr-bar" style="width: 1.8%;"></div>
                            </div>

                            <div>
                                <span class="fw-semibold">
                                    <?= $userif["DiemTichLuy"] ?? 0 ?> điểm
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div>
                        <hr class="my-3">
                    </div>
                </div>

                <div class="row align-items-sm-center">
                    <div class="mb-4 acc-sidebar__items col-sm-6 mb-sm-0 col-md-12 mb-md-3">
                        <a href="/nguoi-dung/thong-tin"
                            class="active d-flex align-items-center justify-content-sm-center justify-content-md-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle"
                                width="30" height="30" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                            </svg>
                            <span class="px-2 fs-5 fw-semibold">Thông
                                tin

                                <?= $isKhachHang ? "khách hàng" : "nhân viên" ?>
                            </span>
                        </a>
                    </div>

                    <?php if ($isKhachHang): ?>
                        <div class="acc-sidebar__items col-sm-6 col-md-12">
                            <a href="/nguoi-dung/lich-su-dat-ve"
                                class="d-flex align-items-center justify-content-sm-center justify-content-md-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-history"
                                    width="30" height="30" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 8l0 4l2 2" />
                                    <path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" />
                                </svg>
                                <span class="px-2 fs-5 fw-semibold">Lịch sử
                                    mua
                                    hàng</span>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="d-sm-none d-md-block">
                        <hr class="my-3">
                    </div>
                </div>

                <div class="row d-sm-none d-md-block">
                    <div class="acc-logout">
                        <a href="/dang-xuat" class="logout d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout"
                                width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                <path d="M9 12h12l-3 -3" />
                                <path d="M18 15l3 -3" />
                            </svg>

                            <span class="px-2 fs-5 fw-semibold">Đăng
                                xuất</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- thông tin khách hàng -->
        <div class="col-sm-12 col-md-8">
            <div class="p-0 container-fluid">
                <div class="mb-3 text-white fs-1 fw-bold">Thông tin <?php if ($isKhachHang) {
                    echo "khách hàng";
                } else {
                    echo "nhân viên";
                } ?></div>

                <div class="p-4 mb-4 bg-white rounded shadow" x-data="
                    formValidator({
                        TenNguoiDung:{
                            required: {
                                message: 'Họ tên không được để trống'
                            },
                            minLength: {
                                value: 3,
                                message: 'Họ tên phải chứa ít nhất 3 ký tự'
                            },
                            maxLength: {
                                value: 50,
                                message: 'Họ tên không được vượt quá 50 ký tự'
                            },
                            default: '<?= $userif['TenNguoiDung'] ?>'
                        },
                        NgaySinh: {
                            required: {
                                message: 'Ngày sinh không được để trống'
                            },
                            default: '<?= $userif['NgaySinh'] ?>'
                        },
                        SoDienThoai: {
                            required: {
                                message: 'Số điện thoại không được để trống'
                            },
                            pattern: {
                                value: /^(0|84|\+84)[3|5|7|8|9][0-9]{8}$/,
                                message: 'Số điện thoại không hợp lệ'
                            },
                            default: '<?= $userif['SoDienThoai'] ?>'
                        },

                        DiaChi: {
                            required: {
                                message: 'Địa chỉ không được để trống'
                            },
                            default: '<?= $userif['DiaChi'] ?>'
                        }
                        
                    })
                ">
                    <div class="mb-3 fs-2 fw-bold">Thông tin cá
                        nhân</div>
                    <form x-on:submit.prevent="
                        if(!validate()) return;
                        const res = await axios.post('/api/nguoi-dung/thong-tin', data, {validateStatus: ()=>true});
                        if(res.status === 200) {
                            toast('Cập nhật thông tin thành công', {
                                position: 'bottom-center',
                                type: 'success',
                            });
                        } else {
                          errors =  res.data.errors;
                        }
                    ">
                        <div class="row">
                            <div class="col-6 col-sm-12 col-md-6">
                                <div class="mb-3">
                                    <label for="hoTenKhachHang" class="form-label">Họ và
                                        tên</label>
                                    <input type="text" x-model="data.TenNguoiDung" class="form-control"
                                        id="hoTenKhachHang" :class="{ 'is-invalid': errors.TenNguoiDung }"
                                        value="<?= $userif["TenNguoiDung"] ?>" required>
                                    <div class="invalid-feedback" x-text="errors.TenNguoiDung">Họ tên không hợp lệ</div>
                                </div>
                            </div>

                            <div class="col-6 col-sm-12 col-md-6">
                                <div class="mb-3">
                                    <label for="ngaySinh" class="form-label">Ngày
                                        sinh</label>
                                    <input x-model="data.NgaySinh" :class="{ 'is-invalid': errors.NgaySinh }"
                                        type="date" class="form-control" id="ngaySinh"
                                        value="<?= $userif["NgaySinh"] ?>" required>
                                    <div x-text="errors.NgaySinh" class="invalid-feedback">Họ tên không hợp lệ</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 col-sm-12 col-md-6">
                                <div class="mb-3">
                                    <label for="soDienThoai" class="form-label">Số điện
                                        thoại</label>
                                    <input type="text" x-model="data.SoDienThoai"
                                        :class="{ 'is-invalid': errors.SoDienThoai }" class="form-control"
                                        id="soDienThoai" pattern="^(0|84|\+84)[3|5|7|8|9][0-9]{8}$"
                                        title="Số điện thoại không hợp lệ" value="<?= $userif["SoDienThoai"] ?>"
                                        required>
                                    <div x-text="errors.SoDienThoai" class="invalid-feedback">Họ tên không hợp lệ</div>
                                </div>
                            </div>

                            <div class="col-6 col-sm-12 col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" disabled class="form-control" id="email"
                                        value="<?= $userif["Email"] ?>">
                                    <div class="invalid-feedback">Họ tên không hợp lệ</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 mb-sm-4 mb-md-3">
                            <label for="diaChi" class="form-label">Địa chỉ</label>
                            <textarea x-model="data.DiaChi" :class="{ 'is-invalid': errors.DiaChi }"
                                class="form-control" id="diaChi" required><?= $userif["DiaChi"] ?></textarea>
                            <div class="invalid-feedback">Họ tên không hợp lệ</div>
                        </div>

                        <button type="submit" class="btn submit-btn col-sm-12 col-md-auto">Lưu thông
                            tin</button>
                    </form>
                </div>

                <div x-data="
                    formValidator({
                        MatKhauCu: '',
                        MatKhauMoi: '',
                        XacThucMatKhauMoi: ''
                    })
                " class="p-4 bg-white rounded shadow">
                    <div class="mb-3 fs-2 fw-bold">Đổi mật khẩu</div>
                    <form x-on:submit.prevent="
                        if(!validate()) return;
                        if(data.MatKhauMoi !== data.XacThucMatKhauMoi) {
                            errors.XacThucMatKhauMoi = 'Mật khẩu không khớp';
                            return;
                        }
                        const res = await axios.post('/api/nguoi-dung/mat-khau', data, {validateStatus: ()=>true});
                        console.log(res);
                        if(res.status === 200) {
                            toast('Đổi mật khẩu thành công', {
                                position: 'bottom-center',
                                type: 'success',
                            });
                            data= {
                                MatKhauCu: '',
                                MatKhauMoi: '',
                                XacThucMatKhauMoi: ''
                            };
                        } else {
                          errors =  res.data.errors;
                        }

                    " id="form-dmk">
                        <div class="mb-3">
                            <label for="matKhauCu" class="form-label">Mật khẩu cũ</label>
                            <input type="password" autocomplete="current-password" x-model="data.MatKhauCu"
                                :class="{ 'is-invalid': errors.MatKhauCu }" pattern=".{6,}"
                                title="Mật khẩu phải chứa ít nhất 6 ký tự" class="form-control" id="MatKhauCu"
                                name="matKhauCu" required>
                            <div x-text="errors.MatKhauCu" class="invalid-feedback">Mật khẩu không hợp lệ</div>
                        </div>

                        <div class="mb-3">
                            <label for="matKhauMoi" class="form-label">Mật khẩu mới</label>
                            <input type="password" autocomplete="new-password" x-model="data.MatKhauMoi"
                                :class="{ 'is-invalid': errors.MatKhauMoi }" pattern=".{6,}"
                                title="Mật khẩu phải chứa ít nhất 6 ký tự" class="form-control" id="MatKhauMoi"
                                name="matKhauMoi" required>
                            <div x-text="errors.MatKhauMoi" class="invalid-feedback">Mật khẩu không hợp lệ</div>
                        </div>

                        <div class="mb-3 mb-sm-4 mb-md-3">
                            <label for="xacThucKhauMoi" class="form-label">Xác thực mật
                                khẩu</label>
                            <input type="password" autocomplete="new-password" x-model="data.XacThucMatKhauMoi"
                                :class="{ 'is-invalid': errors.XacThucMatKhauMoi }" pattern=".{6,}"
                                title="Mật khẩu phải chứa ít nhất 6 ký tự" class="form-control" id="XacThucMatKhauMoi"
                                name="XacThucMatKhauMoi" required>
                            <div x-text="errors.XacThucMatKhauMoi" class="invalid-feedback">Mật khẩu không hợp lệ</div>
                        </div>

                        <button type="submit" class="btn submit-btn col-sm-12 col-md-auto">Đổi mật khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

script('/public/nguoi-dung/thong-tin-nguoi-dung/main.js');
require ('app/views/partials/footer.php');
?>