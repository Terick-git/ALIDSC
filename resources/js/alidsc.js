(function () {
    'use strict';

    // Header sticky
    const header = document.getElementById('siteHeader');
    function updateHeader() {
        if (!header) return;
        header.classList.toggle('solid', window.scrollY > 80);
    }
    window.addEventListener('scroll', updateHeader, { passive: true });
    updateHeader();

    // Menu mobile
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

    // Reveal on scroll
    const reveals = document.querySelectorAll('.fade');
    if (reveals.length && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -60px 0px' });
        reveals.forEach((el) => observer.observe(el));
    } else {
        reveals.forEach((el) => el.classList.add('in'));
    }
})();