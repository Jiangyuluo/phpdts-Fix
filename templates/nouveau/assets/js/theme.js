/**
 * Theme Management for Nouveau Template
 * Handles multiple color schemes and user preferences
 */

class ThemeManager {
    constructor() {
        this.themes = {
            'cyber-blue': {
                name: 'Cyber Blue',
                primary: '#00ffff',
                secondary: '#0066ff',
                accent: '#66ccff',
                background: '#0a0a0a',
                surface: '#1a1a2e'
            },
            'cyber-purple': {
                name: 'Neon Purple',
                primary: '#ff00ff',
                secondary: '#9900ff',
                accent: '#cc66ff',
                background: '#0a0a0a',
                surface: '#2e1a2e'
            },
            'cyber-green': {
                name: 'Matrix Green',
                primary: '#00ff66',
                secondary: '#00cc44',
                accent: '#66ff99',
                background: '#0a0a0a',
                surface: '#1a2e1a'
            },
            'cyber-orange': {
                name: 'Synthwave Orange',
                primary: '#ff6600',
                secondary: '#ff9900',
                accent: '#ffcc66',
                background: '#0a0a0a',
                surface: '#2e1a0a'
            },
            'cyber-pink': {
                name: 'Vaporwave Pink',
                primary: '#ff0066',
                secondary: '#ff3399',
                accent: '#ff99cc',
                background: '#0a0a0a',
                surface: '#2e0a1a'
            }
        };
        
        this.currentTheme = 'cyber-blue';
        this.init();
    }

    init() {
        this.loadSavedTheme();
        this.createThemeSelector();
        this.applyTheme(this.currentTheme);
    }

    loadSavedTheme() {
        const saved = localStorage.getItem('nouveau-theme');
        if (saved && this.themes[saved]) {
            this.currentTheme = saved;
        }
    }

