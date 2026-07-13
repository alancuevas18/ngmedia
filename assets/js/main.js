(function () {
  "use strict";

  // Header shadow on scroll
  var header = document.getElementById("site-header");
  function onScroll() {
    if (!header) return;
    header.classList.toggle("is-scrolled", window.scrollY > 8);
  }
  window.addEventListener("scroll", onScroll, { passive: true });
  onScroll();

  // Mobile nav toggle
  var toggle = document.getElementById("nav-toggle");
  var nav = document.getElementById("main-nav");
  if (toggle && nav) {
    toggle.addEventListener("click", function () {
      var isOpen = nav.classList.toggle("is-open");
      document.body.classList.toggle("nav-open", isOpen);
      toggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
    });
    nav.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () {
        nav.classList.remove("is-open");
        document.body.classList.remove("nav-open");
        toggle.setAttribute("aria-expanded", "false");
      });
    });
  }

  // Fade-in on scroll
  var reveals = document.querySelectorAll(".reveal");
  if ("IntersectionObserver" in window && reveals.length) {
    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 },
    );
    reveals.forEach(function (el) {
      observer.observe(el);
    });
  } else {
    reveals.forEach(function (el) {
      el.classList.add("is-visible");
    });
  }

  // Portfolio filters
  var filterButtons = document.querySelectorAll(".filter-btn");
  var portfolioCards = document.querySelectorAll(".portfolio-card");
  filterButtons.forEach(function (btn) {
    btn.addEventListener("click", function () {
      filterButtons.forEach(function (b) {
        b.classList.remove("is-active");
      });
      btn.classList.add("is-active");
      var category = btn.getAttribute("data-filter");
      portfolioCards.forEach(function (card) {
        var match =
          category === "all" || card.getAttribute("data-category") === category;
        card.classList.toggle("is-hidden", !match);
      });
    });
  });

  // Client carousel buttons + drag interaction
  var carouselTrack = document.querySelector(".clients-track");
  var prevButton = document.querySelector(".carousel-prev");
  var nextButton = document.querySelector(".carousel-next");

  if (carouselTrack && prevButton && nextButton) {
    var isDragging = false;
    var dragStartX = 0;
    var scrollStartX = 0;
    var dragThreshold = 10;

    function getSlideScrollAmount() {
      var slide = carouselTrack.querySelector(".client-slide");
      var slideWidth = slide ? slide.offsetWidth : 200;
      var gap = parseInt(getComputedStyle(carouselTrack).gap, 10) || 20;
      return slideWidth + gap;
    }

    function scrollCarousel(direction) {
      carouselTrack.scrollBy({
        left: direction * getSlideScrollAmount(),
        behavior: "smooth",
      });
    }

    prevButton.addEventListener("click", function () {
      scrollCarousel(-1);
    });

    nextButton.addEventListener("click", function () {
      scrollCarousel(1);
    });

    carouselTrack.addEventListener("pointerdown", function (event) {
      if (event.button !== 0) return;
      isDragging = true;
      dragStartX = event.clientX;
      scrollStartX = carouselTrack.scrollLeft;
      carouselTrack.classList.add("is-dragging");
      carouselTrack.setPointerCapture(event.pointerId);
      event.preventDefault();
    });

    carouselTrack.addEventListener("pointermove", function (event) {
      if (!isDragging) return;
      event.preventDefault();
      var deltaX = event.clientX - dragStartX;
      carouselTrack.scrollLeft = scrollStartX - deltaX;
    });

    function endDrag(event) {
      if (!isDragging) return;
      isDragging = false;
      carouselTrack.classList.remove("is-dragging");
      if (event.pointerId != null) {
        carouselTrack.releasePointerCapture(event.pointerId);
      }
    }

    carouselTrack.addEventListener("pointerup", endDrag);
    carouselTrack.addEventListener("pointercancel", endDrag);
    carouselTrack.addEventListener("pointerleave", endDrag);
  }
})();
