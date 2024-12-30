let perPage = 4;
let currentPage = 1;
let start = 0;
let end = perPage;

let totalPages = Math.ceil(listMovie.length / perPage);

//____________________

var btnNexttPage = document.getElementsByClassName("page-next-btn")[0];
var btnPrevPage = document.getElementsByClassName("page-prev-btn")[0];
var pageNumber = document.getElementsByClassName("number-page")[0];
var pagingWrapper = document.getElementsByClassName("pagination-info")[0];

function addEventPage() {
  const currentPages = document.querySelectorAll(".number-page .page-link");
  console.log(currentPages);
  for (let i = 0; i < currentPages.length; i++) {
    currentPages[i].addEventListener("click", () => {
      currentPage = i + 1;
      updateStartEnd(currentPage);
      renderElement();
    });
  }
}

function renderPaging() {
  console.log("Paging");
  let html = ``;

  for (let i = 1; i <= totalPages; i++) {
    i == currentPage
      ? (html += `<li class="active page-item"><a href="javascript:void(0);" class="page-link">${i}</a></li>`)
      : (html += `<li class="page-item"><a href="javascript:void(0);" class="page-link">${i}</a></li>`);
  }

  pageNumber.innerHTML = html;
  setTimeout(addEventPage, 100);
}

renderPaging();

function updateStartEnd(currentPage) {
  start = (currentPage - 1) * perPage;
  end = currentPage * perPage;
}

var movieWrapper = document.getElementsByClassName("movies-wrapper");

function addEventHoverMovie() {
  const listMovieEles = document.getElementsByClassName(
    "app-carousel-movie-item__img"
  );

  for (let i = 0; i < listMovieEles.length; i++) {
    listMovieEles[i].addEventListener("mouseover", function () {
      var infoEle = listMovieEles[i].getElementsByClassName(
        "app-carousel-movie-item__info--hover"
      )[0];
      infoEle.style.opacity = 1;
    });
  }

  for (let i = 0; i < listMovieEles.length; i++) {
    listMovieEles[i].addEventListener("mouseout", function () {
      var infoEle = listMovieEles[i].getElementsByClassName(
        "app-carousel-movie-item__info--hover"
      )[0];
      infoEle.style.opacity = 0;
    });
  }
}

function renderElement() {
  console.log("js",listMovie);
  var html = listMovie
    .map((movie, index) => {
      if (index >= start && index < end) {
        return `
              <div class="col-xl-3 mb-4 mb-xl-0 col-md-6 col-6 app-carousel-movie-item" >
                  <div class="app-carousel-movie-item__img">
                  <div class="app-carousel-movie-item__info--hover text-white p-4 text-start fs-5" >
                    <h2 class="fs-4 fw-bold" >${movie.TenPhim}</h2>
                    <div class="mt-5" >
                      <div>
                        <i class="me-3 fa-solid fa-layer-group"></i>
                        <span>${movie.DinhDang}</span>
                      </div>

                      <div>
                        <i class="me-3 mt-2 fa-solid fa-clock"></i>
                        <span>${movie.ThoiLuong}'</span>
                      </div>

                      <div>
                      <i class="fa-solid fa-closed-captioning  me-3 mt-2"></i>
                      <span>${movie.NgonNgu}</span>
                    </div>


                    </div>
                  </div>
                      <img src="${movie.HinhAnh}" class="d-block w-100" alt="...">
                  </div>
                  <h4 class="mt-3 app-carousel-movie-item__title">${movie.TenPhim}</h4>
                  <div class="d-flex justify-content-around mt-4 align-items-center" >
                    <a  style="color: var(--color1)" href=${movie.Trailer} >
                        <i class="fa-solid fa-star"></i>
                        <span>Xem Trailer<span/>
                    </a>
                    <a style="margin-right: 4px;" class="login-item btn-login btn" href="/phim/${movie.MaPhim}" >
                         <i class="fa-solid fa-star"></i>
                        <span class="ms-2">Đặt vé</span>
                    </a>
                  </div>
              </div>
          `;
      }
    })
    .join("");

  movieWrapper[0].innerHTML = html;

  setTimeout(() => {
    var listMovies = document.getElementsByClassName("app-carousel-movie-item");

    for (let i = 0; i < listMovies.length; i++) {
      listMovies[i].classList.add("fadeOut");
    }
  }, 150);

  addEventHoverMovie();
  renderPaging();
}

renderElement();

btnPrevPage.addEventListener("click", () => {
  currentPage--;

  if (currentPage <= 1) currentPage = 1;

  updateStartEnd(currentPage);

  renderElement();
});

btnNexttPage.addEventListener("click", () => {
  currentPage++;

  if (currentPage > totalPages) currentPage = totalPages;

  updateStartEnd(currentPage);

  renderElement();
});

//xử lý hover movie item
