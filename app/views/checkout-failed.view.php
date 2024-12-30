<?php
title("Thanh toán thất bại");
require ('partials/head.php');
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
    <div class="tw-max-w-4xl tw-mx-auto tw-mb-4 tw-font-bold tw-text-5xl">
        <h2 class='tw-mx-auto tw-text-center tw-uppercase'>
            Thanh toán thất bại
        </h2>
        <p class='tw-mx-auto tw-text-center tw-text-lg tw-mt-4'>
            Thanh toán không thành công, vui lòng kiểm tra lại thông tin thanh toán của bạn
        </p>
        <div class='tw-flex tw-justify-center tw-mt-8'>
            <a class='tw-btn tw-text-white tw-btn-primary tw-mx-auto' href="/">Quay lại trang chủ</a>
        </div>
    </div>




</main>

<?php
require ('partials/footer.php'); ?>