document.addEventListener("DOMContentLoaded", function () {

    // ============================================
    // SLIDER DE PROPIEDAD
    // ============================================
    const sliders = document.querySelectorAll('[data-slider="property"]');

    sliders.forEach(slider => {
        const track = slider.querySelector('[data-slider-track]');
        const slides = slider.querySelectorAll('[data-slide]');
        const prevBtn = slider.querySelector('[data-prev]');
        const nextBtn = slider.querySelector('[data-next]');

        if (!track || slides.length === 0) return;

        let currentIndex = 0;

        function updateSlider() {
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                updateSlider();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                currentIndex = (currentIndex + 1) % slides.length;
                updateSlider();
            });
        }

        updateSlider();
    });


    // ============================================
    // LIGHTBOX GLOBAL
    // ============================================
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const counter = document.getElementById('lightbox-counter');
    const btnPrev = document.getElementById('lbPrev');
    const btnNext = document.getElementById('lbNext');

    let images = [];
    let currentIndex = 0;

    function updateLightbox() {
        if (!lightboxImg || images.length === 0) return;

        const targetImg = images[currentIndex];
        lightboxImg.src = targetImg.src;
        lightboxImg.alt = targetImg.alt || "Imagen ampliada";

        if (counter) {
            counter.textContent = `${currentIndex + 1} / ${images.length}`;
        }
    }

    function openLightbox(index, imgs) {
        if (!lightbox || !lightboxImg || !imgs.length) return;

        images = imgs;
        currentIndex = index;
        updateLightbox();

        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        if (!lightbox) return;
        lightbox.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Abrir imágenes del slider o galería
    document.body.addEventListener('click', function (e) {
        const clickedImg = e.target.closest('.slider-img, .gallery-img, .villa-card img');

        if (!clickedImg) return;
        if (!lightbox || !lightboxImg) return;

        const groupSelector = clickedImg.classList.contains('gallery-img')
            ? '.gallery-img'
            : clickedImg.classList.contains('slider-img')
            ? '.slider-img'
            : '.villa-card img';

        const groupImages = Array.from(document.querySelectorAll(groupSelector));
        const index = groupImages.indexOf(clickedImg);

        if (index !== -1) {
            openLightbox(index, groupImages);
        }
    });

    if (btnPrev) {
        btnPrev.addEventListener('click', function (e) {
            e.stopPropagation();
            if (images.length === 0) return;
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateLightbox();
        });
    }

    if (btnNext) {
        btnNext.addEventListener('click', function (e) {
            e.stopPropagation();
            if (images.length === 0) return;
            currentIndex = (currentIndex + 1) % images.length;
            updateLightbox();
        });
    }

    if (lightbox) {
        lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox || e.target.classList.contains('lightbox-close')) {
                closeLightbox();
            }
        });
    }

    document.addEventListener('keydown', function (e) {
        if (!lightbox || !lightbox.classList.contains('active')) return;

        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft' && btnPrev) btnPrev.click();
        if (e.key === 'ArrowRight' && btnNext) btnNext.click();
    });


    // ============================================
    // MODAL MAPA
    // ============================================
    const modal = document.getElementById("mapModal");
    const modalImg = document.getElementById("mapModalImg");
    const img = document.getElementById("openMap");
    const closeBtn = document.querySelector(".map-close");

    if (modal && modalImg && img) {
        img.onclick = function () {
            modal.style.display = "block";
            modalImg.src = this.src;
        };

        if (closeBtn) {
            closeBtn.onclick = function () {
                modal.style.display = "none";
            };
        }

        modal.onclick = function (e) {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        };
    }

});