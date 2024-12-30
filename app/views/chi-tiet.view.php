<?php
title("Well come Cinestar");
function getSoNguoiDisplay($rong)
{
    if ($rong == 1) {
        return "Ghế đơn";
    } elseif ($rong == 2) {
        return "Ghế đôi";
    } else {
        return "Ghế $rong người";
    }
}
require ('partials/head.php'); ?>
<link rel="stylesheet" href="/public/chi_tiet_phim/test.css">
<link rel="stylesheet" href="/public/chi_tiet_phim/responsive.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"
    integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
var upcomingShows = <?= json_encode($upcomingShows) ?>;
// / use loaddash to group by date
var groupedShows = _.groupBy(upcomingShows, function(show) {
    return show.NgayGioChieu.split(" ")[0];
});


function indexToAlphabet(index) {
    return String.fromCharCode(65 + index);
}
const ageTags = {
    "P": {
        name: "P - Thích hợp cho mọi độ tuổi",
        minAge: 0,
    },
    "K": {
        name: "K - Được phổ biến người xem dưới 13 tuổi với điều kiện xem cùng cha mẹ hoặc người giám hộ",
        minAge: 0,
    },
    "T13": {
        name: "T13 - cấm người dưới 13 tuổi",
        minAge: 13,
    },
    "T16": {
        name: "T16 - cấm người dưới 16 tuổi",
        minAge: 16,
    },
    "T18": {
        name: "T18 - cấm người dưới 18 tuổi",
        minAge: 18,
    },
    "C": {
        name: "C - Phim không được phép phổ biến.",
        minAge: 0,
    }
}
</script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js" integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
<div class="container-lg -tw-mt-4" x-data="
    {
    selectedSchedule: null,
    fetchedCinemas: [],
    isFetchingCinemas: false,
    getShowsOfRoom: function(roomId) {
        const currentShow = groupedShows[this.selectedSchedule];
        return currentShow.filter(show => show.MaPhongChieu == roomId);
    },
    selectedShow: null,
    selectedTicketTypes: [], // ... seaticket,count
    finalPrice: 0,
    seats: [],
    ChieuDai: 0,
    ChieuRong: 0,
    roomSize: {
        width: 0,
        height: 0
    },
    SEAT_SIZE: 40,
    SEAT_GAP: 5,
    isDoneSelecting: false,
    getCurrentShowInfo: function() {
        return groupedShows[this.selectedSchedule].find(show => show.MaXuatChieu == this.selectedShow.MaXuatChieu);
    },
    getCurrentSelectSeatsInfo: function() {
        let listSelectedSeats = this.selectedTicketTypes.map(type => type.seats).flat();
        return this.seats.filter(seat => listSelectedSeats.includes(seat.MaGhe));
    },
    selectedFood: [],
    selectedCombo: [],
    async calFinalPrice() {
        let price = 0;
        let veCount = 0;
        for (let i = 0; i < this.selectedTicketTypes.length; i++) {
            const type = this.selectedTicketTypes[i];
            price += type.count * type.GiaVe;
        }
        let listSelectedSeats = this.selectedTicketTypes.map(type => type.seats).flat();
        for (let i = 0; i < this.seats.length; i++) {
            const seat = this.seats[i];
            if (listSelectedSeats.includes(seat.MaGhe)) {
                price += seat.GiaVe;
                veCount++;
            }
        }
        let currentShow = groupedShows[this.selectedSchedule];
         let showPrice = currentShow.find(show => show.MaXuatChieu == this.selectedShow.MaXuatChieu)?.GiaVe||0;
        console.log(currentShow,this.MaXuatChieu);
        price += showPrice * veCount;

        this.selectedFood.forEach(food => {
            price += food.GiaThucPham * food.count;
        })
        this.selectedCombo.forEach(combo => {
            price += combo.GiaCombo * combo.count;
        })


        this.finalPrice = price;
        return await Promise.resolve(price);
    },
    onSeatClick: function(seat) {
         if (seat.MaVe !== null) {
            toast('Ghế đã có người đặt', {
                position: 'bottom-center',
                type: 'danger'
            });
            return;
        }
        if(seat.selected) {
            for (let i = 0; i < this.selectedTicketTypes.length; i++) {
                const type = this.selectedTicketTypes[i];
                const index = type.seats.indexOf(seat.MaGhe);
                if (index !== -1) {
                    type.seats.splice(index, 1);
                    this.seats[seat.index].selected = false;
                    break;
                }
            }
            return;
        }
        const listValidSelectedTicketTypes = this.selectedTicketTypes.filter(type => type.Rong == seat.Rong);
        let isHasValid = false;
 
        for (let i = 0; i < listValidSelectedTicketTypes.length; i++) {
            const type = listValidSelectedTicketTypes[i];
            if (type.count > type.seats.length) {
                type.seats.push(seat.MaGhe);
                this.seats[seat.index].selected = true;
                isHasValid = true;
                break;
            }
        }
        if (!isHasValid) {
            toast('Ghế không hợp lệ với loại vé đã chọn', {
                position: 'bottom-center',
                type: 'danger'
            });
            return;
        }
         this.isDoneSelecting = this.selectedTicketTypes.every(type => type.count === type.seats.length);
         if(this.isDoneSelecting)   toast('Chọn ghế xong', {
            position: 'bottom-center',
            type: 'success'
        });
        $nextTick(() => {
            this.calFinalPrice();
        })
    },
    onStartCheckout:async function(){
        if(!this.isDoneSelecting) {
            toast('Vui lòng chọn đủ ghế', {
                position: 'bottom-center',
                type: 'danger'
            });
            return;
        }
        //  list ticket is list of
        // MaLoaiVe + MaGhe
        let ves = [];
        this.selectedTicketTypes.forEach(type => {
            type.seats.forEach(seat => {
                ves.push({
                    MaXuatChieu: this.selectedShow.MaXuatChieu,
                    MaLoaiVe: type.MaLoaiVe,
                    MaGhe: seat,
                    MaVe: this.seats.find(s => s.MaGhe == seat)?.MaVe
                })
            })
        });
        const payload = {
            MaXuatChieu: this.selectedShow.MaXuatChieu,
            DanhSachVe: ves,
            Combos: this.selectedCombo.map(combo => {
                return {
                    MaCombo: combo.MaCombo,
                    SoLuong: combo.count
                }
            }),
            ThucPhams: this.selectedFood.map(food => {
                return {
                    MaThucPham: food.MaThucPham,
                    SoLuong: food.count
                }
            }),
        }
        await axios.post('/api/start-checkout', payload).then(res => {
            // window.location.href = '/thanh-toan';
            console.log(res.data);
            window.location.href = `/thanh-toan`;
        }).catch(err => {
            console.log(err.response.data);
            toast('Đã có lỗi xảy ra', {
                position: 'bottom-center',
                type: 'danger',
                description: err.response.data.message
            });
        })
        
    
    }
}
" x-init="
const viewPortWidth = window.innerWidth;
if(viewPortWidth < 576) {
    SEAT_SIZE = 30;
    SEAT_GAP = 3;
}
$watch('isDoneSelecting', (value) => {
    if(value) {
        const selectedTicketTypeIds = selectedTicketTypes.map(type => type.MaLoaiVe);

        let ves = [];
        selectedTicketTypes.forEach(type => {
            type.seats.forEach(seat => {
                ves.push({
                    MaXuatChieu: selectedShow.MaXuatChieu,
                    MaLoaiVe: type.MaLoaiVe,
                    MaGhe: seat,
                })
            })
        });
        
        console.log(ves);
    }
})
$watch('selectedShow',async (value) => {
   if(value !== null) {
      await axios.get(`/api/suat-chieu/${value.MaXuatChieu}/ghe`).then(res => {
       const ChieuDaiInt = parseInt(ChieuDai);
       const ChieuRongInt = parseInt(ChieuRong);
       roomSize =calculateRoomSize(ChieuDaiInt,ChieuRongInt,SEAT_SIZE,SEAT_GAP);
       let cells = Array.from({length:ChieuDaiInt*ChieuRongInt},(v,i) => {
            return {
                ...emptySeat,
                X: i % ChieuDaiInt,
                Y: Math.floor(i / ChieuDaiInt),
                TrangThai: 0,
                MaLoaiGhe: 1,
                MaGhe: null,
                index: i
            }
        });
        const temp= res.data.data;
        temp.forEach(seat => {
            const seatType = seatTypes.find(type => type.MaLoaiGhe === seat.MaLoaiGhe);
            const index = parseInt(seat.Y) * ChieuRongInt +  parseInt(seat.X)
            cells[index] = {
                ...cells[index],
                ...seat,
                ...seatType,
                index: index
            }
            const take = seatType.Rong - 1
                for(let i = 1; i <= take; i++){
                    cells[index+i] = {
                        ...hiddenSeat,
                        index: index+i,
                    }
                }
        });
        seats = cells;
      })
   }
})

