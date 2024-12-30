var ticketArray = [
    {
        MaLoaiVe : 've1',
        TenLoaiVe: 'NGƯỜI LỚN',
        GiaVe : 65000,
        MoTa : 'đơn',
        TrangThai : 1
    },
    
    {
        MaLoaiVe : 've2',
        TenLoaiVe: 'HSSV-NGƯỜI CAO TUỔI',
        GiaVe : 45000,
        MoTa : 'đơn',
        TrangThai : 1
    },

    {
        MaLoaiVe : 've3',
        TenLoaiVe: 'Người Lớn',
        GiaVe : 135000,
        MoTa : 'đôi',
        TrangThai : 1
    },

    {
        MaLoaiVe : 've4',
        TenLoaiVe: 'ten1',
        GiaVe : 65000,
        MoTa : 'mota1',
        TrangThai : 1
    },

    {
        MaLoaiVe : 've5',
        TenLoaiVe: 'ten1',
        GiaVe : 45.000,
        MoTa : 'mota1',
        TrangThai : 1,
    },
]

const rowTicket = document.getElementById('row-ticket')
const ticketRSWrapper = document.getElementById('ticket-rs-wrapper')
//Hien loai ve  
function renderInfoTicket() {
    var htmlTicketBook = ticketArray.map((ticket, index) => {
      ticket.soLuong = 0;
      // console.log(index);
    return `
        <div class="ticket__item col-4 px-2" id=${ticket.MaLoaiVe}>
            <span class="ticket-type d-block fs-5" >
                ${ticket.TenLoaiVe}
            </span>
            <span class="ticket-des d-block fs-6">
                Đơn
            </span>
            <span class="ticket-price fs-6">
                ${ticket.GiaVe}đ
            </span>
            <div class="ticket-count d-flex mt-3 mb-2 justify-content-center align-items-center">
                <div class="count-btn count-minus">
                    <i class="fa-solid fa-minus"></i>
                </div>
                <div class="count-number mx-2">
                    0
                </div>
                <div class="count-btn count-plus">
                    <i class="fa-solid fa-plus"></i>
                </div>
            </div>
        </div>`
    }).join("");

    rowTicket.innerHTML = htmlTicketBook;

}

//Hien thi so luong
function renderCountTicket() {
  var htmlTicketRS =  ticketArray.map((ticket, index) => {
    if(ticket.soLuong > 0) {
      return `
      <div class="ticket-type">
        <span class="ticket-type__number">${ticket.soLuong}</span>
        <span class="ticket-type__title">${ticket.TenLoaiVe}(${ticket.MoTa})</span>
      </div>`
    }
  }).join("");

  ticketRSWrapper.innerHTML = htmlTicketRS;  
}

renderInfoTicket();
renderCountTicket();

var ticketBlock = document.getElementsByClassName('ticket-type__number')

// Tăng giảm button
const countMinusButtons = document.querySelectorAll('.count-minus');
const countPlusButtons = document.querySelectorAll('.count-plus');
const countNumbers = document.querySelectorAll('.count-number');
const ticketInfoDiv = document.querySelector('.ticket-info');

document.addEventListener("DOMContentLoaded", function() {
  
    for (let i = 0; i < countMinusButtons.length; i++) {
      countMinusButtons[i].addEventListener('click', () => {
        let count = parseInt(countNumbers[i].textContent);
        if (count > 0) {
          count--;
        }      
        countNumbers[i].textContent = count;
      });
    }
  
    for (let i = 0; i < countPlusButtons.length; i++) {
      countPlusButtons[i].addEventListener('click', () => {
        let count = parseInt(countNumbers[i].textContent);
        count++;
        countNumbers[i].textContent = count; 
      });
    }
  });

