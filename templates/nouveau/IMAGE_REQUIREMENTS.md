# Nouveau Template - Image Resource Requirements

## Overview
This document outlines the image resources needed for the Nouveau template. The template is designed to reuse existing game images while adding new cyberpunk-themed visual elements.

## Existing Resources (Reused)
The following existing images from the `img/` directory are being reused:

### Player Avatars
- `img/m_*.gif` - Male character avatars
- `img/f_*.gif` - Female character avatars
- `img/n_*.gif` - NPC avatars

### Status Icons
- `img/injured.gif` / `img/injured2.gif` - Injury status
- `img/p.gif` / `img/p2.gif` - Poison status
- `img/u.gif` / `img/u2.gif` - Burn status
- `img/i.gif` / `img/i2.gif` - Freeze status
- `img/e.gif` / `img/e2.gif` - Paralysis status
- `img/w.gif` / `img/w2.gif` - Confusion status
- `img/hurt.gif` - Injury indicators
- `img/dead.gif` - Death status
- `img/danger.gif` - Critical health
- `img/caution.gif` - Warning status
- `img/fine.gif` - Healthy status

### UI Elements
- `img/state1.gif` / `img/state2.gif` - Status backgrounds
- `img/backround*.gif` - Background images
- `img/location/*.jpg` - Location backgrounds

### Items and Equipment
- All existing item icons and equipment images

## New Resources Needed

### 1. Cyberpunk UI Elements

#### Background Patterns
- **File**: `templates/nouveau/assets/images/cyber-grid.png`
- **Description**: Subtle grid pattern overlay for cyberpunk aesthetic
- **Size**: 512x512px, tileable
- **Format**: PNG with transparency
- **Style**: Thin cyan/blue grid lines on transparent background

#### Scan Lines Effect
- **File**: `templates/nouveau/assets/images/scanlines.png`
- **Description**: Horizontal scan lines for CRT monitor effect
- **Size**: 1920x1080px, tileable vertically
- **Format**: PNG with transparency
- **Style**: Thin horizontal lines with varying opacity

#### Hologram Effect
- **File**: `templates/nouveau/assets/images/hologram-overlay.png`
- **Description**: Holographic interference pattern
- **Size**: 256x256px, tileable
- **Format**: PNG with transparency
- **Style**: Diagonal interference lines with color shifting

### 2. Icon Set

#### Theme Icons
- **File**: `templates/nouveau/assets/images/icons/theme-*.svg`
- **Description**: Icons for different color themes
- **Variants**: 
  - `theme-cyber-blue.svg` - Blue circuit pattern
  - `theme-cyber-purple.svg` - Purple neon pattern
  - `theme-cyber-green.svg` - Green matrix pattern
  - `theme-cyber-orange.svg` - Orange synthwave pattern
  - `theme-cyber-pink.svg` - Pink vaporwave pattern
- **Size**: 24x24px
- **Format**: SVG
- **Style**: Minimalist, geometric, cyberpunk-inspired

#### Interface Icons
- **File**: `templates/nouveau/assets/images/icons/ui-*.svg`
- **Description**: Modern UI icons for various functions
- **Variants**:
  - `ui-refresh.svg` - Refresh/reload icon
  - `ui-settings.svg` - Settings gear icon
  - `ui-fullscreen.svg` - Fullscreen expand icon
  - `ui-minimize.svg` - Minimize/compress icon
  - `ui-close.svg` - Close/X icon
  - `ui-menu.svg` - Hamburger menu icon
  - `ui-search.svg` - Search magnifying glass
  - `ui-filter.svg` - Filter/funnel icon
  - `ui-sort.svg` - Sort arrows icon
  - `ui-notification.svg` - Bell notification icon
- **Size**: 20x20px
- **Format**: SVG
- **Style**: Outlined, consistent stroke width

### 3. Loading Animations

#### Spinner
- **File**: `templates/nouveau/assets/images/loading-spinner.svg`
- **Description**: Animated loading spinner
- **Size**: 48x48px
- **Format**: SVG with CSS animation
- **Style**: Circular progress indicator with cyberpunk styling

#### Progress Bar Elements
- **File**: `templates/nouveau/assets/images/progress-*.png`
- **Description**: Progress bar components
- **Variants**:
  - `progress-bg.png` - Background texture
  - `progress-fill.png` - Fill texture with glow effect
  - `progress-shine.png` - Animated shine overlay
- **Size**: 200x20px, horizontally tileable
- **Format**: PNG with transparency

### 4. Decorative Elements

#### Corner Brackets
- **File**: `templates/nouveau/assets/images/decorative/corner-*.svg`
- **Description**: Cyberpunk-style corner decorations
- **Variants**:
  - `corner-tl.svg` - Top-left corner
  - `corner-tr.svg` - Top-right corner
  - `corner-bl.svg` - Bottom-left corner
  - `corner-br.svg` - Bottom-right corner
