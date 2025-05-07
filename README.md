=== Dokan Kits ===

Plugin Name: Dokan Kits
Plugin URI: https://wordpress.org/plugins/dokan-kits
Description: The Helper Toolkits plugin for Dokan is a feature-packed add-on designed to streamline and enhance the functionality of your Dokan-powered multi-vendor marketplace.
Version: 3.0.0
Author: Tanvir Hasan
Author URI: https://profiles.wordpress.org/tanvirh/
Text Domain: dokan-kits
Requires at least: 6.4.2
Tested up to: 6.5.2
Dokan requires at least: 3.9.7
Dokan tested up to: 3.14.3
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Tags: WooCommerce, Dokan, Tools, Tookit, Dokan Toolkit
Stable tag: 3.0.0
Contributors: tanvirh

Elevate your Dokan multivendor marketplace with enhanced features and tools through Dokan Kits.

== Description ==

Welcome to the Dokan Kits plugin! This plugin is designed to enhance and extend the functionality of the Dokan multivendor marketplace plugin for WordPress. It provides additional features and tools to empower your marketplace and improve the user experience for both vendors and customers.

To use this plugin, it's essential to install and activate [**WooCommerce**](https://wordpress.org/plugins/woocommerce/). Additionally, to access the full range of features in the Dokan plugin, ensure that [**Dokan Lite**](https://wordpress.org/plugins/dokan-lite/) remains activated.

== Features ==

= Vendor Features =
- **Vendor Registration Management:** Customize and control the vendor registration process to suit your marketplace needs
- **Advanced Vendor Capabilities:** Enhance vendor permissions and capabilities for better store management
- **Vendor Account Settings:** Additional configuration options for vendor profiles and storefronts

= Product Features =
- **Image Restrictions:** Control and customize product image uploads and gallery options
- **Extended Product Fields:** Add custom fields to products for enhanced product information
- **Additional Product Options:** Enable advanced product configuration options for vendors
- **Custom Product Types:** Support for specialized product types tailored to your marketplace niche

= Cart Features =
- **Enhanced Cart Buttons:** Improve the shopping experience with customized cart functionality
- **Streamlined Checkout Process:** Optimize the customer journey from cart to completion

= Shipping Features =
- **Lite Shipping Options:** Basic shipping configurations for smaller marketplaces
- **Pro Shipping Capabilities:** Advanced shipping rules and methods for complex logistics needs

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/dokan-kits` directory, or install the plugin through the WordPress plugins screen.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Ensure both WooCommerce and Dokan Lite are installed and activated.
4. Go to the Dokan Kits settings page to configure the plugin according to your needs.

== Development Guide ==

= Plugin Architecture =
Dokan Kits follows a modern, modular architecture to ensure maintainability and extensibility:

1. **Core System:** Located in `/includes/Core/`, handles bootstrapping, service providers, and dependency management.
2. **Feature Modules:** Located in `/includes/Features/`, each feature is implemented as a separate module.
3. **Admin Interface:** Located in `/includes/Admin/`, manages admin settings and UI components.
4. **REST API:** Located in `/includes/Rest/`, provides API endpoints for frontend interactions.

= Setting Up Development Environment =

1. **Clone the Repository:**
   ```
   git clone [repository-url] dokan-kits
   cd dokan-kits
   ```

2. **Install Dependencies:**
   If using Composer for PHP dependencies:
   ```
   composer install
   ```

   For frontend assets:
   ```
   npm install
   ```

3. **Build Assets:**
   ```
   npm run build
   ```

   For development with watch mode:
   ```
   npm run dev
   ```

= Adding New Features =

1. Create a new feature class in the appropriate feature directory that implements `FeatureInterface` or extends `AbstractFeature`
2. Register your feature in the appropriate service provider
3. Implement required hooks and functionality
4. Add settings to the relevant settings tab if needed

= Coding Standards =

This project follows WordPress coding standards. Before submitting a contribution:

1. Ensure your code meets WordPress coding standards
2. Run code quality checks:
   ```
   composer run phpcs
   ```
3. Fix any issues:
   ```
   composer run phpcbf
   ```

= Testing =

Run automated tests to ensure code quality:

```
composer run test
```

= Building for Production =

```
npm run production
composer install --no-dev
```

= Contributing =

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

== Frequently Asked Questions ==

= Is Dokan Kits compatible with the latest WordPress version? =
Yes, Dokan Kits is regularly tested and updated to maintain compatibility with the latest WordPress versions.

= Do I need Dokan Pro to use this plugin? =
No, Dokan Kits works with Dokan Lite, although some advanced features may integrate better with Dokan Pro.

= Can I extend Dokan Kits with custom code? =
Yes, Dokan Kits is built with extensibility in mind. Refer to the Development Guide section for details.

== Changelog ==

= 3.0.0 =
* Initial public release with core feature set
* Added vendor management features
* Added product enhancement capabilities
* Implemented cart and shipping optimizations

== Upgrade Notice ==

= 3.0.0 =
Initial release of Dokan Kits with comprehensive feature set for Dokan marketplace enhancement.

== Support ==

For support inquiries, please visit our support forums or contact us through our website.

== Credits ==

Dokan Kits is developed and maintained by Tanvir Hasan.
`
