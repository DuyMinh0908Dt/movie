<?php
title("Cài đặt WebSite");
require ('app/views/admin/header.php');
$configs = $GLOBALS['config'];
// return [
//     "Auth" => [
//         "secret" => "secret",
//         "remember_time_in_days" => 30,
//     ],
//     "Website" => [
//         "name" => "",
//         "logo" => "/public/assets/img/logo.png",
//         "phone" => "",
//         "email" => "",
//         "description" => "Mô tả nè",
//         "hold_time" => "5",
//     ],
//     "Banners" => [
//         [
//             "image" => "https://cinestar.com.vn/_next/image/?url=https%3A%2F%2Fapi-website.cinestar.com.vn%2Fmedia%2FMageINIC%2Fbannerslider%2Fthanh-xuan-18x2.jpg&w=3840&q=75",
//             "href" => "#",
//         ],
//     ],
// ];
?>

<link rel="stylesheet" href="/public/cai-dat-website/home.css">
<script>
const initbanners = <?= json_encode($configs['Banners']) ?>;
</script>
<div x-data="
   {
    banners: initbanners,
    addBanner: function ({ image, href }) {
        this.banners.push({
            image: image,
            href: href,
        });
    },
    removeBanner: function (image) {
        this.banners = this.banners.filter(banner => banner.image !== image);
    },
    onSaveBanners: async function() {
        const res = await axios.post('/api/cai-dat-website/banners', this.banners, {
            validateStatus:()=>true
        });
        if(res.data.status === 200) {
            toast('Lưu thành công', {
                position: 'bottom-center',
                type: 'success'
            });
        } else {
            toast('Lưu thất bại', {
                position: 'bottom-center',
                type: 'danger'
            });
        }
    },
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
    selectedIndex: 0,
    selectedBanner: {},
   }

