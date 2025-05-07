/**
 * Dokan Kits Admin JavaScript
 */
(function($) {
    'use strict';

    /**
     * Initialize admin functions
     */
    const DokanKitsAdmin = {
        /**
         * Initialize
         */
        init: function() {
            this.toggleSwitches();
            this.saveSettings();
            this.showSuccessMessage();
        },

        /**
         * Handle toggle switches
         */
        toggleSwitches: function() {
            // Update status text color
            function updateStatusTextColor() {
                $('.status-text').each(function() {
                    if ($(this).text() === 'Active') {
                        $(this).css('color', 'green');
                    } else {
                        $(this).css('color', 'red');
                    }
                });
            }

            // Initial color update
            updateStatusTextColor();

            // Update status text and color when toggle buttons are changed
            $('.toggle-label input[type="checkbox"]').on('change', function() {
                const statusText = $(this).closest('.toggle-label').find('.status-text');
                statusText.text(this.checked ? 'Active' : 'Inactive');
                updateStatusTextColor();
            });

            // Toggle advanced settings visibility based on parent toggle
            $('#enable_dimension_restrictions').on('change', function() {
                const settingsDiv = $(this).closest('.dokan_kits_style_box').find('.image-restrictions-settings');
                if (this.checked) {
                    settingsDiv.slideDown();
                } else {
                    settingsDiv.slideUp();
                }
            });

            $('#enable_size_restrictions').on('change', function() {
                const settingsDiv = $(this).closest('.dokan_kits_style_box').find('.image-restrictions-settings');
                if (this.checked) {
                    settingsDiv.slideDown();
                } else {
                    settingsDiv.slideUp();
                }
            });

            // Initial visibility for advanced settings
            if (!$('#enable_dimension_restrictions').is(':checked')) {
                $('#enable_dimension_restrictions').closest('.dokan_kits_style_box').find('.image-restrictions-settings').hide();
            }

            if (!$('#enable_size_restrictions').is(':checked')) {
                $('#enable_size_restrictions').closest('.dokan_kits_style_box').find('.image-restrictions-settings').hide();
            }
        },

        /**
         * Handle settings save
         */
        saveSettings: function() {
            const form = $('form');
            const saveMessage = $('.save-changes-message');

            form.on('submit', function() {
                // Store a flag in localStorage to show success message after redirect
                localStorage.setItem('dokan_kits_settings_saved', 'true');
            });
        },

        /**
         * Show success message if settings were saved
         */
        showSuccessMessage: function() {
            // Check for settings-updated in URL or localStorage flag
            if (
                window.location.search.indexOf('settings-updated=true') > -1 || 
                localStorage.getItem('dokan_kits_settings_saved') === 'true'
            ) {
                // Clear the flag
                localStorage.removeItem('dokan_kits_settings_saved');
                
                // Show message
                const saveMessage = $('.save-changes-message');
                saveMessage.fadeIn();
                
                // Hide after 3 seconds
                setTimeout(function() {
                    saveMessage.fadeOut();
                }, 3000);
            }
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        DokanKitsAdmin.init();
    });

})(jQuery);