<?php
/**
 * Plugin Name: Dokan Kits
 * Plugin URI: https://wordpress.org/plugins/dokan-kits
 * Description: A Helper Toolkits plugin for Dokan
 * Version: 1.0.6
 * Author: Tanvir Hasan
 * Author URI: https://profiles.wordpress.org/tanvirh/
 * Dokan requires at least: 3.9.7
 * Dokan tested up to: 3.11.3
 * Text Domain: dokan-kits
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

function dokan_kits_initialize() {
    if (!is_plugin_active('dokan-lite/dokan.php')) {
        add_action('admin_notices', 'dokan_kits_warning_for_activation');
        deactivate_plugins(plugin_basename(__FILE__));
    } else {
        add_action('admin_menu', 'dokan_kits_add_menu_item');
        add_action('init', 'dokan_kits_remove_actions');
    }
}
add_action('admin_init', 'dokan_kits_initialize');

function dokan_kits_warning_for_activation() {
    ?>
    <div class="notice notice-error">
        <p><?php _e('Please activate Dokan Lite first to use Dokan Kits.', 'dokan-kits'); ?></p>
    </div>
    <?php
}

function dokan_kits_enqueue_styles() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');
}
add_action('admin_enqueue_scripts', 'dokan_kits_enqueue_styles');

function dokan_kits_enqueue_custom_css() {
    wp_enqueue_style('dokan-kits-interface-styles', plugin_dir_url(__FILE__) . 'assets/dokan-kits-interface.css', array(), '1.0.0');
}
add_action('admin_enqueue_scripts', 'dokan_kits_enqueue_custom_css');

function dokan_kits_add_menu_item() {
    add_menu_page('Dokan Kits', 'Dokan Kits', 'manage_options', 'dokan-kits', 'dokan_kits_settings_page', 'dashicons-editor-unlink');
}
add_action('admin_menu', 'dokan_kits_add_menu_item');

function dokan_kits_settings_page() {
    ?>
    <div class="dokan-kits-wrap">
        <div class="dokan_kits_description-box dokan_kits_style_box">
            <h1>Dokan Kits Settings</h1>
            <div class="description-box">
                <p>This plugin provides you with tools to enhance your Dokan experience. Use this plugin to remove or modify various elements and more.</p>
            </div>
        </div>
        <form method="post" action="options.php">
            <?php settings_fields('dokan_kits_settings_group'); ?>
            <div id="dokan-kits-body-content">
                <div class="dokan_kits_style_box">
                    <i class="fa fa-users fa-3x"></i>
                    <div class="toggle-label">
                        <label for="remove_vendor_checkbox" class="for_title_label">Remove Vendor Registration</label>
                        <label class="switch">
                            <input type="checkbox" id="remove_vendor_checkbox" name="remove_vendor_checkbox" value="1" <?php checked(get_option('remove_vendor_checkbox'), 1); if (!is_plugin_active('dokan-lite/dokan.php')) echo 'disabled'; ?>>
                            <span class="slider"></span>
                        </label>
                        <span class="status-text"><?php echo get_option('remove_vendor_checkbox') ? 'Active' : 'Inactive'; ?></span>
                    </div>
                    <p class="additional-text">Remove "I am a vendor" option from the WooCommerce my account page.</p>
                </div>
                
                <div class="dokan_kits_style_box">
                    <i class="fa fa-user-check fa-3x"></i>
                    <div class="toggle-label">
                        <label for="set_default_seller_role_checkbox" class="for_title_label">Enable "I am a Vendor" by default</label>
                        <label class="switch">
                            <input type="checkbox" id="set_default_seller_role_checkbox" name="set_default_seller_role_checkbox" value="1" <?php checked(get_option('set_default_seller_role_checkbox'), 1); if (!is_plugin_active('dokan-lite/dokan.php')) echo 'disabled'; ?>>
                            <span class="slider"></span>
                        </label>
                        <span class="status-text"><?php echo get_option('set_default_seller_role_checkbox') ? 'Active' : 'Inactive'; ?></span>
                    </div>
                    <p class="additional-text">To enable the "I am a Vendor" option by default on the My Account page.</p>
                </div>
                
                <!-- New "Remove Become a Vendor Button" option -->
                <div class="dokan_kits_style_box">
                    <i class="fa fa-user-times fa-3x"></i>
                    <div class="toggle-label">
                        <label for="remove_become_a_vendor_button_checkbox" class="for_title_label">Remove Become a Vendor Button</label>
                        <label class="switch">
                            <input type="checkbox" id="remove_become_a_vendor_button_checkbox" name="remove_become_a_vendor_button_checkbox" value="1" <?php checked(get_option('remove_become_a_vendor_button_checkbox'), 1); if (!is_plugin_active('dokan-lite/dokan.php')) echo 'disabled'; ?>>
                            <span class="slider"></span>
                        </label>
                        <span class="status-text"><?php echo get_option('remove_become_a_vendor_button_checkbox') ? 'Active' : 'Inactive'; ?></span>
                    </div>
                    <p class="additional-text">Remove Become a Vendor button from the WooCommerce My Account page.</p>
                </div>

            
                <div class="dokan_kits_style_box">
                    <i class="fa fa-truck fa-3x"></i>
                    <div class="toggle-label">
                        <label for="remove_split_shipping_checkbox" class="for_title_label">Remove Split Shipping Dokan Lite</label>
                        <label class="switch">
                            <input type="checkbox" id="remove_split_shipping_checkbox" name="remove_split_shipping_checkbox" value="1" <?php checked(get_option('remove_split_shipping_checkbox'), 1); if (!is_plugin_active('dokan-lite/dokan.php')) echo 'disabled'; ?>>
                            <span class="slider"></span>
                        </label>
                        <span class="status-text"><?php echo get_option('remove_split_shipping_checkbox') ? 'Active' : 'Inactive'; ?></span>
                    </div>
                    <p class="additional-text">Remove split shipping from the WooCommerce cart and checkout page using the Dokan Lite plugin.</p>
                </div>

                <div class="dokan_kits_style_box">
                    <i class="fa fa-dolly fa-3x"></i>
                    <div class="toggle-label">
                        <label for="remove_split_shipping_pro_checkbox" class="for_title_label">Remove Split Shipping Dokan Pro</label>
                        <label class="switch">
                            <input type="checkbox" id="remove_split_shipping_pro_checkbox" name="remove_split_shipping_pro_checkbox" value="1" <?php checked(get_option('remove_split_shipping_pro_checkbox'), 1); if (!is_plugin_active('dokan-lite/dokan.php')) echo 'disabled'; ?>>
                            <span class="slider"></span>
                        </label>
                        <span class="status-text"><?php echo get_option('remove_split_shipping_pro_checkbox') ? 'Active' : 'Inactive'; ?></span>
                    </div>
                    <p class="additional-text">Remove Split Shipping from the WooCommerce Cart and Checkout page using the Dokan Pro plugin.</p>
                </div>

                <!-- New "Hide Add to Cart Button" option -->
                <div class="dokan_kits_style_box">
                    <i class="fa fa-shopping-cart fa-3x"></i>
                    <div class="toggle-label">
                        <label for="hide_add_to_cart_button_checkbox" class="for_title_label">Hide Add to Cart Button</label>
                        <label class="switch">
                            <input type="checkbox" id="hide_add_to_cart_button_checkbox" name="hide_add_to_cart_button_checkbox" value="1" <?php checked(get_option('hide_add_to_cart_button_checkbox'), 1); if (!is_plugin_active('dokan-lite/dokan.php')) echo 'disabled'; ?>>
                            <span class="slider"></span>
                        </label>
                        <span class="status-text"><?php echo get_option('hide_add_to_cart_button_checkbox') ? 'Active' : 'Inactive'; ?></span>
                    </div>
                    <p class="additional-text">Hide Add to Cart Button From WooCommerce Product Page.</p>
                </div>

                <!-- New "Remove Product Types" option -->
                <div class="dokan_kits_style_box">
                    <i class="fa fa-tags fa-3x"></i>
                    <div class="toggle-label">
                        <label class="for_title_label">Remove Product Types</label>
                        <div class="additional-text">Remove product types from the Dokan single product page</div>
                        <div class="toggle-group re_product_toggle">
                            <div class="type-bu-si">
                                <label for="remove_variable_product_checkbox" class="for_title_label">Variable</label>
                                <label class="switch">
                                    <input type="checkbox" id="remove_variable_product_checkbox" name="remove_variable_product_checkbox" value="1" <?php checked(get_option('remove_variable_product_checkbox'), 1); ?>>
                                    <span class="slider"></span>
                                </label>
                                <span class="status-text"><?php echo get_option('remove_variable_product_checkbox') ? 'Active' : 'Inactive'; ?></span>
                            </div>
                            <div class="type-bu-si">
                                <label for="remove_external_product_checkbox" class="for_title_label">External</label>
                                <label class="switch">
                                    <input type="checkbox" id="remove_external_product_checkbox" name="remove_external_product_checkbox" value="1" <?php checked(get_option('remove_external_product_checkbox'), 1); ?>>
                                    <span class="slider"></span>
                                </label>
                                <span class="status-text"><?php echo get_option('remove_external_product_checkbox') ? 'Active' : 'Inactive'; ?></span>
                            </div>
                            <div class="type-bu-si">
                                <label for="remove_grouped_product_checkbox" class="for_title_label">Grouped</label>
                                <label class="switch">
                                    <input type="checkbox" id="remove_grouped_product_checkbox" name="remove_grouped_product_checkbox" value="1" <?php checked(get_option('remove_grouped_product_checkbox'), 1); ?>>
                                    <span class="slider"></span>
                                </label>
                                <span class="status-text"><?php echo get_option('remove_grouped_product_checkbox') ? 'Active' : 'Inactive'; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dokan_kits_style_box">
                    <i class="fa fa-cart-flatbed-suitcase fa-3x"></i>
                    <div class="toggle-label">
                        <label for="enable_own_product_purchase_checkbox" class="for_title_label">Enable Purchase of Own Products</label>
                        <label class="switch">
                            <input type="checkbox" id="enable_own_product_purchase_checkbox" name="enable_own_product_purchase_checkbox" value="1" <?php checked(get_option('enable_own_product_purchase_checkbox'), 1); if (!is_plugin_active('dokan-lite/dokan.php')) echo 'disabled'; ?>>
                            <span class="slider"></span>
                        </label>
                        <span class="status-text"><?php echo get_option('enable_own_product_purchase_checkbox') ? 'Active' : 'Inactive'; ?></span>
                    </div>
                    <p class="additional-text">Allow admin and vendors to purchase their own products.</p>
                </div>

            </div>
            <!-- Save Changes button -->
            <div id="dokan_kits_save_ch">           
            <?php submit_button('Save Changes', 'primary', 'save_changes_button'); ?>
            <div class="save-changes-message" style="display: none;">Changes saved successfully!</div>  
            </div>  
            <!-- Save Changes message -->
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Update status text color
                function updateStatusTextColor() {
                    var statusTexts = document.querySelectorAll('.status-text');
                    statusTexts.forEach(function(statusText) {
                        if (statusText.textContent === 'Active') {
                            statusText.style.color = 'green';
                        } else {
                            statusText.style.color = 'red';
                        }
                    });
                }

                // Initial color update
                updateStatusTextColor();

                // Update status text color when toggle buttons are changed
                var toggleButtons = document.querySelectorAll('.toggle-label input[type="checkbox"]');
                toggleButtons.forEach(function(button) {
                    button.addEventListener('change', function() {
                        updateStatusTextColor();
                        // Store the state of toggle button in local storage
                        localStorage.setItem(this.id, this.checked);
                    });
                });

                // Retrieve toggle button states from local storage on page load
                toggleButtons.forEach(function(button) {
                    var storedState = localStorage.getItem(button.id);
                    if (storedState === 'true') {
                        button.checked = true;
                    }
                });
            });
        </script>
    </div>
    <?php
}

function dokan_kits_register_settings() {
    register_setting('dokan_kits_settings_group', 'remove_vendor_checkbox');
    register_setting('dokan_kits_settings_group', 'set_default_seller_role_checkbox');
    register_setting('dokan_kits_settings_group', 'remove_split_shipping_checkbox');
    register_setting('dokan_kits_settings_group', 'remove_split_shipping_pro_checkbox');
    register_setting('dokan_kits_settings_group', 'hide_add_to_cart_button_checkbox');
    register_setting('dokan_kits_settings_group', 'remove_variable_product_checkbox');
    register_setting('dokan_kits_settings_group', 'remove_external_product_checkbox');
    register_setting('dokan_kits_settings_group', 'remove_grouped_product_checkbox');
    register_setting('dokan_kits_settings_group', 'enable_own_product_purchase_checkbox');
    register_setting('dokan_kits_settings_group', 'remove_become_a_vendor_button_checkbox');
}
add_action('admin_init', 'dokan_kits_register_settings');

function dokan_kits_remove_actions() {
    if (!function_exists('dokan_remove_hook_for_anonymous_class') || !class_exists('WeDevs\Dokan\Shipping\Hooks')) {
        return;
    }

    if (get_option('remove_vendor_checkbox') === '1') {
        add_action('init', 'remove_dokan_registration_hooks', 20);
    }
    
    if (get_option('set_default_seller_role_checkbox') === '1') {
        add_filter('dokan_seller_registration_default_role', 'set_dokan_seller_default_role');
    }

    if (get_option('remove_split_shipping_checkbox') === '1') {
        dokan_kits_lite_remove_split_shipping();
    }

    if (get_option('remove_split_shipping_pro_checkbox') === '1') {
        dokan_kits_pro_remove_split_shipping();
    }

    if (get_option('hide_add_to_cart_button_checkbox') === '1') {
        dokan_kits_hide_add_to_cart_button();
    }

    if (get_option('enable_own_product_purchase_checkbox') === '1') {
        remove_filter('woocommerce_is_purchasable', 'dokan_vendor_own_product_purchase_restriction', 10, 2);
        remove_filter('woocommerce_product_review_comment_form_args', 'dokan_vendor_product_review_restriction');
    }

    add_filter('dokan_product_types', 'dokan_kits_remove_product_types', 11);

    if (get_option('remove_become_a_vendor_button_checkbox') === '1') {
        add_action('init', 'remove_become_a_vendor_button');
    }
}
add_action('woocommerce_init', 'dokan_kits_remove_actions'); // Hook into WooCommerce initialization

function dokan_kits_lite_remove_split_shipping() {
    dokan_remove_hook_for_anonymous_class('woocommerce_cart_shipping_packages', 'WeDevs\Dokan\Shipping\Hooks', 'split_shipping_packages', 10);
    dokan_remove_hook_for_anonymous_class('woocommerce_checkout_create_order_shipping_item', 'WeDevs\Dokan\Shipping\Hooks', 'add_shipping_pack_meta', 10);
    dokan_remove_hook_for_anonymous_class('woocommerce_shipping_package_name', 'WeDevs\Dokan\Shipping\Hooks', 'change_shipping_pack_name', 10);
}

function dokan_kits_pro_remove_split_shipping() {
    remove_filter('woocommerce_cart_shipping_packages', 'dokan_custom_split_shipping_packages');
    remove_filter('woocommerce_shipping_package_name', 'dokan_change_shipping_pack_name');
    remove_action('woocommerce_checkout_create_order_shipping_item', 'dokan_add_shipping_pack_meta');
}

// Function to hide the Add to Cart button
function dokan_kits_hide_add_to_cart_button() {
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

    // Ensure to hide add to cart buttons that are added differently
    add_action('wp_enqueue_scripts', 'dokan_kits_hide_add_to_cart_button_css');
}

// Add custom CSS to hide Add to Cart buttons
function dokan_kits_hide_add_to_cart_button_css() {
    if (is_admin()) {
        return;
    }
    ?>
    <style>
        .single-product .product-type-simple .single_add_to_cart_button,
        .single-product .product-type-variable .single_add_to_cart_button,
        .product .add_to_cart_button {
            display: none !important;
        }
    </style>
    <?php
}

// Function to set the default seller role
function set_dokan_seller_default_role() {
    return 'seller';
}

// Function to remove specified product types
function dokan_kits_remove_product_types($product_types) {
    if (get_option('remove_variable_product_checkbox') === '1') {
        unset($product_types['variable']);
    }
    if (get_option('remove_external_product_checkbox') === '1') {
        unset($product_types['external']);
    }
    if (get_option('remove_grouped_product_checkbox') === '1') {
        unset($product_types['grouped']);
    }
    return $product_types;
}

function remove_become_a_vendor_button() {
    remove_action('woocommerce_after_my_account', [ dokan()->frontend_manager->become_a_vendor, 'render_become_a_vendor_section' ]);
}

function remove_dokan_registration_hooks() {
    // Remove Dokan's custom registration form fields
    remove_action('woocommerce_register_form', 'dokan_seller_reg_form_fields', 10);

    // Remove Dokan's registration validation
    remove_filter('woocommerce_process_registration_errors', [dokan()->registration, 'validate_registration'], 10);
    remove_filter('woocommerce_registration_errors', [dokan()->registration, 'validate_registration'], 10);
}
