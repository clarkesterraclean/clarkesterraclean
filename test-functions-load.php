<?php
/**
 * Test Loading functions.php
 * This will show any PHP errors when loading functions.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Enable error display
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Functions.php Load Test</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .pass { color: green; font-weight: bold; }
        .fail { color: red; font-weight: bold; }
        pre { background: white; padding: 10px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <h1>Testing functions.php Load</h1>
    
    <h2>Attempting to load functions.php...</h2>
    <pre>
    <?php
    $functions_file = get_template_directory() . '/functions.php';
    
    if (!file_exists($functions_file)) {
        echo "✗ ERROR: functions.php not found!\n";
        echo "Path: $functions_file\n";
    } else {
        echo "✓ functions.php found\n";
        echo "Path: $functions_file\n";
        echo "Size: " . filesize($functions_file) . " bytes\n\n";
        
        echo "Attempting to include...\n";
        echo str_repeat("-", 50) . "\n";
        
        // Capture any output or errors
        ob_start();
        $error_occurred = false;
        
        // Set error handler to catch errors
        set_error_handler(function($errno, $errstr, $errfile, $errline) use (&$error_occurred) {
            echo "ERROR: [$errno] $errstr in $errfile on line $errline\n";
            $error_occurred = true;
            return false;
        });
        
        try {
            include $functions_file;
            $output = ob_get_clean();
            
            if (!empty($output)) {
                echo "Output:\n$output\n";
            }
            
            if (!$error_occurred) {
                echo "✓ functions.php loaded successfully!\n\n";
                
                // Check if key functions exist
                echo "Checking functions:\n";
                $functions = array(
                    'clarkes_terraclean_setup',
                    'clarkes_terraclean_scripts',
                    'clarkes_sanitize_checkbox',
                    'clarkes_sanitize_hex_color',
                    'clarkes_customize_register',
                    'clarkes_register_review_post_type',
                    'clarkes_render_whatsapp_fab'
                );
                
                foreach ($functions as $func) {
                    if (function_exists($func)) {
                        echo "  ✓ $func() exists\n";
                    } else {
                        echo "  ✗ $func() NOT FOUND\n";
                    }
                }
            }
        } catch (Exception $e) {
            ob_end_clean();
            echo "✗ EXCEPTION: " . $e->getMessage() . "\n";
            echo "File: " . $e->getFile() . "\n";
            echo "Line: " . $e->getLine() . "\n";
        } catch (Error $e) {
            ob_end_clean();
            echo "✗ FATAL ERROR: " . $e->getMessage() . "\n";
            echo "File: " . $e->getFile() . "\n";
            echo "Line: " . $e->getLine() . "\n";
        }
        
        restore_error_handler();
    }
    ?>
    </pre>
</body>
</html>

