<?php
use App\Dtos\TrangThaiPhong;
use App\Dtos\TrangThaiSuatChieu;

title("Sửa suất chiếu");
require ('app/views/admin/header.php');

?>
<script>

</script>

<link rel="stylesheet" href="/public/tiendat/showtime.css">
<script>

</script>
<div x-data="
formValidator({
    GiaVe: {
        required: {
            value: true,
            message: 'Giá vé không được để trống'
        },
        min: {
            value: 0,
            message: 'Giá vé phải lớn hơn 0'
        },
        default: <?= $show['GiaVe'] ?? 0 ?>
    },
    NgayGioBatDau: {
        //2024-04-10 03:00:00
        default: dayjs('<?= $show['NgayGioChieu'] ?? date('Y-m-d H:i:s') ?>').format('YYYY-MM-DDTHH:mm')
    },
    KhoangThoiGianChieu: {
        default: dayjs('<?= $show['NgayGioKetThuc'] ?? date('Y-m-d H:i:s') ?>').diff(dayjs(
            '<?= $show['NgayGioChieu'] ?? date('Y-m-d H:i:s') ?>'), 'minute')
    },
    NgayGioKetThuc: {
        default: dayjs('<?= $show['NgayGioKetThuc'] ?? date('Y-m-d H:i:s') ?>').format('YYYY-MM-DDTHH:mm')
    },
    MaPhim: {
        default: <?= $show['MaPhim'] ?? 0 ?>
    },
    RapChieu: {
        default: <?= $currentCinema['MaRapChieu'] ?? 0 ?>
    },
    PhongChieu: {
        default: <?= $show['MaPhongChieu'] ?? 0 ?>
    }

});
" style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="wrapper  p-5">
    <div x-data="{
        listRoom: [],
        fetchRoom: async function() {
             this.listRoom = [];
            if (!data.RapChieu) {
               
                return;
            }
            const response = await axios.get(`/api/rap-chieu/${data.RapChieu}/phong-chieu?trang-thais[]=${<?= TrangThaiPhong::DangHoatDong->value ?>}`);
            this.listRoom = response.data.data;
            
        }
    }" x-init="
        $watch('data.RapChieu',  (value) => {
            fetchRoom();
        });
        $watch('data.MaPhim',  (value) => {
            const thoiLuongPhim = document.querySelector(`#phim option[value='${value}']`).dataset.thoiLuongPhim;
            data.KhoangThoiGianChieu = thoiLuongPhim;
        });
        $watch('data.KhoangThoiGianChieu',  (value) => {
            console.log(errors);
            if (!data.NgayGioBatDau) {
                return;
            }
            const ngayGioBatDau = dayjs(data.NgayGioBatDau);
            console.log(ngayGioBatDau);
            const texxt=ngayGioBatDau.add(Number(value), 'minute').format('YYYY-MM-DDTHH:mm');
            data.NgayGioKetThuc = texxt;
        });
        $watch('data.NgayGioBatDau',  (value) => {
            if (!data.KhoangThoiGianChieu) {
                return;
            }
            const ngayGioBatDau = dayjs(value);
            console.log(ngayGioBatDau);
            const texxt=ngayGioBatDau.add(Number(data.KhoangThoiGianChieu), 'minute').format('YYYY-MM-DDTHH:mm');
            data.NgayGioKetThuc = texxt;
        });
        fetchRoom().then(() => {
            $nextTick(() => {
               setTimeout(() => {
                document.querySelector(`#phongchieu`).value = data.PhongChieu;
               }, 1000);
            });
        });
    " class="info-movie container-fluid tw-bg-white tw-rounded-md p-4 shadow">

        <div class='tw-flex tw-items-center tw-justify-between'>
            <h3>Sửa SUẤT CHIẾU</h3>
            <a href="/admin/suat-chieu" data-ripple-light="true" class="  tw-btn tw-btn-ghost" type="button">

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
        if (!validate()) {
            return;
        }
        const maSuatChieu = <?= $show['MaXuatChieu'] ?>;
        const maPhim= data.MaPhim;
        let thoiLuongPhim = document.querySelector(`#phim option[value='${maPhim}']`).dataset.thoiLuongPhim;
        thoiLuongPhim = parseInt(thoiLuongPhim);
  //      2024-04-13T16:12
        const ngayGioBatDau = dayjs(data.NgayGioBatDau, 'YYYY-MM-DDTHH:mm');
        const ngayGioKetThuc = dayjs(data.NgayGioKetThuc, 'YYYY-MM-DDTHH:mm');

        const diff = ngayGioKetThuc.diff(ngayGioBatDau, 'minute');
        console.log(diff);
        if (diff < thoiLuongPhim) {
            errors.NgayGioKetThuc = 'Khoảng thời gian chiếu phải lớn hơn thời lượng phim';
            return;
        }
        const payload = {
            MaPhim: data.MaPhim,
            MaPhongChieu: data.PhongChieu,
            NgayGioBatDau: ngayGioBatDau.format('YYYY-MM-DD HH:mm:ss'),
            NgayGioKetThuc: ngayGioKetThuc.format('YYYY-MM-DD HH:mm:ss'),
            GiaVe: data.GiaVe
        };
        const res= await axios.post('', payload,{validateStatus: () => true} );
         if (res.status !=200) {
            toast('Tạo rạp chiếu thất bại', {
                position: 'bottom-center',
                type: 'error'
            });
            
            errors = res.data.errors;
            return;
        };
        window.history.back();
        ">
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label" for="rapchieu">Rạp
                        chiếu</label>
                    <!-- load du lieu rap chieu -->
                    <select x-model="data.RapChieu" class="form-select" id="rapchieu" required>
                        <option value="">Chọn rạp chiếu</option>
                        <?php foreach ($cinemas as $cinema): ?>
                            <option value="<?= $cinema['MaRapChieu'] ?>">
                                <?= $cinema['TenRapChieu'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col">
                    <label for="phongchieu" class="form-label">Phòng
                        chiếu</label>
                    <!-- load du lieu phong chieu -->
                    <select x-model="data.PhongChieu" class="form-select" name id="phongchieu" required>
                        <option value="">Chọn phòng chiếu</option>
                        <template x-for="room in listRoom" :key="room.MaPhongChieu">
                            <option :value="room.MaPhongChieu" x-text="room.TenPhongChieu"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="phim" class="form-label">Tên
                    phim</label>
                <!-- load du lieu phim -->
                <select x-model="data.MaPhim" class="form-select" id="phim" required>
                    <option value="">Chọn phim</option>
                    <?php foreach ($movies as $movie): ?>
                        <option data-thoi-luong-phim="<?= $movie['ThoiLuong'] ?>" value="<?= $movie['MaPhim'] ?>">
                            <?= $movie['TenPhim'] ?> - <?= $movie['ThoiLuong'] ?> phút
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="NgayGioBatDau" class="form-label">Bắt đầu</label>
                    <input :class="{'is-invalid': errors.NgayGioBatDau}" x-model="data.NgayGioBatDau"
                        type="datetime-local" class="form-control" id="NgayGioBatDau" required>
                    <div class="invalid-feedback" x-text="errors.NgayGioBatDau">
                    </div>
                </div>

                <div class="col-2">
                    <label for="KhoangThoiGianChieu" class="form-label">Chiếu trong (phút)</label>
                    <input :class="{'is-invalid': errors.KhoangThoiGianChieu}" x-model="data.KhoangThoiGianChieu"
                        type="number" class="form-control" id="KhoangThoiGianChieu" required>
                    <div class="invalid-feedback" x-text="errors.KhoangThoiGianChieu">
                    </div>
                </div>
                <div class="col">
                    <label for="NgayGioKetThuc" class="form-label">Kết thúc lúc</label>
                    <input disabled :class="{'is-invalid': errors.NgayGioKetThuc}" x-model="data.NgayGioKetThuc"
                        type="text" class="form-control" id="NgayGioKetThuc" required>
                    <div class="invalid-feedback" x-text="errors.NgayGioKetThuc">
                    </div>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col">
                    <label for="GiaVe" class="form-label">Phụ
                        thu</label>
                    <input x-model="data.GiaVe" type="number" class="form-control" id="GiaVe" required>
                </div>

                <div class="col">

                </div>

            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button x-on:click="
                data.MaPhim = <?= $show['MaPhim'] ?>;
                data.RapChieu = <?= $currentCinema['MaRapChieu'] ?>;
                data.GiaVe = <?= $show['GiaVe'] ?>;
                data.PhongChieu = <?= $show['MaPhongChieu'] ?>;
                data.NgayGioBatDau = dayjs('<?= $show['NgayGioChieu'] ?>').format('YYYY-MM-DDTHH:mm');
                data.NgayGioKetThuc = dayjs('<?= $show['NgayGioKetThuc'] ?>').format('HH:mm dd-MM-YYYY');
                data.KhoangThoiGianChieu = dayjs('<?= $show['NgayGioKetThuc'] ?>').diff(dayjs('<?= $show['NgayGioChieu'] ?>'), 'minute');
                setTimeout(() => {
                    document.querySelector(`#phongchieu`).value = data.PhongChieu;
                }, 1000);
                " class="btn btn-light me-md-2" type="button">Reset</button>
                <button class="btn btn-primary" type="submit">Lưu</button>
            </div>
        </form>
    </div>
</div>
<?php
require ('app/views/admin/footer.php');


?>