$watch('selectedSchedule',async (value) => {
    isFetchingCinemas = true;
    selectedShow = null;
    fetchedCinemas = [];
    selectedTicketTypes = [];
    seats = [];
    const selectedShowsDate = groupedShows[value];
    const roomIds = selectedShowsDate.map(show => show.MaPhongChieu);
    const res = await axios.post('/api/phong-chieu/ids/rap', { roomIds })
    let cinemas = res.data.data;
    cinemas = _.groupBy(cinemas, 'MaRapChieu');
    cinemas = Object.values(cinemas);
    cinemas = cinemas.map(cinema => {
        return {
            ...cinema[0],
            PhongChieus: cinema.map(c => c.PhongChieu)
        }
    })
    fetchedCinemas = cinemas;
    isFetchingCinemas = false;
})
">

    <section class="sec-detail pt-sm-5">
        <div class="container-fluid detail__wr">
            <div class="row">
                <div class="detail__left col-xl-5 col-lg-5 col-5">
                    <div class="detail__left--img">
                        <img src=<?= $phim['HinhAnh'] ?> alt="Poster <?= $phim['TenPhim'] ?>" />
                    </div>
                </div>
                <div class="detail__right col-xl-7 col-lg-7 col-7">
                    <div class="detail__right--des">
                        <div class="row">
                            <span class="des__name">
                                <?= $phim['TenPhim'] ?>
                            </span>
                            <div class="info__detail">
                                <div class="info__detail-icon">
                                    <svg width="30" height="30" viewBox="0 0 17 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M3.65102 10.7566C2.62066 9.72627 2.10549 9.21109 1.91379 8.54268C1.72209 7.87428 1.88592 7.16436 2.21357 5.74453L2.40252 4.92575C2.67818 3.73124 2.81601 3.13398 3.22499 2.72499C3.63398 2.31601 4.23123 2.17818 5.42574 1.90252L6.24453 1.71357C7.66436 1.38592 8.37428 1.22209 9.04268 1.41379C9.71109 1.60549 10.2263 2.12066 11.2566 3.15102L12.4764 4.37078C14.269 6.16344 15.1654 7.05976 15.1654 8.17358C15.1654 9.28739 14.269 10.1837 12.4764 11.9764C10.6837 13.769 9.78739 14.6654 8.67358 14.6654C7.55976 14.6654 6.66344 13.769 4.87078 11.9764L3.65102 10.7566Z"
                                            stroke="#F3EA28" stroke-width="1.5" />
                                        <circle cx="6.23718" cy="5.91797" r="1.33333"
                                            transform="rotate(-45 6.23718 5.91797)" stroke="#F3EA28"
                                            stroke-width="1.5" />
                                        <path d="M8.19548 12.332L12.8481 7.6792" stroke="#F3EA28" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>
                                <span class="info__detail-title">
                                    <?php
                                    $categoriesString = "";
                                    foreach ($categories as $category) {
                                        $categoriesString .= $category['TenTheLoai'] . ", ";
                                    }
                                    echo rtrim($categoriesString, ", ");
                                    ?>
                                </span>
                            </div>
                            <div class="info__detail">
                                <div class="info__detail-icon">
                                    <svg width="30" height="30" viewBox="0 0 17 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="8.4987" cy="7.9987" r="6.66667" stroke="#F3EA28"
                                            stroke-width="1.5" />
                                        <path d="M8.5 5.33203V7.9987L10.1667 9.66536" stroke="#F3EA28"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <span class="info__detail-title">
                                    <?= $phim['ThoiLuong'] ?> phút
                                </span>
                            </div>
                            <div class="info__detail">
                                <div class="info__detail-icon">
                                    <i class="fa-solid fa-earth-americas"></i>
                                </div>
                                <span class="info__detail-title">
                                    <?= $phim['NgonNgu'] ?>
                                </span>
                            </div>
                            <div class="info__detail">
                                <div class="info__detail-icon">
                                    <i class="icon-age fa-solid fa-user-check"></i>
                                </div>
                                <span class="info__detail-title" x-text="ageTags['<?= $phim['HanCheDoTuoi'] ?>'].name">
                                    P
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="movie__detail--item row mt-xxl-5 mt-xl-3 mt-lg-3 mt-md-3 mt-3">
                        <h3 class="tt sub-title text-white tw-mb-2  ">MÔ TẢ</h3>
                        <span class="detail-director text-white">
                            Đạo diễn: <?= $phim['DaoDien'] ?>
                        </span>
                        <span class="detail-actor__show text-decoration-underline show__actor" id="btnShowActor">
                            Xem thêm
                        </span>
                        <span class="detail-time text-white">
                            Khởi chiếu: <?php
                            $date = date_create($phim['NgayPhatHanh']);
                            echo date_format($date, "d/m/Y");
                            ?>
                        </span>
                    </div>
                    <div class="mobile-movie-detail movie__detail--item row mt-xxl-5 mt-xl-3 mt-lg-2 mt-md-3 mt-3">
                        <div class="mv__content">
                            <h3 class="mv__content-title text-white">NỘI DUNG PHIM</h3>
                            <span class="mv__content-des text-white tw-py-3 show__des" id="mv__description">
                                <?= $phim['MoTa'] ?>
                            </span>
                            <span class="mv__content-btn text-decoration-underline" id="btnShowDes">
                                Xem thêm
                            </span>
                        </div>
                    </div>
                    <div class="row mt-xl-2 mt-lg-1 mv__trailer" id="trailerContainer">
                        <a href="<?= $phim['Trailer'] ?>" data-fancybox="true" class="mv__trailer" id="showVideo">
                            <span class="mv__trailer-icon">
                                <img src="../assets/img/play.png" alt="" />
                            </span>
                            <span class="mv__trailer-title text-white">
                                Xem Trailer
                            </span>
                        </a>

                        <div id="videoModal" class="modal display-none">

                        </div>
                    </div>
                </div>
            </div>
            <div class="movie_details--mobile">
                <div class="row mt-xxl-5 mt-xl-3 mt-lg-3 mt-md-3 mt-3">
                    <h3 class="tt sub-title text-white">MÔ TẢ</h3>
                    <span class="detail-director text-white">
                        Đạo diễn: Trấn Thành
                    </span>
                    <!-- <span class="detail-actor text-white show__actor-mobile">
                        Diễn viên: Phương Anh Đào, Tuấn Trần, Trấn Thành, Hồng Đào, Uyển
                        Ân, Ngọc Giàu, Việt Anh, Quốc Khánh, Quỳnh Lý, Khả Như, Anh Đức,
                        Thanh Hằng, Ngọc Nga, Lộ Lộ, Kiều Linh, Ngọc Nguyễn, Quỳnh Anh,
                        Anh Thư.
                    </span> -->
                    <!-- <span class="detail-actor__show text-decoration-underline show__actor"
                            id="btnShowActor">
                            Xem thêm
                        </span> -->
                    <span class="detail-time text-white">
                        Khởi chiếu: <?php
                        $date = date_create($phim['NgayPhatHanh']);
                        echo date_format($date, "d/m/Y");
                        ?>
                    </span>
                </div>
                <div class="row mt-xxl-5 mt-xl-3 mt-lg-2 mt-md-3 mt-3">
                    <div class="mv__content">
                        <h3 class="mv__content-title text-white">NỘI DUNG PHIM</h3>
                        <span class="mv__content-des text-white show__des-mobile" id="mv__description-mobile">
                            <?= $phim['MoTa'] ?>
                        </span>
                        <span class="mv__content-btn text-decoration-underline" id="btnShowDesMobile">
                            Xem thêm
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Movie schedule -->
    <section class="movie__schedule pt-sm-5">
        <div class="container movie__schedule-wr">
            <div class="row">
                <div
                    class="mv__schedule-heading justify-content-center text-center mt-xxl-0 mt-xl-0 mt-lg-0 mt-md-0 mt-sm-0 mt-5">
                    <span class="text-center text-warning"> LỊCH CHIẾU </span>
                </div>
                <div class="swiper-wrapper mt-5 justify-content-center   tw-max-w-[100vw] tw-overflow-x-auto">
                    <div class='tw-invisible !tw-w-32 tw-min-w-32' x-show="window.innerWidth < 576"></div>

                    <template x-if="Object.keys(groupedShows).length === 0">
                        <div class="alert alert-warning tw-text-2xl" role="alert">
                            Phim chưa có lịch chiếu sắp tới
                        </div>
                    </template>
                    <template x-for="date in Object.keys(groupedShows)" :key="date">
                        <button class="box-time text-center tw-px-2 md:tw-px-0" x-on:click="
                        toggleActive($event.currentTarget);
                        selectedSchedule = date;
                        ">
                            <h4 class="date mt-3" x-text="dayjs(date).format('DD/MM')"></h4>

                            <p class="day tw-whitespace-nowrap" x-text="dayjs(date).getDayOfWeek()">Thứ Ba</p>
                        </button>
                    </template>

                </div>
            </div>
        </div>
        <div x-show="Object.keys(groupedShows).length !== 0" class="container-fluid movie__schedule-details 
            tw-px-2 md:tw-px-8
            mt-4 pt-5 pb-1 position-relative">
            <div
                class="movie__schedule-item container !tw-pb-2 bg-white rounded-2 pt-5 pt-lg-3 px-3 md:tw-px-8 my-xl-5">
                <template x-if="selectedSchedule === null">
                    <div role="alert" class="tw-alert tw-my-3 tw-alert-info tw-flex tw-items-center tw-justify-center">
                        <div>

                            <span>
                                Vui lòng chọn ngày để xem rạp chiếu
                            </span>
                        </div>
                    </div>
                </template>
                <template x-if="isFetchingCinemas">
                    <div class="tw-flex tw-w-full tw-py-16 tw-justify-center">
                        <span class="tw-loading tw-loading-dots tw-loading-lg"></span>
                    </div>
                </template>
                <template x-if="!isFetchingCinemas">
                    <template x-for="cinema in fetchedCinemas" :key="cinema.MaRapChieu">
                        <div class="row movie__schedule-detail my-xxl-3 mt-lg-5">
                            <span class="theater-name" x-text="cinema.TenRapChieu"></span>
                            <span class="theater-address" x-text="cinema.DiaChi"></span>

                            <div class="list__info-ctype" data-name="PixelCinema Quốc Thanh"
                                data-address="271 Nguyễn Trãi, Phường Nguyễn Cư Trinh, Quận 1, Thành Phố Hồ Chí Minh">
                                <template x-for="room in cinema.PhongChieus" :key="room.MaPhongChieu">
                                    <div>
                                        <div class="ctype-title tw-font-semibold " x-text="room.TenPhongChieu">

                                        </div>
                                        <ul class="row ctype-items justify-content-sm-start ms-sm-0 ps-0">
                                            <template x-for="show in getShowsOfRoom(room.MaPhongChieu)"
                                                :key="show.MaXuatChieu">
                                                <li :id-show="show.MaXuatChieu" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" :title="toVnd(show.GiaVe)" role="button"
                                                    x-on:click="
                                                    selectedShow = {
                                                        ...show,
                                                        PhongChieu: {
                                                            ...room
                                                        }
                                                    };
                                                    ChieuDai=room.ChieuDai;
                                                    ChieuRong=room.ChieuRong;
                                                    $nextTick(() => {
                                                       const elToScroll= document.getElementById('ticket-type-title');
                                                        elToScroll?.scrollIntoView({behavior: 'smooth'});
                                                    })
                                                    " class="ctype__item col-2 text-warning fs-6 text-center"
                                                    x-text="dayjs(show.NgayGioChieu).format('HH:mm')">

                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </template>

                            </div>
                        </div>
                    </template>
                </template>

            </div>



            <div class="moive_schedule-list position-absolute text-white">
                <span> DANH SÁCH RẠP </span>
            </div>

            <!-- Ticket types -->
            <template x-if="selectedShow !== null">
                <div class="container px-0">
                    <div class="mv__schedule_ticket justify-content-center text-center mt-5">
                        <span id="ticket-type-title" class="text-center text-warning"> LOẠI VÉ </span>
                    </div>
                    <div class="row d-flex tw-gap-y-3 justify-content-start list-tickets px-2 justify-content-center fw-bold"
                        id="row-ticket">
                        <?php foreach ($ticketTypes as $loaiVe): ?>

                        <div class="col-xl-4 col-lg-4 col-md-4 col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-2">
                            <div class="ticket__item px-2">
                                <div class="ticket-detail tw-w-full tw-grow">
                                    <span class="ticket-type d-block">
                                        <?= $loaiVe['TenLoaiVe'] ?>
                                    </span>
                                    <span class="ticket-des d-block fs-6">
                                        <?php


                                            $rong = $loaiVe['Rong'];
                                            echo getSoNguoiDisplay($rong)
                                                ?>
                                    </span>
                                    <span class="ticket-price fs-6">
                                        <?= number_format($loaiVe['GiaVe']) ?>đ
                                    </span>
                                </div>

                                <div class="ticket-count d-flex mt-3 mb-2 justify-content-center align-items-center">
                                    <div class="count-btn count-minus" x-on:click="
                                        const index = selectedTicketTypes.findIndex(type => type.MaLoaiVe == <?= $loaiVe['MaLoaiVe'] ?>);
                                        if(index !== -1) {
                                            selectedTicketTypes[index].count = Math.max(selectedTicketTypes[index].count - 1, 0);
                                            const MaGhe= selectedTicketTypes[index].seats.shift();
                                            seats.find(seat => seat.MaGhe == MaGhe).selected = false;
                                            seats = [...seats];
                                            calFinalPrice();
                                            if(selectedTicketTypes[index].count === 0) {
                                                selectedTicketTypes.splice(index, 1);
                                            }
                                        } else {
                                           
                                        }
                                        ">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                    <div class="count-number mx-2">
                                        <span
                                            x-text="selectedTicketTypes.find(type => type.MaLoaiVe == <?= $loaiVe['MaLoaiVe'] ?>)?.count ?? 0"></span>
                                    </div>
                                    <div class="count-btn count-plus" x-on:click="
                                        const index = selectedTicketTypes.findIndex(type => type.MaLoaiVe == <?= $loaiVe['MaLoaiVe'] ?>);
                                        if(index !== -1) {
                                            selectedTicketTypes[index].count += 1;
                                        } else {
                                            selectedTicketTypes.push({
                                                MaLoaiVe: <?= $loaiVe['MaLoaiVe'] ?>,
                                                count: 1,
                                                seats: [],
                                                Rong: <?= $loaiVe['Rong'] ?>,
                                                GiaVe: <?= $loaiVe['GiaVe'] ?>,
                                                SoNguoiDisplay: '<?= getSoNguoiDisplay($loaiVe['Rong']) ?>',
                                                TenLoaiVe:'<?= $loaiVe['TenLoaiVe'] ?>'
                                            })
                                        }
                                        ">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php endforeach; ?>
                    </div>
                </div>

            </template>

            <!-- Chair choice -->
            <template x-if="seats&&seats.length>0">
                <div class="container-xxl container-fluid px-0 mt-5">
                    <div class="chair_title justify-content-center text-center">
                        <span class="text-center text-warning"> CHỌN GHẾ </span>
                    </div>
                    <div class="row seat-screen mt-5 text-center position-relative">
                        <img src="/public/images/img-screen.png" alt="" />
                        <span class="seat-screen-title position-absolute fs-3 text-white">Màn hình</span>
                    </div>

                    <div class="minimap-container tw-mt-12">
                        <div class="seat-table mt-4">
                            <div class='tw-grid  tw-mx-auto' :style="{
                            width: roomSize.width + 'px',
                            gridTemplateColumns: `repeat(${parseInt(ChieuRong)}, ${SEAT_SIZE}px)`,
                            gap: SEAT_GAP + 'px',
                            padding: SEAT_GAP + 'px'
                        }">
                                <template x-for="cell in seats" :key="cell.index">
                                    <div :hidden="cell.MaLoaiGhe === -1" :index="cell.index" :style="{
                                            backgroundColor:cell.MaVe != null?'black': cell.selected? cell.MauSelect : cell.Mau,
                                            gridColumn: `span ${cell.Rong}`,
                                            aspectRatio: `${cell.Rong} / ${cell.Dai}`,
                                        }" :class="{
                                            'tw-cursor-pointer': cell.MaVe ==null,
                                            'tw-cursor-not-allowed': cell.MaVe != null,
                                        }" x-on:click="onSeatClick(cell)"
                                        class="hover-change-bg seat tw-flex tw-text-white  tw-justify-center tw-items-center  tw-seat  tw-rounded"
                                        x-text="cell.SoGhe"></div>
                                </template>

                            </div>
                        </div>
                    </div>
                    <div class="row seat-note mt-4 justify-content-between my-3 text-white">

                        <template x-for="seatType in realSeats" :key="seatType.MaLoaiGhe">
                            <div :style="`background-color: ${seatType.Mau}`"
                                class="seat__note-item col-xl-2 col-lg-2 col-md-2 col-6 d-flex align-items-center text-center">
                                <span x-text="seatType.TenLoaiGhe + ' - ' + toMoneyFormat(seatType.GiaVe)"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </section>


    <!-- Combo -->
    <div class="combo pt-sm-5 mb-5 mt-5 mt-sm-3" x-show="selectedShow !== null">
        <div class="container-fluid combo__heading">
            <div class="row">
                <div class="combo__title justify-content-center text-center">
                    <span class="text-center text-warning">
                        CHỌN BẮP NƯỚC
                    </span>
                </div>
            </div>

            <div id="carouselFood" class="carousel mt-5">
                <div class="carousel-inner">

                    <?php foreach ($combos as $combo): ?>
                    <div class="carousel-item ">
                        <div class="food-item tw-w-36 sm:tw-w-auto">
                            <div
                                class="food-item__image-container !tw-h-auto col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12">
                                <img src="<?= $combo['HinhAnh'] ?>" alt="" class="food-item__img">
                            </div>

                            <div
                                class="food-item__detail col-xl-7 col-lg-7 col-md-7 col-12 tw-justify-between tw-flex tw-flex-col">
                                <div>
                                    <span
                                        class="food-item__name  d-block justify-content-center align-items-center text-center">
                                        <?= $combo['TenCombo'] ?>
                                    </span>
                                    <span class="food-item__des d-block tw-line-clamp-2">
                                        <?= $combo['MoTa'] ?>
                                    </span>
                                </div>
                                <div>
                                    <span class="food-item__price d-block  tw-text-xl">
                                        <?= number_format($combo['GiaCombo']) ?>đ
                                    </span>
                                    <div
                                        class="food-item__btn tw-w-full tw-max-w-32 d-flex mt-3 mb-2 justify-content-center align-items-center">
                                        <div class="count-btn tw-px-2 count-minus" x-on:click="
                                            const index = selectedCombo.findIndex(combo => combo.MaCombo == <?= $combo['MaCombo'] ?>);
                                            if(index !== -1) {
                                                selectedCombo[index].count = Math.max(selectedCombo[index].count - 1, 0);
                                                if(selectedCombo[index].count === 0) {
                                                    selectedCombo.splice(index, 1);
                                                }
                                            }
                                            calFinalPrice()
                                            ">
                                            <i class="fa-solid fa-minus"></i>
                                        </div>
                                        <div class="count-number mx-2 tw-text-center tw-flex-1"
                                            x-text="selectedCombo.find(combo => combo.MaCombo == <?= $combo['MaCombo'] ?>)?.count ?? 0">
                                            0
                                        </div>
                                        <div class="count-btn count-plus tw-px-1" x-on:click="
                                            const index = selectedCombo.findIndex(combo => combo.MaCombo == <?= $combo['MaCombo'] ?>);
                                            if(index !== -1) {
                                                selectedCombo[index].count += 1;
                                            } else {
                                                selectedCombo.push({
                                                    MaCombo: <?= $combo['MaCombo'] ?>,
                                                    count: 1,
                                                    GiaCombo: <?= $combo['GiaCombo'] ?>,
                                                    TenCombo:'<?= $combo['TenCombo'] ?>'
                                                })
                                            }
                                            calFinalPrice()
                                            ">
                                            <i class="fa-solid fa-plus"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php foreach ($foods as $food): ?>
                    <div class="carousel-item">
                        <div class="food-item tw-w-36 sm:tw-w-auto">
                            <div
                                class="food-item__image-container !tw-h-auto col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12">
                                <img src="<?= $food['HinhAnh'] ?>" alt="" class="food-item__img">
                            </div>

                            <div
                                class="food-item__detail col-xl-7 col-lg-7 col-md-7 col-12 tw-justify-between tw-flex tw-flex-col">
                                <div>
                                    <span
                                        class="food-item__name d-block justify-content-center align-items-center text-center">
                                        <?= $food['TenThucPham'] ?>
                                    </span>
                                    <span class="food-item__des d-block tw-line-clamp-2">
                                        <?= $food['MoTa'] ?>
                                    </span>
                                </div>
                                <div>
                                    <span class="food-item__price d-block tw-font-semibold tw-text-xl tw-mt-auto">
                                        <?= number_format($food['GiaThucPham']) ?>đ
                                    </span>
                                    <div
                                        class="food-item__btn d-flex tw-max-w-32 tw-w-full mt-3 mb-2 justify-content-center align-items-center">
                                        <div class="count-btn count-minus tw-px-2" x-on:click="
                                    const index = selectedFood.findIndex(food => food.MaThucPham == <?= $food['MaThucPham'] ?>);
                                    if(index !== -1) {
                                        selectedFood[index].count = Math.max(selectedFood[index].count - 1, 0);
                                        if(selectedFood[index].count === 0) {
                                            selectedFood.splice(index, 1);
                                        }
                                    } else {
                                        selectedFood.push({
                                            MaThucPham: <?= $food['MaThucPham'] ?>,
                                            count: 1,
                                            GiaThucPham: <?= $food['GiaThucPham'] ?>,
                                            TenThucPham:'<?= $food['TenThucPham'] ?>'
                                        })
                                    }
                                    calFinalPrice()
                                    ">
                                            <i class="fa-solid fa-minus"></i>
                                        </div>
                                        <div class="count-number mx-2 tw-text-center tw-flex-1"
                                            x-text="selectedFood.find(food => food.MaThucPham == <?= $food['MaThucPham'] ?>)?.count ?? 0">
                                            0
                                        </div>
                                        <div class="count-btn count-plus tw-px-2" x-on:click="
                                        const index = selectedFood.findIndex(food => food.MaThucPham == <?= $food['MaThucPham'] ?>);
                                        if(index !== -1) {
                                            selectedFood[index].count += 1;
                                           
                                        } else {
                                            selectedFood.push({
                                                MaThucPham: <?= $food['MaThucPham'] ?>,
                                                count: 1,
                                                GiaThucPham: <?= $food['GiaThucPham'] ?>,
                                                TenThucPham:'<?= $food['TenThucPham'] ?>'
                                            })
                                        }
                                        calFinalPrice()
                                        ">
                                            <i class="fa-solid fa-plus"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselFood" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselFood" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>

            </div>
        </div>
    </div>

    <!-- Ticket -->
    <template x-if="selectedShow != null">
        <div class="ticket container-fluid pt-3 pb-3 mt-5">
            <div class="row ticket-details">
                <span class="ticket-title fs-4">
                    <?= $phim['TenPhim'] ?>
                </span>
                <div class="row ticket-theater__detail d-inline-block">
                    <span class="ticket-theater__name position-relative" id="id1">
                    </span>
                    <span class="ticket-theater__address" id="id2"> </span>
                </div>

                <div class="row ticket-detail">
                    <div class="col-6 ps-3" id="ticket-rs-wrapper">
                        <template x-for="type in selectedTicketTypes" :key="type.MaLoaiVe">
                            <div class="ticket-type">
                                <span class="ticket-type__number" x-text="type.count"></span>
                                <span class="ticket-type__title" x-text="type.TenLoaiVe"></span>
                                <span class="ticket-type__title" x-text="` (${type.SoNguoiDisplay})`"></span>

                            </div>
                        </template>

                    </div>
                    <div class="col-6">
                        <div class="room">
                            <span class="txt">Suất chiếu: </span>
                            <span class="room-tilte me-1" x-text="selectedShow?.PhongChieu.TenPhongChieu"></span>

                            <span class="time-tilte" x-text="dayjs(selectedShow?.NgayGioChieu).format('HH:mm')"></span>

                            </span>
                        </div>
                        <div class="chair">
                            <span class="txt">Ghế: </span>
                            <span class="chair-tilte"
                                x-text="getCurrentSelectSeatsInfo()?.map(seat => seat.SoGhe).join(', ')"></span>

                        </div>
                        <div class="combo">
                            <span class="txt">Combo: </span>
                            <template x-for="combo in selectedCombo" :key="combo.MaCombo">
                                <span class="combo-title" x-text="`${combo.TenCombo} x${combo.count}`"></span>
                            </template>
                            <template x-for="food in selectedFood" :key="food.MaThucPham">
                                <span class="combo-title" x-text="`${food.TenThucPham} x${food.count}`"></span>
                            </template>
                            <template x-if="selectedCombo.length === 0 && selectedFood.length === 0">
                                <span class="combo-title">Không có</span>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="row text-warning d-flex mt-2">
                    <div class="col-lg-1 col-1"></div>
                    <div class="bill-time col-lg-2 col-4 justify-content-center align-items-center">
                        <span class="d-block">Thời gian giữ vé: </span>
                        <span class="bill-time-countdown fs-4" id="countdown">
                            <?php
                            $locketTime = getArrayValueSafe($GLOBALS['config']['Website'], 'hold_time', 10);
                            // format MM:SS
                            echo $locketTime . ':00';
                            ?>
                        </span>
                    </div>
                    <div class="col-lg-3 col-1"></div>
                    <div class="bill-detail col-6">
                        <div class="ticket-price fs-5">
                            <span class="ticket-price__title"> Tạm tính: </span>
                            <span class="ticket-price__number" x-text="toMoneyFormat(finalPrice)"></span>
                        </div>
                        <button x-on:click="onStartCheckout()"
                            class="btn-to-pay mt-3 align-items-center text-center justify-content-center">
                            Thanh toán
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinycolor/1.6.0/tinycolor.min.js"></script>
<script>
const emptySeat = {
    MaLoaiGhe: 0,
    TenLoaiGhe: 'Trống',
    MoTa: 'Trống',
    GiaVe: 0,
    Dai: 1,
    Rong: 1,
    Mau: 'rgba(0,0,0,0)',
    MauSelect: 'rgba(0,0,0,0)'
}
const hiddenSeat = {
    MaLoaiGhe: -1,
    TenLoaiGhe: 'Ẩn',
    MoTa: 'Trống',
    GiaVe: 0,
    Dai: 1,
    Rong: 1,
    Mau: '#525252',
    MauSelect: darkerColor('#525252')

}
const bookedSeat = {
    MaLoaiGhe: -2,
    TenLoaiGhe: 'Đã Đặt',
    MoTa: 'Đã Đặt',
    GiaVe: 0,
    Dai: 1,
    Rong: 1,
    Mau: '#020617',
    MauSelect: darkerColor('#525252')
}

const realSeats = <?= json_encode($seatTypes) ?>;
const seatTypes = [...realSeats, emptySeat, bookedSeat].map((item) => {
    return {
        ...item,
        MauSelect: darkerColor(item.Mau)
    }
})

function darkerColor(color) {
    return tinycolor(color).darken(30).toString();
}

function calculateRoomSize(ChieuDai, ChieuRong, SEAT_SIZE, SEAT_GAP) {
    return {
        width: ChieuRong * SEAT_SIZE + (ChieuRong + 1) * SEAT_GAP,
        height: ChieuDai * SEAT_SIZE + (ChieuDai + 1) * SEAT_GAP
    }
}

function toMoneyFormat(value) {
    return Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(value);
}
</script>

<?php
script("https://code.jquery.com/jquery-3.7.1.min.js");
script("/public/chi_tiet_phim/test.js");
script("/public/chi_tiet_phim/carousel.js");


require ('partials/footer.php'); ?>