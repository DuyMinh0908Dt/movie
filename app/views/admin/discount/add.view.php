<?php
use App\Dtos\TrangThaiSuatChieu;

title("Quản lý suất chiếu");
require ('app/views/admin/header.php');

?>

<link rel="stylesheet" href="/public/tiendat/showtime.css">
<script>
const validatorRule = {
    MaKhuyenMai: {
        required: {
            value: true,
            message: 'Mã khuyến mãi không được để trống'
        },
        pattern: {
            value: /^[a-zA-Z0-9_]{4,15}$/,
            message: 'Mã khuyến mãi không hợp lệ (4-15 ký tự gồm chữ cái, số và _)'
        }
    },
    TenKhuyenMai: {
        required: {
            value: true,
            message: 'Tên khuyến mãi không được để trống'
        },
        minLength: {
            value: 5,
            message: 'Tên khuyến mãi phải có ít nhất 5 ký tự'
        },
        maxLength: {
            value: 255,
            message: 'Tên khuyến mãi không được vượt quá 255 ký tự'
        }
    },
    MoTa: {
        required: {
            value: true,
            message: 'Mô tả không được để trống'
        },
        minLength: {
            value: 5,
            message: 'Mô tả phải có ít nhất 5 ký tự'
        },
        maxLength: {
            value: 255,
            message: 'Mô tả không được vượt quá 255 ký tự'
        }
    },
    NgayGioBatDau: {},
    NgayGioKetThuc: {},
    GiamToiDa: {},
    GiaTriGiam: {
        required: {
            value: true,
            message: 'Giá trị giảm không được để trống'
        },
        min: {
            value: 0,
            message: 'Giá trị giảm phải lớn hơn hoặc bằng 0'
        },
    },
    LoaiGiamGia: {
        default: 'fixed'
    },
    GioiHanSuDung: {
        min: {
            value: 0,
            message: 'Giới hạn sử dụng phải lớn hơn hoặc bằng 0'
        },
    },
    GioiHanTrenKhachHang: {
        min: {
            value: 0,
            message: 'Giới hạn trên khách hàng phải lớn hơn hoặc bằng 0'
        },
    },
    GiaTriToiThieu: {
        min: {
            value: 0,
            message: 'Giá trị tối thiểu phải lớn hơn hoặc bằng 0'
        },
    },
    TrangThai: {
        default: '12'
    },
    MaLoaiVe: {},
    DiemToiThieu: {
        min: {
            value: 0,
            message: 'Điểm tối thiểu phải lớn hơn hoặc bằng 0'
        },
    },
}
</script>
<div x-data="
formValidator(validatorRule);
" style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="wrapper  p-5">
    <div x-data="{
      async  checkCodeValid() {
            if(!data.MaKhuyenMai) {
                return;
            }
            const res = await axios.get(`/api/khuyen-mai/${data.MaKhuyenMai}`,{validateStatus: () => true});
            console.log(res);
            if(res.data.data) {
                errors.MaKhuyenMai = 'Mã khuyến mãi đã tồn tại';
                return false;
            }
            return true;
        },
       async onSubmit () {
            if(!validate()) {
                return;
            }
            console.log(data);
            if(data.LoaiGiamGia == 'percentage' && parseInt(data.GiaTriGiam) > 100) {
               errors.GiaTriGiam = 'Giảm theo % không được lớn hơn 100';
                return;
            }
            if(this.checkCodeValid() == false) {
                return;
            }
            const payload = {
                MaKhuyenMai: data.MaKhuyenMai,
                TenKhuyenMai: data.TenKhuyenMai,
                MoTa: data.MoTa,
                NgayBatDau: data.NgayBatDau?dayjs(data.NgayBatDau).format('YYYY-MM-DD') : null,
                NgayKetThuc: data.NgayKetThuc?dayjs(data.NgayKetThuc).format('YYYY-MM-DD') : null,
                GiamToiDa: data.LoaiGiamGia == 'percentage' ? data.GiamToiDa : null,
                GiaTriGiam: data.GiaTriGiam,
                GioiHanSuDung: data.GioiHanSuDung,
                GioiHanTrenKhachHang: data.GioiHanTrenKhachHang,
                GiaTriToiThieu: data.GiaTriToiThieu,
                TrangThai: data.TrangThai,
                MaLoaiVe: data.MaLoaiVe,
                DiemToiThieu: data.DiemToiThieu
            }
            console.log(payload);
            const url = '/api/khuyen-mai';
            const res = await axios.post(url, payload, {validateStatus: () => true});
            console.log(res);
            if(res.status == 200) {
                toast('Thêm khuyến mãi thành công', {
                position: 'bottom-center',
                type: 'success',
                description: 'Thêm khuyến mãi thành công'
                });
                   window.history.back();
            }else {
                toast('Có lỗi xảy ra', {
                position: 'bottom-center',
                type: 'danger',
                description: err.response.data.message
            });
            }
        },
        
    }" x-init="
       
    " class="info-movie container-fluid tw-bg-white tw-rounded-md p-4 shadow">

        <div class='tw-flex tw-items-center tw-justify-between'>
            <h3 class='tw-font-semibold tw-text-lg'>Tạo Khuyến mãi</h3>
            <a href="/admin/khuyen-mai" data-ripple-light="true" class="  tw-btn tw-btn-ghost" type="button">

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
        <form x-on:submit.prevent="
            onSubmit();
        ">
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Mã khuyến mãi</label>
                    <!-- load du lieu rap chieu -->
                    <input :class="{'is-invalid': errors.MaKhuyenMai}" x-on:blur="checkCodeValid()"
                        x-model=" data.MaKhuyenMai" type="text" class="form-control" id="MaKhuyenMai" required>
                    <div class="invalid-feedback" x-text="errors.MaKhuyenMai">
                    </div>
                </div>

                <div class="col">
                    <label class="form-label">Tên khuyến mãi</label>
                    <!-- load du lieu rap chieu -->
                    <input :class="{'is-invalid': errors.TenKhuyenMai}" x-model="data.TenKhuyenMai" type="text"
                        class="form-control" id="TenKhuyenMai" required>
                    <div class="invalid-feedback" x-text="errors.TenKhuyenMai">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <!-- load du lieu phim -->
                <textarea class="form-control" :class="{'is-invalid': errors.MoTa}" x-model="data.MoTa" id="MoTa"
                    rows="3">

                  </textarea>
                <div class="invalid-feedback" x-text="errors.MoTa">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="NgayBatDau" class="form-label">Bắt đầu</label>
                    <input :class="{'is-invalid': errors.NgayBatDau}" x-model="data.NgayBatDau" type="date"
                        class="form-control" id="NgayBatDau">
                    <div class="invalid-feedback" x-text="errors.NgayBatDau">
                    </div>
                </div>
                <div class="col">
                    <label for="NgayKetThuc" class="form-label">Kết thúc lúc</label>
                    <input :class="{'is-invalid': errors.NgayKetThuc}" x-model="data.NgayKetThuc" type="date"
                        class="form-control" id="NgayKetThuc">
                    <div class="invalid-feedback" x-text="errors.NgayKetThuc">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="GiaTriToiThieu" class="form-label">Loại giảm</label>
                    <div class="tw-flex tw-items-center tw-gap-x-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="percentage" x-model="data.LoaiGiamGia">
                            <label class="form-check-label">
                                Giảm theo %
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="fixed" x-model="data.LoaiGiamGia">
                            <label class="form-check-label">
                                Giảm theo số tiền
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <label for="GiaTriGiam" class="form-label">Giá trị giảm</label>
                    <input :class="{'is-invalid': errors.GiaTriGiam}" x-model="data.GiaTriGiam" min="0" type="number"
                        class="form-control" id="GiaTriGiam" required>
                    <div class="invalid-feedback" x-text="errors.GiaTriGiam">
                    </div>
                </div>

            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="GiamToiDa" class="form-label">Giảm tối đa

                        <span class="tw-italic tw-font-light tw-text-sm">
                            (tuỳ chọn)
                        </span>
                    </label>
                    <input :disabled="data.LoaiGiamGia != 'percentage'" x-model="data.GiamToiDa" min="1000"
                        type="number" class="form-control" id="GiamToiDa">
                </div>
                <div class="col">
                    <label for="GiaTriToiThieu" class="form-label">Giá trị đơn hàng tối thiểu <span
                            class="tw-italic tw-font-light tw-text-sm">
                            (tuỳ chọn)
                        </span></label>
                    <input x-model="data.GiaTriToiThieu" min="0" type="number" class="form-control" id="GiaTriToiThieu">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="GioiHanSuDung" class="form-label">Số lượng <span
                            class="tw-italic tw-font-light tw-text-sm">
                            (tuỳ chọn)
                        </span></label>
                    <input x-model="data.GioiHanSuDung" min="1" type="number" class="form-control" id="GioiHanSuDung">
                </div>
                <div class="col">
                    <label for="GioiHanTrenKhachHang" class="form-label">
                        Số lần sử dụng tối đa cho mỗi khách hàng <span class="tw-italic tw-font-light tw-text-sm">
                            (tuỳ chọn)
                        </span>
                    </label>
                    <input x-model="data.GioiHanTrenKhachHang" min="1" type="number" class="form-control"
                        id="GioiHanTrenKhachHang">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="MaLoaiVe" class="form-label">
                        Chỉ cho phép sử dụng cho loại vé
                    </label>
                    <select x-model="data.MaLoaiVe" id="MaLoaiVe" class="selectpicker !tw-w-full">
                        <option value="">
                            Tất cả
                        </option>
                        <?php foreach ($allTicketTypes as $tk): ?>
                        <option value="<?= $tk['MaLoaiVe'] ?>">
                            <?= $tk['TenLoaiVe'] ?>
                        </option>
                        <?php endforeach; ?>

                    </select>
                </div>
                <div class="col">
                    <label for="DiemToiThieu" class="form-label">
                        Số điểm tối thiểu để sử dụng khuyến mãi <span class="tw-italic tw-font-light tw-text-sm">
                            (tuỳ chọn)
                        </span>
                    </label>
                    <input x-model="data.DiemToiThieu" type="number" min="0" class="form-control" id="DiemToiThieu">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="TrangThai" class="form-label">
                        Trạng thái
                    </label>
                    <select x-model="data.TrangThai" id="TrangThai" class="selectpicker !tw-w-full">

                        <?php foreach ($statuses as $tk): ?>
                        <option value="<?= $tk['MaTrangThai'] ?>">
                            <?= $tk['Ten'] ?>
                        </option>
                        <?php endforeach; ?>

                    </select>
                </div>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-primary" type="submit">Lưu</button>
            </div>
        </form>
    </div>
</div>
<?php
require ('app/views/admin/footer.php');


?>