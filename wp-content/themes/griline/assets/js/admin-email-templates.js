/**
 * Admin Email Templates JavaScript
 */

jQuery(document).ready(function($) {
    'use strict';

    // Preview Email Handler
    $('#preview-email').on('click', function(e) {
        e.preventDefault();

        var $button = $(this);
        var originalText = $button.text();

        // Get form data
        var formData = $('form').first().serializeArray();

        // Show loading
        $button.text('Generating Preview...').prop('disabled', true);
        $('#email-preview').html('<p>Loading preview...</p>');

        $.ajax({
            url: loyaltyTemplateData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'preview_email_template',
                nonce: loyaltyTemplateData.nonce,
                template_type: loyaltyTemplateData.template,
                language: loyaltyTemplateData.language,
                form_data: formData
            },
            success: function(response) {
                if (response.success) {
                    // Create iframe for preview
                    var iframe = $('<iframe></iframe>');
                    iframe.css({
                        'width': '100%',
                        'min-height': '600px',
                        'border': 'none'
                    });

                    $('#email-preview').html('').append(iframe);

                    // Write HTML to iframe
                    var iframeDoc = iframe[0].contentDocument || iframe[0].contentWindow.document;
                    iframeDoc.open();
                    iframeDoc.write(response.data.html);
                    iframeDoc.close();

                    // Adjust iframe height after load
                    iframe.on('load', function() {
                        try {
                            var height = $(this).contents().find('html').height();
                            $(this).height(height + 40);
                        } catch (e) {
                            console.log('Could not adjust iframe height:', e);
                        }
                    });
                } else {
                    $('#email-preview').html('<p class="error">Failed to generate preview: ' + response.data + '</p>');
                }
            },
            error: function() {
                $('#email-preview').html('<p class="error">An error occurred while generating the preview.</p>');
            },
            complete: function() {
                $button.text(originalText).prop('disabled', false);
            }
        });
    });

    // Send Test Email Handler
    $('#send-test-email').on('click', function(e) {
        e.preventDefault();

        var $button = $(this);
        var $emailInput = $('#test-email-address');
        var $messageDiv = $('#test-email-message');
        var testEmail = $emailInput.val().trim();

        // Validate email
        if (!testEmail || !isValidEmail(testEmail)) {
            $messageDiv.html('Please enter a valid email address.').removeClass('success').addClass('error');
            return;
        }

        // Show loading
        $button.text('Sending...').prop('disabled', true);
        $messageDiv.html('').removeClass('success error');

        $.ajax({
            url: loyaltyTemplateData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'send_test_email',
                nonce: loyaltyTemplateData.nonce,
                template_type: loyaltyTemplateData.template,
                language: loyaltyTemplateData.language,
                test_email: testEmail
            },
            success: function(response) {
                if (response.success) {
                    $messageDiv.html('✓ ' + response.data).addClass('success');
                } else {
                    $messageDiv.html('✗ ' + response.data).addClass('error');
                }
            },
            error: function() {
                $messageDiv.html('✗ An error occurred while sending the test email.').addClass('error');
            },
            complete: function() {
                $button.text('Send Test Email').prop('disabled', false);
            }
        });
    });

    // Email validation helper
    function isValidEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Auto-save warning for unsaved changes
    var formChanged = false;

    $('form input, form textarea').on('change', function() {
        formChanged = true;
    });

    $('form').on('submit', function() {
        formChanged = false;
    });

    $(window).on('beforeunload', function() {
        if (formChanged) {
            return 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    // Character counter for text inputs (optional enhancement)
    $('.large-text[type="text"]').each(function() {
        var $input = $(this);
        var maxLength = 255; // Most have 255 char limit

        if ($input.attr('id') === 'subject') {
            var $counter = $('<span class="char-counter" style="color: #666; font-size: 12px; margin-left: 10px;"></span>');
            $input.after($counter);

            function updateCounter() {
                var length = $input.val().length;
                $counter.text(length + ' / ' + maxLength + ' characters');
                if (length > maxLength - 20) {
                    $counter.css('color', '#d63638');
                } else {
                    $counter.css('color', '#666');
                }
            }

            $input.on('input', updateCounter);
            updateCounter();
        }
    });

    // WordPress Media Uploader for Images
    $('.upload-image-button').on('click', function(e) {
        e.preventDefault();

        var $button = $(this);
        var targetId = $button.data('target');
        var $input = $('#' + targetId);
        var $wrapper = $button.closest('.image-upload-field');
        var $previewWrapper = $wrapper.find('.image-preview-wrapper');

        // Create a fresh media uploader instance for each button click
        var mediaUploader = wp.media({
            title: 'Select or Upload Image',
            button: {
                text: 'Use this image'
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });

        // When an image is selected, update the field
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();

            // Set the image URL in the hidden input
            $input.val(attachment.url);

            // Update preview
            var $preview = $previewWrapper.find('.image-preview');
            if ($preview.length) {
                $preview.attr('src', attachment.url);
            } else {
                $previewWrapper.html('<img src="' + attachment.url + '" class="image-preview" style="max-width: 150px; height: auto; display: block; margin-bottom: 10px;">');
            }

            // Update button text
            $button.text('Change Image');

            // Add remove button if it doesn't exist
            if (!$wrapper.find('.remove-image-button').length) {
                $button.after('<button type="button" class="button remove-image-button" data-target="' + targetId + '">Remove Image</button>');
            }

            // Mark form as changed
            formChanged = true;
        });

        // Open the media uploader
        mediaUploader.open();
    });

    // Remove image handler
    $(document).on('click', '.remove-image-button', function(e) {
        e.preventDefault();

        var $button = $(this);
        var targetId = $button.data('target');
        var $input = $('#' + targetId);
        var $wrapper = $button.closest('.image-upload-field');
        var $previewWrapper = $wrapper.find('.image-preview-wrapper');
        var $uploadButton = $wrapper.find('.upload-image-button');

        // Clear the input
        $input.val('');

        // Remove preview
        $previewWrapper.html('');

        // Update button text
        $uploadButton.text('Upload Image');

        // Remove the remove button
        $button.remove();

        // Mark form as changed
        formChanged = true;
    });
});
