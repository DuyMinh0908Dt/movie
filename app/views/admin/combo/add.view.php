<?php
use App\Services\PhimService;

title("Quản lý phim");
require ('app/views/admin/header.php');
?>

<link rel="stylesheet" href="/public/tiendat/infoMovie.css">
<script>
const validationRule = {
    TenCombo: {
        required: {
            value: true,
            message: 'Tên combo không được để trống'
        },
        minLength: {
            value: 5,
            message: 'Tên combo phải lớn hơn 5 ký tự'
        },
        maxLength: {
            value: 255,
            message: 'Tên combo phải nhỏ hơn 255 ký tự'
        }
    },
    GiaCombo: {
        required: {
            value: true,
            message: 'Giá combo không được để trống'
        },
        min: {
            value: 0,
            message: 'Giá combo phải lớn hơn 0'
        },
    },
    TrangThai: {
        required: {
            value: true,
            message: 'Trạng thái không được để trống'
        }
    },
    MoTa: {
        required: {
            value: true,
            message: 'Mô tả không được để trống'
        },
        minLength: {
            value: 5,
            message: 'Mô tả phải lớn hơn 5 ký tự'
        },
        maxLength: {
            value: 255,
            message: 'Mô tả phải nhỏ hơn 255 ký tự'
        }
    },
    HinhAnh: {
        required: {
            value: true,
            message: 'Hình ảnh không được để trống'
        }
    },
    ThucPhams: {
        default: [],
    }
}
</script>
<div x-data="formValidator(validationRule)" style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;"
    class="wrapper p-5">
    <div x-data="{
    hinhAnhUploadLoading:false,
    uploadFile:async function(file) {
        
        const formData = new FormData();
        formData.append('file', file);
        const res =  await axios.post('/api/file/upload', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            validateStatus:()=>true
        });
        if(res.status === 200) {
            return res.data.data.path;
        } else {
            toast('Upload file thất bại', {
                position: 'bottom-center',
                type: 'danger'
            });
        }
    },
    calTotalValue:function() {
        return data.ThucPhams.reduce((acc, item) => acc + item.SoLuong * item.GiaThucPham, 0);
    },
    onSubmit:async function() {
        // const check if Giacombo < total
        if(!validate()) {
            return;
        }
        const total = this.calTotalValue();
        if(data.ThucPhams.length === 0) {
            toast('Danh sách thực phẩm không được để trống', {
                position: 'bottom-center',
                type: 'danger'
            });
            return;
        }
        if(data.GiaCombo > total) {
            errors.GiaCombo = 'Giá combo phải bé hơn hoặc bằng tổng giá trị thực phẩm';
            return;
        }
        const payload = {
            TenCombo: data.TenCombo,
            GiaCombo: parseInt(data.GiaCombo),
            TrangThai: data.TrangThai,
            MoTa: data.MoTa,
            HinhAnh: data.HinhAnh,
            ThucPhams: data.ThucPhams.map(item => {
                return {
                    MaThucPham: parseInt(item.MaThucPham),
                    SoLuong: item.SoLuong
                }
            })
        };

       const res = await axios.post('', payload,{
            validateStatus:()=>true
        });
        if(res.status === 200) {
            toast('Thêm combo thành công', {
                position: 'bottom-center',
                type: 'success'
            });
            window.location.href = '/admin/combo';
        } else {
            toast('Thêm combo thất bại', {
                position: 'bottom-center',
                type: 'danger',
                description: res.data.message
            });
            console.log(res);
        }
    },
   
}" x-init="
  $watch('data.FileHinhAnh', (value) => {
    if(value) {
        hinhAnhUploadLoading = true;
        uploadFile(value)
        .then((res) => {
            data.HinhAnh = res;
        })
        .finally(() => {
            hinhAnhUploadLoading = false;
        });
    }
  });

