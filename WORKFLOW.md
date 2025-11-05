# Development & Deployment Workflow

## Overview

This document explains the complete workflow from development in Cursor to deployment on your WordPress server.

## Workflow

```
You (in Cursor) → Give me prompts
    ↓
I build/update theme files
    ↓
Auto-commit to Git (local)
    ↓
Auto-push to GitHub
    ↓
Build Tailwind CSS (dist/style.css)
    ↓
Auto-deploy to WordPress server (if configured)
```

## Step-by-Step Process

### 1. Development (You + Me in Cursor)

You provide prompts like:
- "Update the header to show a new phone number"
- "Add a new section to the homepage"
- "Change the color scheme"

I make the changes to the theme files.

### 2. Automatic Commit

After I make changes, the system automatically:
- Stages all modified files
- Commits with a descriptive message
- Pushes to GitHub

### 3. Build Process

Before deployment, Tailwind CSS is automatically compiled:
- Runs `npm run build`
- Updates `dist/style.css`
- Commits the built file

### 4. Deployment (Optional - Needs Configuration)

If `.deploy-config` is set up, files are automatically synced to your WordPress server.

## Setup Deployment

To enable automatic deployment to your WordPress server:

1. **Copy the example config**:
   ```bash
   cp .deploy-config.example .deploy-config
   ```

2. **Edit `.deploy-config`** with your server details:
   ```bash
   # For SSH/SFTP
   DEPLOY_METHOD="ssh"
   SERVER_HOST="your-server.com"
   SERVER_USER="your-username"
   SERVER_PATH="/var/www/html/wp-content/themes/clarkes-terraclean/"
   SSH_KEY="$HOME/.ssh/id_rsa"
   ```

3. **Test deployment**:
   ```bash
   ./deploy.sh
   ```

4. **Done!** Future commits will auto-deploy.

## Manual Deployment

If you prefer manual deployment:

```bash
# Build CSS
npm run build

# Deploy to server
./deploy.sh
```

Or use the deployment package script:
```bash
./create-deployment-package.sh
# Upload the zip file to your server
```

## Files That Get Deployed

**Always deployed:**
- All PHP templates (`.php` files)
- `dist/style.css` (compiled CSS)
- `js/theme.js`
- `inc/` directory (customizer, reviews, whatsapp)
- `screenshot.png`

**Never deployed:**
- `node_modules/`
- `src/` (Tailwind source)
- Development files (`.md`, scripts)
- `.git/` directory

## Server Requirements

Your WordPress server needs:
- PHP 7.4 or higher
- WordPress 6.0 or higher
- Theme folder: `/wp-content/themes/clarkes-terraclean/`

## Troubleshooting

### Deployment fails
- Check `.deploy-config` settings
- Verify server credentials
- Test connection: `ssh user@server` or `sftp user@server`

### CSS not updating
- Run `npm run build` manually
- Check `dist/style.css` exists and is recent
- Clear browser cache

### Changes not appearing
- Check WordPress theme is activated
- Verify files are in correct server path
- Check file permissions on server

