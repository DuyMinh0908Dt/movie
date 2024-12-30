   <div class='tw-flex tw-gap-x-14 tw-flex-col lg:tw-flex-row tw-text-base sm:tw-text-xl tw-gap-y-8'
       x-show="step === 1">
       <div class='tw-basis-2/5 lg:tw-shrink-0 tw-gap-4 tw-flex tw-flex-col tw-justify-center tw-text-xl'>
           <div>
               <label for="email" class="tw-block tw-font-bold tw-text-gray-700">Email</label>
               <input x-on:blur="
                if(!window.validationsUtils.email(data.email)) {
                    errors.email = 'Email không hợp lệ'
                    return
                }
                const res = await axios.post('/api/nguoi-dung/is-mail-exist', { email: data.email });
                console.log(res.data)
                if (res.data.data.isExist && res.data.data.HasAccount) {
                    errors.email = 'Email này đã có tài khoản, vui lòng đăng nhập'
                    return
                }
                if (res.data.data.isExist) {
                    data.name = res.data.data.HoTen
                    data.phone = res.data.data.SoDienThoai
                    return
                }

                $refs.phone.disabled = false
                $refs.name.disabled = false
                
               " x-on:focus="
               errors.email = ''
               $refs.phone.disabled = true
                $refs.name.disabled = true
               " x-model="data.email" type="text" name="email" id="email"
                   class="tw-mt-1 tw-px-4 tw-w-full tw-py-2 tw-border-[3px]  hover:tw-border-[#0c131d]  tw-border-[#1B2D44]"
                   placeholder="Email">
               <span x-text="errors.email" class="error_message"></span>
           </div>
           <div>
               <label for="name" class="tw-block tw-font-bold tw-text-gray-700">Họ và tên</label>
               <input disabled x-ref="name" x-on:focus="errors.name = ''" x-model="data.name" type="text" name="name"
                   id="name" class="tw-mt-1 
                   disabled:tw-bg-gray-200
                   tw-px-4 tw-w-full tw-py-2 tw-border-[3px]  hover:tw-border-[#0c131d]  tw-border-[#1B2D44]"
                   placeholder="Họ và tên">
               <span x-text="errors.name" class="error_message"></span>
           </div>
           <div>
               <label for="phone" class="tw-block tw-font-bold tw-text-gray-700">Số điện thoại</label>
               <input disabled x-ref="phone" x-on:focus="errors.phone = ''" x-model="data.phone" type="tel" name="phone"
                   id="phone"
                   class="tw-mt-1 disabled:tw-bg-gray-200 tw-px-4 tw-w-full tw-py-2 tw-border-[3px]  hover:tw-border-[#0c131d]  tw-border-[#1B2D44]"
                   placeholder="Số điện thoại">
               <span x-text="errors.phone" class="error_message"></span>
           </div>

           <div class="tw-flex tw-justify-center">
               <button data-ripple-light="true" x-on:click="
                if (validate()) {
                    step = 2
                };  
                console.log(errors)
                "
                   class=' tw-px-12 tw-py-2 tw-flex tw-justify-center tw-items-center tw-bg-[#14244B] tw-text-[#FFC700] tw-rounded-md'>
                   Tiếp tục
               </button>
           </div>
       </div>
       <!-- SELECT  SuatChieu.MaXuatChieu , SuatChieu.NgayGioChieu , SuatChieu.NgayGioKetThuc , Phim.TenPhim , Phim.HinhAnh , PhongChieu.TenPhongChieu , RapChieu.TenRapChieu , RapChieu.DiaChi FROM `SuatChieu` INNER JOIN `Phim` ON SuatChieu.MaPhim = Phim.MaPhim INNER JOIN `PhongChieu` ON SuatChieu.MaPhongChieu = PhongChieu.MaPhongChieu INNER JOIN `RapChieu` ON PhongChieu.MaRapChieu = RapChieu.MaRapChieu WHERE SuatChieu.MaXuatChieu = '867' -->




       <div class="tw-grow">
           <div class='tw-bg-[#045174]  tw-p-6 min-h-28 tw-border-2  tw-text-white tw-border-[#FFC700]'>
               <div class="tw-p-1 tw-flex tw-flex-col tw-gap-y-1">
                   <div class='tw-flex tw-justify-between tw-items-center'>
                       <h3
                           class="tw-uppercase tw-grow tw-line-clamp-3 tw-text-[#E48C44] tw-text-base sm:tw-text-2xl tw-font-bold">
                           <?= $show['TenPhim'] ?>
                       </h3>
                       <div class='tw-uppercase tw-flex tw-items-center tw-gap-x-3'>
                           <span>
                               Giữ vé
                           </span>
                           <div class="tw-bg-secondary tw-py-2 tw-px-2 tw-rounded-xl"
                               x-text="getRemainingDisplayTime()">
                               05:00
                           </div>
                       </div>
                   </div>
                   <h4 class="tw-mt-2">
                       <?= $show['TenRapChieu'] ?>
                   </h4>
                   <h4 class='tw-italic tw-text-base'>
                       <?= $show['DiaChi'] ?>
                   </h4>
                   <h4 class='tw-mt-3 '>
                       <span class='tw-font-semibold tw-text-secondary'>Thời gian: </span><?= $show['NgayGioChieu'] ?>
                   </h4>
                   <h4 class='tw-mt-3'>
                       <span class='tw-font-semibold tw-text-secondary'> Phòng chiếu: </span>
                       <?= $show['TenPhongChieu'] ?>
                   </h4>
                   <div class='tw-flex tw-flex-col tw-mt-3 '>
                       <div class='tw-flex tw-flex-wrap tw-gap-x-4'>
                           <div class='tw-basis-1/3 tw-font-semibold tw-text-secondary'>Loại vé</div>
                           <div class='tw-basis-1/3 tw-font-semibold tw-text-secondary'>Số vé</div>
                       </div>


                       <template x-for="ticket in Object.keys(ticketGroups)">
                           <div class='tw-flex tw-flex-wrap tw-gap-x-4 tw-text-base'>
                               <div class='tw-basis-1/3 tw-font-semibold '>
                                   <span x-text="ticketGroups[ticket][0].TenLoaiVe"></span>
                               </div>
                               <div class='tw-basis-1/3 tw-font-semibold '>
                                   <span x-text="ticketGroups[ticket].length||1"></span>
                               </div>
                           </div>
                       </template>
                   </div>
                   <h4 class='tw-mt-2'>
                       <span class='tw-font-semibold tw-text-secondary'>Ghế: </span>
                       <?php foreach ($seats as $seat): ?>
                       <span><?= $seat['SoGhe'] ?> </span>
                       <?php endforeach; ?>
                   </h4>
                   <h4>
                       <span class='tw-font-semibold tw-text-secondary'>Bắp nước: </span>
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
               <div class=' tw-border-b-4 tw-my-4 tw-border-white tw-border-dashed'></div>
               <div class='tw-flex tw-p-4 tw-text-2xl sm:tw-text-3xl tw-justify-between'>
                   <h2>
                       Số tiền cần thanh toán
                   </h2>
                   <div>
                       <?= number_format($bookingData['TongTien']) ?>đ
                   </div>
               </div>
           </div>
       </div>
   </div>