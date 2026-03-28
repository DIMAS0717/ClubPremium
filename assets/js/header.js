document.addEventListener('DOMContentLoaded', function () {
    const header = document.querySelector('.header-full');
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileLinks = document.querySelectorAll('.mobile-nav a');
    const themeToggle = document.getElementById('themeToggle');
    const langToggle = document.getElementById('langToggle');
    const logoLink = document.getElementById('hiddenAdminTrigger');

    /* =========================
       HEADER SCROLL
    ========================= */
    function handleScroll() {
        if (!header) return;

        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }

    window.addEventListener('scroll', handleScroll);
    handleScroll();

    /* =========================
       MENÚ MÓVIL
    ========================= */
    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function () {
            const isOpen = mobileMenu.classList.toggle('open');

            menuToggle.classList.toggle('active', isOpen);
            menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

            document.body.classList.toggle('menu-open', isOpen);
        });

        mobileLinks.forEach(link => {
            link.addEventListener('click', function () {
                mobileMenu.classList.remove('open');
                menuToggle.classList.remove('active');
                menuToggle.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('menu-open');
            });
        });

        document.addEventListener('click', function (e) {
            if (
                mobileMenu.classList.contains('open') &&
                !mobileMenu.contains(e.target) &&
                !menuToggle.contains(e.target)
            ) {
                mobileMenu.classList.remove('open');
                menuToggle.classList.remove('active');
                menuToggle.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('menu-open');
            }
        });
    }

    /* =========================
       TEMA OSCURO
    ========================= */
    function applyTheme(theme) {
        const isDark = theme === 'dark';

        document.body.classList.toggle('dark-mode', isDark);
        localStorage.setItem('siteTheme', isDark ? 'dark' : 'light');

        if (themeToggle) {
            themeToggle.textContent = isDark ? '☀️' : '🌙';
            themeToggle.setAttribute(
                'aria-label',
                isDark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'
            );
        }
    }

    if (themeToggle) {
        const savedTheme = localStorage.getItem('siteTheme') || 'light';
        applyTheme(savedTheme);

        themeToggle.addEventListener('click', function () {
            const isDark = document.body.classList.contains('dark-mode');
            applyTheme(isDark ? 'light' : 'dark');
        });
    }

    /* =========================
       IDIOMA
    ========================= */
    if (langToggle) {
        let currentLang = localStorage.getItem('siteLang') || 'es';

        function updateLangButton() {
            langToggle.textContent = currentLang === 'es' ? 'ES | EN' : 'EN | ES';
        }

        updateLangButton();

        langToggle.addEventListener('click', function () {
            currentLang = currentLang === 'es' ? 'en' : 'es';
            localStorage.setItem('siteLang', currentLang);
            updateLangButton();
        });
    }

    /* =========================
       ACCESO OCULTO ADMIN
    ========================= */
    const footerAdminTrigger = document.getElementById('hiddenAdminTriggerFooter');

if (footerAdminTrigger) {
    let clickCount = 0;
    let clickTimer;

    footerAdminTrigger.addEventListener('click', function () {
        clickCount++;

        clearTimeout(clickTimer);
        clickTimer = setTimeout(() => {
            clickCount = 0;
        }, 1800);

        if (clickCount === 5) {
            clearTimeout(clickTimer);
            window.location.href = 'admin/login.php';
        }
    });
}
});