// Hiển thị xem thêm/thu gọn nội dung mô tả của phim
var btnShowDescription = document.getElementById("btnShowDes");
btnShowDescription.addEventListener("click", function () {
  var description = btnShowDescription.innerText;
  var showDes = document.getElementById("mv__description");

  if (description.match("Xem thêm")) {
    btnShowDescription.innerText = "Thu gọn";

    showDes.classList.remove("show__des");
  } else {
    btnShowDescription.innerText = "Xem thêm";

    showDes.classList.add("show__des");
  }
});

// Hiển thị xem thêm/thu gọn diễn viên
var btnShowActor = document.getElementById("btnShowActor");
btnShowActor.addEventListener("click", function () {
  var actor = btnShowActor.innerHTML;
  var show_actor = document.getElementById("actor_id");

  if (actor.match("Xem thêm")) {
    btnShowActor.innerHTML = "Thu gọn";

    show_actor.classList.remove("show__actor");
  } else {
    btnShowActor.innerHTML = "Xem thêm";

    show_actor.classList.add("show__actor");
  }
});

// Hiển thị xem thêm/thu gọn nội dung phim trên mobile
var btnShowDes = document.getElementById("btnShowDesMobile");
btnShowDes.addEventListener("click", function () {
  var actor = btnShowDes.innerHTML;
  var show_actor = document.getElementById("mv__description-mobile");

  if (actor.match("Xem thêm")) {
    btnShowDes.innerHTML = "Thu gọn";

    show_actor.classList.remove("show__des-mobile");
  } else {
    btnShowDes.innerHTML = "Xem thêm";

    show_actor.classList.add("show__des-mobile");
  }
});

// Hiển thị video trailer
document.addEventListener("DOMContentLoaded", function () {
  var showVideo = document.getElementById("showVideo");
  var videoModal = document.getElementById("videoModal");
  var btnClose;
  var href = showVideo.getAttribute("href");
  if (!href.includes("https://www.youtube.com/watch?v="))
    if (href.startsWith("http")) window.location.href = href;
    else {
      const url = `http://localhost:8000${href}`;
      window.location.href = url;
    }
  //https://www.youtube.com/watch?v=_UWBj1-_V5M
  var youtubeId = href.split("v=")[1];
  var youtubeEmbed = `https://www.youtube.com/embed/${youtubeId}`;
  showVideo.addEventListener("click", function (event) {
    event.preventDefault();
    videoModal.classList.toggle("display-none");
    videoModal.innerHTML = `
        <span class="videoModal-btn" id="close-mv-trailer">&times;</span>
                                <div class="videoModal-container">
                                    <iframe id="youtubeVideo" width="560" height="315" src="${youtubeEmbed}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                </div> 
        `;

    btnClose = document.getElementById("close-mv-trailer");
    var youtubeVideo = document.getElementById("youtubeVideo");
    youtubeVideo.contentWindow.postMessage(
      '{"event":"command","func":"playVideo","args":""}',
      "*"
    ); // Tắt âm thanh

    btnClose.addEventListener("click", function () {
      videoModal.classList.toggle("display-none");

      videoModal.innerHTML = ``;
      // videoModal.style.display = "none";

      // youtubeVideo.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
      // youtubeVideo.contentWindow.postMessage('{"event":"command","func":"mute","args":""}', '*'); // Tắt âm thanh
    });
  });

  document.addEventListener("click", function (event) {
    if (event.target === videoModal) {
      videoModal.classList.toggle("display-none");

      videoModal.innerHTML = ``;
    }
  });
});

// // Tăng giảm button
// document.addEventListener("DOMContentLoaded", function() {
//   const countMinusButtons = document.querySelectorAll('.count-minus');
//   const countPlusButtons = document.querySelectorAll('.count-plus');
//   const countNumbers = document.querySelectorAll('.count-number');
//   const ticketInfoDiv = document.querySelector('.ticket-info');

//   for (let i = 0; i < countMinusButtons.length; i++) {
//     countMinusButtons[i].addEventListener('click', () => {
//       let count = parseInt(countNumbers[i].textContent);
//       if (count > 0) {
//         count--;
//       }
//       countNumbers[i].textContent = count;
//       updateTicketInfo();
//     });
//   }