    createThemeSelector() {
        const selector = document.createElement('div');
        selector.id = 'theme-selector';
        selector.className = 'fixed bottom-4 right-4 z-50';
        selector.innerHTML = `
            <div class="cyber-card p-2">
                <button id="theme-toggle" class="cyber-btn cyber-btn-primary" data-tooltip="Change Theme">
                    🎨
                </button>
                <div id="theme-menu" class="hidden absolute bottom-full right-0 mb-2 cyber-card p-2 min-w-48">
                    <div class="text-sm cyber-text-primary mb-2">Choose Theme:</div>
                    ${Object.keys(this.themes).map(themeKey => `
                        <button class="theme-option w-full text-left p-2 hover:bg-opacity-20 hover:bg-white rounded" 
                                data-theme="${themeKey}">
                            <span class="inline-block w-4 h-4 rounded mr-2" 
                                  style="background: ${this.themes[themeKey].primary}"></span>
                            ${this.themes[themeKey].name}
                        </button>
                    `).join('')}
                </div>
            </div>
        `;

        document.body.appendChild(selector);

        // Event listeners
        document.getElementById('theme-toggle').addEventListener('click', () => {
            document.getElementById('theme-menu').classList.toggle('hidden');
        });

        document.querySelectorAll('.theme-option').forEach(option => {
            option.addEventListener('click', (e) => {
                const theme = e.currentTarget.getAttribute('data-theme');
                this.setTheme(theme);
                document.getElementById('theme-menu').classList.add('hidden');
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!selector.contains(e.target)) {
                document.getElementById('theme-menu').classList.add('hidden');
            }
        });
    }

    setTheme(themeName) {
        if (!this.themes[themeName]) return;
        
        this.currentTheme = themeName;
        this.applyTheme(themeName);
        this.saveTheme(themeName);
        
        // Notify other components
        document.dispatchEvent(new CustomEvent('themeChanged', {
            detail: { theme: themeName, colors: this.themes[themeName] }
        }));
    }

    applyTheme(themeName) {
        const theme = this.themes[themeName];
        const root = document.documentElement;

        // Update CSS custom properties
        root.style.setProperty('--cyber-primary', theme.primary);
        root.style.setProperty('--cyber-secondary', theme.secondary);
        root.style.setProperty('--cyber-accent', theme.accent);
        root.style.setProperty('--cyber-bg-primary', theme.background);
        root.style.setProperty('--cyber-bg-secondary', theme.surface);
        
        // Update body data attribute
        document.body.setAttribute('data-theme', themeName);
        
        // Update theme-dependent elements
        this.updateThemeElements(theme);
    }

    updateThemeElements(theme) {
        // Update progress bars
        const progressBars = document.querySelectorAll('.cyber-progress-bar');
        progressBars.forEach(bar => {
            bar.style.background = `linear-gradient(90deg, ${theme.primary}, ${theme.secondary})`;
        });

        // Update glow effects
        const glowElements = document.querySelectorAll('.cyber-glow');
        glowElements.forEach(el => {
            el.style.boxShadow = `0 0 20px ${theme.primary}33`;
        });

        // Update particle canvas if it exists
        const canvas = document.getElementById('particle-canvas');
        if (canvas && window.particleSystem) {
            window.particleSystem.updateColor(theme.primary);
        }
    }

    saveTheme(themeName) {
        localStorage.setItem('nouveau-theme', themeName);
    }

    getCurrentTheme() {
        return this.currentTheme;
    }

    getThemeColors(themeName = null) {
        const theme = themeName || this.currentTheme;
        return this.themes[theme];
    }

    // Preset theme combinations for different game states
    setGameStateTheme(state) {
        const stateThemes = {
            'battle': 'cyber-orange',
            'danger': 'cyber-pink',
            'safe': 'cyber-green',
            'normal': 'cyber-blue',
            'special': 'cyber-purple'
        };

        if (stateThemes[state]) {
            this.setTheme(stateThemes[state]);
        }
    }

    // Animation for theme transitions
    animateThemeChange() {
        document.body.style.transition = 'all 0.5s ease';
        setTimeout(() => {
            document.body.style.transition = '';
        }, 500);
    }

    // Auto theme based on time of day
    setAutoTheme() {
        const hour = new Date().getHours();
        let autoTheme;

        if (hour >= 6 && hour < 12) {
            autoTheme = 'cyber-green'; // Morning
        } else if (hour >= 12 && hour < 18) {
            autoTheme = 'cyber-blue'; // Afternoon
        } else if (hour >= 18 && hour < 22) {
            autoTheme = 'cyber-orange'; // Evening
        } else {
            autoTheme = 'cyber-purple'; // Night
        }

        this.setTheme(autoTheme);
    }

    // Export theme as CSS
    exportThemeCSS(themeName = null) {
        const theme = this.getThemeColors(themeName);
        return `
            :root {
                --cyber-primary: ${theme.primary};
                --cyber-secondary: ${theme.secondary};
                --cyber-accent: ${theme.accent};
                --cyber-bg-primary: ${theme.background};
                --cyber-bg-secondary: ${theme.surface};
            }
        `;
    }
}

// Initialize theme manager
window.themeManager = new ThemeManager();

// Listen for theme change events
document.addEventListener('themeChanged', (e) => {
    console.log('Theme changed to:', e.detail.theme);
    
    // Update any theme-dependent components
    if (window.nouveauTheme) {
        window.nouveauTheme.showNotification(
            `Theme changed to ${e.detail.colors.name}`,
            'info'
        );
    }
});

// Keyboard shortcut for theme cycling
document.addEventListener('keydown', (e) => {
    if (e.ctrlKey && e.shiftKey && e.key === 'T') {
        e.preventDefault();
        const themes = Object.keys(window.themeManager.themes);
        const current = window.themeManager.getCurrentTheme();
        const currentIndex = themes.indexOf(current);
        const nextTheme = themes[(currentIndex + 1) % themes.length];
        window.themeManager.setTheme(nextTheme);
    }
});

// Export for global use
window.ThemeManager = ThemeManager;
