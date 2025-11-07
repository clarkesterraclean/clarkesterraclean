# URGENT: Verify Files Are On Server

## Step 1: Check Test File

Visit this URL in your browser:
**https://clarkesterraclean.co.uk/wp-content/themes/clarkes-terraclean/test-path.php**

This will show:
- ✅ If files are actually on the server
- ✅ What files exist
- ✅ If WordPress can load
- ✅ The exact paths being used

## Step 2: Check Test Text File

Visit:
**https://clarkesterraclean.co.uk/wp-content/themes/clarkes-terraclean/TEST-DEPLOY.txt**

If you see "TEST FILE CREATED", then files ARE being uploaded.

## Step 3: Check WordPress Admin

1. Log into WordPress Admin: https://clarkesterraclean.co.uk/wp-admin
2. Go to **Appearance → Themes**
3. Do you see "Clarke's DPF & Engine Specialists" theme listed?

## If Files Are Missing

If the test files don't load, the path might be wrong. The current path is:
`/clickandbuilds/clarkesterraclean/wp-content/themes/clarkes-terraclean/`

**Possible issues:**
- WordPress might be in a different location
- The theme directory might need to be created first
- File permissions might be blocking access

## If Files ARE There But Still 500 Error

Then it's a PHP error, not a deployment issue. Check:
- WordPress error logs
- PHP error logs in your hosting control panel
- The exact error message

## Next Steps

**Please visit the test-path.php URL and tell me:**
1. Does it load?
2. What files does it show?
3. Does it show WordPress loaded?
4. Does it show the inc/ directory?

This will tell us exactly what's happening!

