<?php
use App\Services\PhimService;

title("Quản lý phim");
require ('app/views/admin/header.php');
?>

<link rel="stylesheet" href="/public/tiendat/infoMovie.css">
<script>
const validationRule = {
    TenPhim: {
        required: {
            value: true,
            message: 'Tên phim không được để trống'
        },
        minLength: {
            value: 5,
            message: 'Tên phim phải lớn hơn 5 ký tự'
        },
        maxLength: {
            value: 255,
            message: 'Tên phim phải nhỏ hơn 255 ký tự'
        }
    },
    NgayPhatHanh: {
        required: {
            value: true,
            message: 'Ngày phát hành không được để trống'
        }
    },
    DinhDang: {
        required: {
            value: true,
            message: 'Định dạng không được để trống'
        }
    },
    HanCheDoTuoi: {
        required: {
            value: true,
            message: 'Hạn chế độ tuổi không được để trống'
        }
    },
    HinhAnh: {
        required: {
            value: true,
            message: 'Hình ảnh không được để trống'
        }
    },
    ThoiLuong: {
        required: {
            value: true,
            message: 'Thời lượng không được để trống'
        },
        min: {
            value: 0,
            message: 'Thời lượng phải lớn hơn 0'
        }
    },
    NgonNgu: {
        required: {
            value: true,
            message: 'Ngôn ngữ không được để trống'
        }
    },
    DaoDien: {
        required: {
            value: true,
            message: 'Đạo diễn không được để trống'
        }
    },
    TinhTrang: {
        required: {
            value: true,
            message: 'Tình trạng không được để trống'
        }
    },
    Trailer: {
        required: {
            value: true,
            message: 'Trailer không được để trống'
        }
    },
    MoTa: {
        required: {
            value: true,
            message: 'Mô tả không được để trống'
        }
    },
    TheLoais: {
        default: [],
    }
}

