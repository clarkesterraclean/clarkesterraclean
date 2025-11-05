# Quick GitHub Setup Guide

## Your SSH Public Key (for Option 2)

Copy this key and add it to your GitHub account:

```
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIJ7Sfp65MolFrtTGNrSqAGEuMMN5Fi/eu2D87IOBo9Nu napwoodconstruction@ConstructBMS
```

## Fastest Method: Personal Access Token

1. **Create Token**: https://github.com/settings/tokens/new
   - Name: `Clarke's Theme`
   - Scopes: Check `repo`
   - Click "Generate token"
   - **Copy the token**

2. **Push Now**:
   ```bash
   cd clarkes-terraclean
   git push origin main
   ```
   - Username: `clarkesterraclean`
   - Password: `YOUR_TOKEN` (paste the token)

3. **Done!** Future pushes will be automatic.

---

## Alternative: Add SSH Key to clarkesterraclean Account

1. Go to: https://github.com/settings/keys
2. Click "New SSH key"
3. Paste the public key shown above
4. Run: `git remote set-url origin git@github.com:clarkesterraclean/clarkesterraclean.git`
5. Test: `git push origin main`

