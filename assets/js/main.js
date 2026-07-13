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

  // Portfolio lightbox modal - initialize after DOM ready for reliability
  function initPortfolioLightbox() {
    var lightbox = document.getElementById("portfolio-lightbox");
    if (!lightbox) return;
    var lbBackdrop = lightbox.querySelector(".lightbox-backdrop");
    var lbContent = lightbox.querySelector(".lightbox-content");
    var lbImage = lightbox.querySelector(".lightbox-media img");
    var lbTitle = lightbox.querySelector("#lightbox-title");
    var lbDesc = lightbox.querySelector(".lightbox-description");
    var lbClose = lightbox.querySelector(".lightbox-close");
    var lbPrev = lightbox.querySelector(".lightbox-prev");
    var lbNext = lightbox.querySelector(".lightbox-next");

    var portfolioList = Array.prototype.slice.call(
      document.querySelectorAll(".portfolio-card"),
    );
    var visibleList = function () {
      return portfolioList.filter(function (c) {
        return !c.classList.contains("is-hidden");
      });
    };
    var currentIndex = -1;
    var lastFocused = null;

    function setLightboxImageSrc(src, alt) {
      if (!lbImage) return;
      if (!src) {
        lbImage.src = "";
        lbImage.alt = "";
        lbImage.dataset.currentSrc = "";
        return;
      }
      // avoid redundant changes
      if (lbImage.dataset.currentSrc === src) return;
      var tmp = new Image();
      tmp.onload = function () {
        // fade out current image
        lbImage.classList.add("fading");
        setTimeout(function () {
          lbImage.src = src;
          lbImage.alt = alt || "";
          lbImage.dataset.currentSrc = src;
          // fade in
          requestAnimationFrame(function () {
            lbImage.classList.remove("fading");
          });
        }, 80);
      };
      tmp.onerror = function () {};
      tmp.src = src;
    }

    function openLightbox(card) {
      if (!lightbox) return;
      lastFocused = document.activeElement;
      var visibles = visibleList();
      currentIndex = visibles.indexOf(card);
      if (currentIndex === -1) return;
      var img = card.querySelector(".portfolio-media img");
      var title = card.querySelector("h4");
      var desc = card.querySelector("p");
      lbTitle.textContent = title ? title.textContent : "";
      lbDesc.textContent = desc ? desc.textContent : "";
      var src = img ? img.src : "";
      var alt = img ? img.alt || "" : "";
      setLightboxImageSrc(src, alt);
      lightbox.setAttribute("aria-hidden", "false");
      if (lbClose) lbClose.focus();
      document.body.style.overflow = "hidden";
    }

    function closeLightbox() {
      if (!lightbox) return;
      lightbox.setAttribute("aria-hidden", "true");
      if (lbImage) lbImage.src = "";
      document.body.style.overflow = "";
      if (lastFocused) lastFocused.focus();
    }

    function showPrev() {
      var visibles = visibleList();
      if (visibles.length === 0) return;
      currentIndex = (currentIndex - 1 + visibles.length) % visibles.length;
      var card = visibles[currentIndex];
      var title = card.querySelector("h4");
      var desc = card.querySelector("p");
      lbTitle.textContent = title ? title.textContent : "";
      lbDesc.textContent = desc ? desc.textContent : "";
      var img = card.querySelector(".portfolio-media img");
      setLightboxImageSrc(img ? img.src : "", img ? img.alt || "" : "");
    }

    function showNext() {
      var visibles = visibleList();
      if (visibles.length === 0) return;
      currentIndex = (currentIndex + 1) % visibles.length;
      var card = visibles[currentIndex];
      var title = card.querySelector("h4");
      var desc = card.querySelector("p");
      lbTitle.textContent = title ? title.textContent : "";
      lbDesc.textContent = desc ? desc.textContent : "";
      var img = card.querySelector(".portfolio-media img");
      setLightboxImageSrc(img ? img.src : "", img ? img.alt || "" : "");
    }

    portfolioList.forEach(function (card) {
      card.addEventListener("click", function (e) {
        if (card.classList.contains("is-hidden")) return;
        openLightbox(card);
      });
      card.addEventListener("keydown", function (e) {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          openLightbox(card);
        }
      });
      card.setAttribute("tabindex", "0");
      card.setAttribute("role", "button");
    });

    if (lbClose) lbClose.addEventListener("click", closeLightbox);
    if (lbBackdrop) lbBackdrop.addEventListener("click", closeLightbox);
    if (lbPrev)
      lbPrev.addEventListener("click", function () {
        showPrev();
      });
    if (lbNext)
      lbNext.addEventListener("click", function () {
        showNext();
      });

    document.addEventListener("keydown", function (e) {
      if (!lightbox || lightbox.getAttribute("aria-hidden") === "true") return;
      if (e.key === "Escape") closeLightbox();
      if (e.key === "ArrowLeft") showPrev();
      if (e.key === "ArrowRight") showNext();
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initPortfolioLightbox);
  } else {
    initPortfolioLightbox();
  }

  // Client carousel buttons + drag interaction
  var carouselTrack = document.querySelector(".clients-track");
  var prevButton = document.querySelector(".carousel-prev");
  var nextButton = document.querySelector(".carousel-next");

  if (carouselTrack && prevButton && nextButton) {
    var isDragging = false;
    var dragStartX = 0;
    var dragStartTime = 0;
    var scrollStartX = 0;
    var lastVelocity = 0;
    var animationFrameId = null;
    var isMouseOver = false;
    var SCROLL_SPEED = 40; // pixels per frame (~60fps = 2400px/sec)

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
      resetAutoplay();
    }

    // Continuous smooth autoscroll loop
    function autoScroll() {
      if (!isDragging && !isMouseOver) {
        var scrollWidth = carouselTrack.scrollWidth;
        var clientWidth = carouselTrack.clientWidth;
        var currentScroll = carouselTrack.scrollLeft;
        
        // Scroll forward smoothly
        carouselTrack.scrollLeft += SCROLL_SPEED;
        
        // Loop back to start when reaching end (seamless loop)
        if (carouselTrack.scrollLeft + clientWidth >= scrollWidth - 5) {
          carouselTrack.scrollLeft = 0;
        }
      }
      animationFrameId = requestAnimationFrame(autoScroll);
    }

    function startAutoplay() {
      if (animationFrameId) cancelAnimationFrame(animationFrameId);
      animationFrameId = requestAnimationFrame(autoScroll);
    }

    function stopAutoplay() {
      if (animationFrameId) cancelAnimationFrame(animationFrameId);
    }

    function resetAutoplay() {
      startAutoplay();
    }

    carouselTrack.addEventListener("mouseenter", function () {
      isMouseOver = true;
      stopAutoplay();
    });
    carouselTrack.addEventListener("mouseleave", function () {
      isMouseOver = false;
      startAutoplay();
    });

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
      dragStartTime = Date.now();
      scrollStartX = carouselTrack.scrollLeft;
      lastVelocity = 0;
      carouselTrack.classList.add("is-dragging");
      carouselTrack.setPointerCapture(event.pointerId);
      event.preventDefault();
    });

    carouselTrack.addEventListener("pointermove", function (event) {
      if (!isDragging) return;
      event.preventDefault();
      var deltaX = event.clientX - dragStartX;
      carouselTrack.scrollLeft = scrollStartX - deltaX;
      // calculate velocity for momentum
      var now = Date.now();
      var elapsed = Math.max(now - dragStartTime, 1);
      lastVelocity = deltaX / elapsed;
    });

    function endDrag(event) {
      if (!isDragging) return;
      isDragging = false;
      carouselTrack.classList.remove("is-dragging");
      if (event.pointerId != null) {
        carouselTrack.releasePointerCapture(event.pointerId);
      }
      // apply momentum
      if (Math.abs(lastVelocity) > 0.2) {
        var momentum = lastVelocity * 800; // pixels to scroll
        carouselTrack.scrollBy({
          left: -momentum,
          behavior: "smooth",
        });
      }
      resetAutoplay();
    }

    carouselTrack.addEventListener("pointerup", endDrag);
    carouselTrack.addEventListener("pointercancel", endDrag);
    carouselTrack.addEventListener("pointerleave", endDrag);

    // Start autoplay on init
    startAutoplay();
  }
})();
