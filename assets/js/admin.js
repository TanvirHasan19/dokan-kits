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
            this.handleDependencies();
            this.saveSettings();
            this.showSuccessMessage();
            this.enhanceNumberFields();
        },

        /**
         * Handle toggle switches
         */
        toggleSwitches: function() {
            // Update status text when toggle buttons are changed
            $('.toggle-label input[type="checkbox"]').on('change', function() {
                const statusText = $(this).closest('.type-bu-si, .toggle-label').find('.status-text');
                statusText.text(this.checked ? 'Active' : 'Inactive');
                // Trigger the dependency check
                $(document).trigger('dokan_kits:toggle_changed', [this]);
            });
        },
        
        /**
         * Handle field dependencies
         */
        handleDependencies: function() {
            // Function to check dependencies and show/hide fields
            const checkDependencies = function(changedElement) {
                // Process all fields with dependencies
                $('.type-bu-si').each(function() {
                    const $field = $(this);
                    const dependencyAttr = $field.data('dependency');
                    
                    if (dependencyAttr) {
                        const dependency = JSON.parse(dependencyAttr);
                        const $dependsOn = $('#' + dependency.id);
                        
                        if ($dependsOn.length) {
                            const dependsOnValue = $dependsOn.is(':checked') ? '1' : '0';
                            
                            if (dependsOnValue === dependency.value) {
                                $field.slideDown(200);
                            } else {
                                $field.slideUp(200);
                            }
                        }
                    }
                });
            };
            
            // Initial check on page load
            checkDependencies();
            
            // Check when toggles change
            $(document).on('dokan_kits:toggle_changed', function(e, changedElement) {
                checkDependencies(changedElement);
            });
        },

        /**
         * Enhance number input fields
         */
        enhanceNumberFields: function() {
            // Add increment/decrement buttons functionality
            $('.number-input-container input[type="number"]').each(function() {
                const $input = $(this);
                const min = parseFloat($input.attr('min') || 0);
                const max = parseFloat($input.attr('max') || 999999);
                const step = parseFloat($input.attr('step') || 1);
                
                // Validate input when changed
                $input.on('change', function() {
                    let value = parseFloat($input.val());
                    
                    if (isNaN(value)) {
                        value = min;
                    }
                    
                    if (value < min) {
                        value = min;
                    }
                    
                    if (value > max) {
                        value = max;
                    }
                    
                    $input.val(value);
                });
                
                // Enhance keyboard navigation
                $input.on('keydown', function(e) {
                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        let value = parseFloat($input.val()) + step;
                        if (value > max) value = max;
                        $input.val(value);
                    } else if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        let value = parseFloat($input.val()) - step;
                        if (value < min) value = min;
                        $input.val(value);
                    }
                });
            });
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
                
                // Animate the button
                $('#dokan_kits_save_ch .button-primary').addClass('saving');
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
                saveMessage.addClass('visible').show();
                
                // Hide after animation completes
                setTimeout(function() {
                    saveMessage.removeClass('visible').fadeOut();
                }, 3000);
            }
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        DokanKitsAdmin.init();
    });

})(jQuery);