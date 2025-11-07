<?php
/**
 * Theme Diagnostic Check
 * Upload this file to your theme directory and access it via browser to check for issues
 * 
 * URL: https://clarkesterraclean.co.uk/wp-content/themes/clarkes-terraclean/check-theme.php
 */

// Load WordPress
require_once('../../../wp-load.php');

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Theme Diagnostic Check</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .pass { color: green; }
        .fail { color: red; font-weight: bold; }
        .warning { color: orange; }
        h2 { margin-top: 30px; }
        ul { list-style: none; padding-left: 0; }
        li { padding: 5px 0; }
    </style>
</head>
<body>
    <h1>Clarke's Theme Diagnostic Check</h1>
    
    <h2>WordPress Environment</h2>
    <ul>
        <li>WordPress Version: <?php echo get_bloginfo('version'); ?></li>
        <li>PHP Version: <?php echo PHP_VERSION; ?></li>
        <li>Theme Directory: <?php echo get_template_directory(); ?></li>
        <li>Stylesheet Directory: <?php echo get_stylesheet_directory(); ?></li>
    </ul>
    
    <h2>Required Files</h2>
    <ul>
        <?php
        $required_files = array(
            'style.css',
            'functions.php',
            'header.php',
            'footer.php',
            'index.php',
            'front-page.php',
            'inc/customizer.php',
            'inc/reviews.php',
            'inc/whatsapp.php',
            'dist/style.css',
            'js/theme.js'
        );
        
        $theme_dir = get_template_directory();
        foreach ($required_files as $file) {
            $path = $theme_dir . '/' . $file;
            $exists = file_exists($path);
            $class = $exists ? 'pass' : 'fail';
            $status = $exists ? '✓' : '✗';
            echo "<li class='$class'>$status $file</li>";
        }
        ?>
    </ul>
    
    <h2>PHP Syntax Check</h2>
    <ul>
        <?php
        $php_files = array(
            'functions.php',
            'header.php',
            'footer.php',
            'inc/customizer.php',
            'inc/reviews.php',
            'inc/whatsapp.php'
        );
        
        foreach ($php_files as $file) {
            $path = $theme_dir . '/' . $file;
            if (file_exists($path)) {
                $output = array();
                $return = 0;
                exec("php -l " . escapeshellarg($path) . " 2>&1", $output, $return);
                $class = $return === 0 ? 'pass' : 'fail';
                $status = $return === 0 ? '✓' : '✗';
                $message = $return === 0 ? 'No syntax errors' : implode(' ', $output);
                echo "<li class='$class'>$status $file - $message</li>";
            } else {
                echo "<li class='fail'>✗ $file - File not found</li>";
            }
        }
        ?>
    </ul>
    
    <h2>Theme Functions</h2>
    <ul>
        <?php
        $required_functions = array(
            'clarkes_terraclean_setup',
            'clarkes_customize_register',
            'clarkes_register_review_post_type',
            'clarkes_render_whatsapp_fab'
        );
        
        foreach ($required_functions as $func) {
            $exists = function_exists($func);
            $class = $exists ? 'pass' : 'fail';
            $status = $exists ? '✓' : '✗';
            echo "<li class='$class'>$status $func()</li>";
        }
        ?>
    </ul>
    
    <h2>WordPress Hooks</h2>
    <ul>
        <?php
        global $wp_filter;
        $required_hooks = array(
            'after_setup_theme',
            'wp_enqueue_scripts',
            'customize_register',
            'wp_footer'
        );
        
        foreach ($required_hooks as $hook) {
            $exists = isset($wp_filter[$hook]);
            $class = $exists ? 'pass' : 'warning';
            $status = $exists ? '✓' : '?';
            echo "<li class='$class'>$status Hook: $hook</li>";
        }
        ?>
    </ul>
    
    <h2>File Permissions</h2>
    <ul>
        <?php
        $check_files = array('functions.php', 'style.css', 'dist/style.css');
        foreach ($check_files as $file) {
            $path = $theme_dir . '/' . $file;
            if (file_exists($path)) {
                $perms = substr(sprintf('%o', fileperms($path)), -4);
                $readable = is_readable($path);
                $class = $readable ? 'pass' : 'fail';
                $status = $readable ? '✓' : '✗';
                echo "<li class='$class'>$status $file - Permissions: $perms - " . ($readable ? 'Readable' : 'Not Readable') . "</li>";
            }
        }
        ?>
    </ul>
    
    <hr>
    <p><strong>Note:</strong> Delete this file after checking for security reasons.</p>
</body>
</html>

