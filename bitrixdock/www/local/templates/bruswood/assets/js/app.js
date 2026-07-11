/**
 * БрусВуд · инициализация фронтенда.
 * Анимационные сцены (GSAP) живут в animations.js (спринт 3);
 * здесь — каркас: Lenis, бургер, состояние шапки.
 */
(function () {
    'use strict';

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // GSAP: регистрируем плагины один раз, если библиотека подключена
    if (window.gsap && window.ScrollTrigger) {
        gsap.registerPlugin(ScrollTrigger, SplitText);
    }

    // Плавный инерционный скролл (отключается при reduced-motion)
    if (!reduceMotion && window.Lenis) {
        window.__lenis = new Lenis({ autoRaf: true });
        if (window.ScrollTrigger) {
            window.__lenis.on('scroll', ScrollTrigger.update);
        }
    }

    // Тень шапки: сентинел + IntersectionObserver вместо scroll-листенера
    var header = document.getElementById('header');
    var sentinel = document.getElementById('top-sentinel');
    if (header && sentinel && 'IntersectionObserver' in window) {
        new IntersectionObserver(function (entries) {
            header.classList.toggle('is-scrolled', !entries[0].isIntersecting);
        }).observe(sentinel);
    }

    // Мобильное меню
    var burger = document.getElementById('burger');
    var nav = document.getElementById('nav');
    if (burger && nav) {
        burger.addEventListener('click', function () {
            var open = document.body.classList.toggle('nav-open');
            burger.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        nav.addEventListener('click', function (e) {
            if (e.target.closest('a')) {
                document.body.classList.remove('nav-open');
                burger.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Каталог: мобильная фильтр-панель
    var filterToggle = document.getElementById('filter-toggle');
    var filterClose = document.getElementById('filter-close');
    var filterOverlay = document.getElementById('filter-overlay');
    if (filterToggle) {
        var closeFilter = function () { document.body.classList.remove('filter-open'); };
        filterToggle.addEventListener('click', function () {
            document.body.classList.toggle('filter-open');
        });
        if (filterClose) filterClose.addEventListener('click', closeFilter);
        if (filterOverlay) filterOverlay.addEventListener('click', closeFilter);
    }

    // Каталог: сортировка — меняем параметр sort, сохраняя фильтр в URL
    var sortSelect = document.getElementById('catalog-sort');
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            var url = new URL(window.location.href);
            url.searchParams.set('sort', sortSelect.value);
            window.location.assign(url.toString());
        });
    }

    // Детальная: лайтбокс галереи (PhotoSwipe)
    if (window.PhotoSwipeLightbox && window.PhotoSwipe && document.getElementById('house-gallery')) {
        var lightbox = new PhotoSwipeLightbox({
            gallery: '#house-gallery',
            children: 'a',
            pswpModule: window.PhotoSwipe,
            showHideAnimationType: 'zoom',
            bgOpacity: 0.92
        });
        lightbox.init();
    }

    // Детальная: переключение планировок по этажам
    document.querySelectorAll('[data-plans]').forEach(function (plansEl) {
        plansEl.addEventListener('click', function (e) {
            var tab = e.target.closest('[data-plan-tab]');
            if (!tab) return;
            var idx = tab.getAttribute('data-plan-tab');
            plansEl.querySelectorAll('[data-plan-tab]').forEach(function (t) {
                var active = t === tab;
                t.classList.toggle('is-active', active);
                t.setAttribute('aria-selected', active ? 'true' : 'false');
            });
            plansEl.querySelectorAll('[data-plan-pane]').forEach(function (p) {
                p.classList.toggle('is-active', p.getAttribute('data-plan-pane') === idx);
            });
        });
    });
})();
