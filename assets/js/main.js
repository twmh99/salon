// Smooth scroll untuk anchor link di landing page
document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', e => {
        const targetId = link.getAttribute('href').slice(1);
        const target = document.getElementById(targetId);
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});

// Efek shrink pada navbar ketika user scroll
const nav = document.querySelector('.glass-nav');
const toggleNavClass = () => {
    if (!nav) return;
    if (window.scrollY > 30) {
        nav.classList.add('shadow-lg');
    } else {
        nav.classList.remove('shadow-lg');
    }
};
window.addEventListener('scroll', toggleNavClass);
toggleNavClass();

// Animasi reveal sederhana menggunakan IntersectionObserver
const revealItems = document.querySelectorAll('.reveal');
if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });
    revealItems.forEach(item => observer.observe(item));
}

// Slider testimonial ringan
const testimonials = document.querySelectorAll('.testimonial-slide');
let testimonialIndex = 0;
if (testimonials.length > 1) {
    setInterval(() => {
        testimonials[testimonialIndex].classList.remove('active');
        testimonialIndex = (testimonialIndex + 1) % testimonials.length;
        testimonials[testimonialIndex].classList.add('active');
    }, 4500);
}