- **Size**: 32x32px
- **Format**: SVG
- **Style**: Angular, tech-inspired brackets

#### Dividers
- **File**: `templates/nouveau/assets/images/decorative/divider-*.svg`
- **Description**: Section dividers with cyberpunk styling
- **Variants**:
  - `divider-horizontal.svg` - Horizontal divider
  - `divider-vertical.svg` - Vertical divider
- **Size**: Variable, scalable
- **Format**: SVG
- **Style**: Geometric patterns with glow effects

### 5. Background Textures

#### Noise Texture
- **File**: `templates/nouveau/assets/images/textures/noise.png`
- **Description**: Subtle noise texture for depth
- **Size**: 512x512px, tileable
- **Format**: PNG
- **Style**: Fine grain noise, low opacity

#### Circuit Pattern
- **File**: `templates/nouveau/assets/images/textures/circuit.png`
- **Description**: Circuit board pattern background
- **Size**: 1024x1024px, tileable
- **Format**: PNG with transparency
- **Style**: Thin circuit traces, subtle glow

### 6. Status Indicators

#### Connection Status
- **File**: `templates/nouveau/assets/images/status/connection-*.svg`
- **Description**: Network connection status indicators
- **Variants**:
  - `connection-online.svg` - Green connected icon
  - `connection-offline.svg` - Red disconnected icon
  - `connection-unstable.svg` - Yellow unstable icon
- **Size**: 16x16px
- **Format**: SVG
- **Style**: Simple, clear indicators

#### System Status
- **File**: `templates/nouveau/assets/images/status/system-*.svg`
- **Description**: System status indicators
- **Variants**:
  - `system-operational.svg` - Green checkmark
  - `system-warning.svg` - Yellow warning triangle
  - `system-error.svg` - Red error X
  - `system-maintenance.svg` - Blue wrench icon
- **Size**: 16x16px
- **Format**: SVG
- **Style**: Consistent with connection status

## Implementation Notes

### Color Schemes
All new images should support the following color themes:
- **Cyber Blue**: Primary #00ffff, Secondary #0066ff
- **Neon Purple**: Primary #ff00ff, Secondary #9900ff
- **Matrix Green**: Primary #00ff66, Secondary #00cc44
- **Synthwave Orange**: Primary #ff6600, Secondary #ff9900
- **Vaporwave Pink**: Primary #ff0066, Secondary #ff3399

### File Organization
```
templates/nouveau/assets/images/
├── backgrounds/
├── icons/
│   ├── themes/
│   └── ui/
├── decorative/
├── textures/
├── status/
└── loading/
```

### Optimization
- All PNG files should be optimized for web delivery
- SVG files should be minified
- Consider providing WebP alternatives for better compression
- Implement lazy loading for non-critical images

### Fallbacks
- Provide fallback images for older browsers
- Ensure graceful degradation when images fail to load
- Use CSS-based alternatives where possible

### Accessibility
- Include appropriate alt text for all images
- Ensure sufficient contrast ratios
- Provide text alternatives for icon-only buttons

## Priority Levels

### High Priority (Essential for basic functionality)
1. Theme icons
2. Basic UI icons (refresh, settings, close)
3. Loading spinner
4. Progress bar elements

### Medium Priority (Enhanced experience)
1. Background patterns and textures
2. Decorative elements
3. Status indicators
4. Scan lines and hologram effects

### Low Priority (Polish and refinement)
1. Advanced decorative elements
2. Additional texture variations
3. Animated backgrounds
4. Custom cursor images

## Creation Guidelines

### Style Consistency
- Maintain consistent line weights and corner radii
- Use the established color palette
- Follow cyberpunk/synthwave aesthetic principles
- Ensure scalability across different screen sizes

### Technical Requirements
- Optimize for web delivery (small file sizes)
- Support high-DPI displays (2x variants where needed)
- Ensure cross-browser compatibility
- Test on various devices and screen sizes

### Quality Standards
- Vector graphics (SVG) preferred for scalable elements
- High-quality raster images (PNG) for complex textures
- Consistent naming conventions
- Proper file organization and documentation

## Future Considerations

### Animated Elements
Consider creating animated versions of static elements:
- Pulsing glow effects
- Flowing circuit patterns
- Particle systems
- Morphing geometric shapes

### Interactive Elements
Plan for interactive image elements:
- Hover state variations
- Click/touch feedback
- State change animations
- Progressive disclosure effects

### Customization
Design images to support user customization:
- Color theme variations
- Opacity adjustments
- Size scaling options
- Optional visual effects

This document will be updated as the template development progresses and additional image requirements are identified.
