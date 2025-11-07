<?php
/**
 * File Verification Script
 * Check if all required files exist on the server
 * URL: https://clarkesterraclean.co.uk/wp-content/themes/clarkes-terraclean/verify-files.php
 */

// Load WordPress
require_once('../../../wp-load.php');

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>File Verification</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .pass { color: green; font-weight: bold; }
        .fail { color: red; font-weight: bold; }
        h2 { margin-top: 30px; }
        ul { list-style: none; padding-left: 0; }
        li { padding: 5px 0; }
    </style>
</head>
<body>
    <h1>Clarke's Theme - File Verification</h1>
    
    <h2>Theme Directory</h2>
    <p><?php echo get_template_directory(); ?></p>
    
    <h2>Required Files</h2>
    <ul>
        <?php
        $theme_dir = get_template_directory();
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
        
        foreach ($required_files as $file) {
            $path = $theme_dir . '/' . $file;
            $exists = file_exists($path);
            $readable = $exists ? is_readable($path) : false;
            $class = ($exists && $readable) ? 'pass' : 'fail';
            $status = ($exists && $readable) ? '✓' : '✗';
            $size = $exists ? ' (' . filesize($path) . ' bytes)' : '';
            echo "<li class='$class'>$status $file$size</li>";
        }
        ?>
    </ul>
    
    <h2>Directory Structure</h2>
    <ul>
        <?php
        $dirs = array('inc', 'dist', 'js', 'src');
        foreach ($dirs as $dir) {
            $path = $theme_dir . '/' . $dir;
            $exists = is_dir($path);
            $class = $exists ? 'pass' : 'fail';
            $status = $exists ? '✓' : '✗';
            echo "<li class='$class'>$status $dir/</li>";
        }
        ?>
    </ul>
    
    <h2>Try Loading functions.php</h2>
    <pre>
    <?php
    $func_file = $theme_dir . '/functions.php';
    if (file_exists($func_file)) {
        echo "File exists: YES\n";
        echo "File readable: " . (is_readable($func_file) ? 'YES' : 'NO') . "\n";
        echo "File size: " . filesize($func_file) . " bytes\n";
        
        // Try to read first few lines
        $handle = fopen($func_file, 'r');
        if ($handle) {
            echo "\nFirst 5 lines:\n";
            for ($i = 0; $i < 5; $i++) {
                $line = fgets($handle);
                if ($line === false) break;
                echo htmlspecialchars($line);
            }
            fclose($handle);
        }
    } else {
        echo "File exists: NO\n";
    }
    ?>
    </pre>
</body>
</html>

