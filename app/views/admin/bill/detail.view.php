<?php
title("Quản lý hóa đơn");
require ('app/views/admin/header.php');
?>

<link rel="stylesheet" href="/public/tiendat/infoBill.css">
<style>
h1 {
    font-size: 1.5rem;
    font-weight: 600;

}
</style>
<div x-data="{}" style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="wrapper p-5">
    <div class='tw-flex tw-justify-between tw-items-center tw-mb-2'>

        <div class='tw-font-semibold tw-text-2xl tw-flex tw-gap-x-2'>
            <span style=" color: #2c3e50;">Chi tiết Hoá đơn</span>
            <h1> <span class='tw-select-none'>#</span><?= $order['MaHoaDon'] ?> </h1>
        </div>
        <a href="/admin/hoa-don" data-ripple-light="true" class="  tw-btn tw-btn-ghost" type="button">

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
    <div class="container-fluid">
        <div class="row">
            <!-- chi tiết hóa đơn -->
            <div class="detail-bill-container col-8 p-0 m-0">
                <!-- chi tiết vé đã đặt -->
                <div class="ticket-container row w-100 shadow p-3">
                    <div class="fs-4 fw-bold mb-3">
                        <?= $order['SuatChieu']['TenPhim'] ?>
                    </div>
                    <div class="fw-semibold fs-5">
                        <?= $order['SuatChieu']['TenRapChieu'] ?>
                    </div>
                    <div class="fw-normal">
                        <?= $order['SuatChieu']['DiaChi'] ?>
                    </div>

                    <div class="d-flex align-items-stretch mt-3">
                        <div class="flex-column">
                            <div class="fw-semibold">Thời
                                gian</div>
                            <script>
                            //YYYY-MM-DD HH:mm:ss
                            var dateStr = '<?= $order['SuatChieu']['NgayGioChieu'] ?>';
                            </script>
                            <div class="fs-5 fw-normal tw-capitalize" x-init="
                             const ngayChieuDayJs = dayjs(dateStr, 'YYYY-MM-DD HH:mm:ss');
                                const ngayChieuStr = ngayChieuDayJs.format('HH:mm dddd DD/MM/YYYY');
                                $el.innerText = ngayChieuStr;
                            ">22:45 Thứ
                                Hai
                                08/04/2024</div>
                        </div>

                        <div class="flex-column px-4">
                            <div class="fw-semibold">Phòng
                                chiếu</div>
                            <div class="fs-5 fw-normal">
                                <?= $order['SuatChieu']['TenPhongChieu'] ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    $groupedByTicketType = []; // MaLoaiVe => [Ve]
                    foreach ($order['Ve'] as $ve) {
                        if (!isset($groupedByTicketType[$ve['MaLoaiVe']])) {
                            $groupedByTicketType[$ve['MaLoaiVe']] = [];
                        }
                        $groupedByTicketType[$ve['MaLoaiVe']][] = $ve;
                    }
                    $groupedBySeatType = []; // MaLoaiGhe => [Ve]
                    foreach ($order['Ve'] as $ve) {
                        if (!isset($groupedBySeatType[$ve['MaLoaiGhe']])) {
                            $groupedBySeatType[$ve['MaLoaiGhe']] = [];
                        }
                        $groupedBySeatType[$ve['MaLoaiGhe']][] = $ve;
                    }
                    ?>
                    <div class="d-flex align-items-stretch mt-3">
                        <!-- <div class="flex-column">
                            <div class="fw-semibold">Số vé</div>
                            <div class="fs-5 fw-normal">02</div>
                        </div>

                        <div class="flex-column px-4">
                            <div class="fw-semibold">Loại vé</div>
                            <div class="fs-5 fw-normal">HSSV - Người
                                cao tuổi</div>
                        </div> -->
                        <div class="tw-grid tw-gap-2  tw-gap-x-4 tw-grid-cols-2 tw-max-w-96">
                            <div class="fw-semibold">Loại vé</div>

                            <div class="fw-semibold">Số vé</div>
                            <?php foreach ($groupedByTicketType as $maLoaiVe => $ve): ?>
                            <div class="fs-5 fw-normal"><?= $ve[0]['TenLoaiVe'] ?></div>
                            <div class="fs-5 fw-normal"><?= count($ve) ?></div>
                            <?php endforeach; ?>

                        </div>

                    </div>
                    <div class="d-flex align-items-stretch mt-3">
                        <div class="tw-grid tw-gap-2  tw-gap-x-4 tw-grid-cols-2 tw-max-w-96">
                            <div class="fw-semibold">Loại Ghế</div>

                            <div class="fw-semibold">Số Ghế</div>

                            <?php foreach ($groupedBySeatType as $maLoaiGhe => $ve): ?>

                            <div class="fs-5 fw-normal"><?= $ve[0]['TenLoaiGhe'] ?></div>
                            <div class="fs-5 fw-normal">
                                <?php foreach ($ve as $v): ?>
                                <?= $v['SoGhe'] ?>,
                                <?php endforeach; ?>
                            </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
                <!-- hết chi tiết vé đã đặt -->

                <!-- combo bắp mước -->
                <div
                    class="prucduct-container flex tw-flex-col -tw-ml-3 w-100 shadow p-3 tw-overflow-y-auto tw-max-h-[400px]">
                    <div class="fs-4 fw-semibold mb-3">Bắp
                        nước</div>
                    <?php if (count($order['Combos']) == 0 && count($order['ThucPhams']) == 0): ?>
                    <div class="text-body-secondary tw-w-full tw-text-center">
                        Không có bắp nước
                    </div>
                    <?php endif; ?>
                    <?php foreach ($order['Combos'] as $combo): ?>
                    <div class="row align-items-center">
                        <div class="col-1">
                            <img src="<?= $combo['HinhAnh'] ?>" alt width="48px">
                        </div>
                        <div class="col-7">
                            <div class="fw-medium fs-5">
                                <?= $combo['TenCombo'] ?>
                            </div>
                            <div class="fs-6 fw-light">
                                <?= $combo['MoTa'] ?>
                            </div>
                        </div>
                        <div class="col-1 text-end">
                            x<?= $combo['SoLuong'] ?>
                        </div>
                        <div class="col-3 text-end tw-font-semibold tw-text-lg"
                            x-text="toVnd(<?= $combo['ThanhTien'] ?>)">

                        </div>
                    </div>
                    <hr class="my-4">
                    <?php endforeach; ?>
                    <?php foreach ($order['ThucPhams'] as $thucpham): ?>
                    <div class="row align-items-center">
                        <div class="col-1">
                            <img src="<?= $thucpham['HinhAnh'] ?>" alt width="48px">
                        </div>
                        <div class="col-7">
                            <div class="fw-medium fs-5">
                                <?= $thucpham['TenThucPham'] ?>
                            </div>
                            <div class="fs-6 fw-light"> <?= $thucpham['MoTa'] ?></div>
                        </div>
                        <div class="col-1 text-end">
                            x<?= $thucpham['SoLuong'] ?>
                        </div>
                        <div class="col-3 text-end tw-font-semibold tw-text-lg"
                            x-text="toVnd(<?= $thucpham['ThanhTien'] ?>)">

                        </div>
                    </div>
                    <hr class="my-4">
                    <?php endforeach; ?>
                </div>
                <!-- hết combo bắp nước -->
            </div>

            <!-- thong tin khach hang -->
            <div class="customer-info-container col-4 shadow">
                <!-- thông tin khách hàng -->
                <div class="row pt-4 px-4 ">
                    <p class="fs-4 m-0 fw-semibold p-0 ">Khách
                        hàng</p>
                </div>

                <div class="row p-4 border-bottom ">
                    <div class="col-3 p-0 text-center ">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle"
                            width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                        </svg>
                    </div>

                    <div class="col-9 ">
                        <h6>
                            <?= $order['NguoiDung']['TenNguoiDung'] ?>
                        </h6>
                        <div class="text-body-secondary ">
                            <?= $order['NguoiDung']['Email'] ?>
                        </div>
                        <div class="text-body-secondary ">
                            <?= $order['NguoiDung']['SoDienThoai'] ?>
                        </div>
                    </div>
                </div>

                <!-- khuyến mãi -->
                <div class="row pt-4 px-4 ">
                    <p class="fs-4 m-0 fw-semibold p-0 ">Khuyến
                        mãi</p>
                </div>
                <?php if (isset($order['KhuyenMai'])): ?>
                <div class=" p-4 border-bottom tw-flex -tw-mt-4 tw-flex-col">
                    <div class=" p-0 tw-font-semibold ">
                        <?= $order['KhuyenMai']['MaKhuyenMai'] ?>
                    </div>

                    <div class=" p-0 tw-font-semibold ">
                        <?= $order['KhuyenMai']['TenKhuyenMai'] ?>
                    </div>

                    <div class=" ">
                        <?= $order['KhuyenMai']['MoTa'] ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="row align-items-center px-4 py-2 border-bottom">
                    <div class="col-6 p-0 ">
                        Không sử dụng
                    </div>
                </div>
                <?php endif; ?>

                <!-- thanh toán -->
                <div class="row pt-4 px-4">
                    <p class="fs-4 m-0 fw-semibold p-0 ">Thanh
                        toán</p>
                </div>

                <div class="row align-items-center px-4 py-2  border-bottom">
                    <div class="col-6 p-0 tw-font-semibold">
                        Phương thức:
                    </div>

                    <div class="col-5 tw-uppercase text-end ">
                        <?= $order['PhuongThucThanhToan'] ?>
                    </div>

                </div>

                <!-- tổng tiền -->
                <?php
                $tienVe = 0;
                $tienBapNuoc = 0;
                $tienKhuyenMai = 0;
                $tienThucPham = 0;

                foreach ($order['Ve'] as $ve) {
                    $tienVe += $ve['GiaVe'];
                }
                foreach ($order['Combos'] as $combo) {
                    $tienBapNuoc += $combo['ThanhTien'];
                }
                foreach ($order['ThucPhams'] as $thucPham) {
                    $tienThucPham += $thucPham['ThanhTien'];
                }
                $tienKhuyenMai = $order['TongTien'] - $tienVe - $tienBapNuoc - $tienThucPham;



                ?>
                <div class="row justify-content-end p-4">
                    <table class="table table-sm table-borderless text-end">
                        <tr>
                            <td class="text-dark-emphasis">
                                Tiền vé
                            </td>
                            <td class="text-dark-emphasis">
                                <?= number_format($tienVe) ?> VNĐ
                            </td>
                        </tr>
                        <tr>
                            <td class="text-dark-emphasis">Tiền bắp
                                nước</td>
                            <td class="text-dark-emphasis">
                                <?= number_format($tienBapNuoc + $tienThucPham) ?> VNĐ
                            </td>
                        </tr>
                        <tr>
                            <td class="text-dark-emphasis">Khuyến
                                mãi</td>
                            <td class="text-danger">
                                <?= number_format($tienKhuyenMai) ?> VNĐ

                            </td>
                        </tr>

                        <tr>
                            <td class="fw-semibold">Tổng tiền</td>
                            <td class="fw-semibold">
                                <?= number_format($order['TongTien']) ?> VNĐ
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- javascript -->


<?php
require ('app/views/admin/footer.php');


?>