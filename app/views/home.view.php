<?php
title("Welcome to Cinestar");
$configs = $GLOBALS['config'];
$banners = $configs['Banners'];
require ('partials/head.php'); ?>

<script>
var listCommingMovies = <?= json_encode($commingMovies) ?>;
var listMovie = <?= json_encode($phims) ?>;
console.log(listMovie);
</script>


<script>
var isLogginSuccess = <?php
if (isset($_GET['isloginSuccess']))
    echo 1;
else
    echo 0;
?>;
document.addEventListener("DOMContentLoaded", () => {
    if (isLogginSuccess) {
        toast('Đăng nhập thành công', {
            position: 'bottom-center',
            type: 'success'
        })
    }
})
</script>


<div class="container -tw-mt-32">
    <!-- Header -->

    <!-- Carousel -->
    <div id="carouselExampleCaptions" class="carousel carousel-app slide" data-bs-touch="true" data-bs-ride="carousel"
        data-bs-interval="5000">

        <div class="carousel-inner">

            <?php foreach ($banners as $key => $banner): ?>
                <?php
                $class = $key == 0 ? 'carousel-item active' : 'carousel-item ';
                ?>
                <a class="<?= $class ?>" href="<?= $banner['href'] ?>">
                    <img style="aspect-ratio: 1215/365;object-fit: cover;" src="<?= $banner['image'] ?>"
                        class="d-block w-100 tw-min-h-52 md:tw-min-h-0" alt="...">
                </a>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <!--End Carousel -->

    <!-- Moives -->
    <h2 class="text-uppercase text-center text-dark heading mb-4 fw-bold">Phim đề xuất</h2>
    <h2 class="text-uppercase text-center text-dark heading mb-4 fw-bold">Phim đang chiếu</h2>

    <div class="movies-container">
        <div class="row movies-wrapper g-1">

        </div>
    </div>


    <ul class="pagination pagination-info mt-4 d-flex justify-content-center">
        <li class="page-item"><a href="javascript:void(0);" class="page-link page-prev-btn">PREV</a></li>
        <div class="d-flex number-page">

        </div>
        <li class="page-item"><a href="javascript:void(0);" class="page-link page-next-btn">NEXT</a></li>
    </ul>
    <!--End Moives -->


    <!--Comming Movies-->
    <h2 class="text-uppercase text-center text-dark heading mb-4 fw-bold">Phim Sắp Chiếu</h2>

    <div class="movies-container">
        <div class="row comming-movies-wrapper g-1">

        </div>
    </div>


    <ul class="pagination mt-4 d-flex justify-content-center">
        <li class="page-item"><a href="javascript:void(0);" class="page-link page-prev-btn-comming">PREV</a></li>
        <div class="d-flex number-page-comming">

        </div>
        <li class="page-item"><a href="javascript:void(0);" class="page-link page-next-btn-comming">NEXT</a></li>
    </ul>
    <!--End Comming Movies-->

</div>

<?php
script("/public/assets/javascript/movie.js");
script("/public/assets/javascript/commingMovies.js")
    ?>

<?php require ('partials/footer.php'); ?>