let perPageComming = 4;
let currentPageComming = 1;
let startComming = 0;
let endComming = perPageComming;

var btnNexttPageComming = document.getElementsByClassName(
  "page-next-btn-comming"
)[0];
var btnPrevPageComming = document.getElementsByClassName(
  "page-prev-btn-comming"
)[0];
var pageNumberComming = document.getElementsByClassName(
  "number-page-comming"
)[0];

let totalPagesComming = Math.ceil(listCommingMovies.length / perPageComming);

var commingMoviesWrapper = document.getElementsByClassName(
  "comming-movies-wrapper"
);

console.log(commingMoviesWrapper);

function addEventPageComming() {
  const currentPages = document.querySelectorAll(
    ".number-page-comming .page-link"
  );
  console.log(currentPages);
  for (let i = 0; i < currentPages.length; i++) {
    currentPages[i].addEventListener("click", () => {
      currentPageComming = i + 1;
      updateStartEndComming(currentPageComming);
      renderCommingMovies();
    });
  }
}

function renderPagingComming() {
  let html = ``;

  for (let i = 1; i <= totalPagesComming; i++) {
    i == currentPageComming
      ? (html += `<li class="active page-item"><a href="javascript:void(0);" class="page-link">${i}</a></li>`)
      : (html += `<li class="page-item"><a href="javascript:void(0);" class="page-link">${i}</a></li>`);
  }

  pageNumberComming.innerHTML = html;
  setTimeout(addEventPageComming, 100);
}

function updateStartEndComming(currentPage) {
  startComming = (currentPage - 1) * perPageComming;
  endComming = currentPageComming * perPageComming;
}

function renderCommingMovies() {
  console.log(listCommingMovies);
  var html = listCommingMovies
    .map((movie, index) => {
      if (index >= startComming && index < endComming) {
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
                  <a href="#" style="color: var(--color1)" >
                      <i class="fa-solid fa-star"></i>
                      <span>Xem Trailer</span>
                  </a>
                  <a style="margin-right: 4px;" class="login-item btn-login btn" href="/phim/${movie.MaPhim}" >
                       <i class="fa-solid fa-star"></i>
                      <span class="ms-2">Chi Tiết</span>
                  </a>
                </div>
            </div>
        `;
      }
    })
    .join("");

  commingMoviesWrapper[0].innerHTML = html;

  setTimeout(() => {
    var listMovies = document.getElementsByClassName("app-carousel-movie-item");

    for (let i = 0; i < listMovies.length; i++) {
      listMovies[i].classList.add("fadeOut");
    }
  }, 150);

  addEventHoverMovie();
  renderPagingComming();
}

renderCommingMovies();

btnPrevPageComming.addEventListener("click", () => {
  currentPageComming--;

  if (currentPageComming <= 1) currentPageComming = 1;

  updateStartEndComming(currentPageComming);

  renderCommingMovies();
});

btnNexttPageComming.addEventListener("click", () => {
  currentPageComming++;

  if (currentPageComming > totalPages) currentPageComming = totalPagesComming;

  updateStartEndComming(currentPageComming);

  renderCommingMovies();
});
