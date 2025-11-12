/**
 * Comprehensive Media Editor JavaScript
 */

(function($) {
    'use strict';
    
    var canvas, fabricCanvas, cropper, currentMediaId, currentMediaType, currentImage;
    var filters = {
        brightness: 100,
        contrast: 100,
        saturation: 100,
        blur: 0
    };
    
    // Initialize
    $(document).ready(function() {
        // Media Selection
        $('#select-media-btn').on('click', function() {
            var mediaUploader = wp.media({
                title: 'Select Media to Edit',
                button: { text: 'Select' },
                library: { type: ['image', 'video'] },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                window.location.href = '?page=clarkes-media-editor&media_id=' + attachment.id;
            });
            
            mediaUploader.open();
        });
        
        // Initialize editor if media is loaded
        if ($('.media-editor-interface').length) {
            currentMediaId = $('.media-editor-interface').data('media-id');
            currentMediaType = $('.media-editor-interface').data('media-type');
            initializeEditor();
        }
    });
    
    function initializeEditor() {
        if (currentMediaType === 'image') {
            initializeImageEditor();
        } else {
            initializeVideoEditor();
        }
    }
    
    function initializeImageEditor() {
        var img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function() {
            canvas = document.getElementById('editor-canvas');
            canvas.width = img.width;
            canvas.height = img.height;
            
            fabricCanvas = new fabric.Canvas('editor-canvas', {
                width: Math.min(img.width, 1200),
                height: Math.min(img.height, 800),
            });
            
            fabric.Image.fromURL(img.src, function(fabricImg) {
                var scale = Math.min(
                    fabricCanvas.width / fabricImg.width,
                    fabricCanvas.height / fabricImg.height
                );
                fabricImg.scale(scale);
                fabricCanvas.setBackgroundImage(fabricImg, fabricCanvas.renderAll.bind(fabricCanvas));
                currentImage = fabricImg;
            });
        };
        
        var mediaUrl = $('.media-editor-interface').closest('.wrap').find('img, video').attr('src') || 
                      $('.media-editor-interface').closest('.wrap').find('video').attr('src');
        if (mediaUrl) {
            img.src = mediaUrl;
        } else {
            // Get from AJAX
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'clarkes_get_media',
                    nonce: clarkesMediaEditor.nonce,
                    media_id: currentMediaId
                },
                success: function(response) {
                    if (response.success) {
                        img.src = response.data.url;
                    }
                }
            });
        }
    }
    
    function initializeVideoEditor() {
        var video = document.getElementById('editor-video');
        if (video) {
            // Video editor initialization
        }
    }
    
    // Toolbar Buttons
    $('#btn-crop').on('click', function() {
        togglePanel('crop-controls');
        if (currentMediaType === 'image' && fabricCanvas) {
            // Initialize cropper
        }
    });
    
    $('#btn-resize').on('click', function() {
        togglePanel('resize-controls');
    });
    
    $('#btn-filters').on('click', function() {
        togglePanel('filter-controls');
    });
    
    $('#btn-text-overlay').on('click', function() {
        togglePanel('text-controls');
    });
    
    $('#btn-markup').on('click', function() {
        togglePanel('markup-controls');
    });
    
    $('#btn-ai-seo').on('click', function() {
        togglePanel('ai-seo-panel');
    });
    
    // Filter Controls
    $('#filter-brightness, #filter-contrast, #filter-saturation, #filter-blur').on('input', function() {
        var id = $(this).attr('id').replace('filter-', '');
        var value = $(this).val();
        $('#' + id + '-value').text(value);
        filters[id] = parseInt(value);
    });
    
    $('#apply-filters').on('click', function() {
        if (fabricCanvas && currentImage) {
            var filterString = 'brightness(' + (filters.brightness / 100) + ') ' +
                             'contrast(' + (filters.contrast / 100) + ') ' +
                             'saturate(' + (filters.saturation / 100) + ') ' +
                             'blur(' + filters.blur + 'px)';
            currentImage.filters = [];
            fabricCanvas.renderAll();
        }
    });
    
    $('#reset-filters').on('click', function() {
        filters = { brightness: 100, contrast: 100, saturation: 100, blur: 0 };
        $('#filter-brightness').val(100).trigger('input');
        $('#filter-contrast').val(100).trigger('input');
        $('#filter-saturation').val(100).trigger('input');
        $('#filter-blur').val(0).trigger('input');
    });
    
    // Resize
    $('#apply-resize').on('click', function() {
        var width = parseInt($('#resize-width').val());
        var height = parseInt($('#resize-height').val());
        var maintainAspect = $('#resize-maintain-aspect').is(':checked');
        
        if (fabricCanvas && currentImage) {
            if (maintainAspect) {
                var aspect = currentImage.width / currentImage.height;
                if (width) height = width / aspect;
                else if (height) width = height * aspect;
            }
            
            fabricCanvas.setDimensions({ width: width, height: height });
            currentImage.scaleToWidth(width);
            fabricCanvas.renderAll();
        }
    });
    
    // Text Overlay
    $('#add-text').on('click', function() {
        if (fabricCanvas) {
            var text = new fabric.Text($('#text-content').val(), {
                left: parseInt($('#text-x').val()),
                top: parseInt($('#text-y').val()),
                fontSize: parseInt($('#text-size').val()),
                fill: $('#text-color').val(),
            });
            fabricCanvas.add(text);
            fabricCanvas.renderAll();
        }
    });
    
    // Markup Tools
    $('#btn-arrow, #btn-rectangle, #btn-circle, #btn-line').on('click', function() {
        var tool = $(this).attr('id').replace('btn-', '');
        // Add markup tool functionality
    });
    
    // AI SEO
    $('#generate-ai-seo').on('click', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).text('Generating...');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'clarkes_generate_ai_seo',
                nonce: clarkesMediaEditor.nonce,
                media_id: currentMediaId,
                media_type: currentMediaType
            },
            success: function(response) {
                $btn.prop('disabled', false).text('Generate SEO Info');
                if (response.success) {
                    $('#ai-title').val(response.data.title);
                    $('#ai-alt').val(response.data.alt);
                    $('#ai-description').val(response.data.description);
                    $('#ai-keywords').val(response.data.keywords);
                    $('#ai-results').show();
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('Generate SEO Info');
            }
        });
    });
    
    $('#apply-ai-seo').on('click', function() {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'clarkes_apply_ai_seo',
                nonce: clarkesMediaEditor.nonce,
                media_id: currentMediaId,
                title: $('#ai-title').val(),
                alt: $('#ai-alt').val(),
                description: $('#ai-description').val(),
                keywords: $('#ai-keywords').val()
            },
            success: function(response) {
                if (response.success) {
                    alert('SEO information updated successfully!');
                }
            }
        });
    });
    
    // Video Mute
    $('#btn-mute-video').on('click', function() {
        var video = document.getElementById('editor-video');
        if (video) {
            video.muted = !video.muted;
            $(this).text(video.muted ? 'Unmute Audio' : 'Mute Audio');
        }
    });
    
    // Auto Orient
    $('#btn-auto-orient').on('click', function() {
        if (currentMediaType === 'video') {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'clarkes_process_video',
                    nonce: clarkesMediaEditor.nonce,
                    media_id: currentMediaId,
                    operation: 'auto_orient'
                },
                success: function(response) {
                    alert(response.data.message);
                }
            });
        }
    });
    
    // Save
    $('#btn-save').on('click', function() {
        if (fabricCanvas) {
            var dataURL = fabricCanvas.toDataURL('image/png');
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'clarkes_save_edited_media',
                    nonce: clarkesMediaEditor.nonce,
                    media_id: currentMediaId,
                    image_data: dataURL,
                    operations: filters
                },
                success: function(response) {
                    if (response.success) {
                        alert('Media saved successfully! New version created.');
                        window.location.reload();
                    }
                }
            });
        }
    });
    
    // Reset
    $('#btn-reset').on('click', function() {
        if (confirm('Reset all changes?')) {
            window.location.reload();
        }
    });
    
    function togglePanel(panelId) {
        $('.control-panel').hide();
        $('#' + panelId).show();
    }
    
})(jQuery);

