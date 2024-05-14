<?php
/**
* Plugin Name: Dokan Kits
* Plugin URI: https://wordpress.org/plugins/dokan-kits
* Description: A Helper Toolkits plugin for Dokan
* Version: 1.0.1
* Author: Tanvir Hasan
* Author URI: https://profiles.wordpress.org/tanvirh/
* Dokan requires at least: 3.9.7
* Dokan tested up to: 3.10.4 
* Text Domain: dokan-kits
* License: GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Don't call the file directly
if (!defined('ABSPATH')) {
    exit;
}

// Initialize Dokan Kits after Dokan check
add_action('admin_init', 'dokan_kits_initialize');

function dokan_kits_initialize() {
    if (!is_plugin_active('dokan-lite/dokan.php')) {
        add_action('admin_notices', 'dokan_kits_warning_for_activation');
        deactivate_plugins(plugin_basename(__FILE__));
    }
    else {
        add_action('admin_menu', 'dokan_kits_add_menu_item');
        add_action('init', 'dokan_kits_remove_actions');
    }
}

// Warning message if Dokan is not activated
function dokan_kits_warning_for_activation() {
    ?>
    <div class="notice notice-error">
        <p><?php _e('Please activate Dokan Lite first to use Dokan Kits.', 'dokan-kits'); ?></p>
    </div>
    <?php
}

// Enqueue Font Awesome CSS
function dokan_kits_enqueue_styles() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');
} 
add_action('admin_enqueue_scripts', 'dokan_kits_enqueue_styles');

// Enqueue your custom CSS for the plugin interface from the "assets" folder
function dokan_kits_enqueue_custom_css() {
    wp_enqueue_style('dokan-kits-interface-styles', plugin_dir_url(__FILE__) . 'assets/dokan-kits-interface.css', array(), '1.0.0');
}
add_action('admin_enqueue_scripts', 'dokan_kits_enqueue_custom_css');

// Add a menu item with a Font Awesome "tools" icon
function dokan_kits_add_menu_item() {
    add_menu_page('Dokan Kits', 'Dokan Kits', 'manage_options', 'dokan-kits', 'dokan_kits_settings_page', 'dashicons-editor-unlink');
}
add_action('admin_menu', 'dokan_kits_add_menu_item');

// Settings page with a description, checkboxes, and additional text
function dokan_kits_settings_page() {
    ?>
    <div class="wrap">
        <div class="dokan_kits_description-box">
        <h1>Dokan Kits Settings</h1>
        <div class="description-box">
            <p>This plugin provides you with tools to enhance your Dokan experience. Use this plugin to remove or modify various elements and more.</p>
        </div>
        </div>
        <form method="post" action="options.php">
            <?php settings_fields('dokan_kits_settings_group'); ?>
            <div class="dokan_kits_style_box">
                <div class="checkbox-label">
                    <label for="remove_vendor_checkbox" class="for_title_label">Remove Vendor Registration</label>
                    <input type="checkbox" id="remove_vendor_checkbox" name="remove_vendor_checkbox" value="1" <?php checked(get_option('remove_vendor_checkbox'), 1); if (!is_plugin_active('dokan-lite/dokan.php')) echo 'disabled'; ?>>
                </div>
                <p class="additional-text">Remove "I am a vendor" option from the WooCommerce my account page.</p>
            </div>
            <div class="dokan_kits_style_box">
                <div class="checkbox-label">
                    <label for="remove_split_shipping_checkbox" class="for_title_label">Remove Split Shipping Dokan Lite</label>
                    <input type="checkbox" id="remove_split_shipping_checkbox" name="remove_split_shipping_checkbox" value="1" <?php checked(get_option('remove_split_shipping_checkbox'), 1); if (!is_plugin_active('dokan-lite/dokan.php')) echo 'disabled'; ?>>
                </div>
                <p class="additional-text">Remove split shipping from the WooCommerce cart and checkout page using the Dokan Lite plugin.</p>
            </div>
            <div class="dokan_kits_style_box">
                <div class="checkbox-label">
                    <label for="remove_split_shipping_pro_checkbox" class="for_title_label">Remove Split Shipping Dokan Pro</label>
                    <input type="checkbox" id="remove_split_shipping_pro_checkbox" name="remove_split_shipping_pro_checkbox" value="1" <?php checked(get_option('remove_split_shipping_pro_checkbox'), 1); if (!is_plugin_active('dokan-lite/dokan.php')) echo 'disabled'; ?>>
                </div>
                <p class="additional-text">Remove Split Shipping from the WooCommerce Cart and Checkout page using the Dokan Pro plugin.</p>
            </div>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register and initialize plugin settings
function dokan_kits_register_settings() {
    // Register settings group
    register_setting('dokan_kits_settings_group', 'remove_vendor_checkbox');
    register_setting('dokan_kits_settings_group', 'remove_split_shipping_checkbox');
    register_setting('dokan_kits_settings_group', 'remove_split_shipping_pro_checkbox');
    // Add other settings similarly
}
add_action('admin_init', 'dokan_kits_register_settings');

// Remove actions if the checkboxes are checked
function dokan_kits_remove_actions() {
    if (!function_exists('dokan_remove_hook_for_anonymous_class') || !class_exists('WeDevs\Dokan\Shipping\Hooks')) {
        return;
    }

    if (get_option('remove_vendor_checkbox') === '1') {
        remove_action('woocommerce_register_form', 'dokan_seller_reg_form_fields');
    }

    if (get_option('remove_split_shipping_checkbox') === '1') {
        dokan_kits_lite_remove_split_shipping();
    }

    if (get_option('remove_split_shipping_pro_checkbox') === '1') {
        dokan_kits_pro_remove_split_shipping();
    }
    // Add other actions to remove similarly
}
add_action('init', 'dokan_kits_remove_actions');

// Function to remove split shipping
function dokan_kits_lite_remove_split_shipping() {
    dokan_remove_hook_for_anonymous_class( 'woocommerce_cart_shipping_packages', 'WeDevs\Dokan\Shipping\Hooks', 'split_shipping_packages', 10 );
    dokan_remove_hook_for_anonymous_class( 'woocommerce_checkout_create_order_shipping_item', 'WeDevs\Dokan\Shipping\Hooks', 'add_shipping_pack_meta', 10 );
    dokan_remove_hook_for_anonymous_class( 'woocommerce_shipping_package_name', 'WeDevs\Dokan\Shipping\Hooks', 'change_shipping_pack_name', 10 );
}

// Function to remove split shipping using Dokan Pro
function dokan_kits_pro_remove_split_shipping() {
    remove_filter( 'woocommerce_cart_shipping_packages', 'dokan_custom_split_shipping_packages' );
    remove_filter( 'woocommerce_shipping_package_name', 'dokan_change_shipping_pack_name');
    remove_action( 'woocommerce_checkout_create_order_shipping_item', 'dokan_add_shipping_pack_meta');
}
