 <div class=' tw-gap-x-14 tw-flex-col lg:tw-flex-row  tw-text-xl tw-gap-y-8 tw-hidden' x-data="{
    reducePrice:0,
    }" x-init="
 $el.classList.remove('tw-hidden');
 $el.classList.add('tw-flex');
 " x-show="step === 2">
     <style>
     .checkout-button {
         cursor: pointer;
         display: flex;
         align-items: center;
         padding: 5px;
         padding-left: 15px;
     }

     .checkout-button img {
         height: 50px;
         width: 50px;
         margin-left: 10px;
         padding: 5px;


     }

     .content {
         padding-left: 15px;
     }

     .content .checkout-title {
         color: #000;
         font-size: 13pt;
         font-weight: 600;
     }

     .content .description {
         color: #666
     }

     input[type="radio"]:hover {
         cursor: pointer;
     }
     </style>
     <!-- #[Route(path: '/api/promotions/{code}', method: 'GET')]
    public static function checkPromotion($code)
    {
        $bookingData = $_SESSION['bookingData'];
        $promotion = PromotionService::checkPromotion($code, array_map(fn($item) => $item['MaLoaiVe'], $bookingData['DanhSachVe']), $bookingData['TongTien']);
        return json($promotion);
    } -->
     <div x-data="{
        isLoading:false,
        checkPromotion:async function(code) {
            code = code.trim();
          const res=await  axios.get(`/api/promotions/${code}`,{validateStatus: () => true});
          if(res.status === 200){
            console.log(res.data.reducePrice);
              reducePrice = res.data.data.reducePrice;
              return res.data;
          }
          console.log(res.data);
          console.log(errors);
          errors.discount=res.data.message;
          return null;
        },
        onSubmit:async function(){
            this.isLoading=true;
            if(!validate()){
                this.isLoading=false;
                return;
            }
            if(data.discount){
                const promotion = await this.checkPromotion(data.discount);
                if(promotion === null){
                    this.isLoading=false;
                    return;
                }
            }
           const res = await axios.post('',this.data);
           if(res.data.data.isRedirect){
               if(res.data.data.redirectUrl.startsWith('http')){
                   window.location.href = res.data.data.redirectUrl;
               }else{
                   window.location.href = window.location.origin + res.data.data.redirectUrl;
               }
           }
           console.log('Success:', res.data);
           this.isLoading=false;
        },
     }" class='tw-basis-2/5 tw-grow-0  lg:tw-shrink-0  tw-flex tw-flex-col  tw-gap-4 lg:tw-text-xl'>
         <div class='tw-mt-16 tw-flex tw-flex-col tw-gap-4'>
             <div class="tw-block tw--mb-1 tw-font-bold  tw-text-gray-700">
                 Phương thức thanh toán
             </div>
             <div class="">
                 <label class="checkout-button  tw-border-secondary tw-border-3" for="<?= PaymentType::Momo->value ?>">
                     <div class="checkout-selector">
                         <input x-model="data.payment_method" id="<?= PaymentType::Momo->value ?>" type="radio"
                             class="btn btn-m2 btn-checkout btn-logo-inline" name="payment-method" value="<?php

                             use App\Core\Request;

                             echo PaymentType::Momo->value ?>">
                     </div>
                     <div class="content" style="  display: flex;align-items: center;">
                         <span class="checkout-title">
                             Thanh toán bằng
                         </span>
                         <img src="https://developers.momo.vn/v2/images/logo.svg" />
                     </div>
                 </label>
             </div>
             <div>
                 <label class="checkout-button  tw-border-secondary tw-border-3"
                     for="<?= PaymentType::ZaloPay->value ?>">
                     <div class="checkout-selector">
                         <input x-model="data.payment_method" id="<?= PaymentType::ZaloPay->value ?>" type="radio"
                             class="btn btn-m2 btn-checkout btn-logo-inline" name="payment-method"
                             value="<?php echo PaymentType::ZaloPay->value ?>">
                     </div>
                     <div class="content" style="  display: flex;align-items: center;">
                         <span class="checkout-title">
                             Thanh toán bằng
                         </span>
                         <img
                             src="https://play-lh.googleusercontent.com/MXoXRQvKYcPzk0AITb6nVJUxZMaWYESXar_HwK8KXbGMboZPQjcwVBcVtXlpOkfD7PM" />
                     </div>
                 </label>
             </div>
             <?php if (isset($GLOBALS['config']['env']) && $GLOBALS['config']['env'] == 'dev'): ?>
             <div>
                 <label class="checkout-button  tw-border-secondary tw-border-3"
                     for="<?= PaymentType::Mock_Succeed->value ?>">
                     <div class="checkout-selector">
                         <input x-model="data.payment_method" id="<?= PaymentType::Mock_Succeed->value ?>" type="radio"
                             class="btn btn-m2 btn-checkout btn-logo-inline" name="payment-method"
                             value="<?php echo PaymentType::Mock_Succeed->value ?>">
                     </div>
                     <div class="content" style="  display: flex;align-items: center;">
                         <span class="checkout-title">
                             Test thanh toán thành công
                         </span>
                     </div>
                 </label>
             </div>
             <div>
                 <label class="checkout-button  tw-border-secondary tw-border-3"
                     for="<?= PaymentType::Mock_Failed->value ?>">
                     <div class="checkout-selector">
                         <input x-model="data.payment_method" id="<?= PaymentType::Mock_Failed->value ?>" type="radio"
                             class="btn btn-m2 btn-checkout btn-logo-inline" name="payment-method"
                             value="<?php echo PaymentType::Mock_Failed->value ?>">
                     </div>
                     <div class="content" style="  display: flex;align-items: center;">
                         <span class="checkout-title">
                             Test thanh toán thất bại
                         </span>
                     </div>
                 </label>
             </div>
             <?php endif; ?>
         </div>
         <?php if (Request::isAuthenicated()): ?>
         <div>
             <label for="discount" class="tw-block  tw-font-bold  tw-text-gray-700">
                 Mã giảm giá
             </label>
             <input x-on:blur="checkPromotion(data.discount)" x-on:focus="errors.discount = ''" x-model="data.discount"
                 type="text" name="discount" id="discount"
                 class="tw-mt-1 tw-px-4 tw-w-full tw-py-2 tw-border-3  hover:tw-border-[#0c131d]  tw-border-[#1B2D44]"
                 placeholder="Nhập mã giảm giá">
             <div x-show="errors.discount" x-text="errors.discount"
                 class="tw-text-red-500 tw-mt-1 tw-italic tw-text-sm"></div>
         </div>
         <?php endif; ?>
         <div class="tw-flex tw-justify-center">
             <button data-ripple-light="true" x-on:click="onSubmit()" x-bind:disabled="isLoading"
                 class=' tw-px-12 tw-py-2 tw-flex tw-justify-center tw-items-center tw-bg-primary tw-text-secondary tw-rounded-md'>
                 Tiếp tục
             </button>
         </div>
     </div>
     <div class="tw-grow">
         <div class='tw-bg-[#045174]  tw-p-6 min-h-28 tw-border-2  tw-text-white tw-border-primary'>
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
                         <div class="tw-bg-secondary tw-py-2 tw-px-2 tw-rounded-xl" x-text="getRemainingDisplayTime()">
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
                 <div class='tw-flex tw-flex-col tw-mt-3  '>
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
                 <div class='tw-text-primary'>
                     <?= number_format($bookingData['TongTien']) ?>đ
                 </div>
             </div>
             <div class='tw-flex tw-px-4  tw-text-xl sm:tw-text-2xl tw-justify-between'>
                 <h2>
                     Giảm giá
                 </h2>
                 <div class='tw-text-primary' x-text="formatVnd(reducePrice||0)">
                 </div>
             </div>
             <div class='tw-flex tw-px-4 tw-py-4 tw-text-2xl sm:tw-text-3xl tw-justify-between'>
                 <h2>
                     Tổng thanh toán
                 </h2>
                 <div class='tw-text-primary' x-text="formatVnd(<?= $bookingData['TongTien'] ?> - reducePrice||0)">
                 </div>
             </div>
         </div>
     </div>
 </div>
 <script>
function formatVnd(value) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(value);
}
 </script>