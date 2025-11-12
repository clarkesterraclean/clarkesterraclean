/**
 * SEO Module JavaScript
 */

(function($) {
    'use strict';
    
    // Wait for DOM to be ready
    $(document).ready(function() {
        
    // Crawl Site - Use delegated event
    $(document).on('click', '#btn-crawl-site', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).text('Crawling...');
        
        $('#seo-progress').show();
        updateProgress(0, 'Starting site crawl...');
        
        $.ajax({
            url: clarkesSEO.ajax_url,
            type: 'POST',
            data: {
                action: 'clarkes_crawl_site',
                nonce: clarkesSEO.nonce,
                auto_fix: 'false'
            },
            success: function(response) {
                if (response.success) {
                    updateProgress(100, 'Crawl complete!');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    alert('Error: ' + response.data.message);
                    $btn.prop('disabled', false).text('🕷️ Crawl & Analyze Site');
                }
            },
            error: function() {
                alert('An error occurred during site crawl');
                $btn.prop('disabled', false).text('🕷️ Crawl & Analyze Site');
            }
        });
    });
    
    // Crawl and Auto-Fix - Use delegated event
    $(document).on('click', '#btn-crawl-and-fix', function() {
        if (!confirm('This will automatically fix SEO issues across all pages. This may take a while. Continue?')) {
            return;
        }
        
        var $btn = $(this);
        $btn.prop('disabled', true).text('Crawling & Fixing...');
        
        $('#seo-progress').show();
        updateProgress(0, 'Starting crawl and auto-fix...');
        
        $.ajax({
            url: clarkesSEO.ajax_url,
            type: 'POST',
            data: {
                action: 'clarkes_crawl_site',
                nonce: clarkesSEO.nonce,
                auto_fix: 'true'
            },
            success: function(response) {
                if (response.success) {
                    var message = response.data.message;
                    if (response.data.fixed > 0) {
                        message += '\n\nFixed ' + response.data.fixed + ' pages automatically!';
                    }
                    updateProgress(100, message);
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                } else {
                    alert('Error: ' + response.data.message);
                    $btn.prop('disabled', false).text('⚡ Crawl & Auto-Fix All Issues');
                }
            },
            error: function() {
                alert('An error occurred during crawl and fix');
                $btn.prop('disabled', false).text('⚡ Crawl & Auto-Fix All Issues');
            }
        });
    });
    
    // Generate Sitemap - Use delegated event
    $(document).on('click', '#btn-generate-sitemap', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).text('Generating...');
        
        $.ajax({
            url: clarkesSEO.ajax_url,
            type: 'POST',
            data: {
                action: 'clarkes_generate_sitemap',
                nonce: clarkesSEO.nonce
            },
            success: function(response) {
                if (response.success) {
                    alert('Sitemap generated successfully!\n\nURL: ' + response.data.url);
                } else {
                    alert('Error: ' + response.data.message);
                }
                $btn.prop('disabled', false).text('🗺️ Generate Sitemap');
            },
            error: function() {
                alert('An error occurred');
                $btn.prop('disabled', false).text('🗺️ Generate Sitemap');
            }
        });
    });
    
    // Optimize All Pages - Use delegated event
    $(document).on('click', '#btn-optimize-all', function() {
        if (!confirm('This will optimize all pages. This may take a while. Continue?')) {
            return;
        }
        
        var $btn = $(this);
        $btn.prop('disabled', true).text('Optimizing...');
        
        $('#seo-progress').show();
        updateProgress(0, 'Starting optimization...');
        
        // Get all page IDs
        $.ajax({
            url: clarkesSEO.ajax_url,
            type: 'POST',
            data: {
                action: 'clarkes_get_all_pages',
                nonce: clarkesSEO.nonce
            },
            success: function(response) {
                if (response.success) {
                    optimizePages(response.data.ids, 0);
                }
            }
        });
    });
    
    function optimizePages(ids, index) {
        if (index >= ids.length) {
            updateProgress(100, 'All pages optimized!');
            setTimeout(function() {
                location.reload();
            }, 2000);
            return;
        }
        
        var progress = Math.round((index / ids.length) * 100);
        updateProgress(progress, 'Optimizing page ' + (index + 1) + ' of ' + ids.length);
        
        $.ajax({
            url: clarkesSEO.ajax_url,
            type: 'POST',
            data: {
                action: 'clarkes_optimize_page',
                nonce: clarkesSEO.nonce,
                post_id: ids[index]
            },
            success: function() {
                optimizePages(ids, index + 1);
            },
            error: function() {
                optimizePages(ids, index + 1); // Continue even on error
            }
        });
    }
    
    // Optimize Single Page - Use delegated event
    $(document).on('click', '.optimize-page-btn', function() {
        var $btn = $(this);
        var postId = $btn.data('id');
        
        $btn.prop('disabled', true).text('Optimizing...');
        
        $.ajax({
            url: clarkesSEO.ajax_url,
            type: 'POST',
            data: {
                action: 'clarkes_optimize_page',
                nonce: clarkesSEO.nonce,
                post_id: postId
            },
            success: function(response) {
                if (response.success) {
                    alert('Page optimized! New SEO score: ' + response.data.score + '/100');
                    location.reload();
                } else {
                    alert('Error: ' + response.data.message);
                    $btn.prop('disabled', false).text('Optimize');
                }
            },
            error: function() {
                alert('An error occurred');
                $btn.prop('disabled', false).text('Optimize');
            }
        });
    });
    
    // Update Progress
    function updateProgress(percent, text) {
        $('#seo-progress-bar').css('width', percent + '%');
        $('#seo-progress-text').text(text);
    }
    
    // Start Analysis - Use delegated event
    $(document).on('click', '#btn-start-analysis', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).text('Analyzing...');
        
        $('#analysis-results').show().html('<p>Analysis in progress...</p>');
        
        $.ajax({
            url: clarkesSEO.ajax_url,
            type: 'POST',
            data: {
                action: 'clarkes_crawl_site',
                nonce: clarkesSEO.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#analysis-results').html(
                        '<h2>Analysis Complete</h2>' +
                        '<p>Analyzed: ' + response.data.analyzed + ' pages</p>' +
                        '<p>Average SEO Score: ' + response.data.score + '/100</p>' +
                        '<p>Issues Found: ' + response.data.issues + '</p>'
                    );
                }
                $btn.prop('disabled', false).text('Start Full Site Analysis');
            }
        });
    });
    
    }); // End document.ready
    
})(jQuery);

