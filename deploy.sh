#!/bin/bash
# Deployment script for Clarke's DPF & Engine Specialists WordPress theme
# Syncs theme files to WordPress server

set -e

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Configuration
# These will be set via environment variables or config file
DEPLOY_METHOD="${DEPLOY_METHOD:-}"  # ftp, sftp, ssh, git
SERVER_HOST="${SERVER_HOST:-}"
SERVER_USER="${SERVER_USER:-}"
SERVER_PASS="${SERVER_PASS:-}"
SERVER_PORT="${SERVER_PORT:-22}"
SERVER_PATH="${SERVER_PATH:-/wp-content/themes/clarkes-terraclean/}"
SSH_KEY="${SSH_KEY:-}"
GIT_REMOTE="${GIT_REMOTE:-}"

THEME_DIR="$(cd "$(dirname "$0")" && pwd)"
EXCLUDE_FILE="${THEME_DIR}/.deploy-exclude"

# Files to exclude from deployment
cat > "$EXCLUDE_FILE" <<EOF
.git
.gitignore
node_modules
dist/node_modules
*.log
.DS_Store
.env
.env.local
*.md
README*
QUICK-SETUP.md
SETUP-GITHUB-AUTH.md
AUTH-STATUS.md
DEPLOYMENT.md
.git-credentials-store.sh
commit-and-push.sh
.auto-commit.sh
deploy.sh
EOF

# Build Tailwind CSS before deployment
echo -e "${YELLOW}Building Tailwind CSS...${NC}"
if [ -f "package.json" ]; then
    npm run build 2>&1 || echo "Build warning: npm build failed, continuing..."
fi

echo -e "${GREEN}✓ Build complete${NC}"
echo ""

# Deployment functions
deploy_via_ftp() {
    echo -e "${YELLOW}Deploying via FTP...${NC}"
    
    if ! command -v lftp &> /dev/null; then
        echo -e "${RED}Error: lftp not installed. Install with: brew install lftp${NC}"
        exit 1
    fi
    
    lftp -u "$SERVER_USER,$SERVER_PASS" -p "$SERVER_PORT" "$SERVER_HOST" <<EOF
set ftp:ssl-allow no
mirror -R -e -X .git -X node_modules -X "*.md" -X "*.log" "$THEME_DIR" "$SERVER_PATH"
quit
EOF
}

deploy_via_sftp() {
    echo -e "${YELLOW}Deploying via SFTP...${NC}"
    
    if ! command -v rsync &> /dev/null; then
        echo -e "${RED}Error: rsync not installed${NC}"
        exit 1
    fi
    
    rsync -avz --exclude-from="$EXCLUDE_FILE" \
        -e "ssh -p $SERVER_PORT ${SSH_KEY:+-i $SSH_KEY}" \
        "$THEME_DIR/" "${SERVER_USER}@${SERVER_HOST}:${SERVER_PATH}"
}

deploy_via_ssh() {
    echo -e "${YELLOW}Deploying via SSH/SCP...${NC}"
    
    if ! command -v rsync &> /dev/null; then
        echo -e "${RED}Error: rsync not installed${NC}"
        exit 1
    fi
    
    rsync -avz --exclude-from="$EXCLUDE_FILE" \
        -e "ssh -p $SERVER_PORT ${SSH_KEY:+-i $SSH_KEY}" \
        "$THEME_DIR/" "${SERVER_USER}@${SERVER_HOST}:${SERVER_PATH}"
}

deploy_via_git() {
    echo -e "${YELLOW}Deploying via Git...${NC}"
    
    if [ -z "$GIT_REMOTE" ]; then
        echo -e "${RED}Error: GIT_REMOTE not set${NC}"
        exit 1
    fi
    
    git push "$GIT_REMOTE" main
}

# Main deployment logic
main() {
    echo -e "${GREEN}=== Clarke's Theme Deployment ===${NC}"
    echo ""
    
    if [ -z "$DEPLOY_METHOD" ]; then
        echo -e "${RED}Error: DEPLOY_METHOD not set${NC}"
        echo ""
        echo "Usage:"
        echo "  DEPLOY_METHOD=ftp SERVER_HOST=... SERVER_USER=... SERVER_PASS=... ./deploy.sh"
        echo "  DEPLOY_METHOD=sftp SERVER_HOST=... SERVER_USER=... SERVER_PATH=... ./deploy.sh"
        echo "  DEPLOY_METHOD=ssh SERVER_HOST=... SERVER_USER=... SSH_KEY=... SERVER_PATH=... ./deploy.sh"
        echo "  DEPLOY_METHOD=git GIT_REMOTE=production ./deploy.sh"
        echo ""
        echo "Or create a .deploy-config file with these variables"
        exit 1
    fi
    
    case "$DEPLOY_METHOD" in
        ftp)
            deploy_via_ftp
            ;;
        sftp)
            deploy_via_sftp
            ;;
        ssh)
            deploy_via_ssh
            ;;
        git)
            deploy_via_git
            ;;
        *)
            echo -e "${RED}Error: Unknown deployment method: $DEPLOY_METHOD${NC}"
            exit 1
            ;;
    esac
    
    echo ""
    echo -e "${GREEN}✓ Deployment complete!${NC}"
    echo ""
    echo "Theme files deployed to: $SERVER_PATH"
}

# Load config file if it exists
if [ -f "${THEME_DIR}/.deploy-config" ]; then
    source "${THEME_DIR}/.deploy-config"
fi

main "$@"

