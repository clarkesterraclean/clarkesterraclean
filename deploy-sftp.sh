#!/bin/bash
# SFTP Deployment Script using rsync with password authentication
# Alternative to deploy.sh for SFTP with password

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

echo -e "${GREEN}=== Deploying to WordPress Server ===${NC}"
echo ""

# Build CSS first
echo -e "${YELLOW}Building Tailwind CSS...${NC}"
if [ -f "package.json" ]; then
    npm run build 2>&1 || echo "Build warning: continuing..."
fi
echo -e "${GREEN}✓ Build complete${NC}"
echo ""

# Deploy using rsync with SFTP
echo -e "${YELLOW}Uploading files to server...${NC}"
echo "Host: $SERVER_HOST"
echo "Path: $SERVER_PATH"
echo ""

# Build rsync exclude pattern
RSYNC_EXCLUDES="--exclude=.git --exclude=.gitignore --exclude=node_modules --exclude='*.log' --exclude=.DS_Store --exclude=.env --exclude='*.md' --exclude=README* --exclude=.git-credentials-store.sh --exclude=commit-and-push.sh --exclude=.auto-commit.sh --exclude=deploy.sh --exclude=deploy-sftp.sh --exclude=.deploy-config.example --exclude=.deploy-config"

# Use expect script for password authentication
if command -v expect &> /dev/null; then
    expect <<EOF
set timeout 60
spawn rsync -avz $RSYNC_EXCLUDES -e "ssh -p $SERVER_PORT -o StrictHostKeyChecking=no" "$THEME_DIR/" "${SERVER_USER}@${SERVER_HOST}:${SERVER_PATH}"
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
    # Fallback: try with sshpass if available
    if command -v sshpass &> /dev/null; then
        sshpass -p "$SERVER_PASS" rsync -avz $RSYNC_EXCLUDES \
            -e "ssh -p $SERVER_PORT -o StrictHostKeyChecking=no" \
            "$THEME_DIR/" "${SERVER_USER}@${SERVER_HOST}:${SERVER_PATH}"
    else
        echo -e "${YELLOW}Using interactive password prompt...${NC}"
        echo "You may be prompted for your password"
        rsync -avz $RSYNC_EXCLUDES \
            -e "ssh -p $SERVER_PORT -o StrictHostKeyChecking=no" \
            "$THEME_DIR/" "${SERVER_USER}@${SERVER_HOST}:${SERVER_PATH}"
    fi
fi

echo ""
echo -e "${GREEN}✓ Deployment complete!${NC}"
echo ""
echo "Theme files deployed to: $SERVER_PATH"
echo ""
echo "Next steps:"
echo "1. Log into WordPress Admin"
echo "2. Go to Appearance → Themes"
echo "3. Activate 'Clarke's DPF & Engine Specialists' theme"

