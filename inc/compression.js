/**
 * Media Compression JavaScript
 */

(function($) {
    'use strict';
    
    var selectedMedia = [];
    var compressionSettings = {};
    
    $(document).ready(function() {
        // Quality sliders
        $('#jpeg-quality').on('input', function() {
            $('#jpeg-quality-value').text($(this).val());
        });
        
        $('#png-compression').on('input', function() {
            $('#png-compression-value').text($(this).val());
        });
        
        $('#video-quality').on('input', function() {
            $('#video-quality-value').text($(this).val());
        });
        
        // Compression mode
        $('#compression-mode').on('change', function() {
            if ($(this).val() === 'large') {
                $('#size-threshold').show();
            } else {
                $('#size-threshold').hide();
            }
        });
        
        // Video options toggle
        $('#compress-videos').on('change', function() {
            if ($(this).is(':checked')) {
                $('#video-options').slideDown();
            } else {
                $('#video-options').slideUp();
            }
        });
        
        // Select Media
        $('#select-media-batch').on('click', function() {
            var mediaUploader = wp.media({
                title: 'Select Media to Compress',
                button: { text: 'Select' },
                library: { type: ['image', 'video'] },
                multiple: true
            });
            
            mediaUploader.on('select', function() {
                var attachments = mediaUploader.state().get('selection').toJSON();
                attachments.forEach(function(att) {
                    if (selectedMedia.findIndex(function(m) { return m.id === att.id; }) === -1) {
                        selectedMedia.push({
                            id: att.id,
                            title: att.filename || att.title,
                            url: att.url,
                            type: att.type,
                            size: att.filesizeInBytes || 0
                        });
                    }
                });
                updateMediaList();
            });
            
            mediaUploader.open();
        });
        
        // Select All Media
        $('#select-all-media').on('click', function() {
            $.ajax({
                url: clarkesCompression.ajax_url,
                type: 'POST',
                data: {
                    action: 'clarkes_get_media_list',
                    nonce: clarkesCompression.nonce
                },
                success: function(response) {
                    if (response.success) {
                        selectedMedia = response.data.media;
                        updateMediaList();
                    }
                }
            });
        });
        
        // Clear Selection
        $('#clear-selection').on('click', function() {
            selectedMedia = [];
            updateMediaList();
        });
        
        // Start Compression
        $('#start-compression').on('click', function() {
            if (selectedMedia.length === 0) {
                alert('Please select media files to compress');
                return;
            }
            
            if (!confirm('This will compress ' + selectedMedia.length + ' file(s). Continue?')) {
                return;
            }
            
            compressionSettings = {
                compress_images: $('#compress-images').is(':checked'),
                compress_videos: $('#compress-videos').is(':checked'),
                jpeg_quality: parseInt($('#jpeg-quality').val()),
                png_compression: parseInt($('#png-compression').val()),
                convert_to_webp: $('#convert-to-webp').is(':checked'),
                resize_large_images: $('#resize-large-images').is(':checked'),
                max_image_width: parseInt($('#max-image-width').val()),
                video_quality: parseInt($('#video-quality').val()),
                resize_videos: $('#resize-videos').is(':checked'),
                max_video_width: parseInt($('#max-video-width').val()),
                backup: $('#backup-originals').is(':checked')
            };
            
            startCompression();
        });
    });
    
    function updateMediaList() {
        var $list = $('#selected-media-list');
        $list.empty();
        
        if (selectedMedia.length === 0) {
            $list.html('<p class="description">No media selected. Click "Select Media Files" to choose files to compress.</p>');
            $('#start-compression').prop('disabled', true);
            return;
        }
        
        $('#start-compression').prop('disabled', false);
        
        var totalSize = 0;
        selectedMedia.forEach(function(media) {
            totalSize += media.size || 0;
            var $item = $('<div class="media-item">');
            $item.html(
                '<div class="media-item-preview">' +
                (media.type.indexOf('image') !== -1 ? '<img src="' + media.url + '" />' : '<span class="dashicons dashicons-video-alt3"></span>') +
                '</div>' +
                '<div class="media-item-info">' +
                '<strong>' + media.title + '</strong><br>' +
                '<span class="media-size">' + formatBytes(media.size) + '</span>' +
                '</div>' +
                '<button type="button" class="button remove-media" data-id="' + media.id + '">Remove</button>'
            );
            $list.append($item);
        });
        
        // Update total
        $list.prepend('<div class="media-total"><strong>Total: ' + selectedMedia.length + ' files (' + formatBytes(totalSize) + ')</strong></div>');
        
        // Remove buttons
        $('.remove-media').on('click', function() {
            var id = parseInt($(this).data('id'));
            selectedMedia = selectedMedia.filter(function(m) { return m.id !== id; });
            updateMediaList();
        });
    }
    
    function startCompression() {
        $('#compression-progress').show();
        $('#compression-results').hide();
        
        var total = selectedMedia.length;
        var processed = 0;
        var totalSavings = 0;
        var results = [];
        
        function compressNext() {
            if (processed >= total) {
                // Done
                $('#compression-status').text('Compression complete!');
                showResults(results, totalSavings);
                return;
            }
            
            var media = selectedMedia[processed];
            var progress = Math.round((processed / total) * 100);
            
            $('#compression-progress-bar').css('width', progress + '%');
            $('#compression-status').text('Compressing: ' + media.title + ' (' + (processed + 1) + '/' + total + ')');
            
            $.ajax({
                url: clarkesCompression.ajax_url,
                type: 'POST',
                data: {
                    action: 'clarkes_compress_media',
                    nonce: clarkesCompression.nonce,
                    media_id: media.id,
                    settings: compressionSettings
                },
                success: function(response) {
                    if (response.success) {
                        results.push({
                            media: media,
                            result: response.data
                        });
                        totalSavings += response.data.savings || 0;
                    } else {
                        results.push({
                            media: media,
                            error: response.data.message || 'Compression failed'
                        });
                    }
                    
                    processed++;
                    compressNext();
                },
                error: function() {
                    results.push({
                        media: media,
                        error: 'AJAX error'
                    });
                    processed++;
                    compressNext();
                }
            });
        }
        
        compressNext();
    }
    
    function showResults(results, totalSavings) {
        var $results = $('#results-content');
        $results.empty();
        
        var successCount = 0;
        var failCount = 0;
        
        results.forEach(function(item) {
            var $result = $('<div class="result-item">');
            
            if (item.error) {
                failCount++;
                $result.addClass('result-error');
                $result.html(
                    '<strong>' + item.media.title + '</strong><br>' +
                    '<span class="error">' + item.error + '</span>'
                );
            } else {
                successCount++;
                $result.addClass('result-success');
                $result.html(
                    '<strong>' + item.media.title + '</strong><br>' +
                    '<span class="savings">Saved: ' + formatBytes(item.result.savings) + ' (' + item.result.savings_percent + '%)</span>'
                );
            }
            
            $results.append($result);
        });
        
        $results.prepend(
            '<div class="results-summary">' +
            '<h3>Summary</h3>' +
            '<p>Success: ' + successCount + ' | Failed: ' + failCount + '</p>' +
            '<p><strong>Total Savings: ' + formatBytes(totalSavings) + '</strong></p>' +
            '</div>'
        );
        
        $('#savings-amount').text(formatBytes(totalSavings));
        $('#compression-results').slideDown();
    }
    
    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        var k = 1024;
        var sizes = ['Bytes', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
    
})(jQuery);

