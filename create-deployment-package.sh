#!/bin/bash
# Create a clean deployment package of the theme

echo "Creating deployment package for Clarke's TerraClean theme..."

# Create temporary directory
TEMP_DIR=$(mktemp -d)
PACKAGE_DIR="$TEMP_DIR/clarkes-terraclean"

mkdir -p "$PACKAGE_DIR"

# Copy essential files
echo "Copying theme files..."
cp style.css "$PACKAGE_DIR/"
cp functions.php "$PACKAGE_DIR/"
cp header.php "$PACKAGE_DIR/"
cp footer.php "$PACKAGE_DIR/"
cp index.php "$PACKAGE_DIR/"
cp front-page.php "$PACKAGE_DIR/"
cp page.php "$PACKAGE_DIR/"
cp 404.php "$PACKAGE_DIR/"
cp screenshot.png "$PACKAGE_DIR/"
cp page-about.php "$PACKAGE_DIR/"
cp page-services.php "$PACKAGE_DIR/"
cp page-case-studies.php "$PACKAGE_DIR/"
cp page-testimonials.php "$PACKAGE_DIR/"
cp page-contact-us.php "$PACKAGE_DIR/"

# Copy dist and js directories
mkdir -p "$PACKAGE_DIR/dist"
mkdir -p "$PACKAGE_DIR/js"
cp -r dist/* "$PACKAGE_DIR/dist/"
cp -r js/* "$PACKAGE_DIR/js/"

# Create zip file
cd "$TEMP_DIR"
zip -r clarkes-terraclean-theme.zip clarkes-terraclean/

# Move to desktop
mv clarkes-terraclean-theme.zip ~/Desktop/

echo ""
echo "✓ Deployment package created!"
echo "✓ Location: ~/Desktop/clarkes-terraclean-theme.zip"
echo ""
echo "This zip file contains only the files needed for deployment."
echo "You can upload this directly to your WordPress site."

