#!/bin/bash
# FORCE upload ALL files - ignores rsync's "up to date" check
# This ensures every file is uploaded even if rsync thinks they're current

set -e

THEME_DIR="$(cd "$(dirname "$0")" && pwd)"

# Load config
if [ -f "${THEME_DIR}/.deploy-config" ]; then
    source "${THEME_DIR}/.deploy-config"
fi

echo "=== FORCE UPLOAD ALL FILES ==="
echo "This will upload EVERY file, ignoring rsync's cache"
echo ""

# Build CSS first
echo "Building Tailwind CSS..."
if [ -f "package.json" ]; then
    npm run build 2>&1
fi
echo ""

# Build rsync exclude pattern
RSYNC_EXCLUDES="--exclude=.git --exclude=.gitignore --exclude=node_modules --exclude='*.log' --exclude=.DS_Store --exclude=.env --exclude='*.md' --exclude=README* --exclude=.git-credentials-store.sh --exclude=commit-and-push.sh --exclude=.auto-commit.sh --exclude=deploy.sh --exclude=deploy-sftp.sh --exclude=.deploy-config.example --exclude=.deploy-config --exclude=functions-minimal.php --exclude=verify-files.php --exclude=check-theme.php"

echo "=== FORCING UPLOAD OF ALL FILES ==="
echo "Using --checksum to force re-upload of all files"
echo ""

# Use --checksum to force rsync to check file contents, not just timestamps
# This ensures ALL files are uploaded
expect <<EOF
set timeout 180
spawn rsync -avz --delete --checksum $RSYNC_EXCLUDES -e "ssh -p $SERVER_PORT -o StrictHostKeyChecking=no" "$THEME_DIR/" "${SERVER_USER}@${SERVER_HOST}:${SERVER_PATH}"
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
echo "=== FORCE UPLOAD COMPLETE ==="
echo ""
echo "All files have been force-uploaded to:"
echo "${SERVER_USER}@${SERVER_HOST}:${SERVER_PATH}"
echo ""
echo "Please verify files are on the server by visiting:"
echo "https://clarkesterraclean.co.uk/wp-content/themes/clarkes-terraclean/test-path.php"

