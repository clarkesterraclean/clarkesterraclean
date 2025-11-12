<?php
/**
 * Feature Verification Script
 * Run this to verify all features are loaded correctly
 */

// Load WordPress
require_once('../../../wp-load.php');

echo "<h1>Feature Verification</h1>";

echo "<h2>1. Media Editor</h2>";
if (function_exists('clarkes_add_media_editor_menu')) {
    echo "✓ Media Editor function exists<br>";
} else {
    echo "✗ Media Editor function NOT FOUND<br>";
}

echo "<h2>2. Page Builder</h2>";
if (function_exists('clarkes_add_page_builder_menu')) {
    echo "✓ Page Builder function exists<br>";
} else {
    echo "✗ Page Builder function NOT FOUND<br>";
}

echo "<h2>3. Section Spacing</h2>";
if (function_exists('clarkes_customize_register')) {
    echo "✓ Customizer function exists<br>";
    // Check if section exists
    global $wp_customize;
    if (isset($wp_customize)) {
        $section = $wp_customize->get_section('clarkes_section_spacing');
        if ($section) {
            echo "✓ Section Spacing section registered<br>";
        } else {
            echo "✗ Section Spacing section NOT FOUND<br>";
        }
    }
} else {
    echo "✗ Customizer function NOT FOUND<br>";
}

echo "<h2>4. File Check</h2>";
$files = array(
    'inc/page-builder.php' => 'Page Builder',
    'inc/media-editor.php' => 'Media Editor',
    'inc/customizer.php' => 'Customizer',
);

foreach ($files as $file => $name) {
    $path = get_template_directory() . '/' . $file;
    if (file_exists($path)) {
        echo "✓ {$name} file exists<br>";
    } else {
        echo "✗ {$name} file NOT FOUND at: {$path}<br>";
    }
}

echo "<h2>5. Menu Items Check</h2>";
global $menu, $submenu;
$found_media = false;
$found_builder = false;

if (isset($menu)) {
    foreach ($menu as $item) {
        if (isset($item[2]) && $item[2] === 'clarkes-media-editor') {
            $found_media = true;
            echo "✓ Media Editor menu found<br>";
        }
        if (isset($item[2]) && $item[2] === 'clarkes-page-builder') {
            $found_builder = true;
            echo "✓ Page Builder menu found<br>";
        }
    }
}

if (!$found_media) {
    echo "✗ Media Editor menu NOT FOUND<br>";
}
if (!$found_builder) {
    echo "✗ Page Builder menu NOT FOUND<br>";
}

echo "<h2>6. User Capabilities</h2>";
$current_user = wp_get_current_user();
if ($current_user->ID) {
    echo "Current User: " . $current_user->user_login . "<br>";
    echo "Can upload files: " . (current_user_can('upload_files') ? 'YES' : 'NO') . "<br>";
    echo "Can edit pages: " . (current_user_can('edit_pages') ? 'YES' : 'NO') . "<br>";
    echo "Can manage options: " . (current_user_can('manage_options') ? 'YES' : 'NO') . "<br>";
} else {
    echo "No user logged in<br>";
}

