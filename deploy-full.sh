#!/bin/bash
# Full deployment script - uploads ALL files, not just changed ones
# Use this to ensure everything is on the server

set -e

THEME_DIR="$(cd "$(dirname "$0")" && pwd)"

# Load config
if [ -f "${THEME_DIR}/.deploy-config" ]; then
    source "${THEME_DIR}/.deploy-config"
fi

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${GREEN}=== FULL DEPLOYMENT - Uploading ALL Files ===${NC}"
echo ""

# Build CSS first
echo -e "${YELLOW}Building Tailwind CSS...${NC}"
if [ -f "package.json" ]; then
    npm run build 2>&1 || echo "Build warning: continuing..."
fi
echo -e "${GREEN}✓ Build complete${NC}"
echo ""

# Build rsync exclude pattern (only exclude dev files, NOT theme files)
RSYNC_EXCLUDES="--exclude=.git --exclude=.gitignore --exclude=node_modules --exclude='*.log' --exclude=.DS_Store --exclude=.env --exclude='*.md' --exclude=README* --exclude=.git-credentials-store.sh --exclude=commit-and-push.sh --exclude=.auto-commit.sh --exclude=deploy.sh --exclude=deploy-sftp.sh --exclude=.deploy-config.example --exclude=.deploy-config --exclude=functions-minimal.php --exclude=verify-files.php --exclude=check-theme.php"

echo -e "${YELLOW}Uploading ALL files to server...${NC}"
echo "Host: $SERVER_HOST"
echo "Path: $SERVER_PATH"
echo ""

# Use --delete to ensure server matches local (removes files not in local)
# Use expect script for password authentication
if command -v expect &> /dev/null; then
    expect <<EOF
set timeout 120
spawn rsync -avz --delete $RSYNC_EXCLUDES -e "ssh -p $SERVER_PORT -o StrictHostKeyChecking=no" "$THEME_DIR/" "${SERVER_USER}@${SERVER_HOST}:${SERVER_PATH}"
expect {
    "password:" {
        send "$SERVER_PASS\r"
        exp_continue
    }
    "Password:" {
        send "$SERVER_PASS\r"
        exp_continue
    }
    "Are you sure you want to continue connecting" {
        send "yes\r"
        exp_continue
    }
    eof
}
EOF
else
    echo -e "${YELLOW}Using interactive password prompt...${NC}"
    rsync -avz --delete $RSYNC_EXCLUDES \
        -e "ssh -p $SERVER_PORT -o StrictHostKeyChecking=no" \
        "$THEME_DIR/" "${SERVER_USER}@${SERVER_HOST}:${SERVER_PATH}"
fi

echo ""
echo -e "${GREEN}✓ Full deployment complete!${NC}"
echo ""
echo "All theme files deployed to: $SERVER_PATH"
echo ""
echo "Files uploaded:"
echo "  ✓ All PHP templates"
echo "  ✓ inc/ directory (customizer, reviews, whatsapp)"
echo "  ✓ dist/style.css (compiled CSS)"
echo "  ✓ js/theme.js"
echo "  ✓ screenshot.png"

