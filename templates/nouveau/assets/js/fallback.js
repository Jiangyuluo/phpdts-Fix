/**
 * Nouveau Template - Fallback and Language Support
 * Handles missing images and language switching
 */

// Image fallback system
class ImageFallback {
    constructor() {
        this.init();
    }
    
    init() {
        // Handle all images on page load
        document.addEventListener('DOMContentLoaded', () => {
            this.setupImageFallbacks();
            this.setupLanguageSystem();
        });
    }
    
    setupImageFallbacks() {
        // Find all images and add error handlers
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            if (!img.hasAttribute('onerror')) {
                img.addEventListener('error', (e) => {
                    this.handleImageError(e.target);
                });
            }
        });
        
        // Setup mutation observer for dynamically added images
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        const images = node.querySelectorAll ? node.querySelectorAll('img') : [];
                        images.forEach(img => {
                            if (!img.hasAttribute('onerror')) {
                                img.addEventListener('error', (e) => {
                                    this.handleImageError(e.target);
                                });
                            }
                        });
                    }
                });
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    handleImageError(img) {
        const src = img.src;
        const alt = img.alt || '';
        
        // Create fallback element
        const fallback = this.createFallback(src, alt);
        
        // Replace image with fallback
        img.style.display = 'none';
        if (img.nextElementSibling && img.nextElementSibling.classList.contains('fallback-element')) {
            img.nextElementSibling.style.display = 'inline-block';
        } else {
            img.parentNode.insertBefore(fallback, img.nextSibling);
        }
    }
    
    createFallback(src, alt) {
        const fallback = document.createElement('span');
        fallback.className = 'fallback-element';
        
        // Determine fallback content based on image source
        let emoji = '🖼️'; // default
        let className = 'status-icon-fallback';
        
        if (src.includes('injured')) {
            emoji = '🩸';
            className += ' status-injured';
        } else if (src.includes('dead')) {
            emoji = '💀';
            className += ' status-dead';
        } else if (src.includes('danger')) {
            emoji = '⚠️';
            className += ' status-danger';
        } else if (src.includes('caution')) {
            emoji = '⚠️';
            className += ' status-caution';
        } else if (src.includes('fine')) {
            emoji = '✅';
            className += ' status-fine';
        } else if (src.includes('/p.gif')) {
            emoji = '🟣';
            className += ' status-poisoned';
        } else if (src.includes('/u.gif')) {
            emoji = '🔥';
            className += ' status-burned';
        } else if (src.includes('/i.gif')) {
            emoji = '🧊';
            className += ' status-frozen';
        } else if (src.includes('/e.gif')) {
            emoji = '⚡';
            className += ' status-paralyzed';
        } else if (src.includes('/w.gif')) {
            emoji = '💫';
            className += ' status-confused';
        } else if (src.includes('/m_')) {
            emoji = '👨';
            className = 'avatar-fallback male';
        } else if (src.includes('/f_')) {
            emoji = '👩';
            className = 'avatar-fallback female';
        } else if (alt.toLowerCase().includes('weapon')) {
            emoji = '⚔️';
            className = 'item-icon-fallback item-weapon';
        } else if (alt.toLowerCase().includes('armor')) {
            emoji = '🛡️';
            className = 'item-icon-fallback item-armor';
        } else if (alt.toLowerCase().includes('accessory')) {
            emoji = '💍';
            className = 'item-icon-fallback item-accessory';
        }
        
        fallback.className = className;
        fallback.textContent = emoji;
        fallback.title = alt || '图片加载失败';
        
        return fallback;
    }
    
    setupLanguageSystem() {
        // Initialize language display
        this.updateLanguageDisplay();
        
        // Setup language switcher if it exists
        const langSwitcher = document.getElementById('lang-switcher');
        if (langSwitcher) {
            langSwitcher.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
            });
        }
    }
    
    updateLanguageDisplay() {
        const currentLang = this.getCurrentLanguage();
        const currentLangElement = document.getElementById('current-lang');
        if (currentLangElement) {
            currentLangElement.textContent = currentLang === 'zh' ? '中文' : 'English';
        }
        
        // Update theme names based on language
        this.updateThemeNames(currentLang);
    }
    
    getCurrentLanguage() {
        return this.getCookie('nouveau_lang') || 'zh';
    }
    
    getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }
    
    updateThemeNames(lang) {
        const themeNames = {
            zh: {
                'cyber-blue': '赛博蓝',
                'cyber-purple': '霓虹紫',
                'cyber-green': '矩阵绿',
                'cyber-orange': '合成波橙',
                'cyber-pink': '蒸汽波粉'
            },
            en: {
                'cyber-blue': 'Cyber Blue',
                'cyber-purple': 'Neon Purple',
                'cyber-green': 'Matrix Green',
                'cyber-orange': 'Synthwave Orange',
                'cyber-pink': 'Vaporwave Pink'
            }
        };
        
        // Update footer theme display
        const footerTheme = document.getElementById('footer-theme');
        if (footerTheme && window.themeManager) {
            const currentTheme = window.themeManager.getCurrentTheme();
            const names = themeNames[lang] || themeNames.zh;
            footerTheme.textContent = names[currentTheme] || names['cyber-blue'];
        }
    }
}

