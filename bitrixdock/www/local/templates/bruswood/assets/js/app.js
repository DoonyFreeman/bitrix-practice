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
})();
