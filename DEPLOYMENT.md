# Deployment Setup for WordPress Server

## Overview

This theme is developed in Cursor and needs to be deployed to your WordPress server's theme folder.

## Server Information Needed

Please provide the following details about your WordPress server:

1. **Server Type**: 
   - [ ] FTP/SFTP
   - [ ] SSH/SCP
   - [ ] Git-based deployment
   - [ ] Other (specify)

2. **Server Details** (if FTP/SFTP):
   - Host: `________________`
   - Username: `________________`
   - Password: `________________`
   - Port: `21` (FTP) or `22` (SFTP)
   - WordPress theme path: `/wp-content/themes/clarkes-terraclean/`

3. **Server Details** (if SSH):
   - Host: `________________`
   - Username: `________________`
   - SSH key path: `________________`
   - WordPress theme path: `/wp-content/themes/clarkes-terraclean/`

4. **Server Details** (if Git):
   - Repository URL: `________________`
   - Branch: `main` or `master`
   - Deployment hook/script: `________________`

## Current Setup

- **Local Development**: `/Users/napwoodconstruction/Desktop/ClarkesTerraClean/clarkes-terraclean`
- **Git Repository**: `https://github.com/clarkesterraclean/clarkesterraclean.git`
- **Auto-commit**: Enabled
- **Auto-push to GitHub**: Enabled

## Deployment Options

### Option 1: Automated Deployment Script (Recommended)

A script will sync the theme files to your server after each commit.

### Option 2: Manual Deployment

Run a deployment command when ready to push changes.

### Option 3: Git-based Deployment

If your server supports Git, we can set up a deployment hook.

## Next Steps

1. Provide your server details above
2. I'll create the deployment script
3. Configure it to run automatically after commits
