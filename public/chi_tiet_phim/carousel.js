var multipleItemCarousel = document.querySelector("#carouselFood");

if (window.matchMedia("(min-width: 576px)").matches) {
  var carsouel = new bootstrap.Carousel(multipleItemCarousel, {
    interval: false,
  });
  var carouseWidth = $(".carousel-inner")[0].scrollWidth;
  var cardWidth = $(".carousel-item").width() + 80;

  var scrollPosition = 0;

  $(".carousel-control-next").on("click", function () {
    if (scrollPosition < carouseWidth - cardWidth * 4) {
      console.log(cardWidth);
      console.log("next");
      scrollPosition = scrollPosition + cardWidth;
      $(".carousel-inner").animate({ scrollLeft: scrollPosition }, 600);
    }
  });

  $(".carousel-control-prev").on("click", function () {
    if (scrollPosition > 0) {
      console.log("prev");
      scrollPosition = scrollPosition - cardWidth;
      $(".carousel-inner").animate({ scrollLeft: scrollPosition }, 600);
    }
  });
} else {
  $(multipleItemCarousel).addClass("slide");

  document.querySelectorAll(".carousel-item")[0].classList.add("active");
}