" style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="wrapper p-5">
    <div class="access container-fluid shadow pb-5">
        <div class="row mt-2 d-flex">
            <div class="tab_box">
                <button class="tab_btn">Banner</button>
                <button class="tab_btn">Cấu hình</button>
            </div>
            <div class="line"></div>
        </div>

        <!-- list banner -->
        <div class="container-fluid content mt-3 active">
            <div class="row" style="margin-right: 2px;">

            </div>
            <div class="row mt-3">
                <div class="grid text-center d-flex flex-wrap">
                    <template x-for="(banner, index) in banners" :key="index">
                        <div
                            class="col-xl-4 col-lg-4 mb-3 d-flex justify-content-center align-items-center position-relative">
                            <div class="card" style="max-width: 30rem; width:100%">
                                <div class="tw-cursor-pointer" style="background-color: transparent; border: none;"
                                    id="btn-modal-banner-detail" data-bs-toggle="modal" x-on:click="
                                    selectedIndex = index;
                                    selectedBanner = { ...banner };
                                    $('#banner-detail-modal').modal('show');
                                    ">
                                    <img :src="banner.image" src="https://loading.io/assets/mod/spinner/spinner/lg.gif"
                                        class="card-img" alt="..." />
                                </div>
                            </div>
                        </div>
                    </template>

                </div>

            </div>

            <!-- add new banner -->
            <div class="row mt-4">
                <div class="grid text-center d-flex flex-wrap justify-content-center">
                    <div
                        class="col-xl-4 col-lg-4 mb-3 d-flex justify-content-center align-items-center position-relative">
                        <div class="card justify-content-center align-content-center"
                            style="max-width: 30rem; width:100%; height: 154px; background-color: #cccdd2">
                            <button style="background-color: transparent; border: none" class="h-100"
                                id="btn-create-banner" data-bs-toggle="modal" data-bs-target="#banner-modal"
                                type="button">
                                <img src="/public/images/icons8-plus-100.png" alt="" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class='tw-flex tw-justify-end tw-px-4'>
                <button x-on:click="onSaveBanners()" class='btn btn-primary'>
                    Lưu
                </button>
            </div>
        </div>

        <!-- Settings website -->
        <form class="container-fuild content mt-3 ps-5" x-on:submit.prevent="
        const websiteConfig = {
            name: $event.target.WEBSITE_NAME.value,
            logo: $event.target.WEBSITE_LOGO.value,
            phone: $event.target.WEBSITE_PHONE.value,
            email: $event.target.WEBSITE_EMAIL.value,
            description: $event.target.WEBSITE_DESCRIPTION.value,
            hold_time: $event.target.TICKET_HOLD_TIME.value,
            remember_time_in_days: $event.target.REMEMBER_TIME_IN_DAYS.value,
        };
        console.log(websiteConfig);
        ">
            <div class="row d-flex justify-content-start tw-mb-5">
                <h2 class="title col-8 ps-5">

                </h2>
                <a href="/admin/logs" target="_blank" class="col-1 ms-4 btn btn-outline-success">
                    Logs
                </a>
            </div>

            <!-- Tên Website -->
            <div class="row d-flex mt-2">
                <label for="WEBSITE_NAME" class="col-xl-2 fs-admin pe-0 ps-3">
                    Tên website
                </label>
                <div class="input-group has-validation col-xl-10 p-0">
                    <input type="text" class="form-control " id="WEBSITE_NAME" name="WEBSITE_NAME" pattern=".{3,}"
                        title="Tên website phải có ít nhất 3 ký tự" value="<?= $configs['Website']['name'] ?>"
                        aria-describedby="WEBSITE_NAME-feedback" required>
                    <div id="WEBSITE_NAME-feedback" class="invalid-feedback">
                        Please choose a username.
                    </div>
                </div>
            </div>

            <!-- Logo -->
            <div class="row d-flex mt-2">
                <label class="col-xl-2">Logo</label>
                <div class="input-group  tw-p-0">
                    <input title="Link hình ảnh không hợp lệ" type="text" class="form-control"
                        value="<?= $configs['Website']['logo'] ?>" placeholder="Link logo" id="WEBSITE_LOGO"
                        aria-label="Recipient's username" aria-describedby="button-addon2" name="HinhAnh"
                        pattern="(https?://)?.+\.(png|jpe?g|gif|svg|webp)$" require>
                    <label class="btn btn-outline-secondary">Chọn
                        <input x-on:change="
                                        const file = $event.target.files[0];
                                        const spinner = $event.target.nextElementSibling;
                                        spinner.hidden = false;
                                        uploadFile(file).then((res) => {
                                                $('#WEBSITE_LOGO').val(res);
                                        }).finally(() => {
                                            spinner.hidden = true;
                                        });
                                        " type="file" hidden accept="image/*">
                        <span hidden class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </label>

                </div>
            </div>

            <!-- Số điện thoại -->
            <div class="row d-flex mt-2">
                <label for="WEBSITE_PHONE" class="col-xl-2 fs-admin pe-0 ps-3">
                    Số điện thoại
                </label>
                <div class="input-group has-validation col-xl-10 p-0">
                    <input type="tel" class="form-control " id="WEBSITE_PHONE" name="WEBSITE_PHONE"
                        value="<?= $configs['Website']['phone'] ?>" aria-describedby="WEBSITE_PHONE-feedback" required>
                    <div id="WEBSITE_PHONE-feedback" class="invalid-feedback">
                        Please choose a username.
                    </div>
                </div>
            </div>

            <!-- Email -->
            <div class="row d-flex mt-2">
                <label for="WEBSITE_EMAIL" class="col-xl-2 fs-admin pe-0 ps-3">
                    Email
                </label>
                <div class="input-group has-validation col-xl-10 p-0">
                    <input type="email" class="form-control " id="WEBSITE_EMAIL" name="WEBSITE_EMAIL"
                        value="<?= $configs['Website']['email'] ?>" aria-describedby="WEBSITE_EMAIL-feedback" required>
                    <div id="WEBSITE_EMAIL-feedback" class="invalid-feedback">
                        Please choose a username.
                    </div>
                </div>
            </div>

            <!-- Mô tả website -->
            <div class="row d-flex mt-2">
                <label for="WEBSITE_DESCRIPTION" class="col-xl-2 fs-admin pe-0 ps-3">
                    Mô tả website
                </label>
                <div class="input-group has-validation col-xl-10 p-0">
                    <textarea id="WEBSITE_DESCRIPTION" name="WEBSITE_DESCRIPTION" required class="form-control "
                        aria-describedby="WEBSITE_DESCRIPTION-feedback"><?= $configs['Website']['description'] ?></textarea>
                    <div id="WEBSITE_DESCRIPTION-feedback" class="invalid-feedback">
                        Please choose a username.
                    </div>
                </div>
            </div>

            <!-- Thời gian giữ vé (phút) -->
            <div class="row form-group mt-2">
                <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">
                    Thời gian giữ vé (phút)
                </label>
                <div class="col-4 p-0">
                    <div class="input-group date">
                        <input type="number" min="2" max="60" step="1" title="Thời gian giữ vé phải từ 2 đến 60 phút"
                            name="TICKET_HOLD_TIME" id="TICKET_HOLD_TIME" name="TICKET_HOLD_TIME" class="form-control "
                            required value="<?= $GLOBALS['config']['Website']['hold_time'] ?>">
                        <div id="TICKET_HOLD_TIME-feedback" class="invalid-feedback">
                            Please choose a minutes
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thời gian nhớ đăng nhập (ngày) -->
            <div class="row form-group mt-2">
                <label for="date" class="col-2 col-form-label fs-admin pe-0 ps-3">Thời gian nhớ đăng nhập</label>
                <div class="col-4 p-0">
                    <div class="input-group date">
                        <input type="number" name="REMEMBER_TIME_IN_DAYS" name="REMEMBER_TIME_IN_DAYS" min="2" step="1"
                            title="Thời gian nhớ đăng nhập phải lớn hơn 2" id="REMEMBER_TIME_IN_DAYS"
                            class="form-control " required value="<?= $configs['Auth']['remember_time_in_days'] ?>">
                        <div id="REMEMBER_TIME_IN_DAYS-feedback" class="invalid-feedback">
                            Please choose a day
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <button type="submit" class="btn btn-primary col-1 btn-save-settings">
                    Lưu
                </button>
            </div>
        </form>

        <!-- Modal create new banner -->
        <div class="modal fade bs-example-modal-lg" id="banner-modal" tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form class="modal-content" x-on:submit.prevent="
                const imageLink = $event.target.HinhAnh.value;
                const href = $event.target.href.value;
                addBanner({ image: imageLink, href: href });
                $('#banner-modal').modal('hide');
                ">
                    <div class="modal-header position-relative">
                        <h4 class="modal-title" id="myLargeModalLabel">Hình ảnh</h4>
                        <button type="button" class="close close-modal position-absolute" data-dismiss="modal"
                            aria-hidden="true" id="btn-close-modal-banner">
                            ×
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="create-banner container bg-white">
                            <div class="row d-flex mt-2">
                                <label class="col-xl-2">Hình
                                    ảnh</label>
                                <div class="input-group tw-flex-1 tw-p-0">
                                    <input title="Link hình ảnh không hợp lệ" class="form-control"
                                        placeholder="Link hình ảnh" aria-label="Recipient's username"
                                        aria-describedby="button-addon2" name="HinhAnh"
                                        pattern="https?://.+\.(png|jpe?g|gif|svg|webp)$" require>
                                    <label class="btn btn-outline-secondary">Chọn
                                        <input x-on:change="
                                        const file = $event.target.files[0];
                                        const spinner = $event.target.nextElementSibling;
                                        spinner.hidden = false;
                                        uploadFile(file).then((res) => {
                                                $('#banner-modal input[name=HinhAnh]').val(res);
                                        }).finally(() => {
                                            spinner.hidden = true;
                                        });
                                        " type="file" hidden accept="image/*">
                                        <span hidden class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </label>

                                </div>
                            </div>
                            <div class="row d-flex mt-2">
                                <label for="new-banner-link" class="col-xl-2">
                                    Đường dẫn đến
                                </label>
                                <div class="input-group-modal has-validation col-xl-10 p-0">
                                    <input required class="form-control" aria-describedby="new-banner-link-feedback"
                                        name="href">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-dismiss="modal"
                            id="btn-cancel-modal-banner">
                            Huỷ
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Thêm banner
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal update banner -->
        <div class="modal fade bs-example-modal-lg" id="banner-detail-modal" tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form class="modal-content" x-on:submit.prevent="
                const imageLink = $event.target.HinhAnh.value;
                const href = $event.target.href.value;
                banners[selectedIndex].image = imageLink;
                banners[selectedIndex].href = href;
                banners= [...banners];
                $('#banner-detail-modal').modal('hide');
                ">
                    <div class="modal-header position-relative">
                        <h4 class="modal-title" id="myLargeModalLabel">Hình ảnh</h4>
                        <button type="button" class="close close-modal position-absolute" data-dismiss="modal"
                            aria-hidden="true" id="btn-close-modal-detail-banner">
                            ×
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="create-banner container bg-white">

                            <div class="row d-flex mt-2">
                                <label class="col-xl-2">Hình
                                    ảnh</label>
                                <div class="input-group tw-flex-1 tw-p-0">
                                    <input x-model="selectedBanner.image" class="form-control"
                                        pattern=".+\.(png|jpe?g|gif|svg|webp)$" title="Link hình ảnh không hợp lệ"
                                        placeholder="Link hình ảnh" name="HinhAnh" aria-label="Recipient's username"
                                        aria-describedby="button-addon2">
                                    <label class="btn btn-outline-secondary">Chọn
                                        <input x-on:change="
                                        const file = $event.target.files[0];
                                        const spinner = $event.target.nextElementSibling;
                                        spinner.hidden = false;
                                        uploadFile(file).then((res) => {
                                            $('#banner-detail-modal input[name=HinhAnh]').val(res);
                                        }).finally(() => {
                                            spinner.hidden = true;
                                        });
                                        " type="file" hidden accept="image/*">
                                        <span hidden class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row d-flex mt-2">
                                <label for="detail-banner-link" class="col-xl-2">
                                    Đường dẫn đến
                                </label>
                                <div class="input-group-modal has-validation col-xl-10 p-0">
                                    <input x-model="selectedBanner.href" type="text" id="detail-banner-link" required
                                        class="form-control " aria-describedby="detail-banner-link-feedback"
                                        pattern="https?://.+" name="href">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal" x-on:click="
                        banners.splice(selectedIndex, 1);
                        " id="btn-delete-modal-detail-banner">
                            Delete
                        </button>
                        <button class="btn btn-secondary" data-dismiss="modal" id="btn-cancel-modal-detail-banner">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
script('/public/cai-dat-website/banner.js');
script('/public/cai-dat-website/tab.js');
require ('app/views/admin/footer.php')
    ?>