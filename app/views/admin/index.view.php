<?php
title("Admin");
require ('app/views/admin/header.php');
?>

<div class='tw-min-h-screen tw-flex tw-w-full tw-justify-center tw-items-center'>
    <h1 class='tw-text-4xl tw-font-bold'>
        Chào mừng
        <?php echo $_SESSION['user']['TenNguoiDung'] ?>
        đến với trang quản trị

    </h1>
</div>

<?php
require ('app/views/admin/footer.php');


?>