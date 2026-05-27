document.addEventListener('DOMContentLoaded', () => {
    // Remove hash on load to prevent jumping if the user scrolled away
    if (window.location.hash) {
        setTimeout(() => {
            history.replaceState('', document.title, window.location.pathname + window.location.search);
        }, 10);
    }

    // Smooth scroll for nav links without changing URL hash
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                return;
            }
            const target = document.querySelector(targetId);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // --- Theme Toggle ---
    const themeToggleBtn = document.getElementById('theme-toggle');
    const sunIcon = document.querySelector('.sun-icon');
    const moonIcon = document.querySelector('.moon-icon');
    const htmlEl = document.documentElement;

    if (themeToggleBtn) {
        // Load saved theme
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
            htmlEl.setAttribute('data-theme', 'dark');
            updateIcons('dark');
        } else if (savedTheme === 'light') {
            htmlEl.setAttribute('data-theme', 'light');
            updateIcons('light');
        }

        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = htmlEl.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            htmlEl.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcons(newTheme);
        });

        function updateIcons(theme) {
            if (theme === 'dark') {
                sunIcon.style.display = 'none';
                moonIcon.style.display = 'block';
            } else {
                sunIcon.style.display = 'block';
                moonIcon.style.display = 'none';
            }
        }
    }

    // --- Scroll Animations (Fade-in) ---
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const fadeElements = document.querySelectorAll('.fade-in-section');
    fadeElements.forEach(el => observer.observe(el));

    // Parallax disabled to prevent layout shift with natural height image

    // --- ScrollSpy & Nav Indicator ---
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-links a');
    const navIndicator = document.querySelector('.nav-indicator');

    function updateIndicator(activeLink) {
        if (!activeLink || !navIndicator) return;
        
        const linkRect = activeLink.getBoundingClientRect();
        const navRect = activeLink.closest('.nav-menu').getBoundingClientRect();
        
        navIndicator.style.width = `${linkRect.width}px`;
        navIndicator.style.transform = `translateX(${linkRect.left - navRect.left}px)`;
        navIndicator.style.opacity = '1';
    }

    const scrollSpyOptions = {
        root: null,
        rootMargin: '-40% 0px -60% 0px',
        threshold: 0
    };

    const scrollSpyObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                let activeLink = null;
                
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${id}`) {
                        link.classList.add('active');
                        activeLink = link;
                    }
                });
                
                updateIndicator(activeLink);
            }
        });
    }, scrollSpyOptions);

    sections.forEach(section => scrollSpyObserver.observe(section));

    // Handle click immediate update
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            navLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
            updateIndicator(link);
        });
    });

    // Recalculate on resize
    window.addEventListener('resize', () => {
        const activeLink = document.querySelector('.nav-links a.active');
        if (activeLink) updateIndicator(activeLink);
        
        // Reset carousel if resized
        if (carousel) {
            carousel.style.transform = `translateX(calc(-${currentIndex * 100}% - ${currentIndex * 40}px))`;
        }
    });

    // --- Stories Carousel ---
    const carousel = document.querySelector('.stories-carousel');
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    let currentIndex = 0;
    
    if (carousel && prevBtn && nextBtn) {
        const slides = document.querySelectorAll('.carousel-slide');
        const maxIndex = slides.length - 1;

        function updateCarousel() {
            carousel.style.transform = `translateX(calc(-${currentIndex * 100}% - ${currentIndex * 40}px))`;
            
            // Optional: disable buttons at ends
            prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
            prevBtn.style.pointerEvents = currentIndex === 0 ? 'none' : 'auto';
            
            nextBtn.style.opacity = currentIndex === maxIndex ? '0.5' : '1';
            nextBtn.style.pointerEvents = currentIndex === maxIndex ? 'none' : 'auto';
        }

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            }
        });
        
        // Initialize state
        updateCarousel();

        // Touch swipe support
        let startX = 0;
        let endX = 0;
        const carouselContainer = document.querySelector('.stories-carousel-container');

        if (carouselContainer) {
            carouselContainer.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
            }, {passive: true});

            carouselContainer.addEventListener('touchend', (e) => {
                endX = e.changedTouches[0].clientX;
                handleSwipe();
            });
        }
        
        function handleSwipe() {
            let diffX = startX - endX;
            if (Math.abs(diffX) > 40) {
                if (diffX > 0 && currentIndex < maxIndex) {
                    // Swipe left
                    currentIndex++;
                    updateCarousel();
                } else if (diffX < 0 && currentIndex > 0) {
                    // Swipe right
                    currentIndex--;
                    updateCarousel();
                }
            }
        }
    }

    // --- Gallery Mobile Interaction ---
    const masonryItems = document.querySelectorAll('.masonry-item');
    masonryItems.forEach(item => {
        item.addEventListener('click', (e) => {
            // Ignore clicks if they hit a button inside
            if (e.target.closest('.masonry-btn-social')) return;

            const isActive = item.classList.contains('active');
            
            // Remove active from all
            masonryItems.forEach(i => i.classList.remove('active'));
            
            // Toggle the one we clicked
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });
});
