<?php
namespace DokanKits\Controllers;

/**
 * Product Image Restrictions Controller
 *
 * @package DokanKits\Controllers
 */
class ImageRestrictionsController {
    /**
     * Constructor
     */
    public function __construct() {
        // Hook into product image upload process
        add_filter('dokan_ajax_gallery_upload_prefilter', [$this, 'validate_product_image'], 10, 1);
        add_filter('wp_handle_upload_prefilter', [$this, 'validate_product_image'], 10, 1);
    }

    /**
     * Validate product image dimensions and size
     *
     * @param array $file The uploaded file array
     * 
     * @return array Modified file array with error message if validation fails
     */
    public function validate_product_image($file) {
        // Only apply in vendor dashboard
        if (!function_exists('dokan_is_seller_dashboard') || !dokan_is_seller_dashboard()) {
            return $file;
        }

        // Verify this is an image
        if (substr($file['type'], 0, 6) != 'image/') {
            return $file;
        }

        // Check size restrictions
        if (get_option('enable_size_restrictions') === '1') {
            $max_size_mb = floatval(get_option('image_max_size', 2));
            $file_size_mb = $file['size'] / (1024 * 1024); // Convert to MB
            
            if ($file_size_mb > $max_size_mb) {
                $file['error'] = sprintf(
                    __('File size exceeds the maximum limit of %s MB.', 'dokan-kits'),
                    $max_size_mb
                );
                return $file;
            }
        }

        // Check dimension restrictions
        if (get_option('enable_dimension_restrictions') === '1') {
            $required_width = intval(get_option('image_max_width', 800));
            $required_height = intval(get_option('image_max_height', 800));
            
            // Get uploaded image dimensions
            $image_data = getimagesize($file['tmp_name']);
            
            if (!$image_data) {
                return $file;
            }
            
            list($width, $height) = $image_data;
            
            // Check if dimensions match requirements
            if ($width != $required_width || $height != $required_height) {
                $file['error'] = sprintf(
                    __('Image dimensions must be exactly %d×%d pixels.', 'dokan-kits'),
                    $required_width,
                    $required_height
                );
                return $file;
            }
        }

        return $file;
    }
}