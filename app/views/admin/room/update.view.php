<?php
title("Trang quản lý phòng chiếu");
require ('app/views/admin/header.php');


?>

<script>
const statuses = <?= json_encode($statuses) ?>;
const cinemas = <?= json_encode($cinemas) ?>;
const seats = <?= json_encode($seats) ?>;
const room = <?= json_encode($room) ?>;
const validatorRule = {
    TenPhongChieu: {
        default: room.TenPhongChieu,
        minLength: {
            value: 3,
            message: 'Tên phòng chiếu phải có ít nhất 3 ký tự'
        },
        maxLength: {
            value: 40,
            message: 'Tên phòng chiếu không được vượt quá 50 ký tự'
        },
    },
    ManHinh: {
        default: room.ManHinh,
        required: {
            value: true,
            message: 'Vui lòng chọn màn hình'
        },
    },
    MaRapChieu: {
        default: room.MaRapChieu,
        required: {
            value: true,
            message: 'Vui lòng chọn rạp chiếu'
        },
    },
    TrangThai: {
        default: room.TrangThai,
        required: {
            value: true,
            message: 'Vui lòng chọn trạng thái'
        },
    },
    ChieuDai: {
        default: room.ChieuDai,
        required: {
            value: true,
            message: 'Vui lòng nhập chiều dài'
        },
        min: {
            value: 1,
            message: 'Chiều dài phải lớn hơn 0'
        },
        max: {
            value: 100,
            message: 'Chiều dài không được vượt quá 100'
        },
    },
    ChieuRong: {
        default: room.ChieuRong,
        required: {
            value: true,
            message: 'Vui lòng nhập chiều rộng'
        },
        min: {
            value: 1,
            message: 'Chiều rộng phải lớn hơn 0'
        },
        max: {
            value: 100,
            message: 'Chiều rộng không được vượt quá 100'
        },
    }
}
</script>

