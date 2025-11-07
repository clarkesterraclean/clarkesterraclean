#!/bin/bash
# List files on the server to verify deployment

THEME_DIR="$(cd "$(dirname "$0")" && pwd)"

# Load config
if [ -f "${THEME_DIR}/.deploy-config" ]; then
    source "${THEME_DIR}/.deploy-config"
fi

echo "=== Listing files on server ==="
echo "Host: $SERVER_HOST"
echo "Path: $SERVER_PATH"
echo ""

# Use expect to list files
expect <<EOF
set timeout 30
spawn ssh -p $SERVER_PORT -o StrictHostKeyChecking=no ${SERVER_USER}@${SERVER_HOST} "ls -la ${SERVER_PATH}"
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
echo "=== Listing inc/ directory ==="
expect <<EOF
set timeout 30
spawn ssh -p $SERVER_PORT -o StrictHostKeyChecking=no ${SERVER_USER}@${SERVER_HOST} "ls -la ${SERVER_PATH}inc/"
expect {
    "password:" {
        send "$SERVER_PASS\r"
        exp_continue
    }
    "Password:" {
        send "$SERVER_PASS\r"
        exp_continue
    }
    eof
}
EOF