function getTheLoaiText() {
    return [...document.querySelectorAll("input[name='TheLoais']:checked")].map((el) => el.getAttribute('data-display'))
        .join(', ');
}
</script>
<div x-data="formValidator(validationRule)" style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;"
    class="p-5 wrapper">
    <div x-data="{
    hinhAnhUploadLoading:false,
    trailerUploadLoading:false,
    theLoaiText: '',
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
    onSubmit:async function() {
        if(!validate()) {
            return toast('Vui lòng kiểm tra lại thông tin', {
                position: 'bottom-center',
                type: 'danger'
            });
        }
        const payload = {
            TenPhim: this.data.TenPhim,
            NgayPhatHanh: dayjs(this.data.NgayPhatHanh).format('YYYY-MM-DD'),
            DinhDang: this.data.DinhDang,
            HanCheDoTuoi: this.data.HanCheDoTuoi,
            HinhAnh: this.data.HinhAnh,
            ThoiLuong: this.data.ThoiLuong,
            NgonNgu: this.data.NgonNgu,
            DaoDien: this.data.DaoDien,
            TinhTrang: this.data.TinhTrang,
            Trailer: this.data.Trailer,
            MoTa: this.data.MoTa,
            TheLoais: this.data.TheLoais.map((el) => parseInt(el))
        };
       const res = await axios.post('', payload,{
            validateStatus:()=>true
        });
        if(res.status === 200) {
            toast('Thêm phim thành công', {
                position: 'bottom-center',
                type: 'success'
            });
            window.location.href = '/admin/phim';
        } else {
            toast('Thêm phim thất bại', {
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
  $watch('data.TheLoais', (value) => {
    theLoaiText = getTheLoaiText();
  });
  $watch('data.NgayPhatHanh', (value) => {
    if(value) {
        const dayjsDate = dayjs(value);
        if(dayjsDate.isAfter(dayjs())) {
            data.TinhTrang = 2;
        }else {
            data.TinhTrang = 1;
        }
    }
  });
  $watch('data.FileTrailer', (value) => {
    if(value) {
        trailerUploadLoading = true;
        uploadFile(value)
        .then((res) => {
            data.Trailer = res;
        })
        .finally(() => {
            trailerUploadLoading = false;
        });
    }
  });
" class="p-4 shadow info-movie container-fluid">

        <div class='tw-flex tw-items-center tw-justify-between'>
            <h3 class='tw-font-semibold tw-text-2xl'>
                Thêm phim mới
            </h3>
            <a href="/admin/phim" data-ripple-light="true" class=" tw-btn tw-btn-ghost" type="button">

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
                <label for="tenphim" class="form-label">Tên
                    phim</label>
                <input type="text" :class="{'is-invalid':!!errors.TenPhim}" class="form-control" id="TenPhim"
                    x-model="data.TenPhim" required>
                <div class="invalid-feedback" x-text="errors.TenPhim"></div>

            </div>

            <div class="mb-3 row">
                <div class="col">
                    <label for="ngayphathanh" class="form-label">Ngày phát
                        hành</label>
                    <input :class="{'is-invalid':!!errors.NgayPhatHanh}" type="date" class="form-control"
                        id="NgayPhatHanh" x-model="data.NgayPhatHanh" required>
                    <div class="invalid-feedback" x-text="errors.NgayPhatHanh">
                    </div>
                    <script>
                    const movieTags = <?= json_encode(PhimService::$MOVIE_TAGS) ?>;
                    </script>
                    <div class="col">
                        <label for class="form-label">Định
                            dạng</label>
                        <select class="form-select" id="DinhDang" x-model="data.DinhDang" required>
                            <option checked value="">Tất cả</option>
                            <option value="2D">2D</option>
                            <option value="3D">3D</option>
                            <option value="4D">4D</option>
                            <option value="5D">5D</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col">
                        <label class="form-label" for>Hạn chế độ
                            tuổi</label>
                        <select class="form-select" id="HanCheDoTuoi" x-model="data.HanCheDoTuoi" required>
                            <option value="">
                                Chọn phân loại phim
                            </option>
                            <template x-for="([key, value], index) in Object.entries(movieTags)">
                                <option :value="key" x-text="key+' - '+value"></option>
                            </template>
                        </select>
                    </div>

                    <div class="col">
                        <label for class="form-label">Hình
                            ảnh</label>
                        <div class="mb-3 input-group">
                            <input type="text" class="form-control" placeholder="Link hình ảnh"
                                pattern=".+\.(png|jpe?g|gif|svg|webp)$" title="Link hình ảnh không hợp lệ"
                                :class="{'is-invalid': errors.HinhAnh}" aria-label="Recipient's username"
                                aria-describedby="button-addon2" id="HinhAnh" x-model="data.HinhAnh" required>
                            <div class="invalid-feedback" x-text="errors.HinhAnh"></div>






                            <label class="btn btn-outline-secondary" :disabled="hinhAnhUploadLoading">Chọn
                                <input :disabled="hinhAnhUploadLoading" type="file" hidden accept="image/*" x-on:change="
                            data.FileHinhAnh = $event.target.files[0];
                            ">
                                <span x-show="hinhAnhUploadLoading" class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col">
                        <label for="thoiluong" class="form-label">Thời
                            lượng (phút)</label>
                        <input :class="{'is-invalid':!!errors.ThoiLuong}" type="number" class="form-control"
                            id="ThoiLuong" x-model="data.ThoiLuong" required>
                        <div class="invalid-feedback" x-text="errors.ThoiLuong">
                        </div>
                    </div>

                    <div class="col">
                        <label for="ngonngu" class="form-label">Ngôn
                            ngữ</label>
                        <input type="text" class="form-control" id="NgonNgu" x-model="data.NgonNgu" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col">
                        <label for="daodien" class="form-label">Đạo
                            diễn</label>
                        <input type="text" class="form-control" id="DaoDien" x-model="data.DaoDien" required>
                    </div>

                    <div class="col ">
                        <label for class="form-label">Trạng thái </label>
                        <select disabled class="form-select" id="TinhTrang" x-model="data.TinhTrang" required>
                            <?php foreach ($phimStatuses as $status): ?>
                                <option value="<?= $status['MaTrangThai'] ?>"><?= $status['Ten'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col ">
                        <label for="trailer" class="form-label">Trailer</label>
                        <div class="mb-3 input-group">
                            <input type="text" class="form-control" placeholder="Link trailer"
                                aria-label="Recipient's username" aria-describedby="button-addon2" id="Trailer"
                                x-model="data.Trailer">
                            <label class="btn btn-outline-secondary" :disabled="trailerUploadLoading">Chọn
                                <input :disabled="trailerUploadLoading" type="file" hidden accept="video/*" x-on:change="
                            data.FileTrailer = $event.target.files[0];
                            ">
                                <span x-show="trailerUploadLoading" class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true"></span>
                            </label>
                        </div>

                    </div>

                    <div class="col">
                        <label for="theloai" class="form-label">Thể
                            loại</label>
                        <div class="mb-3 input-group">
                            <input readonly type="text" class="form-control"
                                aria-label="Text input with dropdown button" readonly x-bind:value="theLoaiText">
                            <button data-bs-auto-close="outside" class="btn btn-outline-secondary dropdown-toggle"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">Thể
                                loại</button>
                            <ul class="dropdown-menu dropdown-menu-end tw-max-h-52 tw-overflow-y-auto">
                                <!-- load dữ liệu thể loại phim -->
                                <?php foreach ($categories as $category): ?>
                                    <li>
                                        <div class="dropdown-item">
                                            <label>
                                                <input data-display="<?= $category['TenTheLoai'] ?>" type="checkbox"
                                                    name="TheLoais" x-model="data.TheLoais"
                                                    value="<?= $category['MaTheLoai'] ?>">
                                                <?= $category['TenTheLoai'] ?></label>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô
                        tả</label>
                    <textarea class="form-control" id="description" rows="3" x-model="data.MoTa" required></textarea>
                </div>

                <div class="gap-2 d-grid d-md-flex justify-content-md-end">
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