<?php
title("Kết quả tìm kiếm");
require ('partials/head.php');
?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker-bs5.min.css">
    <style>
        :root {}

        .header2 {
            background-color: rgb(4, 81, 116);
        }

        .heading {
            border: solid rgb(243, 235, 215);
            border-width: 5px 0 5px 0;
        }

        .product {}

        .movie-item {
            width: 25%;
        }

        img {
            object-fit: cover;
            max-width: 100%;
        }

        .button {
            color: rgba(228, 140, 68);
            background-color: rgb(27, 45, 68);
        }
    </style>
    <div x-data="{
    data: [],
    page: 1,
    limit: 12,
    total: 0,
    listPage: [],
    isLoading: false,
    isShowFilter: false,
    handleFileChange: async function(event) {
    this.data = []
            var file = event.target.files[0]; // Get the selected file
            if (file) {
                // Create a new FormData object
                var formData = new FormData();
                formData.append('query_img', file);  // Append the image file to FormData

    // Make a POST request to the FastAPI endpoint
    try {
    let response = await fetch('http://127.0.0.1:8000/api/search_image', {
    method: 'POST',
    body: formData,  // Send the FormData which includes the file
    });

    // Parse the JSON response
    if (response.ok) {
    this.data = await response.json();
    } else {
    throw new Error('API call failed');
    }
    } catch (error) {
    console.error('Error:', error);

    }
    }
    },
    handleChange: async function(event) {
     this.data = []
            const inputValue = document.getElementById('textKeyword').value;

            if (inputValue) {
            const queryParams = new URLSearchParams({ content: inputValue.trim() });

    // Make a POST request to the FastAPI endpoint
    try {
    let response = await fetch(`http://127.0.0.1:8000/api/search_content?${queryParams.toString()}`, {
    method: 'GET',
    });

    // Parse the JSON response
    if (response.ok) {
    const data = await response.json();  // Parse the response body as JSON
                console.log(data);  // Log the entire response to inspect its structure

                // Safely access the matches property
                if (data.matches && Array.isArray(data.matches)) {
                    this.data = data.matches;  // Assign the matches array to this.data
                } else {
                    console.error('No matches found in the response:', data);
                    this.data = [];  // Optionally set an empty array if no matches
                }
    } else {
    throw new Error('API call failed');
    }
    } catch (error) {
    console.error('Error:', error);

    }
    }
    }

}"
">
        <div class="header2 container-fluid ">
            <div class="mt-3 row justify-content-center heading">
                <div class="col-auto">
                    <h1
                            class="m-1 text-center text-uppercase fw-medium tw-font-semibold tw-text-2xl tw-py-2 tw-text-white">
                        Kết quả tìm kiếm
                    </h1>
                </div>
            </div>

        </div>
        <div class='container'>
            <div class="tw-collapse tw-collapse-arrow !tw-overflow-visible  tw-bg-white tw-rounded-md tw-shadow-sm">
                <input type="checkbox" x-model="isShowFilter" />
                <div class="tw-collapse-title tw-text-xl tw-font-medium">
                    Bộ lọc
                </div>
                <div class="tw-collapse-content" style="visibility: hidden;"
                     :style="isShowFilter ? 'visibility: visible; opacity: 1;' : 'visibility: hidden; opacity: 0;'">

                    <div class="tw-collapse tw-collapse-arrow tw-border-b tw-bg-white tw-rounded-md tw-shadow-sm">
                        <input type="checkbox" />
                        <div class="tw-collapse-title tw-text-xl tw-font-medium">
                            Từ khóa
                        </div>
                        <div class="tw-collapse-content">
                            <input x-on:change="handleChange()" id="textKeyword" type="text" class="tw-input tw-input-bordered tw-w-full"
                                   placeholder="Nhập từ khóa">
                        </div>
                    </div>
                    <div class="tw-collapse tw-collapse-arrow tw-border-b tw-bg-white tw-rounded-md tw-shadow-sm">
                        <input type="checkbox" />
                        <div class="tw-collapse-title tw-text-xl tw-font-medium">
                            Hình ảnh
                        </div>
                        <div class="tw-collapse-content">
                            <input  type="file" id="fileInput" x-on:change="handleFileChange(event)" class="tw-input tw-input-bordered tw-w-full"
                                   placeholder="Nhập Hình ảnh">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container product my-lg-4 " x-data="{}">
            <div class="row tw-gap-y-4 ">
                <template x-if="isLoading">
                    <div class="text-center col-12 tw-justify-center tw-items-center tw-py-12">
                        <span class="tw-loading tw-loading-dots tw-loading-lg"></span>
                    </div>
                </template>
                <template x-if="!isLoading && data.length == 0">
                    <div class="text-center col-12 tw-justify-center tw-items-center tw-py-12">
                        <span class="tw-text-xl tw-font-semibold">Không có kết quả nào</span>
                    </div>
                </template>
                <template x-for="item in data" :key="item.MaPhim">
                    <div class="mb-4 col-xl-3 gx-5 mb-xl-0 col-md-4 col-6 col-lg-4 ">
                        <div class="tw-rounded-md tw-pb-2">
                            <div class="app-carousel-movie-item__img" x-on:mouseover="onItemMouseOver($event)"
                                 x-on:mouseout="onItemMouseOut($event)">

                                <div class="p-4 text-white app-carousel-movie-item__info--hover text-start fs-5">
                                    <h2 class="fs-4 fw-bold" x-text="item.TenPhim"></h2>

                                    <div class="mt-5">
                                        <div>
                                            <i class="me-3 fa-solid fa-layer-group"></i>
                                            <span x-text="item.DinhDang"></span>
                                        </div>

                                        <div>
                                            <i class="mt-2 me-3 fa-solid fa-clock"></i>
                                            <span x-text="item.ThoiLuong"></span>
                                        </div>

                                        <div>
                                            <i class="mt-2 fa-solid fa-closed-captioning me-3"></i>
                                            <span x-text="item.NgonNgu"></span>
                                        </div>


                                    </div>
                                </div>
                                <img :src="item.HinhAnh" class="d-block w-100" alt="...">
                            </div>
                            <h4 class="mt-3 app-carousel-movie-item__title" x-text="item.TenPhim"></h4>
                            <div class="mt-4 d-flex justify-content-around align-items-center">
                                <a style="color: var(--color1)" :href="item.Trailer">
                                    <i class="fa-solid fa-star"></i>
                                    <span>Xem Trailer</span>
                                </a>
                                <a style="margin-right: 4px;" class="login-item btn-login btn"
                                   :href="'/phim/' + item.MaPhim">

                                    <i class="fa-solid fa-star"></i>
                                    <span class="ms-2">Đặt vé</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <nav aria-label="..." class='tw-flex tw-items-center tw-justify-center tw-py-5 tw-my-10'>
                <div class="tw-join">
                    <template x-for="item in listPage" :key="item">

                        <input class="tw-join-item tw-btn tw-btn-square" type="radio" name="options" :aria-label="item"
                               :value="item" x-model="page" :checked="item == page" />

                    </template>
                </div>

            </nav>
        </div>
    </div>
    <script>
        function onItemMouseOver(e) {
            const target = e.currentTarget;
            const infoEle = target.getElementsByClassName(
                "app-carousel-movie-item__info--hover"
            )[0];
            infoEle.style.opacity = 1;
        }

        function onItemMouseOut(e) {
            const target = e.currentTarget;
            const infoEle = target.getElementsByClassName(
                "app-carousel-movie-item__info--hover"
            )[0];
            infoEle.style.opacity = 0;
        }


    </script>

    <script type="module">
        import queryString from 'https://cdn.jsdelivr.net/npm/query-string@9.0.0/+esm'
        window.queryString = queryString
    </script>

<?php
script("https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker.min.js");
script("https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/locales/vi.js");
require ('partials/footer.php'); ?>