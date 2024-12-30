<?php
title("Tiến hành thanh toán");
require ('partials/head.php');
script(("/public/js/validation.js"));
?>



<script src="
https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js
"></script>
<script>
const tickets = <?= json_encode($tickets) ?>;
// ticked grouped by type
const ticketGroups = _.groupBy(tickets, 'MaLoaiVe');
</script>


<dialog id="timeout_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">
            Thời gian giữ vé đã hết
        </h3>
        <p class="py-4">
            Thời gian giữ vé đã hết, vui lòng chọn lại ghế
        </p>
        <div class="modal-action">
            <form method="dialog">
                <!-- if there is a button in form, it will close the modal -->
                <button onclick="window.location.href = '/phim/<?= $show['MaPhim'] ?>'" class="btn">
                    Quay lại
                </button>
            </form>
        </div>
    </div>
</dialog>
<main
    class="2xl:tw-max-w-7xl xl:tw-max-w-[78rem] lg:tw-max-w-[63rem]  tw-mx-auto tw-mt-10 md:tw-max-w-2xl sm:tw-max-w-[38rem]  tw-px-4 sm:tw-px-1"
    x-data="{
    step: <?php if (isset($_SESSION['user']))
        echo 2;
    else
        echo 1; ?>,
    remainingTime: 0,
    getRemainingDisplayTime() {
        const minutes = Math.floor(this.remainingTime / 60);
        const seconds = this.remainingTime % 60;
        return `${minutes<10?'0'+minutes:minutes}:${seconds<10?'0'+seconds:seconds}`;
    }
}" x-init="
    const lockToDayjs = dayjs.unix(<?= $bookingData['lockTo'] ?>);  
    const now = dayjs();
    const diff = lockToDayjs.diff(now, 'second');
    console.log(diff);
    remainingTime = diff;
  const interval=  setInterval(() => {
        remainingTime--;
        if (remainingTime <= 0) {
            clearInterval(interval);
            alert('Thời gian giữ vé đã hết');
            window.location.href = '/phim/<?= $show['MaPhim'] ?>';
            
        }
    }, 1000);
">

    <div class='tw-flex  tw-gap-x-4 tw-mb-8 '>
        <template x-for="i in [1, 2, 3]" :key="i">
            <span x-on:click="if (i < step) { step = i }" x-bind:class="step >= i ? 'tw-bg-[#FFE7AA]' : ''"
                class="tw-w-12 tw-font-bold tw-text-2xl tw-h-12 tw-flex tw-items-center tw-justify-center tw-rounded-full  tw-border tw-border-black"
                x-text="i">
            </span>
        </template>
    </div>
    <?php
    if (isset($_SESSION['user'])) {
        $json = '{}';
    } else {
        $json = "
        {
        name:{
            required: {
                message: 'Họ và tên không được để trống'
            }
            
        },
        phone:{
            pattern: {
                value: /^((\+84|0)[2|3|5|7|8|9])+([0-9]{8})\b/,
                message: 'Số điện thoại không hợp lệ'
            }
        },
        email:{
            pattern: {
                value: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,
                message: 'Email không hợp lệ'
            }
        }
    }
       ";
    }

    ?>
    <div id="step-content" x-data="formValidator(<?= $json ?>)">
        <?php
        include_once ('partials/checkout.step2.php');
        ?>
        <?php include_once ('partials/checkout.step1.php'); ?>

    </div>
</main>

<?php require ('partials/footer.php'); ?>