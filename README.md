# Clarke's TerraClean WordPress Theme

Custom WordPress theme for Clarke's TerraClean Specialists - professional engine carbon cleaning services.

## Setup Instructions

1. **Install Node.js dependencies** (for Tailwind CSS compilation):
   ```bash
   npm install
   # or
   pnpm install
   ```

2. **Build Tailwind CSS**:
   ```bash
   npm run build-css
   # or
   pnpm build-css
   ```

   This will compile `src/style.css` into `dist/style.css`.

3. **For development with auto-rebuild**:
   ```bash
   npm run watch-css
   # or
   pnpm watch-css
   ```

4. **Activate the theme** in WordPress:
   - Upload the `clarkes-terraclean` folder to `wp-content/themes/`
   - Activate the theme in WordPress Admin → Appearance → Themes

5. **Set up the menu**:
   - Go to Appearance → Menus
   - Create a new menu and assign it to "Primary Menu" location

## Theme Structure

```
clarkes-terraclean/
├── style.css              # WordPress theme header
├── functions.php          # Theme functions and setup
├── header.php             # Header template
├── footer.php             # Footer template
├── index.php              # Main template fallback
├── front-page.php         # Home page template
├── page.php               # Page template
├── src/
│   └── style.css          # Tailwind source file
├── dist/
│   └── style.css          # Compiled Tailwind CSS (generated)
├── js/
│   └── theme.js           # Theme JavaScript
├── tailwind.config.js     # Tailwind configuration
└── package.json           # Node.js dependencies
```

## Color Scheme

- **Dark Background**: Charcoal/black/graphite (`bg-gray-900`)
- **Highlight Color**: Eco green (`bg-green-500`)
- **Text on White**: Light grey/off-white (`text-gray-700`)

## Pages

The theme automatically creates the following pages on activation:
- Home
- About Us
- Services
- Case Studies
- Testimonials
- Contact Us

## Contact Information

- Phone: 07706 230867