<main class=' tw-h-full tw-w-full tw-overflow-y-auto tw-pb-40' x-data="
formValidator(validatorRule);
            ">
    <div class='tw-m-10 tw-relative tw-flex tw-flex-col tw-bg-white tw-p-5 tw-rounded-md tw-shadow-md tw-bg-clip-border'
        x-data="
         {
               listCells: [],
                getCellName:function (value) {
                    if (value.MaLoaiGhe === 0) {
                        return ''
                    }
                    if(value.SoGhe)
                    {
                        return value.SoGhe;
                    }
                    const index = value.index
                    const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                    const row = Math.floor(index / data.ChieuRong)
                    const col = index % data.ChieuRong
                    let skip = 0
                    //  duyệt hàng hiện tại, nếu có ghế trống thì tăng biến skip
                    // nếu có ghế col-span thì tăng biến skip
                    for (let i = 0; i < col; i++) {
                        if (this.listCells[row * data.ChieuRong + i].MaLoaiGhe === 0) {
                            skip++
                        }
                        if (this.listCells[row * data.ChieuRong + i].MaLoaiGhe === -1) {
                            skip++
                        }
                    }
                    return alphabet[row] + (col - skip + 1)
                },
                createInputSeats: function(maPhongChieu) {
                    const inputSeats = []
                    const deleteSeats = []
                    this.listCells.forEach((cell, index) => {
                        if (cell.MaLoaiGhe > 0) {
                            if(cell.MaLoaiGhe != cell.defaultType||cell.SoGhe !=cell.defaultSoGhe)
                            {
                                    inputSeats.push({
                                        MaGhe: cell.MaGhe,
                                        MaLoaiGhe: cell.MaLoaiGhe,
                                        SoGhe: this.getCellName(cell),
                                         X: index % data.ChieuRong,
                                        Y: Math.floor(index / data.ChieuRong),
                                    })
                            }
                        }else {
                            if(cell.MaGhe){
                                deleteSeats.push(cell.MaGhe)
                            }
                        }
                    })
                    return [inputSeats, deleteSeats]
                },
                createRequest: async function() {
                    const updateRoomPayload = {
                        ...data,
                        MaRapChieu: parseInt(data.MaRapChieu),
                        TrangThai: parseInt(data.TrangThai),
                    };
                    const res = await axios.post('', updateRoomPayload,{validateStatus: () => true})
                    if (res.status !=200) {
                        toast('Cập nhật phòng chiếu thất bại', {
                            position: 'bottom-center',
                            type: 'error'
                        });
                        return;
                    }else{
                        toast('Cập nhật phòng chiếu thành công', {
                            position: 'bottom-center',
                            type: 'success'
                        });
                    }
                    const MaPhongChieu = <?= $room['MaPhongChieu'] ?>;
                    const [
                        inputSeats,
                        deleteSeats
                    ] = this.createInputSeats(MaPhongChieu)
                    if (inputSeats.length === 0 && deleteSeats.length === 0) {
                        return;
                    }
                    const res1 = await axios.put('/api/ghe/cap-nhat-nhieu', {
                        MaPhongChieu: MaPhongChieu,
                        inputSeats: inputSeats,
                        deleteSeats: deleteSeats
                    },{validateStatus: () => true})
                    if (res1.status !=200) {
                        toast('Cập nhật ghế thất bại', {
                            position: 'bottom-center',
                            type: 'error'
                        });
                        window.location.reload()
                        return;
                    }else{
                        toast('Cập nhật ghế thành công', {
                            position: 'bottom-center',
                            type: 'success'
                        });
                    }
                  
                }
            }
            
        " x-init="
            // parse initial seats
            ChieuDai = parseInt(room.ChieuDai)
            ChieuRong = parseInt(room.ChieuRong)
            $nextTick(() => {
              const tempCells = Array.from({length: ChieuDai *ChieuRong}, (_, index) => {
                return {
                    ...emptySeat,
                    index: index,
                    }
                })

            seats.forEach((seat) => {
                const seatType = seatTypes.find((item) => item.MaLoaiGhe === seat.MaLoaiGhe)
                const index = seat.Y * ChieuRong + seat.X
                tempCells[index] = {
                    ...seat,
                    ...seatType,
                    defaultType: seat.MaLoaiGhe,
                    defaultSoGhe: seat.SoGhe,
                    index: index,
                }
                const take = seatType.Rong - 1
                for(let i = 1; i <= take; i++){
                    tempCells[index+i] = {
                        ...hiddenSeat,
                        index: index+i,
                    }
                }

            })
            listCells = [...tempCells]

            })
        ">
        <div class=" tw-flex tw-items-center tw-justify-between tw-gap-8 tw-mb-2">
            <div class='tw-flex tw-gap-x-6 tw-items-center'>
                <h5
                    class="tw-block tw-font-sans tw-text-2xl tw-antialiased tw-font-semibold tw-leading-snug tw-tracking-normal text-blue-gray-900">
                    Thêm phòng chiếu
                </h5>

            </div>
            <div class="tw-flex tw-flex-col tw-gap-2 shrink-0 sm:tw-flex-row tw-my-2">

                <a href="/admin/phong-chieu" data-ripple-light="true" class=" tw-btn tw-btn-ghost" type="button">

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
        </div>
        <div class='tw-mt-4 tw-px-4'>
            <form action="" class='tw-flex tw-flex-col tw-gap-y-3 form form-active'>
                <div class="form-group ">
                    <label for="TenPhongChieu" class="tw-text-lg tw-p-1">
                        Tên phòng chiếu
                    </label>
                    <input x-model="data.TenPhongChieu" type="text" name="TenPhongChieu" id="TenPhongChieu"
                        placeholder="Nhập tên phòng chiếu" class="form-control"
                        :class="{'is-invalid': errors?.TenPhongChieu && errors?.TenPhongChieu.length > 0}" required>
                    <div class="invalid-feedback" x-show="errors?.TenPhongChieu">
                        <span x-text="errors?.TenPhongChieu?.join(', ')"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ManHinh" class="tw-text-lg tw-p-1">
                        Màn hình
                    </label>
                    <input x-model="data.ManHinh" type="text" name="ManHinh" id="ManHinh" placeholder="Nhập màn hình"
                        class="form-control" :class="{'is-invalid': errors?.ManHinh && errors?.ManHinh.length > 0}"
                        required>
                    <div class="invalid-feedback" x-show="errors?.ManHinh">
                        <span x-text="errors?.ManHinh?.join(', ')"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="MaRapChieu" class="tw-text-lg tw-p-1">
                        Rạp chiếu
                    </label>
                    <select x-model="data.MaRapChieu" name="MaRapChieu" id="MaRapChieu" class="form-select"
                        :class="{'is-invalid': errors?.MaRapChieu && errors?.MaRapChieu.length > 0}" required>
                        <option value="" disabled selected hidden>Chọn rạp chiếu</option>

                        <template x-for="item in cinemas" :key="item.MaRapChieu">
                            <option :value="item.MaRapChieu" x-text="item.TenRapChieu"></option>
                        </template>

                    </select>
                    <div class="invalid-feedback" x-show="errors?.MaRapChieu">
                        <span x-text="errors?.MaRapChieu?.join(', ')"></span>
                    </div>
                </div>
                <div class="form-group ">
                    <label for="TrangThai" class="tw-text-lg tw-p-1">
                        Trạng thái
                    </label>
                    <select x-model="data.TrangThai" name="TrangThai" id="TrangThai" class="form-select"
                        :class="{'is-invalid': errors?.TrangThai && errors?.TrangThai.length > 0}" required>
                        <option value="" disabled selected hidden>Chọn trạng thái</option>
                        <?php foreach ($statuses as $status): ?>
                                                    <option value="<?= $status['MaTrangThai'] ?>"><?= $status['Ten'] ?></option>
                        <?php endforeach; ?>

                    </select>
                    <div class="invalid-feedback" x-show="errors?.TrangThai">
                        <span x-text="errors?.TrangThai?.join(', ')"></span>
                    </div>
                </div>
                <div class='tw-grid tw-grid-cols-2 tw-gap-x-2'>
                    <div class="form-group ">
                        <label for="ChieuDai" class="tw-text-lg tw-p-1">
                            Chiều dài
                        </label>
                        <input disabled x-model="data.ChieuDai" name="ChieuDai" id="ChieuDai"
                            placeholder="Nhập chiều dài" class="form-control"
                            :class="{'is-invalid': errors?.ChieuDai && errors?.ChieuDai.length > 0}" required>
                        <div class="invalid-feedback" x-show="errors?.ChieuDai">
                            <span x-text="errors?.ChieuDai?.join(', ')"></span>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="ChieuRong" class="tw-text-lg tw-p-1">
                            Chiều rộng
                        </label>
                        <input disabled x-model="data.ChieuRong" value='10' type="number" name="ChieuRong"
                            id="ChieuRong" placeholder="Nhập chiều rộng" class="form-control"
                            :class="{'is-invalid': errors?.ChieuRong && errors?.ChieuRong.length > 0}" required>
                        <div class="invalid-feedback" x-show="errors?.ChieuRong">
                            <span x-text="errors?.ChieuRong?.join(', ')"></span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class='tw-mt-8 '>
            <h5
                class="tw-block tw-font-sans tw-text-2xl tw-antialiased tw-font-semibold tw-leading-snug tw-tracking-normal text-blue-gray-900">
                Bố trí ghế
            </h5>
            <div class='tw-flex tw-gap-y-4 tw-pb-10 tw-flex-col tw-items-center tw-relative tw-select-none tw-overflow-x-auto'
                x-data="{
                isMouseDown: false,
                currentRect: null,
                startX: 0,
                startY: 0,
                rememberSelectedSeats : [],
                selectedSeats: [],
                isHoldCtrl: false,
         
                getPosition: (event) => {
                   const rect = $el.getBoundingClientRect();
                    const x = event.clientX - rect.left;
                    const y = event.clientY - rect.top;
                    return { x, y };
                }
            }" x-on:mousedown="
            if(2 === $event.button) return;
            if($refs.contextMenu.contains($event.target))return;
            $refs.contextMenu.classList.add('tw-hidden');
            console.log(isHoldCtrl);
                 if (isHoldCtrl) {
                    rememberSelectedSeats = [...selectedSeats];
                 } else {
                     document.querySelectorAll('.seat').forEach((seat) => {
                        seat.style.backgroundColor = seat.getAttribute('bg-normal');
                     });
                    rememberSelectedSeats = [];
                 }

                isMouseDown = true;
                currentRect = document.createElement('div');
                currentRect.style.position = 'absolute';
                currentRect.style.backgroundColor = 'rgba(0, 0, 0, 0.1)';
                currentRect.style.border = '1px solid rgba(0, 0, 0, 0.2)';
                currentRect.style.pointerEvents = 'none';
                currentRect.classList.add('rect');
                const { x, y } = getPosition($event);
                $el.appendChild(currentRect);
                startX =x;
         
                startY = y;
                currentRect.style.left = startX + 'px';
                currentRect.style.top = startY + 'px';
                currentRect.style.width = '0px';
                currentRect.style.height = '0px';
            " x-on:mousemove="
                if (!isMouseDown) return;
                const { x, y } = getPosition($event);
                 const endX = x;
                const endY = y;
                const minX = Math.min(startX, endX);
                const minY = Math.min(startY, endY);
                const maxX = Math.max(startX, endX);
                const maxY = Math.max(startY, endY);
                currentRect.style.left = minX + 'px';
                currentRect.style.top = minY + 'px';
                currentRect.style.width = maxX - minX + 'px';
                currentRect.style.height = maxY - minY + 'px';;
            " x-on:mouseup="
               if (!isMouseDown) return;
        const listSeats = document.querySelectorAll('.seat');
        isMouseDown = false;
        const { x, y } = getPosition($event);
        const endX = x;
        const endY = y;
        const minX = Math.min(startX, endX);
        const minY = Math.min(startY, endY);
        const maxX = Math.max(startX, endX);
        const maxY = Math.max(startY, endY);
        selectedSeats = [...rememberSelectedSeats];
        const seatElements = document.querySelectorAll('.seat');
        const rootRect = $el.getBoundingClientRect();
        seatElements.forEach((seat, index) => {
          const rect = seat.getBoundingClientRect();
          const rectLeft = rect.left - rootRect.left;
            const rectRight = rect.right - rootRect.left;
            const rectTop = rect.top - rootRect.top;
            const rectBottom = rect.bottom - rootRect.top;
          if (
            rectLeft < maxX &&
            rectRight > minX &&
            rectTop < maxY &&
            rectBottom  > minY
          ) {
            const row = Math.floor(index / data.ChieuRong);
            const col = index % data.ChieuRong;
            seat.style.backgroundColor = seat.getAttribute('bg-select');
            selectedSeats.push(seat.getAttribute('index'));
          }
        });
        document.querySelectorAll('.rect').forEach((rect) => {
          $el.removeChild(rect);
        });
        currentRect = null;
        " x-init="
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Control') {
                    isHoldCtrl = true;
                }
            });
            document.addEventListener('keyup', (event) => {
                if (event.key === 'Control') {
                    isHoldCtrl = false;
                }
            });
        " x-on:contextmenu.prevent="
            $refs.contextMenu.classList.toggle('tw-hidden');
            const { x, y } = getPosition($event);
            $refs.contextMenu.style.left = x + 'px';
            $refs.contextMenu.style.top = y + 'px';
         ">

                <ul x-ref="contextMenu"
                    class="tw-menu tw-shadow-lg tw-hidden tw-z-50 tw-absolute tw-bg-base-200 tw-min-w-60 tw-rounded-box">

                    <template x-for="item in seatTypes" :key="item.MaLoaiGhe">
                        <li x-on:click="
                              if (selectedSeats.length === 0) return toast('Vui lòng chọn ô', {
                                    position: 'bottom-center',
                                    type: 'warning'
                                });
                                if (item.Rong > 1 && selectedSeats.length !== item.Rong) return toast(`Vui lòng chọn ${item.Rong} ô cho loại ghế này`, {
                                    position: 'bottom-center',
                                    type: 'warning'
                                });
                                if (item.Rong > 1) {
                                    const tem = [...selectedSeats].map((item) => parseInt(item)).sort((a, b) => a - b);
                                    for (let i = 0; i < tem.length - 1; i++) {
                                        if (tem[i + 1] - tem[i] !== 1) {
                                            return toast('Vui lòng chọn ô liên tiếp', {
                                                position: 'bottom-center',
                                                type: 'warning'
                                            });
                                        }
                                    }
                                    const start = tem[0];
                                    listCells[start] = {
                                        ...listCells[start],
                                        ...item,
                                    }
                                    for (let i = 1; i < item.Rong; i++) {
                                        listCells[start + i] = {
                                            ...listCells[start + i],
                                            ...hiddenSeat,
                                        }
                                    }
                                    listCells = [...listCells];
                                }else{
                                    
                                listCells = listCells.map((cell, index) => {
                                    if (selectedSeats.includes(index.toString())) {
                                        
                                        if(cell.Rong > 1){
                                            for(let i = 1; i < cell.Rong; i++){
                                                selectedSeats.push((index+1).toString())
                                            }
                                        }
                                        return {
                                            ...cell,
                                            ...item,
                                        }
                                    }
                                    return cell;
                                });
                                }
                                $nextTick(() => {
                                    selectedSeats = [];
                                    $refs.contextMenu.classList.add('tw-hidden');
                                });
                        "><a class='tw-flex tw-items-center'>
                                <span :style="'background-color: ' + item.Mau" class='tw-w-5 tw-h-5 tw-rounded-md'>

                                </span> <span class='tw-grow' x-text="`Đặt thành ${item.TenLoaiGhe}`"></span>
                            </a></li>
                    </template>
                </ul>
                <div class='tw-w-full tw-flex tw-justify-center tw-mx-auto'>
                    <div
                        class='tw-w-[300px] tw-h-20 tw-bg-gray-200 tw-mx-auto tw-rounded-md tw-flex tw-justify-center tw-items-center'>
                        <p class='tw-text-lg tw-font-semibold'>Màn hình</p>
                    </div>
                </div>
                <div class='tw-w-full'>
                    <div class='tw-grid tw-mx-auto' x-data="{ 
        }" @createRequest.window="createRequest()" x-init="
         
          const cal= (value)=>{

            value.width = parseInt(value.width)
            value.height = parseInt(value.height)
            const tempCells = Array.from({length: value.width * value.height}, (_, index) => {
              return {
                ...emptySeat,
                index: index,
              }
            })
            
            listCells = tempCells   
          const SEAT_SIZE = 40;
          const SEAT_GAP = 5;
          const roomWidth = value.width * SEAT_SIZE + (value.width + 1) * SEAT_GAP;
          const roomHeight = value.height * SEAT_SIZE + (value.height + 1) * SEAT_GAP;
          $el.style.width = roomWidth + 'px';
         
          $el.style.height = roomHeight + 'px';
          $el.style.gridTemplateColumns = `repeat(${value.width}, ${SEAT_SIZE}px)`;
          $el.style.gap = SEAT_GAP + 'px';
          $el.style.padding = SEAT_GAP + 'px';
          }
          cal({
            width: data.ChieuRong,
            height: data.ChieuDai
          })
      ">
                        <template x-for="cell in listCells" :key="cell.index">
                            <div :hidden="cell.MaLoaiGhe === -1" :index="cell.index" :bg-select="cell.MauSelect"
                                :bg-normal="cell.Mau"
                                :style="`background-color: ${cell.Mau}; grid-column: span ${cell.Rong}; aspect-ratio: ${cell.Rong} / ${cell.Dai}`"
                                class=" seat tw-flex tw-text-white tw-cursor-pointer tw-justify-center tw-items-center tw-seat tw-rounded"
                                x-text="getCellName(cell)"></div>
                        </template>


                    </div>
                </div>
            </div>
        </div>
        <div class='tw-flex tw-justify-end tw-mt-4'>
            <button @click="
                if (!validate()) return toast('Vui lòng kiểm tra lại thông tin', {
                    position: 'bottom-center',
                    type: 'warning'
                });
                createRequest()
           " class='tw-btn tw-btn-primary tw-px-10'>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M14 4l0 4l-6 0l0 -4" />
                </svg>
                Lưu
            </button>
        </div>
    </div>

</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinycolor/1.6.0/tinycolor.min.js">

</script>
<script>
const emptySeat = {
    MaLoaiGhe: 0,
    TenLoaiGhe: 'Trống',
    MoTa: 'Trống',
    GiaVe: 0,
    Dai: 1,
    Rong: 1,
    Mau: '#525252',
    MauSelect: darkerColor('#525252')
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
var temp = <?= json_encode($seatTypes) ?>;

const seatTypes = [...temp, emptySeat].map((item) => {
    return {
        ...item,
        MauSelect: darkerColor(item.Mau)
    }
})

function darkerColor(color) {
    return tinycolor(color).darken(30).toString();
}
</script>
<?php

require ('app/views/admin/footer.php');
?>