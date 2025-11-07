<?php
/**
 * PATH TEST FILE
 * This file tests if the theme path is correct
 * Visit: https://clarkesterraclean.co.uk/wp-content/themes/clarkes-terraclean/test-path.php
 */

// Try to load WordPress
$wp_load_paths = array(
    '../../../wp-load.php',
    '../../../../wp-load.php',
    '../../../../../wp-load.php',
    dirname(dirname(dirname(__FILE__))) . '/wp-load.php',
);

$wp_loaded = false;
foreach ($wp_load_paths as $path) {
    if (file_exists($path)) {
        require_once($path);
        $wp_loaded = true;
        break;
    }
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Path Test</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .pass { color: green; font-weight: bold; }
        .fail { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Theme Path Verification</h1>
    
    <h2>Current File Location</h2>
    <p><strong>This file:</strong> <?php echo __FILE__; ?></p>
    <p><strong>Directory:</strong> <?php echo __DIR__; ?></p>
    
    <?php if ($wp_loaded): ?>
        <p class="pass">✓ WordPress loaded successfully!</p>
        <h2>WordPress Paths</h2>
        <p><strong>Theme Directory:</strong> <?php echo get_template_directory(); ?></p>
        <p><strong>Stylesheet Directory:</strong> <?php echo get_stylesheet_directory(); ?></p>
        <p><strong>Theme URI:</strong> <?php echo get_template_directory_uri(); ?></p>
        
        <h2>File Check</h2>
        <ul>
            <?php
            $theme_dir = get_template_directory();
            $files = array(
                'functions.php',
                'style.css',
                'header.php',
                'footer.php',
                'inc/customizer.php',
                'inc/reviews.php',
                'inc/whatsapp.php',
                'dist/style.css',
                'js/theme.js'
            );
            
            foreach ($files as $file) {
                $path = $theme_dir . '/' . $file;
                $exists = file_exists($path);
                $class = $exists ? 'pass' : 'fail';
                $status = $exists ? '✓' : '✗';
                echo "<li class='$class'>$status $file</li>";
            }
            ?>
        </ul>
    <?php else: ?>
        <p class="fail">✗ WordPress NOT loaded</p>
        <p>Tried paths:</p>
        <ul>
            <?php foreach ($wp_load_paths as $path): ?>
                <li><?php echo $path; ?> - <?php echo file_exists($path) ? 'EXISTS' : 'NOT FOUND'; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
    <h2>Directory Listing</h2>
    <pre><?php
    $dir = __DIR__;
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $path = $dir . '/' . $file;
            $type = is_dir($path) ? 'DIR' : 'FILE';
            $size = is_file($path) ? ' (' . filesize($path) . ' bytes)' : '';
            echo "$type: $file$size\n";
        }
    }
    ?></pre>
    
    <?php if (is_dir(__DIR__ . '/inc')): ?>
        <h2>inc/ Directory</h2>
        <pre><?php
        $inc_dir = __DIR__ . '/inc';
        $inc_files = scandir($inc_dir);
        foreach ($inc_files as $file) {
            if ($file != '.' && $file != '..') {
                $path = $inc_dir . '/' . $file;
                $size = is_file($path) ? ' (' . filesize($path) . ' bytes)' : '';
                echo "FILE: $file$size\n";
            }
        }
        ?></pre>
    <?php else: ?>
        <p class="fail">✗ inc/ directory NOT FOUND</p>
    <?php endif; ?>
</body>
</html>

