(function () {
    'use strict';

    // Marque la page comme "JS actif" → débloque les animations de reveal
    document.documentElement.classList.add('js');

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ═══════════════════════════════════════════════════
       HEADER — sticky solid
       ═══════════════════════════════════════════════════ */
    const header = document.getElementById('siteHeader');
    function updateHeader() {
        if (!header) return;
        header.classList.toggle('solid', window.scrollY > 20);
    }
    window.addEventListener('scroll', updateHeader, { passive: true });
    updateHeader();

    /* ═══════════════════════════════════════════════════
       MENU MOBILE
       ═══════════════════════════════════════════════════ */
    const toggle = document.getElementById('mobileToggle');
    const panel  = document.getElementById('mobilePanel');

    function closeMobile() {
        if (!toggle || !panel) return;
        panel.classList.remove('open');
        toggle.classList.remove('active');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    if (toggle && panel) {
        toggle.addEventListener('click', () => {
            const isOpen = panel.classList.toggle('open');
            toggle.classList.toggle('active', isOpen);
            toggle.setAttribute('aria-expanded', String(isOpen));
            document.body.style.overflow = isOpen ? 'hidden' : '';
        });

        panel.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', closeMobile);
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeMobile();
        });
    }

    /* ═══════════════════════════════════════════════════
       SCROLL REVEAL — robuste
       ═══════════════════════════════════════════════════ */
    const reveals = document.querySelectorAll('[data-reveal], .fade');

    function revealNow(el) {
        el.classList.add('is-revealed', 'in');
    }

    function revealAll() {
        reveals.forEach(revealNow);
    }

    if (!prefersReducedMotion && reveals.length && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    revealNow(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.05,
            rootMargin: '0px 0px -60px 0px',
        });

        // 1. Révèle immédiatement ce qui est dans le viewport initial
        reveals.forEach((el) => {
            const rect = el.getBoundingClientRect();
            const inInitialViewport = rect.top < window.innerHeight && rect.bottom > 0;

            if (inInitialViewport) {
                revealNow(el);
            } else {
                observer.observe(el);
            }
        });

        // 2. Filet de sécurité : si quelque chose traîne, on révèle tout après 4s
        setTimeout(() => {
            document.querySelectorAll('[data-reveal]:not(.is-revealed), .fade:not(.in)')
                .forEach(revealNow);
        }, 4000);

        // 3. Filet de sécurité supplémentaire : si l'utilisateur scrolle et que
        //    des éléments restent invisibles après 1s, on révèle tout
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                document.querySelectorAll('[data-reveal]:not(.is-revealed), .fade:not(.in)')
                    .forEach(revealNow);
            }, 1000);
        }, { passive: true });
    } else {
        revealAll();
    }

    /* ═══════════════════════════════════════════════════
       COMPTEURS ANIMÉS
       ═══════════════════════════════════════════════════ */
    const counters = document.querySelectorAll('[data-counter]');

    if (counters.length && !prefersReducedMotion && 'IntersectionObserver' in window) {
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;

                const el = entry.target;
                const target = parseInt(el.dataset.counter, 10);
                const duration = parseInt(el.dataset.counterDuration || '1400', 10);
                const start = performance.now();

                function easeOutQuart(t) {
                    return 1 - Math.pow(1 - t, 4);
                }

                function tick(now) {
                    const progress = Math.min((now - start) / duration, 1);
                    const value = Math.round(target * easeOutQuart(progress));
                    el.textContent = value.toLocaleString('fr-FR');
                    if (progress < 1) requestAnimationFrame(tick);
                }

                requestAnimationFrame(tick);
                counterObserver.unobserve(el);
            });
        }, { threshold: 0.4 });

        counters.forEach((el) => counterObserver.observe(el));
    } else {
        counters.forEach((el) => {
            el.textContent = parseInt(el.dataset.counter, 10).toLocaleString('fr-FR');
        });
    }

    /* ═══════════════════════════════════════════════════
       SMOOTH SCROLL
       ═══════════════════════════════════════════════════ */
    document.querySelectorAll('a[href^="#"]').forEach((link) => {
        link.addEventListener('click', (e) => {
            const id = link.getAttribute('href');
            if (id === '#' || id.length < 2) return;
            const target = document.querySelector(id);
            if (!target) return;
            e.preventDefault();
            const headerHeight = header ? header.offsetHeight : 0;
            const top = target.getBoundingClientRect().top + window.scrollY - headerHeight - 16;
            window.scrollTo({
                top,
                behavior: prefersReducedMotion ? 'auto' : 'smooth',
            });
        });
    });
})();