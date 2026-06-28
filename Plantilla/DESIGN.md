---
name: Modern Tech Educator
colors:
  surface: '#fff8f5'
  surface-dim: '#f1d4c2'
  surface-bright: '#fff8f5'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#fff1e9'
  surface-container: '#ffeade'
  surface-container-high: '#ffe3d2'
  surface-container-highest: '#faddcb'
  on-surface: '#27180d'
  on-surface-variant: '#584235'
  inverse-surface: '#3e2d21'
  inverse-on-surface: '#ffede4'
  outline: '#8c7263'
  outline-variant: '#e0c0af'
  surface-tint: '#994700'
  primary: '#994700'
  on-primary: '#ffffff'
  primary-container: '#ff7a00'
  on-primary-container: '#5c2800'
  inverse-primary: '#ffb68b'
  secondary: '#284ade'
  on-secondary: '#ffffff'
  secondary-container: '#4766f8'
  on-secondary-container: '#fffbff'
  tertiary: '#5e5e5f'
  on-tertiary: '#ffffff'
  tertiary-container: '#a0a0a0'
  on-tertiary-container: '#363737'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ffdbc8'
  primary-fixed-dim: '#ffb68b'
  on-primary-fixed: '#321200'
  on-primary-fixed-variant: '#753400'
  secondary-fixed: '#dee1ff'
  secondary-fixed-dim: '#bac3ff'
  on-secondary-fixed: '#001159'
  on-secondary-fixed-variant: '#0031c5'
  tertiary-fixed: '#e3e2e2'
  tertiary-fixed-dim: '#c7c6c6'
  on-tertiary-fixed: '#1a1c1c'
  on-tertiary-fixed-variant: '#464747'
  background: '#fff8f5'
  on-background: '#27180d'
  surface-variant: '#faddcb'
typography:
  display-lg:
    fontFamily: Montserrat
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -1px
  headline-lg:
    fontFamily: Montserrat
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
  headline-md:
    fontFamily: Montserrat
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-bold:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
  headline-lg-mobile:
    fontFamily: Montserrat
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 36px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  xs: 4px
  sm: 12px
  md: 24px
  lg: 48px
  xl: 80px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 64px
---

## Brand & Style

This design system is built to evoke a sense of high-velocity learning and professional growth. It targets ambitious individuals looking to upskill in the technology sector, demanding a UI that feels both academically rigorous and digitally native. 

The aesthetic identity is a fusion of **Corporate Modern** structure and **High-Contrast** energy, punctuated by **Glassmorphism** accents. The brand personality is "The Expert Mentor": encouraging and vibrant, but deeply grounded in industry standards. Visuals prioritize clarity through generous whitespace while using bold color blocks and tech-focused illustrations to maintain an energetic, interactive atmosphere.

## Colors

The color palette is designed for maximum impact and clear functional signaling, now grounded by a warm, organic neutral palette.

- **Vibrant Orange (#FF7A00):** Used exclusively for primary calls-to-action, progress indicators, and "active" learning states. It represents energy and the spark of discovery.
- **Deep Blue (#0033CC):** Acts as the stabilizing force. Used for navigation bars, footer sections, and link text to establish a sense of institutional trust and depth.
- **Professional Gray (#A0A0A0):** Utilized for secondary UI elements, borders, and disabled states to ensure the vibrant primaries do not overwhelm the user.
- **Warm Neutral (#CBB09F):** Provides a sophisticated, earthy foundation for surfaces and containers, softening the technical feel of the interface and reducing eye strain.

High contrast is maintained by pairing dark text against warm-tinted light backgrounds, with specific neutral surfaces used to differentiate content modules.

## Typography

This design system utilizes two distinct sans-serif families to balance character with utility. 

**Montserrat** is the voice of the brand. It is used for all headings and display text. Its geometric construction feels modern and confident. On mobile, headlines scale down slightly to ensure readability without losing their "bold" impact.

**Inter** is the workhorse for all functional UI elements and long-form educational content. It was chosen for its exceptional legibility at small sizes and its neutral tone, which allows the learner to focus on the course material without distraction. 

Weight is used as a primary tool for hierarchy: bold headers lead the eye, while medium-weight labels categorize information within cards and lists.

## Layout & Spacing

This design system employs a **12-column fluid grid** for desktop environments, transitioning to a **4-column grid** for mobile devices. 

The spacing rhythm is based on an **8px linear scale**. This ensures a consistent vertical cadence across all pages. 
- **Large gaps (48px - 80px):** Used to separate major content sections (e.g., Hero from Course Catalog).
- **Medium gaps (24px):** Used for standard margins and gutters between cards.
- **Small gaps (4px - 12px):** Used for internal component spacing, such as an icon's proximity to a label.

On desktop, content is typically contained within a max-width of 1280px to prevent line lengths from becoming unreadable, while backgrounds and decorative elements may bleed to the screen edges.

## Elevation & Depth

Hierarchy in this design system is created through a combination of **Tonal Layers** and **Glassmorphism**, leveraging the new warm neutral palette.

1.  **Background (Level 0):** Uses the warmest surface tones (#FFF8F5) to provide a comfortable reading environment.
2.  **Surface (Level 1):** Slightly elevated containers use the Neutral palette (#F6DED2 or #E0C0AF) to create distinct content areas with subtle 1px borders.
3.  **Interactive (Level 2):** Primary buttons and active states use a vibrant orange with a soft, diffused shadow (12px blur, 15% opacity, tinted with the primary color) to "lift" them off the page.
4.  **Glass Layers (Level 3):** Overlays, sticky navigation bars, and video player controls use a backdrop filter (blur: 16px) with a semi-transparent fill that retains the warm neutral undertones.

Avoid heavy, dark shadows; keep the depth feeling light, airy, and translucent.

## Shapes

The shape language is consistently **Rounded**, reinforcing the approachable and friendly nature of an educational platform.

- **Standard Elements:** Buttons, input fields, and small tags use a 0.5rem (8px) corner radius.
- **Large Elements:** Course cards, containers, and hero image placeholders use a 1rem (16px) corner radius.
- **Exceptional Elements:** Profile avatars and specific "status" pill indicators use a fully rounded (pill-shaped) style.

The consistent use of rounded corners softens the high-contrast color palette, making the interface feel modern rather than aggressive.

## Components

### Buttons
Primary buttons are solid Vibrant Orange with bold white Inter text. Secondary buttons use a Deep Blue outline or a "Ghost" style (text only) for less critical actions. Hover states should involve a slight scale-up (1.02x) and a deepening of the shadow.

### Cards
Course cards are the most common element. They feature a top-aligned professional photograph or tech illustration, followed by a headline in Montserrat. Metadata (time, difficulty, rating) should use Inter Label-Bold with the secondary gray or the warm neutral outline for definition.

### Input Fields
Forms should be clean with a 1px border using the `outline` color. On focus, the border transitions to Deep Blue with a soft blue outer glow. Labels are always visible above the field to ensure accessibility.

### Progress Bars
Interactive elements like course progress bars use the Vibrant Orange to signify movement and achievement. The background of the bar should be a light warm neutral to ensure the "fill" is the focal point.

### Glassmorphic Navigation
The main header should be a sticky glassmorphic bar. This keeps the primary navigation (Search, My Courses, Profile) always accessible while allowing the colorful background illustrations and warm neutral surfaces to peek through as the user scrolls.