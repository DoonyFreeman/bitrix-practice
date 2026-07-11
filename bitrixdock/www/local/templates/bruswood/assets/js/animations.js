/**
 * БрусВуд · GSAP-сцены главной.
 * Все начальные «спрятанные» состояния задаются ТОЛЬКО из JS:
 * без JS или при prefers-reduced-motion контент виден сразу.
 */
(function () {
    'use strict';

    if (!window.gsap || !window.ScrollTrigger) {
        return;
    }

    var mm = gsap.matchMedia();

    /* ---------- Сцены для всех вьюпортов (при разрешённом моушене) ---------- */
    mm.add('(prefers-reduced-motion: no-preference)', function () {

        // Hero: фото «отъезжает» + заголовок построчно из маски
        var heroTitle = document.querySelector('.hero__title');
        var heroImg = document.querySelector('.hero__media img');
        if (heroTitle && heroImg && window.SplitText) {
            document.fonts.ready.then(function () {
                var split = new SplitText(heroTitle, { type: 'lines', mask: 'lines' });
                gsap.timeline({ defaults: { ease: 'power3.out' } })
                    .from(heroImg, { scale: 1.08, duration: 1.8, ease: 'power2.out' }, 0)
                    .from(split.lines, { yPercent: 115, duration: 0.9, stagger: 0.12 }, 0.2)
                    .from(['.hero__lead', '.hero__cta'], { autoAlpha: 0, y: 26, duration: 0.7, stagger: 0.12 }, 0.6);
            });
        }

        // Hero: parallax фона (scrub — прогресс привязан к скроллу)
        if (document.querySelector('.hero__media')) {
            gsap.to('.hero__media', {
                yPercent: 12,
                ease: 'none',
                scrollTrigger: {
                    trigger: '.hero',
                    start: 'top top',
                    end: 'bottom top',
                    scrub: true
                }
            });
        }

        // Ревилы: всё с [data-reveal] всплывает при входе во вьюпорт
        var reveals = gsap.utils.toArray('[data-reveal]');
        if (reveals.length) {
            gsap.set(reveals, { autoAlpha: 0, y: 32 });
            ScrollTrigger.batch(reveals, {
                start: 'top 86%',
                once: true,
                onEnter: function (batch) {
                    gsap.to(batch, {
                        autoAlpha: 1,
                        y: 0,
                        duration: 0.8,
                        ease: 'power3.out',
                        stagger: 0.08
                    });
                }
            });
        }

        // Счётчики: числа докручиваются один раз при появлении
        gsap.utils.toArray('[data-count]').forEach(function (el) {
            var target = parseInt(el.getAttribute('data-count'), 10) || 0;
            ScrollTrigger.create({
                trigger: el,
                start: 'top 88%',
                once: true,
                onEnter: function () {
                    var counter = { val: 0 };
                    gsap.to(counter, {
                        val: target,
                        duration: 1.6,
                        ease: 'power2.out',
                        onUpdate: function () {
                            el.textContent = Math.round(counter.val);
                        }
                    });
                }
            });
        });
    });

    /* ---------- Pin-сцена «Как мы строим»: десктоп + моушен ---------- */
    mm.add('(min-width: 901px) and (prefers-reduced-motion: no-preference)', function () {
        var section = document.querySelector('.steps');
        if (!section) {
            return;
        }

        section.classList.add('steps--pinned');

        var items = gsap.utils.toArray('.steps__item');
        var imgs = gsap.utils.toArray('.steps__img');
        var bar = document.querySelector('.steps__bar');

        gsap.set(items, { autoAlpha: 0, y: 44 });
        gsap.set(items[0], { autoAlpha: 1, y: 0 });
        gsap.set(imgs, { autoAlpha: 0 });
        gsap.set(imgs[0], { autoAlpha: 1 });

        var tl = gsap.timeline({
            scrollTrigger: {
                trigger: section,
                start: 'top top',
                end: '+=' + items.length * 85 + '%', // длина «плёнки»: ~85% высоты экрана на шаг
                pin: true,
                scrub: 0.6,
                onUpdate: function (self) {
                    if (bar) {
                        gsap.set(bar, { scaleX: self.progress });
                    }
                }
            }
        });

        items.forEach(function (item, i) {
            if (i === 0) {
                return;
            }
            tl.to(items[i - 1], { autoAlpha: 0, y: -36, duration: 0.35 }, '+=0.5')
                .to(imgs[i - 1], { autoAlpha: 0, duration: 0.45 }, '<')
                .fromTo(items[i], { autoAlpha: 0, y: 44 }, { autoAlpha: 1, y: 0, duration: 0.4 }, '<0.08')
                .fromTo(imgs[i], { autoAlpha: 0, scale: 1.05 }, { autoAlpha: 1, scale: 1, duration: 0.5 }, '<');
        });
        tl.to({}, { duration: 0.5 }); // пауза в конце: последний шаг «держится»

        return function () {
            section.classList.remove('steps--pinned');
        };
    });
})();
