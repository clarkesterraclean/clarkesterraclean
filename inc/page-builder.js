/**
 * Comprehensive Page Builder JavaScript
 * DIVI-Level Visual Page Building
 */

(function($) {
    'use strict';
    
    var builderData = [];
    var selectedElement = null;
    var elementIdCounter = 0;
    var autoSaveTimer = null;
    var previewIframe = null;
    var isPreviewVisible = false;
    
    // Initialize
    $(document).ready(function() {
        var savedData = $('#builder-data').text();
        if (savedData) {
            try {
                builderData = JSON.parse(savedData);
            } catch(e) {
                builderData = [];
            }
        }
        
        // Check for existing page content
        var pageContentData = $('#page-content-data').text();
        if (pageContentData) {
            try {
                var pageData = JSON.parse(pageContentData);
                if (pageData.has_content && builderData.length === 0) {
                    // Show import option
                    $('#btn-import-content').on('click', function() {
                        importPageContent(pageData.content);
                    });
                    $('#btn-dismiss-import').on('click', function() {
                        $('.import-content-notice').slideUp();
                    });
                }
            } catch(e) {
                console.error('Error parsing page content data:', e);
            }
        }
        
        renderCanvas();
        initDragAndDrop();
        initElementSettings();
        initPreview();
        initSave();
        initLivePreview();
    });
    
    // Import Page Content
    function importPageContent(content) {
        if (!content || content.trim() === '') {
            alert('No content to import');
            return;
        }
        
        // Convert HTML content to builder elements
        var $temp = $('<div>').html(content);
        var elements = [];
        
        // Extract headings
        $temp.find('h1, h2, h3, h4, h5, h6').each(function() {
            var level = parseInt($(this).prop('tagName').substring(1));
            elements.push({
                id: 'element-' + (++elementIdCounter),
                type: 'heading',
                settings: {
                    text: $(this).text(),
                    level: level
                },
                children: []
            });
        });
        
        // Extract paragraphs
        $temp.find('p').each(function() {
            var text = $(this).text();
            if (text.trim()) {
                elements.push({
                    id: 'element-' + (++elementIdCounter),
                    type: 'text',
                    settings: {
                        text: text,
                        tag: 'p'
                    },
                    children: []
                });
            }
        });
        
        // Extract images
        $temp.find('img').each(function() {
            elements.push({
                id: 'element-' + (++elementIdCounter),
                type: 'image',
                settings: {
                    image_url: $(this).attr('src'),
                    alt: $(this).attr('alt') || ''
                },
                children: []
            });
        });
        
        if (elements.length > 0) {
            builderData = elements;
            renderCanvas();
            $('.import-content-notice').slideUp();
            alert('Content imported successfully! ' + elements.length + ' elements added.');
        } else {
            alert('No importable content found. The content may already be in the builder format.');
        }
    }
    
    // Initialize Preview
    function initPreview() {
        $('#btn-preview').on('click', function() {
            if (clarkesPageBuilder.preview_url) {
                // Save first, then open preview
                saveBuilderData(function() {
                    window.open(clarkesPageBuilder.preview_url, '_blank');
                });
            } else {
                alert('Preview URL not available');
            }
        });
    }
    
    // Initialize Live Preview
    function initLivePreview() {
        previewIframe = document.getElementById('builder-preview-iframe');
        
        // Toggle preview panel
        $('#btn-toggle-preview').on('click', function() {
            togglePreview();
        });
        
        // Close preview
        $('#btn-close-preview').on('click', function() {
            togglePreview(false);
        });
        
        // Refresh preview
        $('#btn-refresh-preview').on('click', function() {
            refreshPreview();
        });
        
        // Auto-save on changes
        $(document).on('input change', '#element-settings-panel input, #element-settings-panel textarea, #element-settings-panel select', function() {
            triggerAutoSave();
        });
    }
    
    // Toggle Preview
    function togglePreview(show) {
        var panel = $('#builder-preview-panel');
        var container = $('#page-builder-container');
        
        if (show === undefined) {
            isPreviewVisible = !isPreviewVisible;
        } else {
            isPreviewVisible = show;
        }
        
        if (isPreviewVisible) {
            panel.show();
            container.hide();
            refreshPreview();
        } else {
            panel.hide();
            container.show();
        }
    }
    
    // Refresh Preview
    function refreshPreview() {
        if (!previewIframe || !isPreviewVisible) return;
        
        var statusLoading = $('#preview-loading');
        var statusSaved = $('#preview-saved');
        
        // Show loading
        statusLoading.show();
        statusSaved.hide();
        
        // Save data first
        saveBuilderData(function() {
            // Reload iframe
            var iframeSrc = previewIframe.src;
            previewIframe.src = '';
            setTimeout(function() {
                previewIframe.src = iframeSrc + (iframeSrc.indexOf('?') > -1 ? '&' : '?') + '_t=' + Date.now();
                
                // Hide loading after a moment
                setTimeout(function() {
                    statusLoading.hide();
                    statusSaved.show();
                    setTimeout(function() {
                        statusSaved.fadeOut();
                    }, 2000);
                }, 500);
            }, 100);
        }, true); // Silent save
    }
    
    // Trigger Auto-Save
    function triggerAutoSave() {
        // Clear existing timer
        if (autoSaveTimer) {
            clearTimeout(autoSaveTimer);
        }
        
        // Update status
        updateAutoSaveStatus('saving');
        
        // Set new timer
        autoSaveTimer = setTimeout(function() {
            saveBuilderData(function() {
                updateAutoSaveStatus('saved');
                
                // Refresh preview if visible
                if (isPreviewVisible) {
                    refreshPreview();
                }
            }, true); // Silent save
        }, clarkesPageBuilder.auto_save_delay || 2000);
    }
    
    // Update Auto-Save Status
    function updateAutoSaveStatus(status) {
        var statusEl = $('#auto-save-status');
        
        switch(status) {
            case 'saving':
                statusEl.html('<span class="dashicons dashicons-update" style="animation: spin 1s linear infinite;"></span> Saving...').css('color', '#666');
                break;
            case 'saved':
                statusEl.html('<span class="dashicons dashicons-yes"></span> Saved').css('color', '#46b450');
                setTimeout(function() {
                    statusEl.fadeOut(function() {
                        statusEl.html('').show();
                    });
                }, 2000);
                break;
            case 'error':
                statusEl.html('<span class="dashicons dashicons-warning"></span> Error saving').css('color', '#dc3232');
                break;
            default:
                statusEl.html('').hide();
        }
    }
    
    // Initialize Save
    function initSave() {
        $('#btn-save-builder').on('click', function() {
            saveBuilderData(function() {
                alert('Page saved successfully!');
            });
        });
    }
    
    // Save Builder Data
    function saveBuilderData(callback, silent) {
        if (!silent) {
            updateAutoSaveStatus('saving');
        }
        
        $.ajax({
            url: clarkesPageBuilder.ajax_url,
            type: 'POST',
            data: {
                action: 'clarkes_save_page_builder',
                nonce: clarkesPageBuilder.nonce,
                post_id: clarkesPageBuilder.post_id,
                builder_data: JSON.stringify(builderData)
            },
            success: function(response) {
                if (response.success) {
                    if (!silent) {
                        updateAutoSaveStatus('saved');
                    }
                    if (callback) callback();
                } else {
                    if (!silent) {
                        updateAutoSaveStatus('error');
                        alert('Error saving: ' + (response.data.message || 'Unknown error'));
                    }
                }
            },
            error: function() {
                if (!silent) {
                    updateAutoSaveStatus('error');
                    alert('AJAX error while saving');
                }
            }
        });
    }
    
    // Drag and Drop
    function initDragAndDrop() {
        $('.builder-element').on('dragstart', function(e) {
            var elementType = $(this).data('type');
            e.originalEvent.dataTransfer.setData('text/plain', elementType);
        });
        
        $('#builder-canvas').on('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('drag-over');
        });
        
        $('#builder-canvas').on('dragleave', function() {
            $(this).removeClass('drag-over');
        });
        
        $('#builder-canvas').on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('drag-over');
            
            var elementType = e.originalEvent.dataTransfer.getData('text/plain');
            addElement(elementType);
        });
    }
    
    // Add Element
    function addElement(type, settings) {
        var element = {
            id: 'element-' + (++elementIdCounter),
            type: type,
            settings: settings || getDefaultSettings(type),
            children: []
        };
        
        builderData.push(element);
        renderCanvas();
        selectElement(element.id);
    }
    
    // Get Default Settings
    function getDefaultSettings(type) {
        var defaults = {
            text: { text: 'Text content', tag: 'p' },
            heading: { text: 'Heading', level: 2 },
            image: { image_id: 0, size: 'large', alignment: 'left' },
            button: { text: 'Button', url: '#', style: 'primary' },
            row: { gap: '20px', columns: 2 },
            column: { width: '50%' },
            spacer: { height: '50px' },
            divider: {},
        };
        
        return defaults[type] || {};
    }
    
    // Render Canvas
    function renderCanvas() {
        var canvas = $('#builder-canvas');
        canvas.empty();
        
        if (builderData.length === 0) {
            if ($('.canvas-empty-state').length === 0) {
                canvas.html('<div class="canvas-empty-state"><div class="empty-state-icon">🏗️</div><h3>Start Building Your Page</h3><p>Drag elements from the left sidebar to start building your page</p></div>');
            }
            return;
        }
        
        // Remove empty state if elements exist
        $('.canvas-empty-state').remove();
        
        builderData.forEach(function(element) {
            var $element = renderElement(element);
            canvas.append($element);
        });
        
        // Trigger auto-save after rendering
        triggerAutoSave();
    }
    
    // Render Element
    function renderElement(element) {
        var $element = $('<div>', {
            class: 'pb-element-wrapper',
            'data-element-id': element.id,
            html: getElementHTML(element)
        });
        
        $element.on('click', function(e) {
            e.stopPropagation();
            selectElement(element.id);
        });
        
        // Add controls
        var $controls = $('<div>', {
            class: 'pb-element-controls',
            html: '<button class="pb-btn-delete" title="Delete">×</button><button class="pb-btn-duplicate" title="Duplicate">+</button><button class="pb-btn-move-up" title="Move Up">↑</button><button class="pb-btn-move-down" title="Move Down">↓</button>'
        });
        
        $element.append($controls);
        
        // Delete
        $controls.find('.pb-btn-delete').on('click', function(e) {
            e.stopPropagation();
            deleteElement(element.id);
        });
        
        // Duplicate
        $controls.find('.pb-btn-duplicate').on('click', function(e) {
            e.stopPropagation();
            duplicateElement(element.id);
        });
        
        return $element;
    }
    
    // Get Element HTML
    function getElementHTML(element) {
        var type = element.type;
        var settings = element.settings || {};
        var cssClass = settings.css_class || '';
        var cssId = settings.css_id || '';
        
        var attrs = '';
        if (cssId) attrs += ' id="' + cssId + '"';
        if (cssClass) attrs += ' class="pb-' + type + ' ' + cssClass + '"';
        else attrs += ' class="pb-' + type + '"';
        
        switch(type) {
            case 'text':
                var tag = settings.tag || 'p';
                return '<' + tag + attrs + '>' + (settings.text || 'Text') + '</' + tag + '>';
                
            case 'heading':
                var level = settings.level || 2;
                return '<h' + level + attrs + '>' + (settings.text || 'Heading') + '</h' + level + '>';
                
            case 'image':
                if (settings.image_id && settings.image_url) {
                    return '<img src="' + settings.image_url + '"' + attrs + ' alt="' + (settings.alt || '') + '" />';
                }
                return '<div class="pb-image-placeholder">Image Placeholder</div>';
                
            case 'video':
                if (settings.video_id && settings.video_url) {
                    return '<video' + attrs + ' controls><source src="' + settings.video_url + '" type="video/mp4"></video>';
                }
                return '<div class="pb-video-placeholder">Video Placeholder</div>';
                
            case 'gallery':
                var galleryHtml = '<div' + attrs + '>';
                if (settings.images && settings.images.length > 0) {
                    settings.images.forEach(function(img) {
                        galleryHtml += '<img src="' + img.url + '" class="pb-gallery-image" />';
                    });
                } else {
                    galleryHtml += 'Gallery Placeholder';
                }
                galleryHtml += '</div>';
                return galleryHtml;
                
            case 'button':
                return '<a href="' + (settings.url || '#') + '" class="pb-button pb-button-' + (settings.style || 'primary') + (cssClass ? ' ' + cssClass : '') + '"' + (cssId ? ' id="' + cssId + '"' : '') + '>' + (settings.text || 'Button') + '</a>';
                
            case 'row':
                var rowGap = settings.gap || '20px';
                return '<div class="pb-row" style="display: flex; gap: ' + rowGap + ';">' + (element.children && element.children.length > 0 ? 'Row with ' + element.children.length + ' columns' : 'Empty Row') + '</div>';
                
            case 'column':
                var colWidth = settings.width || '50%';
                return '<div class="pb-column" style="width: ' + colWidth + ';">' + (element.children && element.children.length > 0 ? 'Column content' : 'Empty Column') + '</div>';
                
            case 'container':
                var containerWidth = settings.width || '1200px';
                return '<div class="pb-container" style="max-width: ' + containerWidth + '; margin: 0 auto;">Container</div>';
                
            case 'spacer':
                return '<div class="pb-spacer" style="height: ' + (settings.height || '50px') + ';"></div>';
                
            case 'divider':
                var dividerStyle = settings.style || 'solid';
                var dividerColor = settings.color || '#ddd';
                return '<hr class="pb-divider" style="border-style: ' + dividerStyle + '; border-color: ' + dividerColor + ';" />';
                
            case 'accordion':
                return '<div' + attrs + '><div class="pb-accordion-item"><div class="pb-accordion-header">Accordion Item 1</div><div class="pb-accordion-content">Content here</div></div></div>';
                
            case 'tabs':
                return '<div' + attrs + '><div class="pb-tabs"><div class="pb-tab-header">Tab 1</div><div class="pb-tab-content">Tab content</div></div></div>';
                
            case 'testimonial':
                return '<div' + attrs + '><blockquote class="pb-testimonial"><p>' + (settings.quote || 'Testimonial text') + '</p><cite>' + (settings.author || 'Author') + '</cite></blockquote></div>';
                
            case 'pricing':
                return '<div' + attrs + '><div class="pb-pricing-table"><div class="pb-price">' + (settings.price || '£99') + '</div><div class="pb-features">Features list</div></div></div>';
                
            case 'countdown':
                return '<div' + attrs + '><div class="pb-countdown">00:00:00</div></div>';
                
            case 'progress':
                var progressValue = settings.value || 50;
                return '<div' + attrs + '><div class="pb-progress-bar"><div class="pb-progress-fill" style="width: ' + progressValue + '%;">' + progressValue + '%</div></div></div>';
                
            case 'map':
                return '<div' + attrs + '><div class="pb-map-placeholder">Google Map - ' + (settings.address || 'Address') + '</div></div>';
                
            case 'form':
                return '<div' + attrs + '><form class="pb-contact-form"><input type="text" placeholder="Name" /><input type="email" placeholder="Email" /><textarea placeholder="Message"></textarea><button type="submit">Submit</button></form></div>';
                
            default:
                return '<div class="pb-element-' + type + '">' + type + '</div>';
        }
    }
    
    // Select Element
    function selectElement(elementId) {
        selectedElement = elementId;
        $('.pb-element-wrapper').removeClass('selected');
        $('.pb-element-wrapper[data-element-id="' + elementId + '"]').addClass('selected');
        
        var element = findElement(elementId);
        if (element) {
            showElementSettings(element);
        }
    }
    
    // Find Element
    function findElement(elementId) {
        for (var i = 0; i < builderData.length; i++) {
            if (builderData[i].id === elementId) {
                return builderData[i];
            }
        }
        return null;
    }
    
    // Show Element Settings
    function showElementSettings(element) {
        var panel = $('#element-settings-panel');
        panel.empty();
        
        var type = element.type;
        var settings = element.settings || {};
        
        panel.append('<h4>' + type.charAt(0).toUpperCase() + type.slice(1).replace(/-/g, ' ') + ' Settings</h4>');
        
        // Type-specific settings
        switch(type) {
            case 'text':
                panel.append('<label>Text Content:<textarea class="pb-setting-text" rows="4">' + (settings.text || '') + '</textarea></label>');
                panel.append('<label>HTML Tag:<select class="pb-setting-tag"><option value="p">Paragraph (p)</option><option value="div">Div</option><option value="span">Span</option></select></label>');
                break;
                
            case 'heading':
                panel.append('<label>Heading Text:<input type="text" class="pb-setting-text" value="' + (settings.text || '') + '" /></label>');
                panel.append('<label>Heading Level:<select class="pb-setting-level"><option value="1">H1</option><option value="2">H2</option><option value="3">H3</option><option value="4">H4</option><option value="5">H5</option><option value="6">H6</option></select></label>');
                break;
                
            case 'image':
                panel.append('<button type="button" class="button pb-select-image">Select Image</button>');
                panel.append('<label>Image Size:<select class="pb-setting-size"><option value="thumbnail">Thumbnail</option><option value="medium">Medium</option><option value="large">Large</option><option value="full">Full</option></select></label>');
                panel.append('<label>Alt Text:<input type="text" class="pb-setting-alt" value="' + (settings.alt || '') + '" /></label>');
                panel.append('<label>Alignment:<select class="pb-setting-alignment"><option value="left">Left</option><option value="center">Center</option><option value="right">Right</option></select></label>');
                break;
                
            case 'video':
                panel.append('<button type="button" class="button pb-select-video">Select Video</button>');
                panel.append('<label>Autoplay:<input type="checkbox" class="pb-setting-autoplay" ' + (settings.autoplay ? 'checked' : '') + ' /></label>');
                panel.append('<label>Loop:<input type="checkbox" class="pb-setting-loop" ' + (settings.loop ? 'checked' : '') + ' /></label>');
                panel.append('<label>Muted:<input type="checkbox" class="pb-setting-muted" ' + (settings.muted ? 'checked' : '') + ' /></label>');
                break;
                
            case 'gallery':
                panel.append('<button type="button" class="button pb-select-gallery">Select Images</button>');
                panel.append('<label>Columns:<select class="pb-setting-columns"><option value="2">2 Columns</option><option value="3">3 Columns</option><option value="4">4 Columns</option></select></label>');
                break;
                
            case 'button':
                panel.append('<label>Button Text:<input type="text" class="pb-setting-text" value="' + (settings.text || '') + '" /></label>');
                panel.append('<label>Button URL:<input type="url" class="pb-setting-url" value="' + (settings.url || '') + '" /></label>');
                panel.append('<label>Button Style:<select class="pb-setting-style"><option value="primary">Primary</option><option value="secondary">Secondary</option><option value="outline">Outline</option><option value="text">Text Link</option></select></label>');
                panel.append('<label>Open in New Tab:<input type="checkbox" class="pb-setting-target" ' + (settings.target === '_blank' ? 'checked' : '') + ' /></label>');
                break;
                
            case 'row':
                panel.append('<label>Gap Between Columns (px):<input type="number" class="pb-setting-gap" value="' + (parseInt(settings.gap) || 20) + '" /></label>');
                panel.append('<label>Number of Columns:<select class="pb-setting-columns"><option value="2">2 Columns</option><option value="3">3 Columns</option><option value="4">4 Columns</option></select></label>');
                break;
                
            case 'column':
                panel.append('<label>Column Width (%):<input type="number" class="pb-setting-width" value="' + (parseInt(settings.width) || 50) + '" min="1" max="100" /></label>');
                break;
                
            case 'container':
                panel.append('<label>Max Width (px):<input type="number" class="pb-setting-width" value="' + (parseInt(settings.width) || 1200) + '" /></label>');
                break;
                
            case 'spacer':
                panel.append('<label>Height (px):<input type="number" class="pb-setting-height" value="' + (parseInt(settings.height) || 50) + '" min="0" /></label>');
                break;
                
            case 'divider':
                panel.append('<label>Style:<select class="pb-setting-divider-style"><option value="solid">Solid</option><option value="dashed">Dashed</option><option value="dotted">Dotted</option></select></label>');
                panel.append('<label>Color:<input type="color" class="pb-setting-color" value="' + (settings.color || '#ddd') + '" /></label>');
                break;
                
            case 'accordion':
                panel.append('<label>Number of Items:<input type="number" class="pb-setting-items" value="' + (settings.items || 3) + '" min="1" max="10" /></label>');
                break;
                
            case 'tabs':
                panel.append('<label>Number of Tabs:<input type="number" class="pb-setting-tabs" value="' + (settings.tabs || 3) + '" min="1" max="10" /></label>');
                break;
                
            case 'testimonial':
                panel.append('<label>Quote Text:<textarea class="pb-setting-quote" rows="3">' + (settings.quote || '') + '</textarea></label>');
                panel.append('<label>Author Name:<input type="text" class="pb-setting-author" value="' + (settings.author || '') + '" /></label>');
                panel.append('<label>Author Title:<input type="text" class="pb-setting-title" value="' + (settings.title || '') + '" /></label>');
                break;
                
            case 'pricing':
                panel.append('<label>Price:<input type="text" class="pb-setting-price" value="' + (settings.price || '') + '" placeholder="£99" /></label>');
                panel.append('<label>Currency Symbol:<input type="text" class="pb-setting-currency" value="' + (settings.currency || '£') + '" /></label>');
                panel.append('<label>Period:<input type="text" class="pb-setting-period" value="' + (settings.period || '/month') + '" /></label>');
                break;
                
            case 'countdown':
                panel.append('<label>Target Date:<input type="datetime-local" class="pb-setting-date" value="' + (settings.date || '') + '" /></label>');
                break;
                
            case 'progress':
                panel.append('<label>Progress Value (%):<input type="number" class="pb-setting-value" value="' + (settings.value || 50) + '" min="0" max="100" /></label>');
                panel.append('<label>Progress Color:<input type="color" class="pb-setting-color" value="' + (settings.color || '#3b82f6') + '" /></label>');
                break;
                
            case 'map':
                panel.append('<label>Address:<input type="text" class="pb-setting-address" value="' + (settings.address || '') + '" /></label>');
                panel.append('<label>Zoom Level:<input type="number" class="pb-setting-zoom" value="' + (settings.zoom || 15) + '" min="1" max="20" /></label>');
                break;
                
            case 'form':
                panel.append('<label>Form Fields:<textarea class="pb-setting-fields" rows="4" placeholder="name, email, message"></textarea></label>');
                panel.append('<label>Submit Button Text:<input type="text" class="pb-setting-submit-text" value="' + (settings.submit_text || 'Submit') + '" /></label>');
                break;
        }
        
        // Common settings
        panel.append('<h4>Advanced</h4>');
        panel.append('<label>CSS Class:<input type="text" class="pb-setting-class" value="' + (settings.css_class || '') + '" placeholder="custom-class" /></label>');
        panel.append('<label>CSS ID:<input type="text" class="pb-setting-id" value="' + (settings.css_id || '') + '" placeholder="custom-id" /></label>');
        panel.append('<label>Background Color:<input type="color" class="pb-setting-bg-color" value="' + (settings.bg_color || '') + '" /></label>');
        panel.append('<label>Text Color:<input type="color" class="pb-setting-text-color" value="' + (settings.text_color || '') + '" /></label>');
        panel.append('<label>Padding (px):<input type="number" class="pb-setting-padding" value="' + (settings.padding || '') + '" placeholder="20" /></label>');
        panel.append('<label>Margin (px):<input type="number" class="pb-setting-margin" value="' + (settings.margin || '') + '" placeholder="10" /></label>');
        
        // Save button
        panel.append('<button type="button" class="button button-primary pb-save-settings" style="margin-top: 15px;">Save Settings</button>');
        
        // Bind save
        $('.pb-save-settings').on('click', function() {
            saveElementSettings(element.id);
        });
        
        // Image selector
        $('.pb-select-image').on('click', function() {
            var mediaUploader = wp.media({
                title: 'Select Image',
                button: { text: 'Select' },
                library: { type: 'image' },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                element.settings.image_id = attachment.id;
                element.settings.image_url = attachment.url;
                renderCanvas();
                showElementSettings(element);
            });
            
            mediaUploader.open();
        });
        
        // Video selector
        $('.pb-select-video').on('click', function() {
            var mediaUploader = wp.media({
                title: 'Select Video',
                button: { text: 'Select' },
                library: { type: 'video' },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                element.settings.video_id = attachment.id;
                element.settings.video_url = attachment.url;
                renderCanvas();
                showElementSettings(element);
            });
            
            mediaUploader.open();
        });
        
        // Gallery selector
        $('.pb-select-gallery').on('click', function() {
            var mediaUploader = wp.media({
                title: 'Select Images for Gallery',
                button: { text: 'Select' },
                library: { type: 'image' },
                multiple: true
            });
            
            mediaUploader.on('select', function() {
                var attachments = mediaUploader.state().get('selection').toJSON();
                element.settings.images = attachments.map(function(att) {
                    return { id: att.id, url: att.url };
                });
                renderCanvas();
                showElementSettings(element);
            });
            
            mediaUploader.open();
        });
    }
    
    // Save Element Settings
    function saveElementSettings(elementId) {
        var element = findElement(elementId);
        if (!element) return;
        
        // Get all settings from form
        $('.pb-setting-text').each(function() {
            element.settings.text = $(this).val();
        });
        
        $('.pb-setting-tag').each(function() {
            element.settings.tag = $(this).val();
        });
        
        $('.pb-setting-level').each(function() {
            element.settings.level = parseInt($(this).val());
        });
        
        $('.pb-setting-url').each(function() {
            element.settings.url = $(this).val();
        });
        
        $('.pb-setting-style').each(function() {
            element.settings.style = $(this).val();
        });
        
        $('.pb-setting-height').each(function() {
            element.settings.height = $(this).val() + 'px';
        });
        
        $('.pb-setting-width').each(function() {
            var val = $(this).val();
            if ($(this).closest('#element-settings-panel').find('.pb-setting-width').parent().find('label').text().indexOf('%') > -1) {
                element.settings.width = val + '%';
            } else {
                element.settings.width = val + 'px';
            }
        });
        
        $('.pb-setting-gap').each(function() {
            element.settings.gap = $(this).val() + 'px';
        });
        
        $('.pb-setting-alt').each(function() {
            element.settings.alt = $(this).val();
        });
        
        $('.pb-setting-target').each(function() {
            element.settings.target = $(this).is(':checked') ? '_blank' : '_self';
        });
        
        $('.pb-setting-autoplay').each(function() {
            element.settings.autoplay = $(this).is(':checked');
        });
        
        $('.pb-setting-loop').each(function() {
            element.settings.loop = $(this).is(':checked');
        });
        
        $('.pb-setting-muted').each(function() {
            element.settings.muted = $(this).is(':checked');
        });
        
        $('.pb-setting-quote').each(function() {
            element.settings.quote = $(this).val();
        });
        
        $('.pb-setting-author').each(function() {
            element.settings.author = $(this).val();
        });
        
        $('.pb-setting-price').each(function() {
            element.settings.price = $(this).val();
        });
        
        $('.pb-setting-value').each(function() {
            element.settings.value = parseInt($(this).val());
        });
        
        $('.pb-setting-color').each(function() {
            element.settings.color = $(this).val();
        });
        
        $('.pb-setting-address').each(function() {
            element.settings.address = $(this).val();
        });
        
        $('.pb-setting-class').each(function() {
            element.settings.css_class = $(this).val();
        });
        
        $('.pb-setting-id').each(function() {
            element.settings.css_id = $(this).val();
        });
        
        $('.pb-setting-bg-color').each(function() {
            element.settings.bg_color = $(this).val();
        });
        
        $('.pb-setting-text-color').each(function() {
            element.settings.text_color = $(this).val();
        });
        
        $('.pb-setting-padding').each(function() {
            element.settings.padding = $(this).val() + 'px';
        });
        
        $('.pb-setting-margin').each(function() {
            element.settings.margin = $(this).val() + 'px';
        });
        
        renderCanvas();
        showElementSettings(element);
        triggerAutoSave();
    }
    
    // Delete Element
    function deleteElement(elementId) {
        if (!confirm('Delete this element?')) return;
        
        builderData = builderData.filter(function(el) {
            return el.id !== elementId;
        });
        
        renderCanvas();
        $('#element-settings-panel').html('<p class="description">Select an element to edit its settings</p>');
        triggerAutoSave();
    }
    
    // Duplicate Element
    function duplicateElement(elementId) {
        var element = findElement(elementId);
        if (!element) return;
        
        var cloned = JSON.parse(JSON.stringify(element));
        cloned.id = 'element-' + (++elementIdCounter);
        
        var index = builderData.findIndex(function(el) {
            return el.id === elementId;
        });
        
        builderData.splice(index + 1, 0, cloned);
        renderCanvas();
        triggerAutoSave();
    }
    
    // Save Builder
    $('#btn-save-builder').on('click', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).text('Saving...');
        
        var postId = new URLSearchParams(window.location.search).get('post_id');
        
        $.ajax({
            url: clarkesPageBuilder.ajax_url,
            type: 'POST',
            data: {
                action: 'clarkes_save_builder',
                nonce: clarkesPageBuilder.nonce,
                post_id: postId,
                builder_data: JSON.stringify(builderData)
            },
            success: function(response) {
                if (response.success) {
                    alert('Page builder saved successfully!');
                } else {
                    alert('Error: ' + response.data.message);
                }
                $btn.prop('disabled', false).text('Save');
            },
            error: function() {
                alert('An error occurred');
                $btn.prop('disabled', false).text('Save');
            }
        });
    });
    
    // Preview
    $('#btn-preview').on('click', function() {
        var postId = new URLSearchParams(window.location.search).get('post_id');
        if (postId) {
            window.open('?p=' + postId + '&preview=true', '_blank');
        }
    });
    
})(jQuery);

