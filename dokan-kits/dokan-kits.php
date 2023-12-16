<?php
/**
* Plugin Name: Dokan Kits
* Plugin URI: https://wordpress.org/plugins/dokankits
* Description: A Helper Toolkits plugin for Dokan
* Version: 1.0.0
* Author: Tanvir Hasan
* Author URI: https://tanvirdevpool.com
* Text Domain: dokan_kits
* License: GPL2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Copyright (c) 2023 Tanvir Hasan (email: th.shovon2014@gmail.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// Enqueue Font Awesome CSS
function dokan_kits_enqueue_styles() {
    wp_enqueue_style('font-awesome', plugin_dir_url(__FILE__) . 'font-awesome.min.css'); // Replace with the correct path to your Font Awesome CSS file
}
add_action('admin_enqueue_scripts', 'dokan_kits_enqueue_styles');

// Enqueue your custom CSS for the plugin interface from the "assets" folder
function dokan_kits_enqueue_custom_css() {
    wp_enqueue_style('dokan-kits-interface-styles', plugin_dir_url(__FILE__) . 'assets/dokan-kits-interface.css');
}
add_action('admin_enqueue_scripts', 'dokan_kits_enqueue_custom_css');

// Add a menu item with a Font Awesome "tools" icon
function dokan_kits_add_menu_item() {
    add_menu_page('Dokan Kits', 'Dokan Kits', 'manage_options', 'dokan-kits', 'dokan_kits_settings_page', 'dashicons-shop'); // Use 'dashicons-admin-tools' class for the "tools" icon
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
        <form method="post" action="">
            <div class="dokan_kits_style_box">
                <div class="checkbox-label">
                    <label for="remove_vendor_checkbox" class="for_title_label">Remove Vendor Registration</label>
                    <input type="checkbox" id="remove_vendor_checkbox" name="remove_vendor_checkbox" value="1" <?php checked(get_option('remove_vendor_checkbox'), 1); ?>>
                </div>
                <p class="additional-text">Remove "I am a vendor" option from the WooCommerce my account page.</p>
            </div>
            <div class="dokan_kits_style_box">
                <div class="checkbox-label">
                    <label for="remove_split_shipping_checkbox" class="for_title_label">Remove Split Shipping Dokan Lite</label>
                    <input type="checkbox" id="remove_split_shipping_checkbox" name="remove_split_shipping_checkbox" value="1" <?php checked(get_option('remove_split_shipping_checkbox'), 1); ?>>
                </div>
                <p class="additional-text">Remove split shipping from the WooCommerce cart and checkout page using the Dokan Lite plugin.</p>
            </div>
            <div class="dokan_kits_style_box">
                <div class="checkbox-label">
                    <label for="remove_split_shipping_pro_checkbox" class="for_title_label">Remove Split Shipping Dokan Pro</label>
                    <input type="checkbox" id="remove_split_shipping_pro_checkbox" name="remove_split_shipping_pro_checkbox" value="1" <?php checked(get_option('remove_split_shipping_pro_checkbox'), 1); ?>>
                </div>
                <p class="additional-text">Remove Split Shipping from the WooCommerce Cart and Checkout page using the Dokan Pro plugin.</p>
            </div>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Save checkbox values
function dokan_kits_save_settings() {
    if (isset($_POST['remove_vendor_checkbox'])) {
        update_option('remove_vendor_checkbox', 1);
    } else {
        update_option('remove_vendor_checkbox', 0);
    }

    if (isset($_POST['remove_split_shipping_checkbox'])) {
        update_option('remove_split_shipping_checkbox', 1);
    } else {
        update_option('remove_split_shipping_checkbox', 0);
    }

    if (isset($_POST['remove_split_shipping_pro_checkbox'])) {
        update_option('remove_split_shipping_pro_checkbox', 1);
    } else {
        update_option('remove_split_shipping_pro_checkbox', 0);
    }

    if (isset($_POST['allow_vendor_purchase_own_products_checkbox'])) {
        update_option('allow_vendor_purchase_own_products_checkbox', 1);
    } else {
        update_option('allow_vendor_purchase_own_products_checkbox', 0);
    }

    if (isset($_POST['remove_vendor_name_checkbox'])) {
        update_option('remove_vendor_name_checkbox', 1);
    } else {
        update_option('remove_vendor_name_checkbox', 0);
    }
}
add_action('admin_init', 'dokan_kits_save_settings');

// Remove actions if the checkboxes are checked
function dokan_kits_remove_actions() {
    if (get_option('remove_vendor_checkbox') === '1') {
        remove_action('woocommerce_register_form', 'dokan_seller_reg_form_fields');
    }

    if (get_option('remove_split_shipping_checkbox') === '1') {
        dokan_lite_remove_split_shipping();
    }

    if (get_option('remove_split_shipping_pro_checkbox') === '1') {
        dokan_pro_remove_split_shipping();
    }


}
add_action('init', 'dokan_kits_remove_actions');

// Function to remove split shipping
function dokan_lite_remove_split_shipping() {
    dokan_remove_hook_for_anonymous_class( 'woocommerce_cart_shipping_packages', 'WeDevs\Dokan\Shipping\Hooks', 'split_shipping_packages', 10 );
    dokan_remove_hook_for_anonymous_class( 'woocommerce_checkout_create_order_shipping_item', 'WeDevs\Dokan\Shipping\Hooks', 'add_shipping_pack_meta', 10 );
    dokan_remove_hook_for_anonymous_class( 'woocommerce_shipping_package_name', 'WeDevs\Dokan\Shipping\Hooks', 'change_shipping_pack_name', 10 );
}

// Function to remove split shipping using Dokan Pro
function dokan_pro_remove_split_shipping() {
    remove_filter( 'woocommerce_cart_shipping_packages', 'dokan_custom_split_shipping_packages' );
    remove_filter( 'woocommerce_shipping_package_name', 'dokan_change_shipping_pack_name');
    remove_action( 'woocommerce_checkout_create_order_shipping_item', 'dokan_add_shipping_pack_meta');
}