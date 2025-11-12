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
                
                // Show preview immediately
                showMediaPreview(attachment);
                
                // Option to load editor or just preview
                if (!$('#media-preview-container').is(':visible')) {
                    $('#media-preview-container').slideDown();
                }
                
                // Update URL without reload
                var newUrl = window.location.pathname + '?page=clarkes-media-editor&media_id=' + attachment.id;
                window.history.pushState({}, '', newUrl);
                
                // Load editor
                currentMediaId = attachment.id;
                currentMediaType = attachment.type.indexOf('video') !== -1 ? 'video' : 'image';
                loadMediaForEditing(attachment.id);
            });
            
            mediaUploader.open();
        });
        
        // Change Media Button
        $('#change-media-btn').on('click', function() {
            $('#select-media-btn').trigger('click');
        });
        
        // Initialize editor if media is loaded via URL parameter
        if (getUrlParameter('media_id')) {
            var mediaId = getUrlParameter('media_id');
            currentMediaId = mediaId;
            
            // If editor interface exists, initialize it
            if ($('.media-editor-interface').length) {
                currentMediaType = $('.media-editor-interface').data('media-type');
                initializeEditor();
                initAllToolbarButtons();
            } else {
                // Load media and show preview with "Start Editing" button
                loadMediaForEditing(mediaId);
            }
        } else if ($('.media-editor-interface').length) {
            // Editor interface already exists (loaded via PHP)
            currentMediaId = $('.media-editor-interface').data('media-id');
            currentMediaType = $('.media-editor-interface').data('media-type');
            initializeEditor();
            initAllToolbarButtons();
        }
    });
    
    // Show Media Preview
    function showMediaPreview(attachment) {
        var previewHtml = '';
        
        if (attachment.type.indexOf('image') !== -1) {
            previewHtml = '<div class="media-preview-image">';
            previewHtml += '<img src="' + attachment.url + '" alt="' + (attachment.alt || '') + '" />';
            previewHtml += '<div class="media-info">';
            previewHtml += '<p><strong>File:</strong> ' + attachment.filename + '</p>';
            previewHtml += '<p><strong>Size:</strong> ' + (attachment.filesizeHumanReadable || formatBytes(attachment.filesizeInBytes || 0)) + '</p>';
            previewHtml += '<p><strong>Dimensions:</strong> ' + (attachment.width || 'N/A') + ' × ' + (attachment.height || 'N/A') + '</p>';
            previewHtml += '</div>';
            previewHtml += '</div>';
        } else if (attachment.type.indexOf('video') !== -1) {
            previewHtml = '<div class="media-preview-video">';
            previewHtml += '<video src="' + attachment.url + '" controls style="max-width: 100%; max-height: 400px;"></video>';
            previewHtml += '<div class="media-info">';
            previewHtml += '<p><strong>File:</strong> ' + attachment.filename + '</p>';
            previewHtml += '<p><strong>Size:</strong> ' + (attachment.filesizeHumanReadable || formatBytes(attachment.filesizeInBytes || 0)) + '</p>';
            previewHtml += '<p><strong>Duration:</strong> ' + (attachment.lengthFormatted || 'N/A') + '</p>';
            previewHtml += '</div>';
            previewHtml += '</div>';
        }
        
        $('#media-preview-content').html(previewHtml);
    }
    
    // Load Media for Editing
    function loadMediaForEditing(mediaId) {
        $.ajax({
            url: clarkesMediaEditor.ajax_url,
            type: 'POST',
            data: {
                action: 'clarkes_get_media',
                nonce: clarkesMediaEditor.nonce,
                media_id: mediaId
            },
            success: function(response) {
                if (response.success) {
                    var attachment = response.data;
                    showMediaPreview(attachment);
                    
                    // Show "Start Editing" button in preview
                    if (!$('#start-editing-btn').length) {
                        var startBtn = $('<button>', {
                            id: 'start-editing-btn',
                            class: 'button button-primary button-large',
                            style: 'margin-top: 20px;',
                            html: '<span class="dashicons dashicons-edit"></span> Start Editing'
                        });
                        startBtn.on('click', function() {
                            startEditing(attachment);
                        });
                        $('#media-preview-content').append(startBtn);
                    }
                }
            }
        });
    }
    
    // Start Editing - Create Full Editor Interface
    function startEditing(attachment) {
        // Hide preview container
        $('#media-preview-container').slideUp();
        
        // Remove start button
        $('#start-editing-btn').remove();
        
        // Create full editor interface
        if (!$('.media-editor-interface').length) {
            createFullEditorInterface(attachment);
        } else {
            // Update existing interface
            $('.media-editor-interface').attr('data-media-id', attachment.id);
            $('.media-editor-interface').attr('data-media-type', attachment.type.indexOf('video') !== -1 ? 'video' : 'image');
        }
        
        currentMediaId = attachment.id;
        currentMediaType = attachment.type.indexOf('video') !== -1 ? 'video' : 'image';
        
        // Initialize editor after a short delay to ensure DOM is ready
        setTimeout(function() {
            initializeEditor();
            initAllToolbarButtons();
        }, 100);
    }
    
    // Create Full Editor Interface with all controls
    function createFullEditorInterface(attachment) {
        // Reload page with media_id to get full PHP-rendered interface
        var currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('media_id', attachment.id);
        window.location.href = currentUrl.toString();
    }
    
    // Get URL Parameter
    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }
    
    // Format Bytes
    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        var k = 1024;
        var sizes = ['Bytes', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
    
    function initializeEditor() {
        if (currentMediaType === 'image') {
            initializeImageEditor();
        } else {
            initializeVideoEditor();
        }
    }
    
    function initializeImageEditor() {
        if (!currentMediaId) return;
        
        $.ajax({
            url: clarkesMediaEditor.ajax_url,
            type: 'POST',
            data: {
                action: 'clarkes_get_media',
                nonce: clarkesMediaEditor.nonce,
                media_id: currentMediaId
            },
            success: function(response) {
                if (response.success) {
                    var img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload = function() {
                        canvas = document.getElementById('editor-canvas');
                        if (!canvas) {
                            console.error('Canvas element not found');
                            return;
                        }
                        
                        var maxWidth = 1200;
                        var maxHeight = 800;
                        var scale = Math.min(maxWidth / img.width, maxHeight / img.height, 1);
                        
                        try {
                            fabricCanvas = new fabric.Canvas('editor-canvas', {
                                width: img.width * scale,
                                height: img.height * scale,
                            });
                            
                            fabric.Image.fromURL(response.data.url, function(fabricImg) {
                                if (!fabricImg) {
                                    console.error('Failed to load image into Fabric.js');
                                    return;
                                }
                                
                                fabricImg.scale(scale);
                                fabricCanvas.setBackgroundImage(fabricImg, fabricCanvas.renderAll.bind(fabricCanvas), {
                                    scaleX: scale,
                                    scaleY: scale
                                });
                                currentImage = fabricImg;
                                
                                // Initialize advanced features after image loads
                                setTimeout(function() {
                                    initAllToolbarButtons();
                                    if (window.clarkesMediaEditorAdvanced && window.clarkesMediaEditorAdvanced.initAdvancedFeatures) {
                                        window.clarkesMediaEditorAdvanced.initAdvancedFeatures();
                                    }
                                }, 500);
                            });
                        } catch (e) {
                            console.error('Error initializing Fabric.js canvas:', e);
                        }
                    };
                    img.onerror = function() {
                        console.error('Failed to load image');
                    };
                    img.src = response.data.url;
                } else {
                    console.error('Failed to get media:', response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error loading media:', error);
            }
        });
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
            // Enable crop mode - allow selection
            fabricCanvas.isDrawingMode = false;
            fabricCanvas.selection = true;
            // Add instructions
            if (!$('#crop-instructions').length) {
                $('#crop-controls').prepend('<p id="crop-instructions" style="color: #666; font-size: 13px; margin-bottom: 10px;">Select an area on the canvas, then click "Apply Crop"</p>');
            }
        }
    });
    
    // Apply Crop
    $('#apply-crop').on('click', function() {
        if (fabricCanvas && fabricCanvas.backgroundImage) {
            var aspect = $('#crop-aspect').val();
            var aspectRatio = null;
            
            if (aspect !== 'free') {
                var parts = aspect.split(':');
                aspectRatio = parseFloat(parts[0]) / parseFloat(parts[1]);
            }
            
            // Get selected area or use full canvas
            var activeObject = fabricCanvas.getActiveObject();
            var bgImg = fabricCanvas.backgroundImage;
            var imgElement = bgImg.getElement();
            var canvas = document.createElement('canvas');
            var ctx = canvas.getContext('2d');
            
            var left, top, width, height;
            
            if (activeObject && activeObject.type !== 'image' && activeObject !== bgImg) {
                // Use selected object as crop area
                left = activeObject.left;
                top = activeObject.top;
                width = activeObject.width * activeObject.scaleX;
                height = activeObject.height * activeObject.scaleY;
            } else {
                // Use full canvas
                left = 0;
                top = 0;
                width = fabricCanvas.width;
                height = fabricCanvas.height;
            }
            
            if (aspectRatio) {
                if (width / height > aspectRatio) {
                    width = height * aspectRatio;
                } else {
                    height = width / aspectRatio;
                }
            }
            
            // Calculate source coordinates in original image
            var scaleX = bgImg.scaleX;
            var scaleY = bgImg.scaleY;
            var sourceX = Math.max(0, left / scaleX);
            var sourceY = Math.max(0, top / scaleY);
            var sourceWidth = Math.min(width / scaleX, imgElement.width - sourceX);
            var sourceHeight = Math.min(height / scaleY, imgElement.height - sourceY);
            
            canvas.width = width;
            canvas.height = height;
            
            ctx.drawImage(imgElement, sourceX, sourceY, sourceWidth, sourceHeight, 0, 0, width, height);
            
            fabric.Image.fromURL(canvas.toDataURL(), function(croppedImg) {
                // Remove all objects except background
                var objects = fabricCanvas.getObjects();
                objects.forEach(function(obj) {
                    if (obj !== bgImg && obj.type !== 'image') {
                        fabricCanvas.remove(obj);
                    }
                });
                
                fabricCanvas.setBackgroundImage(croppedImg, fabricCanvas.renderAll.bind(fabricCanvas), {
                    scaleX: 1,
                    scaleY: 1
                });
                fabricCanvas.setDimensions({ width: width, height: height });
                fabricCanvas.renderAll();
            });
        }
    });
    
    $(document).on('click', '#btn-resize', function() {
        togglePanel('resize-controls');
    });
    
    $(document).on('click', '#btn-filters', function() {
        togglePanel('filter-controls');
    });
    
    $(document).on('click', '#btn-text-overlay', function() {
        togglePanel('text-controls');
    });
    
    $(document).on('click', '#btn-markup', function() {
        togglePanel('markup-controls');
    });
    
    $(document).on('click', '#btn-ai-seo', function() {
        togglePanel('ai-seo-panel');
    });
    
    $(document).on('click', '#btn-adjustments', function() {
        togglePanel('adjustments-controls');
    });
    
    $(document).on('click', '#btn-effects', function() {
        togglePanel('effects-controls');
    });
    
    $(document).on('click', '#btn-presets', function() {
        togglePanel('presets-controls');
    });
    
    $(document).on('click', '#btn-layers', function() {
        togglePanel('layers-controls');
    });
    
    $(document).on('click', '#btn-video-tools', function() {
        togglePanel('video-tools-controls');
    });
    
    // Filter Controls
    $('#filter-brightness, #filter-contrast, #filter-saturation, #filter-blur').on('input', function() {
        var id = $(this).attr('id').replace('filter-', '');
        var value = $(this).val();
        $('#' + id + '-value').text(value);
        filters[id] = parseInt(value);
        applyFilters();
    });
    
    function applyFilters() {
        if (fabricCanvas && currentImage) {
            var imgElement = fabricCanvas.backgroundImage;
            if (imgElement) {
                imgElement.filters = [];
                
                if (filters.brightness !== 100) {
                    imgElement.filters.push(new fabric.Image.filters.Brightness({
                        brightness: (filters.brightness - 100) / 100
                    }));
                }
                if (filters.contrast !== 100) {
                    imgElement.filters.push(new fabric.Image.filters.Contrast({
                        contrast: (filters.contrast - 100) / 100
                    }));
                }
                if (filters.saturation !== 100) {
                    imgElement.filters.push(new fabric.Image.filters.Saturation({
                        saturation: (filters.saturation - 100) / 100
                    }));
                }
                if (filters.blur > 0) {
                    imgElement.filters.push(new fabric.Image.filters.Blur({
                        blur: filters.blur / 10
                    }));
                }
                
                imgElement.applyFilters();
                fabricCanvas.renderAll();
            }
        }
    }
    
    $(document).on('click', '#apply-filters', function() {
        applyFilters();
    });
    
    $(document).on('click', '#reset-filters', function() {
        filters = { brightness: 100, contrast: 100, saturation: 100, blur: 0 };
        $('#filter-brightness').val(100).trigger('input');
        $('#filter-contrast').val(100).trigger('input');
        $('#filter-saturation').val(100).trigger('input');
        $('#filter-blur').val(0).trigger('input');
    });
    
    // Resize
    $(document).on('click', '#apply-resize', function() {
        var width = parseInt($('#resize-width').val());
        var height = parseInt($('#resize-height').val());
        var maintainAspect = $('#resize-maintain-aspect').is(':checked');
        
        if (fabricCanvas && fabricCanvas.backgroundImage) {
            var bgImg = fabricCanvas.backgroundImage;
            var originalWidth = bgImg.width * bgImg.scaleX;
            var originalHeight = bgImg.height * bgImg.scaleY;
            
            if (maintainAspect) {
                var aspect = originalWidth / originalHeight;
                if (width && !height) {
                    height = Math.round(width / aspect);
                } else if (height && !width) {
                    width = Math.round(height * aspect);
                } else if (width && height) {
                    // Use the dimension that maintains aspect better
                    var widthAspect = width / originalWidth;
                    var heightAspect = height / originalHeight;
                    if (widthAspect < heightAspect) {
                        height = Math.round(width / aspect);
                    } else {
                        width = Math.round(height * aspect);
                    }
                }
            }
            
            if (width && height) {
                var scaleX = width / bgImg.width;
                var scaleY = height / bgImg.height;
                bgImg.scaleX = scaleX;
                bgImg.scaleY = scaleY;
                fabricCanvas.setDimensions({ width: width, height: height });
                fabricCanvas.renderAll();
            }
        }
    });
    
    // Text Overlay
    $(document).on('click', '#add-text', function() {
        if (fabricCanvas) {
            var textOptions = {
                left: parseInt($('#text-x').val()),
                top: parseInt($('#text-y').val()),
                fontSize: parseInt($('#text-size').val()),
                fill: $('#text-color').val(),
                fontFamily: $('#text-font').val() || 'Arial',
                fontWeight: $('#text-weight').val() || 'normal',
                textAlign: $('#text-align').val() || 'left',
                angle: parseInt($('#text-rotation').val()) || 0
            };
            
            // Background color
            if ($('#text-bg-color').val() && $('#text-bg-color').val() !== 'transparent') {
                textOptions.backgroundColor = $('#text-bg-color').val();
                textOptions.padding = 5;
            }
            
            // Text shadow
            if ($('#text-shadow').is(':checked')) {
                textOptions.shadow = {
                    color: 'rgba(0, 0, 0, 0.5)',
                    blur: 5,
                    offsetX: 2,
                    offsetY: 2
                };
            }
            
            // Text outline
            if ($('#text-outline').is(':checked')) {
                textOptions.stroke = '#000000';
                textOptions.strokeWidth = parseInt($('#text-outline-width').val()) || 2;
            }
            
            var text = new fabric.Text($('#text-content').val(), textOptions);
            fabricCanvas.add(text);
            fabricCanvas.renderAll();
            
            // Save to history
            if (window.clarkesMediaEditorAdvanced && window.clarkesMediaEditorAdvanced.saveHistory) {
                window.clarkesMediaEditorAdvanced.saveHistory();
            }
            
            // Update layers
            if (window.clarkesMediaEditorAdvanced && window.clarkesMediaEditorAdvanced.updateLayers) {
                window.clarkesMediaEditorAdvanced.updateLayers();
            }
        }
    });
    
    // Markup Tools
    var currentMarkupTool = null;
    $(document).on('click', '#btn-rectangle', function() {
        currentMarkupTool = 'rect';
        fabricCanvas.isDrawingMode = false;
        fabricCanvas.selection = false;
    });
    
    $(document).on('click', '#btn-circle', function() {
        currentMarkupTool = 'circle';
        if (fabricCanvas) {
            fabricCanvas.isDrawingMode = false;
            fabricCanvas.selection = false;
        }
    });
    
    $(document).on('click', '#btn-line', function() {
        currentMarkupTool = 'line';
        fabricCanvas.isDrawingMode = false;
        fabricCanvas.selection = false;
    });
    
    // Handle markup drawing with Fabric.js
    var isDrawing = false;
    var startX, startY, currentShape;
    
    if (typeof fabric !== 'undefined' && fabricCanvas) {
        fabricCanvas.on('mouse:down', function(options) {
            if (currentMarkupTool && fabricCanvas) {
                isDrawing = true;
                var pointer = fabricCanvas.getPointer(options.e);
                startX = pointer.x;
                startY = pointer.y;
                var color = $('#markup-color').val();
                var width = parseInt($('#markup-width').val());
                
                switch(currentMarkupTool) {
                    case 'rect':
                        currentShape = new fabric.Rect({
                            left: startX,
                            top: startY,
                            width: 0,
                            height: 0,
                            stroke: color,
                            fill: 'transparent',
                            strokeWidth: width,
                            selectable: true
                        });
                        break;
                    case 'circle':
                        currentShape = new fabric.Circle({
                            left: startX,
                            top: startY,
                            radius: 0,
                            stroke: color,
                            fill: 'transparent',
                            strokeWidth: width,
                            selectable: true
                        });
                        break;
                    case 'line':
                        currentShape = new fabric.Line([startX, startY, startX, startY], {
                            stroke: color,
                            strokeWidth: width,
                            selectable: true
                        });
                        break;
                }
                
                if (currentShape) {
                    fabricCanvas.add(currentShape);
                }
            }
        });
        
        fabricCanvas.on('mouse:move', function(options) {
            if (isDrawing && currentShape && currentMarkupTool) {
                var pointer = fabricCanvas.getPointer(options.e);
                var color = $('#markup-color').val();
                var width = parseInt($('#markup-width').val());
                
                switch(currentMarkupTool) {
                    case 'rect':
                        currentShape.set({
                            width: Math.abs(pointer.x - startX),
                            height: Math.abs(pointer.y - startY),
                            left: Math.min(startX, pointer.x),
                            top: Math.min(startY, pointer.y)
                        });
                        break;
                    case 'circle':
                        var radius = Math.sqrt(Math.pow(pointer.x - startX, 2) + Math.pow(pointer.y - startY, 2));
                        currentShape.set({ radius: radius });
                        break;
                    case 'line':
                        currentShape.set({ x2: pointer.x, y2: pointer.y });
                        break;
                }
                fabricCanvas.renderAll();
            }
        });
        
        fabricCanvas.on('mouse:up', function() {
            if (isDrawing) {
                isDrawing = false;
                currentShape = null;
                currentMarkupTool = null;
            }
        });
    }
    
    // AI SEO
    $(document).on('click', '#generate-ai-seo', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).text('Generating...');
        
        $.ajax({
            url: clarkesMediaEditor.ajax_url,
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
    
    $(document).on('click', '#apply-ai-seo', function() {
        $.ajax({
            url: clarkesMediaEditor.ajax_url,
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
    $(document).on('click', '#btn-mute-video', function() {
        var video = document.getElementById('editor-video');
        if (video) {
            video.muted = !video.muted;
            $(this).text(video.muted ? 'Unmute Audio' : 'Mute Audio');
        }
    });
    
    // Auto Orient
    $(document).on('click', '#btn-auto-orient', function() {
        if (currentMediaType === 'video') {
            var video = document.getElementById('editor-video');
            if (video) {
                video.addEventListener('loadedmetadata', function() {
                    var width = video.videoWidth;
                    var height = video.videoHeight;
                    var isPortrait = height > width;
                    
                    if (confirm('Convert to ' + (isPortrait ? 'landscape' : 'portrait') + ' orientation?')) {
                        $.ajax({
                            url: clarkesMediaEditor.ajax_url,
                            type: 'POST',
                            data: {
                                action: 'clarkes_process_video',
                                nonce: clarkesMediaEditor.nonce,
                                media_id: currentMediaId,
                                operation: 'auto_orient',
                                target_orientation: isPortrait ? 'landscape' : 'portrait'
                            },
                            success: function(response) {
                                alert(response.data.message || 'Video orientation conversion queued. Note: Requires FFmpeg on server.');
                            }
                        });
                    }
                });
                video.load();
            }
        } else if (currentMediaType === 'image' && fabricCanvas && fabricCanvas.backgroundImage) {
            var bgImg = fabricCanvas.backgroundImage;
            var currentWidth = bgImg.width * bgImg.scaleX;
            var currentHeight = bgImg.height * bgImg.scaleY;
            var isPortrait = currentHeight > currentWidth;
            
            if (confirm('Convert to ' + (isPortrait ? 'landscape' : 'portrait') + ' orientation?')) {
                var targetWidth, targetHeight;
                if (isPortrait) {
                    // Convert to landscape (16:9)
                    targetWidth = Math.max(currentWidth, currentHeight * 16/9);
                    targetHeight = targetWidth * 9/16;
                } else {
                    // Convert to portrait (9:16)
                    targetHeight = Math.max(currentHeight, currentWidth * 16/9);
                    targetWidth = targetHeight * 9/16;
                }
                
                $('#resize-width').val(Math.round(targetWidth));
                $('#resize-height').val(Math.round(targetHeight));
                $('#resize-maintain-aspect').prop('checked', false);
                $('#apply-resize').trigger('click');
            }
        }
    });
    
    // Save
    $('#btn-save').on('click', function() {
        if (fabricCanvas) {
            var dataURL = fabricCanvas.toDataURL('image/png');
            $.ajax({
                url: clarkesMediaEditor.ajax_url,
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
    $(document).on('click', '#btn-reset', function() {
        if (confirm('Reset all changes?')) {
            window.location.reload();
        }
    });
    
    // Initialize All Toolbar Buttons
    function initAllToolbarButtons() {
        // This function ensures all toolbar buttons are wired up
        // Most are already initialized, but we'll make sure they're all connected
        
        // Adjustments button
        $('#btn-adjustments').off('click').on('click', function() {
            togglePanel('adjustments-controls');
        });
        
        // Effects button
        $('#btn-effects').off('click').on('click', function() {
            togglePanel('effects-controls');
        });
        
        // Presets button
        $('#btn-presets').off('click').on('click', function() {
            togglePanel('presets-controls');
        });
        
        // Video tools button
        $('#btn-video-tools').off('click').on('click', function() {
            togglePanel('video-tools-controls');
        });
        
        // Initialize advanced features if available
        if (window.clarkesMediaEditorAdvanced && window.clarkesMediaEditorAdvanced.initAdvancedFeatures) {
            setTimeout(function() {
                window.clarkesMediaEditorAdvanced.initAdvancedFeatures();
            }, 500);
        }
    }
    
    function togglePanel(panelId) {
        // Hide all panels
        $('.control-panel').hide().removeClass('active');
        
        // Show selected panel
        var $panel = $('#' + panelId);
        if ($panel.length) {
            $panel.show().addClass('active');
            
            // Scroll to panel if needed
            $('html, body').animate({
                scrollTop: $panel.offset().top - 100
            }, 300);
        }
        $('#' + panelId).show();
    }
    
})(jQuery);

