# Clarke's TerraClean Theme - Deployment Guide

## Pre-Deployment Checklist

### Required Information
- [ ] Web hosting provider name
- [ ] FTP/SFTP credentials (host, username, password, port)
- [ ] WordPress installation path (usually `/public_html/` or `/wp-content/`)
- [ ] WordPress admin access (to activate theme)
- [ ] Domain name (e.g., clarkesterraclean.co.uk)

### Files to Upload

**Essential Theme Files** (must upload):
- `style.css` - WordPress theme header
- `functions.php` - Core functionality
- `header.php` - Header template
- `footer.php` - Footer template
- `index.php` - Main template
- `front-page.php` - Homepage template
- `page.php` - Page template
- `404.php` - Error page
- `screenshot.png` - Theme preview (1200×900)
- `page-about.php` - About page template
- `page-services.php` - Services page template
- `page-case-studies.php` - Case Studies template
- `page-testimonials.php` - Testimonials template
- `page-contact-us.php` - Contact page template
- `dist/style.css` - Compiled Tailwind CSS (CRITICAL)
- `js/theme.js` - Theme JavaScript

**Development Files** (optional - can skip):
- `src/style.css` - Tailwind source (not needed on server)
- `tailwind.config.js` - Tailwind config (not needed)
- `package.json` - NPM config (not needed)
- `node_modules/` - Dependencies (not needed)
- `screenshot.svg` - SVG source (optional)

### Deployment Steps

1. **Prepare Files**
   - Ensure `dist/style.css` is built and up-to-date
   - Verify all PHP files are present

2. **Upload to Server**
   - Upload entire `clarkes-terraclean` folder to `/wp-content/themes/`
   - Final path should be: `/wp-content/themes/clarkes-terraclean/`

3. **Activate Theme**
   - Log into WordPress Admin
   - Go to Appearance → Themes
   - Find "Clarke's TerraClean" theme
   - Click "Activate"

4. **Configure WordPress**
   - Go to Appearance → Menus
   - Create a menu and assign to "Primary Menu"
   - Set up pages (they should auto-create on activation)

5. **Test**
   - Visit homepage
   - Test contact form
   - Check mobile menu
   - Verify all pages load correctly

## Important Notes

- **Tailwind CSS**: The `dist/style.css` file MUST be uploaded - this contains all compiled styles
- **No Build Process Needed**: The theme uses pre-compiled CSS, no npm/node required on server
- **PHP Version**: Requires PHP 7.4 or higher
- **WordPress Version**: Requires WordPress 6.0 or higher

