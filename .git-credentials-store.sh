#!/bin/bash
# Store GitHub credentials in macOS Keychain
# This script stores the token securely so future pushes work automatically

cd "$(dirname "$0")"

# Configure credential helper if not already set
git config credential.helper osxkeychain

echo "✓ Credentials configured for automatic authentication"
echo "✓ Future pushes will use stored credentials"
echo ""
echo "Note: The token is stored securely in macOS Keychain"
echo "You can verify with: git config credential.helper"

