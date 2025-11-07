<?php
/**
 * Test Functions File
 * Access this to see if functions.php loads without errors
 * URL: https://clarkesterraclean.co.uk/wp-content/themes/clarkes-terraclean/test-functions.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Try to load functions.php
$functions_file = get_template_directory() . '/functions.php';

echo "<h1>Testing functions.php</h1>";
echo "<pre>";

if (file_exists($functions_file)) {
    echo "✓ functions.php exists\n";
    
    // Check syntax
    $output = array();
    $return = 0;
    exec("php -l " . escapeshellarg($functions_file) . " 2>&1", $output, $return);
    
    if ($return === 0) {
        echo "✓ PHP syntax is valid\n";
    } else {
        echo "✗ PHP syntax error:\n";
        echo implode("\n", $output) . "\n";
    }
    
    // Try to include it
    echo "\nAttempting to include functions.php...\n";
    try {
        ob_start();
        include $functions_file;
        $output = ob_get_clean();
        
        if (!empty($output)) {
            echo "Output: " . htmlspecialchars($output) . "\n";
        }
        echo "✓ functions.php included successfully\n";
        
        // Check if key functions exist
        $required_functions = array(
            'clarkes_terraclean_setup',
            'clarkes_terraclean_scripts',
            'clarkes_sanitize_checkbox',
            'clarkes_sanitize_hex_color'
        );
        
        echo "\nChecking required functions:\n";
        foreach ($required_functions as $func) {
            if (function_exists($func)) {
                echo "✓ $func() exists\n";
            } else {
                echo "✗ $func() NOT FOUND\n";
            }
        }
        
    } catch (Exception $e) {
        echo "✗ Error including functions.php: " . $e->getMessage() . "\n";
    } catch (Error $e) {
        echo "✗ Fatal error: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . "\n";
        echo "Line: " . $e->getLine() . "\n";
    }
    
} else {
    echo "✗ functions.php NOT FOUND\n";
}

echo "</pre>";

