<?php
namespace DokanKits\Core;

/**
 * Default Service Provider
 *
 * @package DokanKits\Core
 */
class DefaultServiceProvider implements ServiceProvider {
    /**
     * Register services to the container
     *
     * @param Container $container Container instance
     *
     * @return void
     */
    public function register( Container $container ) {
        // Dependencies checker
        $container->factory( 'dependencies', function ( $container ) {
            return new Dependencies();
        });

        // Bootstrap handler
        $container->factory( 'bootstrap', function ( $container ) {
            return new Bootstrap( $container );
        });

        // Hook manager
        $container->factory( 'hooks', function ( $container ) {
            return new Hooks( $container );
        });

        // Asset manager
        $container->factory( 'assets', function ( $container ) {
            return new Assets( $container );
        });

        // Internationalization
        $container->factory( 'i18n', function ( $container ) {
            return new Internationalization( $container );
        });
        
        // Admin components
        $this->register_admin( $container );
        
        // Frontend components
        $this->register_frontend( $container );
        
        // Features
        $this->register_features( $container );
        
        // REST API
        $this->register_rest( $container );
        
        // Image Restrictions Controller
        $container->factory( 'controllers.image_restrictions', function ( $container ) {
            return new \DokanKits\Controllers\ImageRestrictionsController();
        });
    }

    /**
     * Register admin components
     *
     * @param Container $container Container instance
     *
     * @return void
     */
    protected function register_admin( Container $container ) {
        // Main admin class
        $container->factory( 'admin', function ( $container ) {
            return new \DokanKits\Admin\Admin( $container );
        });

        // Admin menu
        $container->factory( 'admin.menu', function ( $container ) {
            return new \DokanKits\Admin\Menu( $container );
        });

        // Admin assets
        $container->factory( 'admin.assets', function ( $container ) {
            return new \DokanKits\Admin\Assets( $container );
        });

        // Admin notices
        $container->factory( 'admin.notices', function ( $container ) {
            return new \DokanKits\Admin\Notices( $container );
        });

        // Settings page
        $container->factory( 'admin.settings.page', function ( $container ) {
            return new \DokanKits\Admin\Settings\Page( $container );
        });

        // Settings manager
        $container->factory( 'admin.settings', function ( $container ) {
            return new \DokanKits\Admin\Settings\Settings( $container );
        });

        // Settings API wrapper
        $container->factory( 'admin.settings.api', function ( $container ) {
            return new \DokanKits\Admin\Settings\SettingsAPI( $container );
        });
        
        // Tabs
        $this->register_tabs( $container );
    }

    /**
     * Register settings tabs
     *
     * @param Container $container Container instance
     *
     * @return void
     */
    protected function register_tabs( Container $container ) {
        // Vendor tab
        $container->factory( 'admin.settings.tabs.vendor', function ( $container ) {
            return new \DokanKits\Admin\Settings\Tabs\VendorTab( $container );
        });

        // Product tab
        $container->factory( 'admin.settings.tabs.product', function ( $container ) {
            return new \DokanKits\Admin\Settings\Tabs\ProductTab( $container );
        });

        // Shipping tab
        $container->factory( 'admin.settings.tabs.shipping', function ( $container ) {
            return new \DokanKits\Admin\Settings\Tabs\ShippingTab( $container );
        });

        // Display tab
        $container->factory( 'admin.settings.tabs.display', function ( $container ) {
            return new \DokanKits\Admin\Settings\Tabs\DisplayTab( $container );
        });

        // Advanced tab
        $container->factory( 'admin.settings.tabs.advanced', function ( $container ) {
            return new \DokanKits\Admin\Settings\Tabs\AdvancedTab( $container );
        });
    }

    /**
     * Register frontend components
     *
     * @param Container $container Container instance
     *
     * @return void
     */
    protected function register_frontend( Container $container ) {
        // Main frontend class
        $container->factory( 'frontend', function ( $container ) {
            return new \DokanKits\Frontend\Frontend( $container );
        });

        // Frontend assets
        $container->factory( 'frontend.assets', function ( $container ) {
            return new \DokanKits\Frontend\Assets( $container );
        });
    }

    /**
     * Register features
     *
     * @param Container $container Container instance
     *
     * @return void
     */
    protected function register_features( Container $container ) {
        // Vendor features
        $container->factory( 'features.vendor.registration', function ( $container ) {
            return new \DokanKits\Features\VendorFeatures\VendorRegistration( $container );
        });

        $container->factory( 'features.vendor.capabilities', function ( $container ) {
            return new \DokanKits\Features\VendorFeatures\VendorCapabilities( $container );
        });

        $container->factory( 'features.vendor.account', function ( $container ) {
            return new \DokanKits\Features\VendorFeatures\AccountSettings( $container );
        });

        // Product features
        $container->factory( 'features.product.types', function ( $container ) {
            return new \DokanKits\Features\ProductFeatures\ProductTypes( $container );
        });

        $container->factory( 'features.product.fields', function ( $container ) {
            return new \DokanKits\Features\ProductFeatures\ProductFields( $container );
        });

        $container->factory( 'features.product.options', function ( $container ) {
            return new \DokanKits\Features\ProductFeatures\ProductOptions( $container );
        });

        // Add image restrictions feature - MATCH THE NAMING CONVENTION USED IN BOOTSTRAP
        $container->factory( 'features.product.image-restrictions', function ( $container ) {
            return new \DokanKits\Features\ProductFeatures\ImageRestrictions( $container );
        });

        // Shipping features
        $container->factory( 'features.shipping.lite', function ( $container ) {
            return new \DokanKits\Features\ShippingFeatures\LiteShipping( $container );
        });

        $container->factory( 'features.shipping.pro', function ( $container ) {
            return new \DokanKits\Features\ShippingFeatures\ProShipping( $container );
        });

        // Cart features
        $container->factory( 'features.cart.buttons', function ( $container ) {
            return new \DokanKits\Features\CartFeatures\CartButtons( $container );
        });
    }

    /**
     * Register REST API components
     *
     * @param Container $container Container instance
     *
     * @return void
     */
    protected function register_rest( Container $container ) {
        // Main REST API class
        $container->factory( 'rest', function ( $container ) {
            return new \DokanKits\Rest\Rest( $container );
        });

        // Settings controller
        $container->factory( 'rest.controllers.settings', function ( $container ) {
            return new \DokanKits\Rest\Controllers\SettingsController( $container );
        });
    }
}