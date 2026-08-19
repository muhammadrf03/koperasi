import gsap from 'gsap';

const prefersReduced =
    typeof window !== 'undefined' && window.matchMedia
        ? window.matchMedia('(prefers-reduced-motion: reduce)').matches
        : false;

document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll<HTMLElement>('[data-stat-card]');
    if (!cards.length || prefersReduced) return;

    gsap.fromTo(
        cards,
        { y: 28, opacity: 0, scale: 0.96 },
        { y: 0, opacity: 1, scale: 1, duration: 0.9, ease: 'power3.out', stagger: 0.1, delay: 0.1 }
    );

    cards.forEach((card) => {
        const bar = card.querySelector<HTMLElement>('[data-stat-bar]');
        const num = card.querySelector<HTMLElement>('[data-stat-num]');
        const label = card.querySelector<HTMLElement>('[data-stat-label]');
        const icon = card.querySelector<HTMLElement>('[data-stat-icon]');

        const rotXTo = gsap.quickTo(card, 'rotationX', { duration: 0.5, ease: 'power3.out', overwrite: 'auto' });
        const rotYTo = gsap.quickTo(card, 'rotationY', { duration: 0.5, ease: 'power3.out', overwrite: 'auto' });

        const enter = () => {
            gsap.to(card, { scale: 1.04, y: -4, duration: 0.5, ease: 'power3.out', overwrite: 'auto' });
            gsap.to(icon, { scale: 1.12, duration: 0.5, ease: 'power3.out', overwrite: 'auto' });
            if (bar) gsap.to(bar, { scaleX: 1.9, transformOrigin: 'left center', duration: 0.5, ease: 'power3.out', overwrite: 'auto' });
            if (num) gsap.to(num, { y: -3, duration: 0.5, ease: 'power3.out', overwrite: 'auto' });
            if (label) gsap.to(label, { x: 3, duration: 0.5, ease: 'power3.out', overwrite: 'auto' });
        };

        const leave = () => {
            gsap.to(card, { scale: 1, y: 0, rotationX: 0, rotationY: 0, duration: 0.5, ease: 'power3.out', overwrite: 'auto' });
            gsap.to(icon, { scale: 1, duration: 0.5, ease: 'power3.out', overwrite: 'auto' });
            if (bar) gsap.to(bar, { scaleX: 1, duration: 0.4, ease: 'power3.out', overwrite: 'auto' });
            if (num) gsap.to(num, { y: 0, duration: 0.4, ease: 'power3.out', overwrite: 'auto' });
            if (label) gsap.to(label, { x: 0, duration: 0.4, ease: 'power3.out', overwrite: 'auto' });
        };

        card.addEventListener('mouseenter', enter);
        card.addEventListener('mouseleave', leave);

        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const px = (e.clientX - rect.left) / rect.width - 0.5;
            const py = (e.clientY - rect.top) / rect.height - 0.5;
            rotYTo(px * 10);
            rotXTo(py * -10);
        });
    });
});
