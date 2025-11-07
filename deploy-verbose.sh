#!/bin/bash
# Verbose deployment - shows exactly what files are being transferred

set -e

THEME_DIR="$(cd "$(dirname "$0")" && pwd)"

# Load config
if [ -f "${THEME_DIR}/.deploy-config" ]; then
    source "${THEME_DIR}/.deploy-config"
fi

echo "=== VERBOSE DEPLOYMENT ==="
echo "Source: $THEME_DIR"
echo "Destination: ${SERVER_USER}@${SERVER_HOST}:${SERVER_PATH}"
echo ""

# Build CSS first
echo "Building Tailwind CSS..."
if [ -f "package.json" ]; then
    npm run build 2>&1
fi
echo ""

# Build rsync exclude pattern
RSYNC_EXCLUDES="--exclude=.git --exclude=.gitignore --exclude=node_modules --exclude='*.log' --exclude=.DS_Store --exclude=.env --exclude='*.md' --exclude=README* --exclude=.git-credentials-store.sh --exclude=commit-and-push.sh --exclude=.auto-commit.sh --exclude=deploy.sh --exclude=deploy-sftp.sh --exclude=.deploy-config.example --exclude=.deploy-config --exclude=functions-minimal.php --exclude=verify-files.php --exclude=check-theme.php"

echo "=== FILES TO BE DEPLOYED ==="
echo "PHP files:"
find . -name "*.php" ! -path "./node_modules/*" ! -path "./.git/*" ! -name "functions-minimal.php" ! -name "verify-files.php" ! -name "check-theme.php" ! -name "deploy*.sh" | sort
echo ""
echo "inc/ directory files:"
ls -1 inc/*.php inc/*.js 2>/dev/null || echo "WARNING: inc/ directory not found!"
echo ""
echo "Other essential files:"
ls -1 dist/style.css js/theme.js style.css screenshot.png 2>/dev/null | head -10
echo ""

echo "=== DEPLOYING NOW ==="
expect <<EOF
set timeout 120
spawn rsync -avz --delete --progress $RSYNC_EXCLUDES -e "ssh -p $SERVER_PORT -o StrictHostKeyChecking=no" "$THEME_DIR/" "${SERVER_USER}@${SERVER_HOST}:${SERVER_PATH}"
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

echo ""
echo "=== DEPLOYMENT COMPLETE ==="

