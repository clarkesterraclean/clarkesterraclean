/**
 * SEO Module JavaScript
 */

(function($) {
    'use strict';
    
    // Activity Log Management
    var activityLog = [];
    var maxLogEntries = 100;
    
    function addLogEntry(message, type) {
        type = type || 'info';
        var timestamp = new Date().toLocaleTimeString();
        var icon = '';
        var color = '#e5e7eb';
        
        switch(type) {
            case 'success':
                icon = '✓';
                color = '#10b981';
                break;
            case 'error':
                icon = '✗';
                color = '#ef4444';
                break;
            case 'warning':
                icon = '⚠';
                color = '#f59e0b';
                break;
            case 'info':
                icon = 'ℹ';
                color = '#3b82f6';
                break;
            case 'processing':
                icon = '⟳';
                color = '#8b5cf6';
                break;
        }
        
        activityLog.push({
            timestamp: timestamp,
            message: message,
            type: type,
            icon: icon,
            color: color
        });
        
        // Keep only last maxLogEntries
        if (activityLog.length > maxLogEntries) {
            activityLog.shift();
        }
        
        updateLogDisplay();
    }
    
    function updateLogDisplay() {
        var $logContent = $('#seo-log-content');
        var html = '';
        
        if (activityLog.length === 0) {
            html = '<div style="color: #9ca3af; font-style: italic;">' + 'Activity log will appear here...' + '</div>';
        } else {
            activityLog.forEach(function(entry) {
                html += '<div style="margin-bottom: 4px;">';
                html += '<span style="color: #6b7280;">[' + entry.timestamp + ']</span> ';
                html += '<span style="color: ' + entry.color + '; font-weight: bold;">' + entry.icon + '</span> ';
                html += '<span style="color: ' + entry.color + ';">' + escapeHtml(entry.message) + '</span>';
                html += '</div>';
            });
        }
        
        $logContent.html(html);
        
        // Auto-scroll to bottom
        var $logContainer = $('#seo-activity-log');
        $logContainer.scrollTop($logContainer[0].scrollHeight);
    }
    
    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
    
    function clearLog() {
        activityLog = [];
        updateLogDisplay();
        addLogEntry('Log cleared', 'info');
    }
    
    // Clear/Refresh Log Buttons
    $(document).on('click', '#btn-clear-log', function() {
        clearLog();
    });
    
    $(document).on('click', '#btn-refresh-log', function() {
        updateLogDisplay();
        addLogEntry('Log refreshed', 'info');
    });
    
    // Wait for DOM to be ready
    $(document).ready(function() {
        
    // Crawl Site - Use delegated event
    $(document).on('click', '#btn-crawl-site', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).text('Crawling...');
        
        $('#seo-progress').show();
        $('#btn-clear-log, #btn-refresh-log').show();
        clearLog();
        addLogEntry('Starting site crawl and analysis...', 'processing');
        updateProgress(0, 'Starting site crawl...', '0 / 0 pages');
        
        $.ajax({
            url: clarkesSEO.ajax_url,
            type: 'POST',
            data: {
                action: 'clarkes_crawl_site',
                nonce: clarkesSEO.nonce,
                auto_fix: 'false'
            },
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        var percentComplete = (e.loaded / e.total) * 100;
                        updateProgress(Math.min(percentComplete, 90), 'Uploading data...', '');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                if (response.success) {
                    addLogEntry('Crawl completed successfully', 'success');
                    addLogEntry('Analyzed ' + response.data.analyzed + ' pages', 'info');
                    addLogEntry('Average SEO score: ' + response.data.score + '/100', 'info');
                    addLogEntry('Found ' + response.data.issues + ' issues', response.data.issues > 0 ? 'warning' : 'success');
                    updateProgress(100, 'Crawl complete!', response.data.analyzed + ' pages analyzed');
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                } else {
                    addLogEntry('Error: ' + response.data.message, 'error');
                    alert('Error: ' + response.data.message);
                    $btn.prop('disabled', false).text('🕷️ Crawl & Analyze Site');
                }
            },
            error: function(xhr, status, error) {
                addLogEntry('AJAX error occurred: ' + error, 'error');
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
        $('#btn-clear-log, #btn-refresh-log').show();
        clearLog();
        addLogEntry('Starting crawl and auto-fix process...', 'processing');
        updateProgress(0, 'Starting crawl and auto-fix...', '0 / 0 pages');
        
        $.ajax({
            url: clarkesSEO.ajax_url,
            type: 'POST',
            data: {
                action: 'clarkes_crawl_site',
                nonce: clarkesSEO.nonce,
                auto_fix: 'true'
            },
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        var percentComplete = (e.loaded / e.total) * 100;
                        updateProgress(Math.min(percentComplete, 90), 'Processing...', '');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                if (response.success) {
                    addLogEntry('Crawl and auto-fix completed', 'success');
                    addLogEntry('Analyzed ' + response.data.analyzed + ' pages', 'info');
                    addLogEntry('Average SEO score: ' + response.data.score + '/100', 'info');
                    if (response.data.fixed > 0) {
                        addLogEntry('Auto-fixed ' + response.data.fixed + ' pages', 'success');
                    }
                    if (response.data.issues > 0) {
                        addLogEntry('Remaining issues: ' + response.data.issues, 'warning');
                    }
                    var message = response.data.message;
                    if (response.data.fixed > 0) {
                        message += ' Fixed ' + response.data.fixed + ' pages automatically!';
                    }
                    updateProgress(100, message, response.data.analyzed + ' pages processed');
                    setTimeout(function() {
                        location.reload();
                    }, 4000);
                } else {
                    addLogEntry('Error: ' + response.data.message, 'error');
                    alert('Error: ' + response.data.message);
                    $btn.prop('disabled', false).text('⚡ Crawl & Auto-Fix All Issues');
                }
            },
            error: function(xhr, status, error) {
                addLogEntry('AJAX error occurred: ' + error, 'error');
                alert('An error occurred during crawl and fix');
                $btn.prop('disabled', false).text('⚡ Crawl & Auto-Fix All Issues');
            }
        });
    });
    
    // Generate Sitemap - Use delegated event
    $(document).on('click', '#btn-generate-sitemap', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).text('Generating...');
        
        $('#seo-progress').show();
        $('#btn-clear-log, #btn-refresh-log').show();
        addLogEntry('Generating XML sitemap...', 'processing');
        updateProgress(50, 'Generating sitemap...', '');
        
        $.ajax({
            url: clarkesSEO.ajax_url,
            type: 'POST',
            data: {
                action: 'clarkes_generate_sitemap',
                nonce: clarkesSEO.nonce
            },
            success: function(response) {
                if (response.success) {
                    addLogEntry('Sitemap generated successfully', 'success');
                    addLogEntry('Sitemap URL: ' + response.data.url, 'info');
                    updateProgress(100, 'Sitemap generated!', '');
                    alert('Sitemap generated successfully!\n\nURL: ' + response.data.url);
                    setTimeout(function() {
                        $('#seo-progress').hide();
                    }, 3000);
                } else {
                    addLogEntry('Error: ' + response.data.message, 'error');
                    alert('Error: ' + response.data.message);
                }
                $btn.prop('disabled', false).text('🗺️ Generate Sitemap');
            },
            error: function(xhr, status, error) {
                addLogEntry('AJAX error: ' + error, 'error');
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
        $('#btn-clear-log, #btn-refresh-log').show();
        clearLog();
        addLogEntry('Starting optimization of all pages...', 'processing');
        updateProgress(0, 'Starting optimization...', '0 / 0 pages');
        
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
                    addLogEntry('Found ' + response.data.ids.length + ' pages to optimize', 'info');
                    optimizePages(response.data.ids, 0);
                } else {
                    addLogEntry('Error getting page list', 'error');
                    $btn.prop('disabled', false).text('⚡ Optimize All Pages');
                }
            },
            error: function() {
                addLogEntry('Error fetching pages', 'error');
                $btn.prop('disabled', false).text('⚡ Optimize All Pages');
            }
        });
    });
    
    function optimizePages(ids, index) {
        if (index >= ids.length) {
            addLogEntry('All pages optimized successfully!', 'success');
            updateProgress(100, 'All pages optimized!', ids.length + ' / ' + ids.length + ' pages');
            setTimeout(function() {
                location.reload();
            }, 3000);
            return;
        }
        
        var progress = Math.round((index / ids.length) * 100);
        var currentPage = index + 1;
        updateProgress(progress, 'Optimizing page ' + currentPage + ' of ' + ids.length, currentPage + ' / ' + ids.length + ' pages');
        
        // Add log entry every 5 pages or on first/last
        if (index === 0 || index % 5 === 0 || index === ids.length - 1) {
            addLogEntry('Optimizing page ' + currentPage + ' of ' + ids.length, 'processing');
        }
        
        $.ajax({
            url: clarkesSEO.ajax_url,
            type: 'POST',
            data: {
                action: 'clarkes_optimize_page',
                nonce: clarkesSEO.nonce,
                post_id: ids[index]
            },
            success: function(response) {
                if (response.success && response.data.fixed_issues > 0) {
                    addLogEntry('Page ' + currentPage + ' optimized (' + response.data.fixed_issues + ' issues fixed)', 'success');
                }
                optimizePages(ids, index + 1);
            },
            error: function() {
                addLogEntry('Error optimizing page ' + currentPage, 'error');
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
    function updateProgress(percent, text, stats) {
        percent = Math.max(0, Math.min(100, percent));
        $('#seo-progress-bar').css('width', percent + '%');
        $('#seo-progress-percent').text(Math.round(percent) + '%');
        $('#seo-progress-text').text(text || '');
        $('#seo-progress-stats').text(stats || '');
        
        // Add pulsing animation when processing
        if (percent > 0 && percent < 100) {
            $('#seo-progress-bar').css('animation', 'pulse 2s ease-in-out infinite');
        } else {
            $('#seo-progress-bar').css('animation', 'none');
        }
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

