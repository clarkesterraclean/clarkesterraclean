#!/bin/bash
# Auto-commit and push script for Clarke's DPF & Engine Specialists theme

cd "$(dirname "$0")"

# Get commit message from argument or use default
COMMIT_MSG="${1:-Auto-commit: Theme updates}"

# Stage all changes
git add -A

# Check if there are changes to commit
if git diff --staged --quiet; then
    echo "No changes to commit."
    exit 0
fi

# Commit
git commit -m "$COMMIT_MSG"

# Try to push
if git push origin main 2>&1; then
    echo "✓ Successfully pushed to GitHub"
else
    echo "⚠ Push failed - you may need to authenticate"
    echo "Run: git push origin main"
fi

