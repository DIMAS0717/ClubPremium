// ============================================
// LIGHTBOX GLOBAL (galerías del sitio)
// ============================================
document.addEventListener("DOMContentLoaded", function () {

    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const counter = document.getElementById('lightbox-counter');
    const btnPrev = document.getElementById('lbPrev');
    const btnNext = document.getElementById('lbNext');

    // Si no existe lightbox en la página, no ejecuta nada
    if (!lightbox || !lightboxImg) return;

    const selector = '.slider-img, .gallery-img, .villa-card img';
    let images = Array.from(document.querySelectorAll(selector));
    let currentIndex = 0;

    function updateLightbox() {
        const targetImg = images[currentIndex];
        lightboxImg.src = targetImg.src;

        if (counter) {
            counter.textContent = `${currentIndex + 1} / ${images.length}`;
        }
    }

    function openLightbox(index) {
        currentIndex = index;
        updateLightbox();

        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    document.body.addEventListener('click', (e) => {

        if (e.target.matches(selector)) {

            images = Array.from(document.querySelectorAll(selector));
            const index = images.indexOf(e.target);

            openLightbox(index);
        }

    });

    if (btnPrev) {
        btnPrev.onclick = (e) => {
            e.stopPropagation();
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateLightbox();
        };
    }

    if (btnNext) {
        btnNext.onclick = (e) => {
            e.stopPropagation();
            currentIndex = (currentIndex + 1) % images.length;
            updateLightbox();
        };
    }

    lightbox.onclick = (e) => {

        if (e.target === lightbox || e.target.classList.contains('lightbox-close')) {
            closeLightbox();
        }

    };

    document.addEventListener('keydown', (e) => {

        if (lightbox.classList.contains('active')) {

            if (e.key === "Escape") closeLightbox();
            if (e.key === "ArrowLeft") btnPrev.click();
            if (e.key === "ArrowRight") btnNext.click();

        }

    });

});


// ============================================
// MODAL MAPA (página alrededores)
// ============================================

document.addEventListener("DOMContentLoaded", function () {

    const modal = document.getElementById("mapModal");
    const modalImg = document.getElementById("mapModalImg");
    const img = document.getElementById("openMap");
    const closeBtn = document.querySelector(".map-close");

    if (!modal || !modalImg || !img) return;

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

});