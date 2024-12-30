<?php
title("Tiến hành thanh toán");
require ('partials/head.php');
?>



<?php
//  group by php instead of js
$ticketGroups = [];
foreach ($tickets as $ticket) {
    if (!isset($ticketGroups[$ticket['MaLoaiVe']])) {
        $ticketGroups[$ticket['MaLoaiVe']] = [];
    }
    $ticketGroups[$ticket['MaLoaiVe']][] = $ticket;
}

$seatGroups = [];
foreach ($seats as $seat) {
    if (!isset($seatGroups[$seat['MaLoaiGhe']])) {
        $seatGroups[$seat['MaLoaiGhe']] = [];
    }
    $seatGroups[$seat['MaLoaiGhe']][] = $seat;
}

?>


<main class="2xl:tw-max-w-7xl xl:tw-max-w-[78rem] tw-mx-auto tw-mt-10  tw-px-1" x-data="{
    step: 3,
}">
    <div class='tw-flex tw-gap-x-4 tw-mb-8'>
        <template x-for="i in [1, 2, 3]" :key="i">
            <span x-bind:class="step >= i ? 'tw-bg-[#FFE7AA]' : ''"
                class="tw-w-12 tw-font-bold tw-text-2xl tw-h-12 tw-flex tw-items-center tw-justify-center tw-rounded-full  tw-border tw-border-black"
                x-text="i">
            </span>
        </template>
    </div>
    <div class="tw-max-w-4xl tw-mx-auto tw-mb-4 tw-font-bold tw-text-2xl">
        <h2 class='tw-mx-auto tw-text-center tw-uppercase'>
            CHÚC MỪNG BẠN ĐÃ THANH TOÁN THÀNH CÔNG BẰNG <?= $paymentType ?>
        </h2>
    </div>

    <div class="tw-max-w-4xl tw-mx-auto">

        <div
            class='tw-bg-[#045174] tw-mx-auto sm:tw-max-w-md md:tw-max-w-2xl tw-max-w-xs tw-p-3 md:tw-p-6 min-h-28 tw-border-2  tw-text-white tw-border-[#FFC700]'>
            <div class="tw-p-1 tw-flex tw-flex-col tw-gap-y-1">
                <div class="tw-flex tw-gap-x-2 md:tw-gap-x-8 tw-flex-col sm:tw-flex-row">
                    <div class="tw-flex-none tw-basis-1/3 tw-flex tw-mb-4 sm:tw-mb-0">
                        <img class="tw-object-cover
                        tw-max-w-44
                        tw-mx-auto
                        " style="display: inline-block;" src="<?= $show['HinhAnh'] ?>" alt="">
                    </div>
                    <div class="tw-flex-1">
                        <div class='tw-flex tw-justify-between tw-items-center'>
                            <h3
                                class="tw-uppercase tw-grow tw-line-clamp-3 tw-text-[#E48C44] tw-text-base sm:tw-text-2xl tw-font-bold">
                                <?= $show['TenPhim'] ?>
                            </h3>
                        </div>
                        <h4 class="tw-mt-2">
                            <?= $show['TenRapChieu'] ?>
                        </h4>
                        <h4 class='tw-italic tw-text-base'>
                            <?= $show['DiaChi'] ?>
                        </h4>
                        <h4 class='tw-mt-3 tw-hidden md:tw-block'>
                            <span class='tw-font-semibold tw-text-secondary'>Thời gian: </span>
                            <?= $show['NgayGioChieu'] ?>
                        </h4>
                        <h4 class='tw-mt-3 tw-hidden md:tw-block'>
                            <span class='tw-font-semibold tw-text-secondary'> Phòng chiếu: </span>
                            <?= $show['TenPhongChieu'] ?>
                        </h4>
                    </div>
                </div>
                <h4 class='tw-mt-3 md:tw-hidden'>
                    <span class='tw-font-semibold tw-text-secondary'>Thời gian: </span> <?= $show['NgayGioChieu'] ?>
                </h4>
                <h4 class='tw-mt-3 md:tw-hidden'>
                    <span class='tw-font-semibold tw-text-secondary'> Phòng chiếu: </span>
                    <?= $show['TenPhongChieu'] ?>
                </h4>
                <div class='tw-flex tw-flex-col tw-mt-3  '>
                    <div class='tw-flex tw-flex-wrap tw-gap-x-4'>
                        <div class='tw-basis-1/3 tw-font-semibold tw-text-secondary'>Loại vé</div>
                        <div class='tw-basis-1/3 tw-font-semibold tw-text-secondary'>Số vé</div>
                    </div>
                    <?php foreach ($ticketGroups as $ticketGroup): ?>
                        <div class='tw-flex tw-flex-wrap tw-gap-x-4 tw-text-base'>
                            <div class='tw-basis-1/3 tw-font-semibold '>
                                <span><?= $ticketGroup[0]['TenLoaiVe'] ?></span>
                            </div>
                            <div class='tw-basis-1/3 tw-font-semibold '>
                                <span><?= count($ticketGroup) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class='tw-flex tw-flex-col tw-mt-3  '>
                    <div class='tw-flex tw-flex-wrap tw-gap-x-4'>
                        <div class='tw-basis-1/3 tw-font-semibold tw-text-secondary'>Loại Ghế</div>
                        <div class='tw-basis-1/3 tw-font-semibold tw-text-secondary'>Số Ghế</div>
                    </div>
                    <?php foreach ($seatGroups as $seatGroup): ?>
                        <div class='tw-flex tw-flex-wrap tw-gap-x-4 tw-text-base'>
                            <div class='tw-basis-1/3 tw-font-semibold '>
                                <span><?= $seatGroup[0]['TenLoaiGhe'] ?></span>
                            </div>
                            <div class='tw-basis-1/3 tw-font-semibold '>
                                <?php foreach ($seatGroup as $seat): ?>
                                    <span><?= $seat['SoGhe'] ?> </span>

                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <h4 class='tw-my-2'>
                    <span class=' tw-font-semibold tw-text-secondary'>Bắp nước: </span>
                    <?php foreach ($foods as $food): ?>

                        <span>
                            <?= $food['TenThucPham'] ?> X
                            <?= $bookingData['ThucPhams'][array_search($food['MaThucPham'], array_column($bookingData['ThucPhams'], 'MaThucPham'))]['SoLuong'] ?>
                        </span>
                    <?php endforeach; ?>
                    <?php foreach ($combos as $combo): ?>
                        <span>
                            <?= $combo['TenCombo'] ?> X
                            <?= $bookingData['Combos'][array_search($combo['MaCombo'], array_column($bookingData['Combos'], 'MaCombo'))]['SoLuong'] ?>
                        </span>
                    <?php endforeach; ?>
                </h4>
            </div>
            <div class=' tw-border-b-4 tw-my-2 tw-border-white tw-border-dashed'></div>
            <div class='tw-flex tw-p-2 tw-text-xl sm:tw-text-2xl tw-justify-between'>
                <h2>
                    Tổng thanh toán
                </h2>
                <div>
                    <?= number_format($bookingData['TongTien']) ?>đ
                </div>
            </div>
            <div class="tw-flex tw-flex-col tw-mt-4 tw-items-center tw-justify-end tw-gap-1">
                <img style="display: inline-block;"
                    src="https://barcode.orcascan.com/?type=code128&format=svg&data=<?= $orderId ?>"
                    class="tw-mix-blend-multiply tw-max-h-20" />
                <div class="tw-text-center tw-text-xs ">
                    Mã hoá đơn: <?= $orderId ?>
                </div>
            </div>
        </div>
        <div>

        </div>


    </div>



</main>

<?php
require ('partials/footer.php'); ?>