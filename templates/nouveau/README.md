# Nouveau Template for PHPDTS

A modern, cyberpunk-themed template for the PHPDTS game engine featuring responsive design, multiple color themes, and enhanced user experience.

## 🎨 Features

### Visual Design
- **Cyberpunk/Synthwave Aesthetic**: Neon colors, glow effects, and futuristic styling
- **5 Color Themes**: Cyber Blue, Neon Purple, Matrix Green, Synthwave Orange, Vaporwave Pink
- **Responsive Design**: Optimized for desktop, tablet, and mobile devices
- **Modern Animations**: Smooth transitions, particle effects, and interactive elements

### User Experience
- **Enhanced Navigation**: Sticky header with mobile-friendly menu
- **Real-time Updates**: Auto-refresh functionality and live data updates
- **Keyboard Shortcuts**: Quick access to common actions
- **Accessibility**: Screen reader support and keyboard navigation

### Game Integration
- **Full Compatibility**: Works with all existing game features
- **Enhanced Chat**: Improved chat interface with emoji support
- **Advanced Panels**: Sliding panel with detailed game information
- **Combat Interface**: Specialized battle mode with tactical information

## 🚀 Installation

The template is already installed in the `templates/nouveau/` directory. To activate it:

1. Log into your game account
2. Go to User Profile (user_profile.php)
3. Scroll to the "界面模板" (Interface Template) section
4. Select "NOUVEAU（测试中）"
5. Save your settings
6. Refresh the page to see the new interface

## 🎮 Usage

### Theme Switching
- **Manual**: Click the theme button in the footer or use the floating theme selector
- **Keyboard**: Press `Ctrl+Shift+T` to cycle through themes
- **Auto**: Themes can automatically change based on time of day

### Keyboard Shortcuts

#### General Navigation
- `Ctrl+Shift+T` - Cycle color themes
- `F11` - Toggle fullscreen mode
- `Ctrl+R` - Refresh page

#### Game Commands (when in game)
- `M` - Move
- `S` - Search
- `R` - Rest
- `A` - Attack
- `T` - Team actions
- `H` - Open help
- `P` - Toggle sliding panel

#### Battle Mode
- `A` - Attack
- `D` - Defend
- `I` - Use item
- `E` - Escape
- `S` - Use skills

### Mobile Features
- **Touch-friendly**: Large buttons and touch targets
- **Swipe Navigation**: Swipe gestures for mobile menu
- **Responsive Layout**: Adapts to screen orientation changes
- **Mobile Chat**: Optimized chat interface for mobile devices

## 🎨 Customization

### Color Themes
The template includes 5 built-in themes:

1. **Cyber Blue** (Default)
   - Primary: #00ffff (Cyan)
   - Secondary: #0066ff (Blue)
   - Best for: General gameplay

2. **Neon Purple**
   - Primary: #ff00ff (Magenta)
   - Secondary: #9900ff (Purple)
   - Best for: Night gaming sessions

3. **Matrix Green**
   - Primary: #00ff66 (Green)
   - Secondary: #00cc44 (Dark Green)
   - Best for: Hacker/tech aesthetic

4. **Synthwave Orange**
   - Primary: #ff6600 (Orange)
   - Secondary: #ff9900 (Light Orange)
   - Best for: Retro 80s vibe

5. **Vaporwave Pink**
   - Primary: #ff0066 (Pink)
   - Secondary: #ff3399 (Light Pink)
   - Best for: Aesthetic gaming

### Advanced Customization
For developers who want to modify the template:

1. **CSS Variables**: Edit `assets/css/cyberpunk.css` to change colors
2. **JavaScript**: Modify `assets/js/main.js` for behavior changes
3. **Templates**: Edit `.htm` files for layout modifications

## 📱 Responsive Breakpoints

- **Mobile**: < 640px
- **Tablet**: 640px - 1024px
- **Desktop**: > 1024px

The interface automatically adapts to different screen sizes with:
- Collapsible navigation menu
- Responsive grid layouts
- Touch-optimized controls
- Scalable text and images

## 🔧 Technical Details

### Browser Support
- **Modern Browsers**: Chrome 80+, Firefox 75+, Safari 13+, Edge 80+
- **Mobile Browsers**: iOS Safari 13+, Chrome Mobile 80+
- **Fallback**: Graceful degradation for older browsers

### Performance
- **Optimized Assets**: Minified CSS and JavaScript
- **CDN Resources**: External resources loaded from CDN with local fallbacks
- **Lazy Loading**: Non-critical resources loaded on demand
- **Efficient Animations**: CSS-based animations for smooth performance

### Dependencies
- **Tailwind CSS**: Utility-first CSS framework (loaded via CDN)
- **jQuery**: Required by the game engine (already included)
- **Custom Fonts**: Orbitron font family for cyberpunk aesthetic

## 🐛 Troubleshooting

### Common Issues

#### Template Not Loading
- Ensure you've selected "NOUVEAU（测试中）" in user profile
- Clear browser cache and refresh
- Check browser console for JavaScript errors

#### Mobile Display Issues
- Ensure viewport meta tag is present
- Check for CSS conflicts with browser extensions
- Try refreshing the page or restarting the browser

#### Theme Not Switching
- Check if JavaScript is enabled
- Verify localStorage is available
- Try the keyboard shortcut `Ctrl+Shift+T`

#### Performance Issues
- Disable browser extensions that might interfere
- Check internet connection for CDN resources
- Consider switching to a simpler theme

### Fallback Mode
If the template fails to load properly, it will automatically fall back to the default template. You can also manually switch back by:

1. Going to user profile
2. Selecting "经典界面" (Classic Interface)
3. Saving settings

## 🔄 Updates and Maintenance

### Version History
- **v2.0** (Current): Initial release with full feature set
- **v2.1** (Planned): Performance optimizations and bug fixes
- **v2.2** (Planned): Additional themes and customization options

### Reporting Issues
If you encounter bugs or have suggestions:

1. Check the troubleshooting section first
2. Report issues on the game forum
3. Include browser version and device information
4. Describe steps to reproduce the issue

## 🎯 Future Plans

### Upcoming Features
- **Sound Effects**: Audio feedback for actions
- **More Themes**: Additional color schemes
- **Customization Panel**: User-configurable interface options
- **Advanced Animations**: More sophisticated visual effects
- **PWA Support**: Progressive Web App capabilities

### Community Contributions
We welcome community feedback and suggestions for:
- New color themes
- Interface improvements
- Accessibility enhancements
- Mobile optimizations

## 📄 License

This template is part of the PHPDTS game engine and follows the same licensing terms as the main project.

## 🙏 Credits

- **Design**: Inspired by cyberpunk and synthwave aesthetics
- **Development**: AI Assistant with game engine integration
- **Testing**: PHPDTS community members
- **Fonts**: Orbitron font family
- **Icons**: Custom SVG icons and Unicode symbols

---

**Note**: This template is currently in beta testing. While fully functional, some features may be refined based on user feedback. For the most stable experience, you can always switch back to the classic template.

Enjoy your enhanced PHPDTS gaming experience! 🎮✨
