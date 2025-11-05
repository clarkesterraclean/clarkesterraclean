# Git Setup for Clarke's DPF & Engine Specialists Theme

## Automatic Commits

The theme is configured to automatically commit changes. The system includes:

- **Pre-commit hook**: Automatically stages all modified/new files
- **Post-commit hook**: Attempts to auto-push to GitHub after each commit
- **Helper script**: `commit-and-push.sh` for manual commits with custom messages

## Pushing to GitHub

### Initial Setup (One-time)

The repository remote is set to: `git@github.com:clarkesterraclean/clarkesterraclean.git`

**Option 1: SSH (Recommended if keys are set up)**
```bash
git push origin main
```

**Option 2: HTTPS with Personal Access Token**
```bash
git remote set-url origin https://github.com/clarkesterraclean/clarkesterraclean.git
git push origin main
# When prompted: Username = your GitHub username, Password = your Personal Access Token
```

**Option 3: GitHub CLI**
```bash
gh auth login
git push origin main
```

## Manual Commit & Push

To manually commit and push with a custom message:
```bash
./commit-and-push.sh "Your custom commit message"
```

Or use git directly:
```bash
git add -A
git commit -m "Your message"
git push origin main
```

## Current Status

- ✓ Git repository initialized
- ✓ All theme files committed locally
- ✓ Auto-commit hooks configured
- ⚠ GitHub push requires authentication (see above)

## Files Committed

All theme files are tracked, including:
- PHP templates (header, footer, pages, etc.)
- JavaScript files (theme.js, reviews.js, customizer preview)
- Customizer configuration
- Reviews system
- WhatsApp FAB system
- Tailwind CSS configuration
- Screenshot and documentation

Note: `node_modules/` and build artifacts are excluded via `.gitignore`.

