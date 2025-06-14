/**
 * Nouveau Template Main JavaScript
 * Cyberpunk/Synthwave Theme for PHPDTS Game
 */

class NouveauTheme {
    constructor() {
        this.init();
        this.setupEventListeners();
        this.loadThemePreferences();
    }

    init() {
        // Add loading animation
        this.showLoadingAnimation();
        
        // Initialize theme
        document.addEventListener('DOMContentLoaded', () => {
            this.hideLoadingAnimation();
            this.initializeComponents();
        });
    }

    setupEventListeners() {
        // Theme toggle
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-theme-toggle]')) {
                this.toggleTheme();
            }
        });

        // Mobile menu toggle
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-mobile-menu-toggle]')) {
                this.toggleMobileMenu();
            }
        });

        // Modal handling
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-modal-open]')) {
                const modalId = e.target.getAttribute('data-modal-open');
                this.openModal(modalId);
            }
            if (e.target.matches('[data-modal-close]')) {
                this.closeModal();
            }
        });

        // Smooth scrolling for anchor links
        document.addEventListener('click', (e) => {
            if (e.target.matches('a[href^="#"]')) {
                e.preventDefault();
                const target = document.querySelector(e.target.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });

        // Form enhancements
        this.enhanceForms();
    }

    showLoadingAnimation() {
        const loader = document.createElement('div');
        loader.id = 'nouveau-loader';
        loader.innerHTML = `
            <div class="fixed inset-0 bg-black z-50 flex items-center justify-center">
                <div class="text-center">
                    <div class="cyber-terminal mb-4">
                        <div class="cyber-text-glow">INITIALIZING NOUVEAU INTERFACE...</div>
                    </div>
                    <div class="cyber-progress w-64">
                        <div class="cyber-progress-bar" style="width: 0%" id="loading-progress"></div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(loader);

        // Simulate loading progress
        let progress = 0;
        const progressBar = document.getElementById('loading-progress');
        const interval = setInterval(() => {
            progress += Math.random() * 30;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
            }
            progressBar.style.width = progress + '%';
        }, 100);
    }

    hideLoadingAnimation() {
        const loader = document.getElementById('nouveau-loader');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => loader.remove(), 500);
        }
    }

    initializeComponents() {
        this.initializeAnimations();
        this.initializeTooltips();
        this.initializeProgressBars();
        this.initializeParticleEffect();
    }

    initializeAnimations() {
        // Add fade-in animation to elements
        const elements = document.querySelectorAll('.cyber-card, .cyber-btn, .cyber-nav-link');
        elements.forEach((el, index) => {
            el.style.animationDelay = `${index * 0.1}s`;
            el.classList.add('cyber-fade-in');
        });
    }

    initializeTooltips() {
        // Simple tooltip implementation
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        tooltipElements.forEach(el => {
            el.addEventListener('mouseenter', (e) => {
                const tooltip = document.createElement('div');
                tooltip.className = 'cyber-tooltip';
                tooltip.textContent = e.target.getAttribute('data-tooltip');
                tooltip.style.cssText = `
                    position: absolute;
                    background: var(--cyber-bg-secondary);
                    color: var(--cyber-text-primary);
                    padding: 0.5rem;
                    border: 1px solid var(--cyber-border);
                    border-radius: 4px;
                    font-size: 0.875rem;
                    z-index: 1000;
                    pointer-events: none;
                `;
                document.body.appendChild(tooltip);

                const rect = e.target.getBoundingClientRect();
                tooltip.style.left = rect.left + 'px';
                tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
            });

            el.addEventListener('mouseleave', () => {
                const tooltip = document.querySelector('.cyber-tooltip');
                if (tooltip) tooltip.remove();
            });
        });
    }

    initializeProgressBars() {
        // Animate progress bars
        const progressBars = document.querySelectorAll('.cyber-progress-bar[data-value]');
        progressBars.forEach(bar => {
            const value = bar.getAttribute('data-value');
            setTimeout(() => {
                bar.style.width = value + '%';
            }, 500);
        });
    }

    initializeParticleEffect() {
        // Add subtle particle effect to background
        const canvas = document.createElement('canvas');
        canvas.id = 'particle-canvas';
        canvas.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            opacity: 0.3;
        `;
        document.body.appendChild(canvas);

        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const particles = [];
        for (let i = 0; i < 50; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                size: Math.random() * 2 + 1
            });
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            particles.forEach(particle => {
                particle.x += particle.vx;
                particle.y += particle.vy;

                if (particle.x < 0 || particle.x > canvas.width) particle.vx *= -1;
                if (particle.y < 0 || particle.y > canvas.height) particle.vy *= -1;

                ctx.beginPath();
                ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
                ctx.fillStyle = '#00ffff';
                ctx.fill();
            });

            requestAnimationFrame(animate);
        }
        animate();

        // Resize handler
        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    }

    toggleTheme() {
        const themes = ['cyber-blue', 'cyber-purple', 'cyber-green', 'cyber-orange'];
        const currentTheme = document.body.getAttribute('data-theme') || 'cyber-blue';
        const currentIndex = themes.indexOf(currentTheme);
        const nextTheme = themes[(currentIndex + 1) % themes.length];
        
        document.body.setAttribute('data-theme', nextTheme);
        localStorage.setItem('nouveau-theme', nextTheme);
        
        this.showNotification(`Theme changed to ${nextTheme.replace('cyber-', '').toUpperCase()}`);
    }

    toggleMobileMenu() {
        const menu = document.querySelector('[data-mobile-menu]');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    closeModal() {
        const modals = document.querySelectorAll('[data-modal]');
        modals.forEach(modal => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    }

    enhanceForms() {
        // Add floating labels
        const inputs = document.querySelectorAll('.cyber-input');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', () => {
                if (!input.value) {
                    input.parentElement.classList.remove('focused');
                }
            });
        });
    }

    loadThemePreferences() {
        const savedTheme = localStorage.getItem('nouveau-theme');
        if (savedTheme) {
            document.body.setAttribute('data-theme', savedTheme);
        }
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 cyber-card p-4 z-50 cyber-fade-in`;
        notification.innerHTML = `
            <div class="flex items-center">
                <span class="cyber-text-${type === 'error' ? 'danger' : 'primary'}">${message}</span>
                <button class="ml-4 cyber-text-secondary hover:cyber-text-primary" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    // Utility methods for game-specific functionality
    updatePlayerStats(stats) {
        Object.keys(stats).forEach(stat => {
            const element = document.querySelector(`[data-stat="${stat}"]`);
            if (element) {
                element.textContent = stats[stat];
                element.classList.add('cyber-pulse');
                setTimeout(() => element.classList.remove('cyber-pulse'), 1000);
            }
        });
    }

    updateProgressBar(selector, value, max = 100) {
        const bar = document.querySelector(selector);
        if (bar) {
            const percentage = (value / max) * 100;
            bar.style.width = percentage + '%';
            bar.setAttribute('data-value', percentage);
        }
    }

    playSound(soundName) {
        // Placeholder for sound effects
        console.log(`Playing sound: ${soundName}`);
    }
}

// Initialize theme when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.nouveauTheme = new NouveauTheme();
    });
} else {
    window.nouveauTheme = new NouveauTheme();
}

// Export for use in other scripts
window.NouveauTheme = NouveauTheme;
