# Deployment Verification

## ✅ All Files Are Being Deployed

The deployment script is configured to upload **ALL** theme files including:

- ✅ **All PHP templates** (functions.php, header.php, footer.php, all page templates)
- ✅ **inc/ directory** (customizer.php, reviews.php, whatsapp.php + JS files)
- ✅ **dist/style.css** (compiled Tailwind CSS)
- ✅ **js/theme.js** (theme JavaScript)
- ✅ **screenshot.png** (theme preview)
- ✅ **style.css** (WordPress theme header)

## What Gets Excluded (Development Files Only)

- `.git/` - Git repository
- `node_modules/` - NPM dependencies
- `*.md` - Documentation files
- Development scripts (deploy.sh, etc.)
- Config files (.deploy-config)

## Verification

To verify files are on the server:

1. **Use the verification script:**
   - Visit: `https://clarkesterraclean.co.uk/wp-content/themes/clarkes-terraclean/verify-files.php`
   - This shows all files and their status

2. **Check via WordPress Admin:**
   - Go to Appearance → Themes
   - The theme should appear if all files are present

## Deployment Process

Every time you commit changes:

1. ✅ Files are committed to Git
2. ✅ Changes pushed to GitHub
3. ✅ Tailwind CSS is built
4. ✅ **ALL files are synced to server** (using `--delete` flag to ensure exact match)

## Manual Full Deployment

If you need to force a full deployment:

```bash
cd clarkes-terraclean
./deploy-full.sh
```

This ensures every file is uploaded, even if unchanged.

