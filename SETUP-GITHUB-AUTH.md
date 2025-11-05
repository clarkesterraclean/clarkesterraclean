# GitHub Authentication Setup

## Option 1: Personal Access Token (Recommended)

### Step 1: Create a Personal Access Token

1. Go to: https://github.com/settings/tokens
2. Click **"Generate new token"** → **"Generate new token (classic)"**
3. Name it: `Clarke's Theme Auto-Push`
4. Select scopes:
   - ✅ `repo` (Full control of private repositories)
5. Click **"Generate token"**
6. **Copy the token immediately** (you won't see it again!)

### Step 2: Test Push

Run this command (replace `YOUR_TOKEN` with your actual token):

```bash
cd clarkes-terraclean
git push https://YOUR_TOKEN@github.com/clarkesterraclean/clarkesterraclean.git main
```

Or use git credential helper:
```bash
git push origin main
# Username: clarkesterraclean
# Password: YOUR_TOKEN
```

### Step 3: Store Credentials (Optional)

To avoid entering credentials each time:

```bash
# macOS Keychain (already configured)
git config credential.helper osxkeychain

# Then push once manually - it will save the credentials
git push origin main
```

---

## Option 2: SSH Key (Alternative)

If you want to use SSH instead:

### Step 1: Add SSH Key to clarkesterraclean Account

1. Display your public key:
   ```bash
   cat ~/.ssh/id_ed25519.pub
   ```

2. Copy the output

3. Go to: https://github.com/settings/keys
4. Click **"New SSH key"**
5. Title: `MacBook - Clarke's Theme`
6. Paste your public key
7. Click **"Add SSH key"**

### Step 2: Test Connection

```bash
ssh -T git@github.com
# Should say: "Hi clarkesterraclean! ..."
```

### Step 3: Update Remote URL

```bash
cd clarkes-terraclean
git remote set-url origin git@github.com:clarkesterraclean/clarkesterraclean.git
```

---

## Option 3: Add Collaborator (If ConstructBMS has access)

If the ConstructBMS account should have access:

1. Go to: https://github.com/clarkesterraclean/clarkesterraclean/settings/access
2. Click **"Add people"**
3. Search for: `ConstructBMS`
4. Add as **Collaborator** or **Admin**
5. Then push will work with current SSH setup

---

## Current Status

- **Remote URL**: `https://github.com/clarkesterraclean/clarkesterraclean.git` (HTTPS)
- **SSH Status**: Authenticated as `ConstructBMS` (not matching repo owner)
- **Credential Helper**: `osxkeychain` (configured)

## Quick Test

After setting up authentication, test with:

```bash
cd clarkes-terraclean
git push origin main
```

If successful, future commits will auto-push via the post-commit hook!


