/**
 * AI Content Creator JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Main AI Content Generator (Standalone Page)
        $('#btn-generate-content').on('click', function() {
            var $btn = $(this);
            var $form = $('#ai-content-form');
            var $result = $('#ai-content-result');
            var $loading = $('#ai-content-loading');
            
            var contentType = $('#content-type').val();
            var topic = $('#content-topic').val();
            var tone = $('#content-tone').val();
            var length = $('#content-length').val();
            var keywords = $('#content-keywords').val();
            
            if (!topic.trim()) {
                alert('Please enter a topic/subject');
                return;
            }
            
            $btn.prop('disabled', true).text('Generating...');
            $result.hide();
            $loading.show();
            
            $.ajax({
                url: clarkesAIContent.ajax_url,
                type: 'POST',
                data: {
                    action: 'clarkes_generate_ai_content',
                    nonce: clarkesAIContent.nonce,
                    content_type: contentType,
                    topic: topic,
                    tone: tone,
                    length: length,
                    keywords: keywords
                },
                success: function(response) {
                    $loading.hide();
                    $btn.prop('disabled', false).text('✨ Generate Content');
                    
                    if (response.success) {
                        $('#ai-content-output').html(response.data.content.replace(/\n/g, '<br>'));
                        $result.show();
                    } else {
                        alert('Error: ' + response.data.message);
                    }
                },
                error: function(xhr, status, error) {
                    $loading.hide();
                    $btn.prop('disabled', false).text('✨ Generate Content');
                    alert('An error occurred: ' + error);
                }
            });
        });
        
        // Copy to Clipboard
        $('#btn-copy-content').on('click', function() {
            var content = $('#ai-content-output').text();
            if (navigator.clipboard) {
                navigator.clipboard.writeText(content).then(function() {
                    alert('Content copied to clipboard!');
                });
            } else {
                // Fallback
                var $temp = $('<textarea>');
                $('body').append($temp);
                $temp.val(content).select();
                document.execCommand('copy');
                $temp.remove();
                alert('Content copied to clipboard!');
            }
        });
        
        // Regenerate
        $('#btn-regenerate-content').on('click', function() {
            $('#btn-generate-content').click();
        });
        
        // Editor Meta Box AI Content Generation
        $('#btn-ai-generate-editor').on('click', function() {
            var $btn = $(this);
            var $result = $('#ai-content-result-editor');
            var $loading = $('#ai-content-loading-editor');
            var $output = $('#ai-content-output-editor');
            
            var prompt = $('#ai-content-prompt').val();
            var contentType = $('#ai-content-type-editor').val();
            
            if (!prompt.trim()) {
                alert('Please enter what the content should be about');
                return;
            }
            
            $btn.prop('disabled', true).text('Generating...');
            $result.hide();
            $loading.show();
            
            $.ajax({
                url: clarkesAIContent.ajax_url,
                type: 'POST',
                data: {
                    action: 'clarkes_generate_ai_content',
                    nonce: clarkesAIContent.nonce,
                    content_type: contentType === 'excerpt' ? 'description' : contentType,
                    topic: prompt,
                    tone: 'professional',
                    length: contentType === 'content' || contentType === 'title' ? 'medium' : 'short',
                    keywords: ''
                },
                success: function(response) {
                    $loading.hide();
                    $btn.prop('disabled', false).text('✨ Generate with AI');
                    
                    if (response.success) {
                        $output.html(response.data.content.replace(/\n/g, '<br>'));
                        $result.show();
                        window.generatedAIContent = response.data.content; // Store for use
                    } else {
                        alert('Error: ' + response.data.message);
                    }
                },
                error: function(xhr, status, error) {
                    $loading.hide();
                    $btn.prop('disabled', false).text('✨ Generate with AI');
                    alert('An error occurred: ' + error);
                }
            });
        });
        
        // Use Generated Content in Editor
        $('#btn-ai-use-content').on('click', function() {
            if (!window.generatedAIContent) {
                alert('No content generated yet');
                return;
            }
            
            var contentType = $('#ai-content-type-editor').val();
            var content = window.generatedAIContent;
            
            if (contentType === 'title') {
                $('#title').val(content);
            } else if (contentType === 'excerpt') {
                if ($('#excerpt').length) {
                    $('#excerpt').val(content);
                } else {
                    // Try to find excerpt field
                    $('textarea[name="excerpt"]').val(content);
                }
            } else if (contentType === 'content') {
                // Use WordPress editor
                if (typeof tinyMCE !== 'undefined' && tinyMCE.get('content')) {
                    tinyMCE.get('content').setContent(content);
                } else if ($('#content').length) {
                    $('#content').val(content);
                }
            } else if (contentType === 'meta_description') {
                // Try to find meta description field
                $('input[name="_clarkes_seo_description"], textarea[name="_clarkes_seo_description"]').val(content);
            }
            
            alert('Content inserted!');
        });
        
        // Regenerate in Editor
        $('#btn-ai-regenerate-editor').on('click', function() {
            $('#btn-ai-generate-editor').click();
        });
        
    });
    
})(jQuery);

