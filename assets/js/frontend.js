/**
 * Dokan Kits Frontend JavaScript
 */
(function($) {
    'use strict';

    /**
     * Initialize frontend functions
     */
    const DokanKitsFrontend = {
        /**
         * Initialize
         */
        init: function() {
            // Add 'dokan-kits-hide-add-to-cart' class to body if needed
            if (dokanKitsFrontend.hideAddToCart === 'yes') {
                $('body').addClass('dokan-kits-hide-add-to-cart');
            }
            
            // Initialize image validation if on product edit page
            if (this.isVendorDashboard()) {
                this.initImageValidation();
            }
        },

        /**
         * Check if we're on vendor dashboard
         */
        isVendorDashboard: function() {
            return $('body').hasClass('dokan-dashboard');
        },

        /**
         * Initialize image validation
         */
        initImageValidation: function() {
            // This is now handled by the inline script in ImageRestrictions class
            // This method is kept for future extensibility
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        DokanKitsFrontend.init();
    });

})(jQuery);