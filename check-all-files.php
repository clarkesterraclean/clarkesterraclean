<?php
/**
 * Comprehensive Theme File Checker
 * Visit: https://clarkesterraclean.co.uk/wp-content/themes/clarkes-terraclean/check-all-files.php
 */

// Load WordPress
$wp_load = dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php';
if (!file_exists($wp_load)) {
    die('WordPress not found. Please check the path.');
}
require_once($wp_load);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Theme File Checker</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1a1a1a; color: #0f0; }
        .success { color: #0f0; }
        .error { color: #f00; }
        .warning { color: #ff0; }
        h2 { color: #0ff; margin-top: 30px; }
        pre { background: #000; padding: 10px; border: 1px solid #333; }
    </style>
</head>
<body>
    <h1>Theme File Checker</h1>
    
    <?php
    $theme_dir = get_template_directory();
    $errors = array();
    $warnings = array();
    $success = array();
    
    // Core files that must exist
    $required_files = array(
        'style.css',
        'functions.php',
        'index.php',
        'header.php',
        'footer.php',
        'front-page.php',
        'page.php',
    );
    
    echo '<h2>1. Required Files Check</h2>';
    foreach ($required_files as $file) {
        $path = $theme_dir . '/' . $file;
        if (file_exists($path)) {
            echo '<div class="success">✓ ' . $file . ' exists (' . filesize($path) . ' bytes)</div>';
            $success[] = $file;
        } else {
            echo '<div class="error">✗ ' . $file . ' MISSING</div>';
            $errors[] = $file;
        }
    }
    
    // Check inc/ files
    echo '<h2>2. Inc Directory Files</h2>';
    $inc_files = array('customizer.php', 'reviews.php', 'whatsapp.php');
    foreach ($inc_files as $file) {
        $path = $theme_dir . '/inc/' . $file;
        if (file_exists($path)) {
            echo '<div class="success">✓ inc/' . $file . ' exists (' . filesize($path) . ' bytes)</div>';
        } else {
            echo '<div class="warning">⚠ inc/' . $file . ' not found (optional)</div>';
        }
    }
    
    // PHP Syntax Check
    echo '<h2>3. PHP Syntax Check</h2>';
    $php_files = array_merge($required_files, array_map(function($f) { return 'inc/' . $f; }, $inc_files));
    
    foreach ($php_files as $file) {
        $path = $theme_dir . '/' . $file;
        if (file_exists($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $output = array();
            $return = 0;
            exec("php -l " . escapeshellarg($path) . " 2>&1", $output, $return);
            if ($return === 0) {
                echo '<div class="success">✓ ' . $file . ' - No syntax errors</div>';
            } else {
                echo '<div class="error">✗ ' . $file . ' - Syntax error:</div>';
                echo '<pre>' . htmlspecialchars(implode("\n", $output)) . '</pre>';
                $errors[] = $file;
            }
        }
    }
    
    // Test template loading
    echo '<h2>4. Template Loading Test</h2>';
    try {
        ob_start();
        $test_header = $theme_dir . '/header.php';
        if (file_exists($test_header)) {
            // Simulate WordPress globals
            global $wp_query, $wp;
            if (!isset($wp_query)) {
                $wp_query = new stdClass();
            }
            if (!isset($wp)) {
                $wp = new stdClass();
            }
            
            // Try to include header (but catch errors)
            $old_error_handler = set_error_handler(function($errno, $errstr, $errfile, $errline) {
                throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
            });
            
            try {
                include($test_header);
                echo '<div class="success">✓ header.php loads without fatal errors</div>';
            } catch (Exception $e) {
                echo '<div class="error">✗ header.php error: ' . htmlspecialchars($e->getMessage()) . '</div>';
                $errors[] = 'header.php';
            }
            
            restore_error_handler();
        }
        ob_end_clean();
    } catch (Exception $e) {
        echo '<div class="error">✗ Template test failed: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    
    // Check WordPress theme support
    echo '<h2>5. WordPress Theme Support</h2>';
    if (function_exists('get_theme_support')) {
        echo '<div class="success">✓ WordPress functions available</div>';
    } else {
        echo '<div class="error">✗ WordPress functions not available</div>';
    }
    
    // Summary
    echo '<h2>6. Summary</h2>';
    if (empty($errors)) {
        echo '<div class="success"><strong>✓ All checks passed! No critical errors found.</strong></div>';
    } else {
        echo '<div class="error"><strong>✗ Found ' . count($errors) . ' error(s):</strong></div>';
        foreach ($errors as $error) {
            echo '<div class="error">  - ' . $error . '</div>';
        }
    }
    
    // Show PHP error log location
    echo '<h2>7. Debug Information</h2>';
    echo '<div>PHP Version: ' . phpversion() . '</div>';
    echo '<div>WordPress Version: ' . get_bloginfo('version') . '</div>';
    echo '<div>Theme Directory: ' . $theme_dir . '</div>';
    
    $error_log = ini_get('error_log');
    if ($error_log) {
        echo '<div>Error Log: ' . $error_log . '</div>';
    } else {
        echo '<div>Error Log: (check server default location)</div>';
    }
    
    // Try to read last few lines of error log if possible
    if ($error_log && file_exists($error_log) && is_readable($error_log)) {
        $lines = file($error_log);
        $last_lines = array_slice($lines, -10);
        echo '<h3>Last 10 Error Log Entries:</h3>';
        echo '<pre>' . htmlspecialchars(implode('', $last_lines)) . '</pre>';
    }
    ?>
</body>
</html>

