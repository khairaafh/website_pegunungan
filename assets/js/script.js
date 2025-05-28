// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    document.querySelector(this.getAttribute("href")).scrollIntoView({
      behavior: "smooth",
    });
  });
});

// Form validation
document.addEventListener("DOMContentLoaded", function () {
  const forms = document.querySelectorAll("form");

  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      const requiredFields = form.querySelectorAll("[required]");
      let isValid = true;

      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          field.style.borderColor = "#e74c3c";
          isValid = false;
        } else {
          field.style.borderColor = "#ddd";
        }
      });

      if (!isValid) {
        e.preventDefault();
        alert("Mohon lengkapi semua field yang wajib diisi!");
      }
    });
  });
});

// Image preview for file uploads
function previewImage(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();

    reader.onload = function (e) {
      let preview = document.getElementById("image-preview");
      if (!preview) {
        preview = document.createElement("img");
        preview.id = "image-preview";
        preview.style.maxWidth = "200px";
        preview.style.marginTop = "10px";
        preview.style.borderRadius = "5px";
        input.parentNode.appendChild(preview);
      }
      preview.src = e.target.result;
    };

    reader.readAsDataURL(input.files[0]);
  }
}

// Add event listener to file inputs
document.addEventListener("DOMContentLoaded", function () {
  const fileInputs = document.querySelectorAll('input[type="file"]');
  fileInputs.forEach((input) => {
    input.addEventListener("change", function () {
      previewImage(this);
    });
  });
});

// Search functionality with debounce
let searchTimeout;
function debounceSearch(func, delay) {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(func, delay);
}

// Auto-submit search form after user stops typing
document.addEventListener("DOMContentLoaded", function () {
  let currentSlideIndex = 0;
  const slides = document.querySelectorAll(".hero-slide");
  const indicators = document.querySelectorAll(".hero-indicator");
  const totalSlides = slides.length;

  if (slides.length === 0 || indicators.length === 0) return;

  function showSlide(n) {
    slides.forEach((slide, i) => {
      slide.classList.toggle("active", i === n);
      if (indicators[i]) indicators[i].classList.toggle("active", i === n);
    });
  }

  function autoSlide() {
    currentSlideIndex = (currentSlideIndex + 1) % totalSlides;
    showSlide(currentSlideIndex);
  }

  function changeSlide(direction) {
    currentSlideIndex =
      (currentSlideIndex + direction + totalSlides) % totalSlides;
    showSlide(currentSlideIndex);
    resetInterval();
  }

  function currentSlide(n) {
    currentSlideIndex = n - 1;
    showSlide(currentSlideIndex);
    resetInterval();
  }

  function resetInterval() {
    clearInterval(slideInterval);
    slideInterval = setInterval(autoSlide, 5000);
  }

  // Expose to global scope
  window.changeSlide = changeSlide;
  window.currentSlide = currentSlide;

  let slideInterval = setInterval(autoSlide, 5000);

  document.querySelector(".hero").addEventListener("mouseenter", () => {
    clearInterval(slideInterval);
  });

  document.querySelector(".hero").addEventListener("mouseleave", () => {
    slideInterval = setInterval(autoSlide, 5000);
  });
  const prevBtn = document.querySelector(".hero-nav.prev");
  const nextBtn = document.querySelector(".hero-nav.next");

  if (prevBtn && nextBtn) {
    prevBtn.addEventListener("click", () => changeSlide(-1));
    nextBtn.addEventListener("click", () => changeSlide(1));
  }

  indicators.forEach((dot, index) => {
    dot.addEventListener("click", () => currentSlide(index + 1));
 });
  // Inisialisasi tampilan slide pertama
  showSlide(currentSlideIndex);
  const searchInput = document.querySelector('input[name="search"]');
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      debounceSearch(() => {
        this.form.submit();
      }, 500);
    });
  }
});
