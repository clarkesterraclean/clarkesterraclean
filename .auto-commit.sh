#!/bin/bash
# Auto-commit helper - commits all changes with timestamp

cd "$(dirname "$0")"

# Stage all changes
git add -A

# Check if there are changes
if git diff --staged --quiet; then
    exit 0
fi

# Create commit message with timestamp
TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')
COMMIT_MSG="Auto-commit: Theme updates at $TIMESTAMP"

# Commit
git commit -m "$COMMIT_MSG" > /dev/null 2>&1

# Try to push (will fail silently if not authenticated)
git push origin main > /dev/null 2>&1