" class="info-movie container-fluid p-4 shadow">

        <div class='tw-flex tw-items-center tw-justify-between'>
            <h3 class='tw-font-semibold tw-text-2xl'>
                Thêm combo mới
            </h3>
            <a href="/admin/combo" data-ripple-light="true" class="  tw-btn tw-btn-ghost" type="button">

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
        <form x-on:submit.prevent="onSubmit()">
            <div class="mb-3">
                <label for="TenCombo" class="form-label">Tên
                    combo</label>
                <input :class="{'is-invalid': errors.TenCombo}" type="text" class="form-control" id="TenCombo"
                    x-model="data.TenCombo" required>
                <div class="invalid-feedback" x-text="errors.TenCombo"></div>
            </div>


            <div class="mb-3">
                <label for="description" class="form-label">Mô
                    tả</label>
                <textarea :class="{'is-invalid': errors.MoTa}" class="form-control" id="description" rows="3"
                    x-model="data.MoTa" required></textarea>
                <div class="invalid-feedback" x-text="errors.MoTa"></div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Danh sách thực phẩm</label>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Mã thực phẩm</th>
                            <th scope="col">Tên Thực phẩm</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in data.ThucPhams" :key="index">

                            <tr>
                                <td x-text="index+1"></td>
                                <td x-text="item.TenThucPham"></td>
                                <td x-text="item.SoLuong"></td>
                                <td ">
                                <button type=" button" class="btn btn-danger" x-on:click="
                                    data.ThucPhams.splice(index, 1);
                                    data.ThucPhams = [...data.ThucPhams];
                                    ">
                                    Xóa
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr>
                            <td>

                            </td>
                            <td>
                                <style>
                                .dropdown.bootstrap-select {
                                    min-width: 300px;
                                }
                                </style>
                                <select placeholder=" Chọn thực phẩm" data-live-search=" true" id="SelectThucPham"
                                    class="selectpicker">
                                    option value="" disabled selected>Chọn thực phẩm</option>
                                    <?php foreach ($foods as $food): ?>
                                    <option data-tokens="<?= $food['MaThucPham'] ?><?= $food['TenThucPham'] ?>"
                                        value="<?= $food['MaThucPham'] ?>">
                                        <?= $food['TenThucPham'] ?> - <?= number_format($food['GiaThucPham']) ?>đ
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input id="SelectQuantity" type="number" class="form-control">
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary" x-on:click="
                                    const selectEl =document.getElementById('SelectThucPham');
                                    const quantityEl = document.getElementById('SelectQuantity');
                                    const isNotNumber = isNaN(parseInt(quantityEl.value));
                                    if(isNotNumber) {
                                        toast('Số lượng phải là số', {
                                            position: 'bottom-center',
                                            type: 'danger'
                                        });
                                        return;
                                    }
                                    if(parseInt(quantityEl.value) <= 0) {
                                        toast('Số lượng phải lớn hơn 0', {
                                            position: 'bottom-center',
                                            type: 'danger'
                                        });
                                        return;
                                    }
                                    const exist = data.ThucPhams.find(item => item.MaThucPham === selectEl.value);
                                    if(exist) {
                                        toast('Thực phẩm đã tồn tại', {
                                            position: 'bottom-center',
                                            type: 'danger'
                                        });
                                        return;
                                    }
                                    const selectedItem =selectEl.options[selectEl.selectedIndex];
                                    data.ThucPhams.push({
                                        MaThucPham: selectEl.value,
                                        TenThucPham: selectedItem.text.split('-')[0],
                                        SoLuong: parseInt(quantityEl.value),
                                        GiaThucPham: parseInt(selectedItem.text.split('-')[1].replace('đ', '').replace(',', '').trim())
                                    });
                                    selectEl.selectedIndex = 0;
                                    quantityEl.value = '';
                                    ">
                                    Thêm
                                </button>
                            </td>
                    <tfoot>
                        <tr>
                            <td colspan="4">
                                <div class='tw-flex tw-text-xl tw-justify-end tw-gap-x-5 tw-px-14">
                                    <span class=' tw-font-semibold'>
                                    Tổng giá trị:
                                    </span>
                                    <span
                                        x-text="calTotalValue().toLocaleString('vi-VN', {style: 'currency', currency: 'VND'})">
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                    </tbody>
                </table>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="GiaCombo" class="form-label">Giá combo</label>
                    <input :class="{'is-invalid': errors.GiaCombo}" type="number" class="form-control" id="GiaCombo"
                        x-model="data.GiaCombo" required>
                    <div class="invalid-feedback" x-text="errors.GiaCombo">
                    </div>
                </div>

                <div class="col">
                    <label for class="form-label">Hình
                        ảnh</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Link hình ảnh"
                            pattern=".+\.(png|jpe?g|gif|svg|webp)$" title="Link hình ảnh không hợp lệ"
                            :class="{'is-invalid': errors.HinhAnh}" aria-label="Recipient's username"
                            aria-describedby="button-addon2" id="HinhAnh" x-model="data.HinhAnh" required>
                        <div class="invalid-feedback" x-text="errors.HinhAnh"></div>


                        <label class="btn btn-outline-secondary" :disabled="hinhAnhUploadLoading">Chọn
                            <input :disabled="hinhAnhUploadLoading" type="file" hidden accept="image/*" x-on:change="
                            data.FileHinhAnh = $event.target.files[0];
                            ">
                            <span x-show="hinhAnhUploadLoading" class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true"></span>
                        </label>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col ">
                        <label for class="form-label">Trạng thái </label>
                        <select class="form-select" id="TrangThai" x-model="data.TrangThai" required>
                            <?php foreach ($statuses as $status): ?>
                            <option value="<?= $status['MaTrangThai'] ?>"><?= $status['Ten'] ?></option>
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
</body>

<?php
require ('app/views/admin/footer.php');


?>