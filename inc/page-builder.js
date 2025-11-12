/**
 * Comprehensive Page Builder JavaScript
 * DIVI-Level Visual Page Building
 */

(function($) {
    'use strict';
    
    var builderData = [];
    var selectedElement = null;
    var elementIdCounter = 0;
    
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
        
        renderCanvas();
        initDragAndDrop();
        initElementSettings();
    });
    
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
            canvas.html('<div class="canvas-empty-state" style="text-align: center; padding: 100px 20px; color: #999;"><p>Drag elements from the left sidebar to start building your page</p></div>');
            return;
        }
        
        builderData.forEach(function(element) {
            var $element = renderElement(element);
            canvas.append($element);
        });
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
        
        switch(type) {
            case 'text':
                var tag = settings.tag || 'p';
                return '<' + tag + ' class="pb-text">' + (settings.text || 'Text') + '</' + tag + '>';
                
            case 'heading':
                var level = settings.level || 2;
                return '<h' + level + ' class="pb-heading">' + (settings.text || 'Heading') + '</h' + level + '>';
                
            case 'image':
                if (settings.image_id) {
                    return '<img src="' + settings.image_url + '" class="pb-image" />';
                }
                return '<div class="pb-image-placeholder">Image Placeholder</div>';
                
            case 'button':
                return '<a href="' + (settings.url || '#') + '" class="pb-button pb-button-' + (settings.style || 'primary') + '">' + (settings.text || 'Button') + '</a>';
                
            case 'row':
                return '<div class="pb-row">Row Container</div>';
                
            case 'column':
                return '<div class="pb-column">Column</div>';
                
            case 'spacer':
                return '<div class="pb-spacer" style="height: ' + (settings.height || '50px') + ';"></div>';
                
            case 'divider':
                return '<hr class="pb-divider" />';
                
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
        
        panel.append('<h4>' + type.charAt(0).toUpperCase() + type.slice(1) + ' Settings</h4>');
        
        // Type-specific settings
        switch(type) {
            case 'text':
                panel.append('<label>Text:<textarea class="pb-setting-text" rows="4">' + (settings.text || '') + '</textarea></label>');
                panel.append('<label>Tag:<select class="pb-setting-tag"><option value="p">Paragraph</option><option value="div">Div</option><option value="span">Span</option></select></label>');
                break;
                
            case 'heading':
                panel.append('<label>Text:<input type="text" class="pb-setting-text" value="' + (settings.text || '') + '" /></label>');
                panel.append('<label>Level:<select class="pb-setting-level"><option value="1">H1</option><option value="2">H2</option><option value="3">H3</option><option value="4">H4</option></select></label>');
                break;
                
            case 'image':
                panel.append('<button type="button" class="button pb-select-image">Select Image</button>');
                panel.append('<label>Size:<select class="pb-setting-size"><option value="thumbnail">Thumbnail</option><option value="medium">Medium</option><option value="large">Large</option><option value="full">Full</option></select></label>');
                break;
                
            case 'button':
                panel.append('<label>Text:<input type="text" class="pb-setting-text" value="' + (settings.text || '') + '" /></label>');
                panel.append('<label>URL:<input type="url" class="pb-setting-url" value="' + (settings.url || '') + '" /></label>');
                panel.append('<label>Style:<select class="pb-setting-style"><option value="primary">Primary</option><option value="secondary">Secondary</option><option value="outline">Outline</option></select></label>');
                break;
                
            case 'spacer':
                panel.append('<label>Height (px):<input type="number" class="pb-setting-height" value="' + (parseInt(settings.height) || 50) + '" /></label>');
                break;
        }
        
        // Common settings
        panel.append('<h4>Advanced</h4>');
        panel.append('<label>CSS Class:<input type="text" class="pb-setting-class" value="' + (settings.css_class || '') + '" /></label>');
        panel.append('<label>CSS ID:<input type="text" class="pb-setting-id" value="' + (settings.css_id || '') + '" /></label>');
        
        // Save button
        panel.append('<button type="button" class="button button-primary pb-save-settings">Save Settings</button>');
        
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
        
        $('.pb-setting-class').each(function() {
            element.settings.css_class = $(this).val();
        });
        
        $('.pb-setting-id').each(function() {
            element.settings.css_id = $(this).val();
        });
        
        renderCanvas();
        showElementSettings(element);
    }
    
    // Delete Element
    function deleteElement(elementId) {
        if (!confirm('Delete this element?')) return;
        
        builderData = builderData.filter(function(el) {
            return el.id !== elementId;
        });
        
        renderCanvas();
        $('#element-settings-panel').html('<p class="description">Select an element to edit its settings</p>');
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

