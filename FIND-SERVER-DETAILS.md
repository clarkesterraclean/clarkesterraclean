# How to Find Your Server Details

## Quick Answer: What I Need

I need **FTP or SFTP credentials** to upload files to your WordPress server. Here's where to find them:

## Where to Find FTP/SFTP Details

### Option 1: Your Hosting Control Panel (Most Common)

**cPanel, Plesk, or Hosting Dashboard:**

1. **Log into your hosting account** (the company where you host clarkesterraclean.co.uk)
2. Look for one of these sections:
   - **"FTP Accounts"** or **"FTP Settings"**
   - **"File Manager"** → **"FTP Information"**
   - **"Hosting"** → **"FTP Details"**
   - **"Account Details"** → **"FTP Access"**

3. You'll see something like:
   ```
   FTP Host: ftp.clarkesterraclean.co.uk (or an IP address)
   FTP Username: your-username
   FTP Password: your-password
   FTP Port: 21 (FTP) or 22 (SFTP)
   ```

### Option 2: Your Hosting Provider's Email

When you first signed up for hosting, they sent you an email with:
- FTP hostname
- FTP username
- FTP password
- Port number

**Search your email for:** "FTP", "hosting", "cPanel", or your hosting provider's name.

### Option 3: WordPress Admin (Sometimes)

Some hosts show FTP details in:
- **WordPress Admin** → **Tools** → **Site Health** → **Info** → **Server**
- Or check your hosting provider's documentation

### Option 4: Contact Your Hosting Provider

If you can't find it:
- **Call or email your hosting provider**
- Ask: "What are my FTP/SFTP credentials for uploading files?"
- They'll give you: host, username, password, port

## What I Need From You

Just provide these 4 pieces of information:

1. **FTP Host** (or SFTP Host)
   - Example: `ftp.clarkesterraclean.co.uk` or `123.45.67.89`
   - Or: `clarkesterraclean.co.uk`

2. **FTP Username**
   - Example: `clarkesterraclean` or `your-username`

3. **FTP Password**
   - The password for FTP access

4. **FTP Port**
   - Usually `21` for FTP or `22` for SFTP
   - If unsure, try `21` first

5. **WordPress Theme Path** (I can guess this, but confirm)
   - Usually: `/public_html/wp-content/themes/clarkes-terraclean/`
   - Or: `/www/wp-content/themes/clarkes-terraclean/`
   - Or: `/htdocs/wp-content/themes/clarkes-terraclean/`

## Common Hosting Providers

### If you use **cPanel** (most common):
1. Log into cPanel
2. Click **"FTP Accounts"** in the Files section
3. Your FTP details are listed there

### If you use **WordPress.com** or **WordPress.org hosting**:
- Check your hosting dashboard
- Look for "FTP" or "SFTP" settings

### If you use **SiteGround, Bluehost, HostGator, etc.**:
- Log into your hosting account
- Go to **"My Accounts"** → **"FTP Details"** or similar

## Security Note

**Don't share your FTP password publicly!** Just tell me the details here in Cursor, and I'll configure it securely in `.deploy-config` (which is NOT committed to Git).

## Once You Have the Details

Just tell me:
- "My FTP host is: [hostname]"
- "My FTP username is: [username]"
- "My FTP password is: [password]"
- "My FTP port is: [21 or 22]"

And I'll set everything up so that every time you give me a prompt and I build something, it automatically uploads to your WordPress server!

