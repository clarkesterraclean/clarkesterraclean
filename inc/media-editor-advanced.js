/**
 * Advanced Media Editor Features
 * Undo/Redo, Zoom, Layers, Advanced Filters, etc.
 */

(function($) {
    'use strict';
    
    // History management
    var historyStack = [];
    var historyIndex = -1;
    var maxHistory = 50;
    
    // Zoom management
    var currentZoom = 1;
    var minZoom = 0.1;
    var maxZoom = 5;
    
    // Layer management
    var layers = [];
    
    // Grid and guides
    var showGrid = false;
    var showGuides = false;
    
    // Initialize advanced features
    function initAdvancedFeatures() {
        if (typeof fabricCanvas === 'undefined' || !fabricCanvas) return;
        
        // Save initial state
        saveHistory();
        
        // Undo/Redo
        initUndoRedo();
        
        // Zoom controls
        initZoom();
        
        // Layer management
        initLayers();
        
        // Grid and guides
        initGridGuides();
        
        // Advanced filters
        initAdvancedFilters();
        
        // Advanced text
        initAdvancedText();
        
        // Advanced markup
        initAdvancedMarkup();
        
        // Export
        initExport();
        
        // Presets
        initPresets();
        
        // Video tools
        initVideoTools();
    }
    
    // Undo/Redo
    function initUndoRedo() {
        $('#btn-undo').on('click', function() {
            undo();
        });
        
        $('#btn-redo').on('click', function() {
            redo();
        });
        
        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'z' && !e.shiftKey) {
                e.preventDefault();
                undo();
            } else if ((e.ctrlKey || e.metaKey) && (e.key === 'y' || (e.key === 'z' && e.shiftKey))) {
                e.preventDefault();
                redo();
            }
        });
        
        // Save history on canvas changes
        if (fabricCanvas) {
            fabricCanvas.on('object:added', saveHistory);
            fabricCanvas.on('object:removed', saveHistory);
            fabricCanvas.on('object:modified', saveHistory);
            fabricCanvas.on('path:created', saveHistory);
        }
    }
    
    function saveHistory() {
        if (!fabricCanvas) return;
        
        var json = JSON.stringify(fabricCanvas.toJSON());
        
        // Remove old history if we're not at the end
        if (historyIndex < historyStack.length - 1) {
            historyStack = historyStack.slice(0, historyIndex + 1);
        }
        
        historyStack.push(json);
        
        // Limit history size
        if (historyStack.length > maxHistory) {
            historyStack.shift();
        } else {
            historyIndex++;
        }
        
        updateUndoRedoButtons();
    }
    
    function undo() {
        if (historyIndex > 0) {
            historyIndex--;
            loadHistory();
        }
    }
    
    function redo() {
        if (historyIndex < historyStack.length - 1) {
            historyIndex++;
            loadHistory();
        }
    }
    
    function loadHistory() {
        if (!fabricCanvas || !historyStack[historyIndex]) return;
        
        fabricCanvas.loadFromJSON(historyStack[historyIndex], function() {
            fabricCanvas.renderAll();
            updateLayers();
        });
        
        updateUndoRedoButtons();
    }
    
    function updateUndoRedoButtons() {
        $('#btn-undo').prop('disabled', historyIndex <= 0);
        $('#btn-redo').prop('disabled', historyIndex >= historyStack.length - 1);
    }
    
    // Zoom controls
    function initZoom() {
        $('#btn-zoom-in').on('click', function() {
            zoomIn();
        });
        
        $('#btn-zoom-out').on('click', function() {
            zoomOut();
        });
        
        $('#btn-zoom-fit').on('click', function() {
            zoomFit();
        });
        
        // Mouse wheel zoom
        if (fabricCanvas) {
            fabricCanvas.on('mouse:wheel', function(opt) {
                var delta = opt.e.deltaY;
                if (delta > 0) {
                    zoomOut();
                } else {
                    zoomIn();
                }
                opt.e.preventDefault();
                opt.e.stopPropagation();
            });
        }
    }
    
    function zoomIn() {
        currentZoom = Math.min(currentZoom * 1.2, maxZoom);
        applyZoom();
    }
    
    function zoomOut() {
        currentZoom = Math.max(currentZoom / 1.2, minZoom);
        applyZoom();
    }
    
    function zoomFit() {
        if (!fabricCanvas) return;
        var container = $('#canvas-wrapper');
        var containerWidth = container.width();
        var containerHeight = container.height();
        var canvasWidth = fabricCanvas.width;
        var canvasHeight = fabricCanvas.height;
        
        currentZoom = Math.min(containerWidth / canvasWidth, containerHeight / canvasHeight, 1);
        applyZoom();
    }
    
    function applyZoom() {
        if (!fabricCanvas) return;
        
        var canvas = document.getElementById('editor-canvas');
        var wrapper = document.getElementById('canvas-wrapper');
        
        if (canvas && wrapper) {
            var newWidth = fabricCanvas.width * currentZoom;
            var newHeight = fabricCanvas.height * currentZoom;
            
            canvas.style.width = newWidth + 'px';
            canvas.style.height = newHeight + 'px';
            
            fabricCanvas.setDimensions({
                width: newWidth,
                height: newHeight
            }, {
                backstoreOnly: true
            });
            
            fabricCanvas.setZoom(currentZoom);
            fabricCanvas.renderAll();
            
            $('#zoom-level').text(Math.round(currentZoom * 100) + '%');
        }
    }
    
    // Layer management
    function initLayers() {
        $('#btn-layers').on('click', function() {
            togglePanel('layers-controls');
            updateLayers();
        });
        
        $('#btn-layer-up').on('click', function() {
            var active = fabricCanvas.getActiveObject();
            if (active) {
                active.bringForward();
                fabricCanvas.renderAll();
                updateLayers();
            }
        });
        
        $('#btn-layer-down').on('click', function() {
            var active = fabricCanvas.getActiveObject();
            if (active) {
                active.sendBackwards();
                fabricCanvas.renderAll();
                updateLayers();
            }
        });
        
        $('#btn-layer-delete').on('click', function() {
            var active = fabricCanvas.getActiveObject();
            if (active) {
                fabricCanvas.remove(active);
                fabricCanvas.renderAll();
                updateLayers();
                saveHistory();
            }
        });
        
        $('#btn-layer-duplicate').on('click', function() {
            var active = fabricCanvas.getActiveObject();
            if (active) {
                active.clone(function(cloned) {
                    cloned.set({
                        left: cloned.left + 10,
                        top: cloned.top + 10
                    });
                    fabricCanvas.add(cloned);
                    fabricCanvas.renderAll();
                    updateLayers();
                    saveHistory();
                });
            }
        });
        
        if (fabricCanvas) {
            fabricCanvas.on('selection:created', updateLayers);
            fabricCanvas.on('selection:updated', updateLayers);
            fabricCanvas.on('selection:cleared', updateLayers);
        }
    }
    
    function updateLayers() {
        if (!fabricCanvas) return;
        
        var list = $('#layers-list');
        list.empty();
        
        var objects = fabricCanvas.getObjects();
        objects.reverse(); // Show top to bottom
        
        objects.forEach(function(obj, index) {
            var type = obj.type || 'unknown';
            var name = type.charAt(0).toUpperCase() + type.slice(1);
            if (obj.text) name = 'Text: ' + obj.text.substring(0, 20);
            
            var item = $('<div>', {
                class: 'layer-item',
                style: 'padding: 5px; border: 1px solid #ddd; margin-bottom: 5px; cursor: pointer; ' +
                       (fabricCanvas.getActiveObject() === obj ? 'background: #e3f2fd;' : ''),
                text: name
            });
            
            item.on('click', function() {
                fabricCanvas.setActiveObject(obj);
                fabricCanvas.renderAll();
                updateLayers();
            });
            
            list.append(item);
        });
    }
    
    // Grid and guides
    function initGridGuides() {
        $('#btn-grid').on('click', function() {
            showGrid = !showGrid;
            $(this).toggleClass('button-primary', showGrid);
            drawGrid();
        });
        
        $('#btn-guides').on('click', function() {
            showGuides = !showGuides;
            $(this).toggleClass('button-primary', showGuides);
            drawGuides();
        });
    }
    
    function drawGrid() {
        if (!fabricCanvas || !showGrid) {
            $('#grid-canvas').hide();
            return;
        }
        
        var gridCanvas = document.getElementById('grid-canvas');
        if (!gridCanvas) return;
        
        gridCanvas.width = fabricCanvas.width;
        gridCanvas.height = fabricCanvas.height;
        gridCanvas.style.width = fabricCanvas.width + 'px';
        gridCanvas.style.height = fabricCanvas.height + 'px';
        
        var ctx = gridCanvas.getContext('2d');
        ctx.clearRect(0, 0, gridCanvas.width, gridCanvas.height);
        ctx.strokeStyle = 'rgba(0, 0, 0, 0.1)';
        ctx.lineWidth = 1;
        
        var spacing = 20;
        for (var x = 0; x < gridCanvas.width; x += spacing) {
            ctx.beginPath();
            ctx.moveTo(x, 0);
            ctx.lineTo(x, gridCanvas.height);
            ctx.stroke();
        }
        
        for (var y = 0; y < gridCanvas.height; y += spacing) {
            ctx.beginPath();
            ctx.moveTo(0, y);
            ctx.lineTo(gridCanvas.width, y);
            ctx.stroke();
        }
        
        $('#grid-canvas').show();
    }
    
    function drawGuides() {
        // Guide lines implementation
        if (showGuides) {
            // Add guide lines at center
        }
    }
    
    // Advanced filters
    function initAdvancedFilters() {
        $('#filter-sepia').on('click', function() {
            applyFilter('sepia');
        });
        
        $('#filter-grayscale').on('click', function() {
            applyFilter('grayscale');
        });
        
        $('#filter-invert').on('click', function() {
            applyFilter('invert');
        });
        
        $('#filter-vintage').on('click', function() {
            applyFilter('vintage');
        });
        
        $('#filter-sharpen').on('click', function() {
            applyFilter('sharpen');
        });
        
        $('#filter-emboss').on('click', function() {
            applyFilter('emboss');
        });
    }
    
    function applyFilter(filterName) {
        if (!fabricCanvas || !fabricCanvas.backgroundImage) return;
        
        var bgImg = fabricCanvas.backgroundImage;
        var filter;
        
        switch(filterName) {
            case 'sepia':
                filter = new fabric.Image.filters.Sepia();
                break;
            case 'grayscale':
                filter = new fabric.Image.filters.Grayscale();
                break;
            case 'invert':
                filter = new fabric.Image.filters.Invert();
                break;
            case 'vintage':
                filter = new fabric.Image.filters.Vintage();
                break;
            case 'sharpen':
                filter = new fabric.Image.filters.Convolute({
                    matrix: [0, -1, 0, -1, 5, -1, 0, -1, 0]
                });
                break;
            case 'emboss':
                filter = new fabric.Image.filters.Convolute({
                    matrix: [1, 1, 1, 1, 0.7, -1, -1, -1, -1]
                });
                break;
        }
        
        if (filter) {
            bgImg.filters.push(filter);
            bgImg.applyFilters();
            fabricCanvas.renderAll();
            saveHistory();
        }
    }
    
    // Advanced text
    function initAdvancedText() {
        $('#text-outline').on('change', function() {
            $('#text-outline-width').prop('disabled', !$(this).is(':checked'));
        });
    }
    
    // Advanced markup
    function initAdvancedMarkup() {
        var isDrawing = false;
        var points = [];
        
        $('#btn-polygon').on('click', function() {
            currentMarkupTool = 'polygon';
            points = [];
            fabricCanvas.isDrawingMode = false;
            fabricCanvas.selection = false;
        });
        
        $('#btn-freehand').on('click', function() {
            currentMarkupTool = 'freehand';
            fabricCanvas.isDrawingMode = true;
            fabricCanvas.freeDrawingBrush.width = parseInt($('#markup-width').val());
            fabricCanvas.freeDrawingBrush.color = $('#markup-color').val();
        });
        
        if (fabricCanvas) {
            fabricCanvas.on('mouse:down', function(options) {
                if (currentMarkupTool === 'polygon') {
                    var pointer = fabricCanvas.getPointer(options.e);
                    points.push(pointer);
                    
                    if (points.length >= 3) {
                        var polygon = new fabric.Polygon(points, {
                            fill: $('#markup-filled').is(':checked') ? $('#markup-fill-color').val() : 'transparent',
                            stroke: $('#markup-color').val(),
                            strokeWidth: parseInt($('#markup-width').val()),
                            selectable: true
                        });
                        fabricCanvas.add(polygon);
                        fabricCanvas.renderAll();
                        points = [];
                        currentMarkupTool = null;
                        saveHistory();
                    }
                }
            });
        }
    }
    
    // Export
    function initExport() {
        $('#btn-export').on('click', function() {
            togglePanel('export-controls');
        });
        
        $('#export-quality').on('input', function() {
            $('#quality-value').text($(this).val());
        });
        
        $('#btn-export-now').on('click', function() {
            exportImage();
        });
    }
    
    function exportImage() {
        if (!fabricCanvas) return;
        
        var format = $('#export-format').val();
        var quality = $('#export-quality').val() / 100;
        var maxWidth = parseInt($('#export-width').val()) || 0;
        var maxHeight = parseInt($('#export-height').val()) || 0;
        
        var dataURL = fabricCanvas.toDataURL({
            format: format,
            quality: quality,
            multiplier: 1
        });
        
        // Resize if needed
        if (maxWidth > 0 || maxHeight > 0) {
            var img = new Image();
            img.onload = function() {
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');
                var width = img.width;
                var height = img.height;
                
                if (maxWidth > 0 && width > maxWidth) {
                    height = (height * maxWidth) / width;
                    width = maxWidth;
                }
                if (maxHeight > 0 && height > maxHeight) {
                    width = (width * maxHeight) / height;
                    height = maxHeight;
                }
                
                canvas.width = width;
                canvas.height = height;
                ctx.drawImage(img, 0, 0, width, height);
                
                downloadImage(canvas.toDataURL('image/' + format, quality));
            };
            img.src = dataURL;
        } else {
            downloadImage(dataURL);
        }
    }
    
    function downloadImage(dataURL) {
        var link = document.createElement('a');
        link.download = 'edited-image.' + $('#export-format').val();
        link.href = dataURL;
        link.click();
    }
    
    // Presets
    function initPresets() {
        $('.preset-btn').on('click', function() {
            var preset = $(this).data('preset');
            applyPreset(preset);
        });
    }
    
    function applyPreset(presetName) {
        if (!fabricCanvas || !fabricCanvas.backgroundImage) return;
        
        var bgImg = fabricCanvas.backgroundImage;
        bgImg.filters = [];
        
        switch(presetName) {
            case 'vintage':
                bgImg.filters.push(new fabric.Image.filters.Sepia());
                bgImg.filters.push(new fabric.Image.filters.Vintage());
                break;
            case 'black-white':
                bgImg.filters.push(new fabric.Image.filters.Grayscale());
                break;
            case 'warm':
                bgImg.filters.push(new fabric.Image.filters.Brightness({ brightness: 0.1 }));
                bgImg.filters.push(new fabric.Image.filters.Saturation({ saturation: 0.2 }));
                break;
            case 'cool':
                bgImg.filters.push(new fabric.Image.filters.Brightness({ brightness: -0.05 }));
                break;
            case 'dramatic':
                bgImg.filters.push(new fabric.Image.filters.Contrast({ contrast: 0.3 }));
                bgImg.filters.push(new fabric.Image.filters.Brightness({ brightness: -0.1 }));
                break;
            case 'soft':
                bgImg.filters.push(new fabric.Image.filters.Blur({ blur: 0.5 }));
                bgImg.filters.push(new fabric.Image.filters.Brightness({ brightness: 0.1 }));
                break;
        }
        
        bgImg.applyFilters();
        fabricCanvas.renderAll();
        saveHistory();
    }
    
    // Video tools
    function initVideoTools() {
        $('#btn-video-tools').on('click', function() {
            togglePanel('video-tools-controls');
        });
        
        $('#video-speed').on('input', function() {
            var speed = $(this).val();
            $('#speed-value').text(speed + 'x');
            var video = document.getElementById('editor-video');
            if (video) {
                video.playbackRate = parseFloat(speed);
            }
        });
        
        $('#video-volume').on('input', function() {
            var volume = $(this).val() / 100;
            $('#volume-value').text(Math.round(volume * 100) + '%');
            var video = document.getElementById('editor-video');
            if (video) {
                video.volume = volume;
            }
        });
    }
    
    // Adjustments
    function initAdjustments() {
        $('#btn-adjustments').on('click', function() {
            togglePanel('adjustments-controls');
        });
        
        $('#adjust-hue, #adjust-exposure, #adjust-vibrance, #adjust-highlights, #adjust-shadows, #adjust-whites, #adjust-blacks').on('input', function() {
            var id = $(this).attr('id').replace('adjust-', '');
            $('#' + id + '-value').text($(this).val());
        });
        
        $('#apply-adjustments').on('click', function() {
            applyAdjustments();
        });
        
        $('#reset-adjustments').on('click', function() {
            $('#adjust-hue, #adjust-exposure, #adjust-vibrance, #adjust-highlights, #adjust-shadows, #adjust-whites, #adjust-blacks').val(0).trigger('input');
        });
    }
    
    function applyAdjustments() {
        if (!fabricCanvas || !fabricCanvas.backgroundImage) return;
        
        var bgImg = fabricCanvas.backgroundImage;
        var hue = parseInt($('#adjust-hue').val());
        var exposure = parseInt($('#adjust-exposure').val()) / 100;
        var vibrance = parseInt($('#adjust-vibrance').val()) / 100;
        
        // Apply adjustments using filters
        if (hue !== 0) {
            // Hue adjustment would require custom filter
        }
        if (exposure !== 0) {
            bgImg.filters.push(new fabric.Image.filters.Brightness({ brightness: exposure }));
        }
        if (vibrance !== 0) {
            bgImg.filters.push(new fabric.Image.filters.Saturation({ saturation: vibrance }));
        }
        
        bgImg.applyFilters();
        fabricCanvas.renderAll();
        saveHistory();
    }
    
    // Effects
    function initEffects() {
        $('#btn-effects').on('click', function() {
            togglePanel('effects-controls');
        });
        
        $('#effect-noise, #effect-pixelate, #effect-vignette').on('input', function() {
            var id = $(this).attr('id').replace('effect-', '');
            $('#' + id + '-value').text($(this).val());
        });
        
        $('#apply-effects').on('click', function() {
            applyEffects();
        });
        
        $('#reset-effects').on('click', function() {
            $('#effect-noise').val(0).trigger('input');
            $('#effect-pixelate').val(1).trigger('input');
            $('#effect-vignette').val(0).trigger('input');
        });
    }
    
    function applyEffects() {
        if (!fabricCanvas || !fabricCanvas.backgroundImage) return;
        
        var bgImg = fabricCanvas.backgroundImage;
        var noise = parseInt($('#effect-noise').val());
        var pixelate = parseInt($('#effect-pixelate').val());
        
        if (pixelate > 1) {
            bgImg.filters.push(new fabric.Image.filters.Pixelate({
                blocksize: pixelate
            }));
        }
        
        bgImg.applyFilters();
        fabricCanvas.renderAll();
        saveHistory();
    }
    
    // Initialize when editor is ready
    $(document).ready(function() {
        setTimeout(function() {
            if (typeof fabricCanvas !== 'undefined' && fabricCanvas) {
                initAdvancedFeatures();
                initAdjustments();
                initEffects();
            }
        }, 1000);
    });
    
    // Expose functions globally
    window.clarkesMediaEditorAdvanced = {
        initAdvancedFeatures: initAdvancedFeatures,
        saveHistory: saveHistory,
        undo: undo,
        redo: redo,
        zoomIn: zoomIn,
        zoomOut: zoomOut,
        zoomFit: zoomFit,
        updateLayers: updateLayers
    };
    
})(jQuery);