//   for (let i = 0; i < countPlusButtons.length; i++) {
//     countPlusButtons[i].addEventListener('click', () => {
//       let count = parseInt(countNumbers[i].textContent);
//       count++;
//       countNumbers[i].textContent = count;
//       updateTicketInfo();
//     });
//   }

//   function updateTicketInfo() {
//     const ticketTypes = document.querySelectorAll('.ticket-type__number');
//     const ticketTitles = document.querySelectorAll('.ticket-type__title');
//     const comboTitles = document.querySelectorAll('.combo-title');

//     ticketInfoDiv.innerHTML = '';

//     for (let i = 0; i < countNumbers.length; i++) {
//       const count = parseInt(countNumbers[i].textContent);
//       const ticketType = ticketTypes[i].textContent;
//       const ticketTitle = ticketTItles[i].textContent;

//       if (count > 0) {
//         const ticketElement = document.createElement('div');
//         ticketElement.classList.add('ticket-type');

//         const ticketNumberSpan = document.createElement('span');
//         ticketNumberSpan.classList.add('ticket-type__number');
//         ticketNumberSpan.textContent = count;

//         const ticketTitleSpan = document.createElement('span');
//         ticketTitleSpan.classList.add('ticket-type__title');
//         ticketTitleSpan.textContent = ticketTitle;

//         ticketElement.appendChild(ticketNumberSpan);
//         ticketElement.appendChild(ticketTitleSpan);

//         ticketInfoDiv.appendChild(ticketElement);
//       }
//     }
//   }

//   for (let i = 0; i < comboTitles.length; i++) {
//     const comboTitle = comboTitles[i].textContent;
//     const comboELement = document.createElement('span');
//     comboELement.classList.add('combo-title');
//     comboELement.textContent = comboTitle;

//     ticketInfoDiv.appendChild(comboELement);
//   }
// });

// Button time choic
var activeButton = null;
var standardTimes = ["17:00", "18:00", "19:00", "20:00", "21:00", "22:00"];
var deluxeTimes = ["17:00", "18:00", "19:00", "20:00", "21:00", "22:00"];
function toggleActive(btn) {
  if (activeButton !== null) {
    activeButton.classList.remove("box-time-active");
  }

  btn.classList.add("box-time-active");
  activeButton = btn;

  var infoCTypeElements = document.querySelectorAll(".list__info-ctype");
  infoCTypeElements.forEach(function (infoCTypeElement) {
    var times = infoCTypeElement.classList.contains("standard")
      ? standardTimes
      : deluxeTimes;
    var ulElement = infoCTypeElement.querySelector("ul");

    while (ulElement.firstChild) {
      ulElement.removeChild(ulElement.firstChild);
    }

    times.forEach(function (time) {
      var li = document.createElement("li");
      li.className = "text-center ctype__item col-2 text-warning fs-col6";
      li.textContent = time;
      ulElement.appendChild(li);
    });
  });
}

// List time
var liElements = document.querySelectorAll(".ctype__item");
liElements.forEach(function (li) {
  li.addEventListener("click", function () {
    var parentDiv = li.parentElement.parentElement;
    var isStandard =
      parentDiv.querySelector(".ctype-title").textContent.trim() === "Standard";

    var dataName = parentDiv.dataset.name;
    var dataAddress = parentDiv.dataset.address;
    var time = li.textContent.trim();

    var list_chair = ``;

    console.log(dataName + " " + dataAddress + " " + time);

    document.querySelector(".ticket-theater__name").textContent = dataName;
    document.querySelector(".ticket-theater__address").textContent =
      dataAddress;
    document.querySelector(".time-tilte").textContent = time;
    document.querySelector(".seat-table-inner").innerHTML = list_chair;

    setInterval(updateCountdown, 1000);

    if (isStandard) {
      var hiddenElements = document.querySelectorAll(
        ".container.px-0.d-none, .container.px-0.mt-5.d-none, .combo.pt-sm-5.mb-5.mt-3.d-none, .ticket.container-fluid.pt-3.pb-3.d-none"
      );

      hiddenElements.forEach(function (element) {
        element.classList.remove("d-none");
      });
    }
  });
});

// Countdown
const startingMinutes = 5;
let time = startingMinutes * 60;

const countdownEl = document.getElementById("countdown");

function updateCountdown() {
  const minutes = Math.floor(time / 60);
  let seconds = time % 60;

  countdownEl.innerHTML = `${minutes} : ${seconds}`;
  time--;
}

//Xu ly cua tinh tien