// Language switching functions
window.switchLanguage = function(lang) {
    // Set cookie for language preference
    document.cookie = `nouveau_lang=${lang}; path=/; max-age=31536000`; // 1 year
    
    // Update current language display
    const currentLangElement = document.getElementById('current-lang');
    if (currentLangElement) {
        currentLangElement.textContent = lang === 'zh' ? '中文' : 'English';
    }
    
    // Show notification
    const message = lang === 'zh' ? '语言已切换为中文，刷新页面生效' : 'Language switched to English, refresh to take effect';
    if (window.nouveauTheme) {
        window.nouveauTheme.showNotification(message, 'info');
    } else {
        alert(message);
    }
    
    // Reload page to apply language changes
    setTimeout(() => {
        window.location.reload();
    }, 1500);
};

// Background pattern fallback
window.createBackgroundPattern = function() {
    // Create CSS pattern if background images fail
    const style = document.createElement('style');
    style.textContent = `
        .location-bg-fallback {
            background: linear-gradient(135deg, 
                var(--cyber-bg-primary) 0%, 
                var(--cyber-bg-secondary) 50%, 
                var(--cyber-bg-tertiary) 100%);
            position: relative;
        }
        
        .location-bg-fallback::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, var(--cyber-primary)33 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, var(--cyber-secondary)33 0%, transparent 50%);
            opacity: 0.3;
            pointer-events: none;
        }
    `;
    document.head.appendChild(style);
};

// Initialize fallback system
const imageFallback = new ImageFallback();

// Export for global use
window.ImageFallback = ImageFallback;

// Additional utility functions
window.nouveauUtils = {
    // Create emoji-based status indicator
    createStatusIndicator: function(type, size = 'normal') {
        const indicators = {
            poisoned: '🟣',
            burned: '🔥',
            frozen: '🧊',
            paralyzed: '⚡',
            confused: '💫',
            injured: '🩸',
            dead: '💀',
            danger: '⚠️',
            caution: '⚠️',
            fine: '✅',
            male: '👨',
            female: '👩',
            weapon: '⚔️',
            armor: '🛡️',
            accessory: '💍',
            consumable: '💊',
            tool: '🔧',
            special: '✨'
        };
        
        const span = document.createElement('span');
        span.className = `status-indicator status-${type}`;
        span.textContent = indicators[type] || '❓';
        
        if (size === 'large') {
            span.style.fontSize = '24px';
        } else if (size === 'small') {
            span.style.fontSize = '12px';
        }
        
        return span;
    },
    
    // Create avatar fallback
    createAvatarFallback: function(gender = 'unknown', size = 'normal') {
        const div = document.createElement('div');
        div.className = `avatar-fallback ${gender}`;
        
        if (size === 'large') {
            div.style.width = '128px';
            div.style.height = '80px';
            div.style.fontSize = '48px';
        } else if (size === 'small') {
            div.style.width = '32px';
            div.style.height = '20px';
            div.style.fontSize = '16px';
        }
        
        return div;
    },
    
    // Get localized text
    getText: function(key, fallback = '') {
        const lang = imageFallback.getCurrentLanguage();
        const texts = {
            zh: {
                loading: '加载中...',
                error: '错误',
                success: '成功',
                warning: '警告',
                info: '信息',
                image_failed: '图片加载失败',
                language_switched: '语言已切换',
                refresh_required: '刷新页面生效'
            },
            en: {
                loading: 'Loading...',
                error: 'Error',
                success: 'Success',
                warning: 'Warning',
                info: 'Info',
                image_failed: 'Image failed to load',
                language_switched: 'Language switched',
                refresh_required: 'Refresh to take effect'
            }
        };
        
        return texts[lang] && texts[lang][key] ? texts[lang][key] : fallback;
    }
};

console.log('Nouveau Template: Fallback system initialized');
