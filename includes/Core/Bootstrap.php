<?php
namespace DokanKits\Core;

/**
 * Bootstrap Class
 *
 * @package DokanKits\Core
 */
class Bootstrap {
    /**
     * The container instance
     *
     * @var Container
     */
    protected $container;

    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        $this->container = $container;
    }

    /**
     * Boot up the plugin
     *
     * @return void
     */
    public function boot() {
        // Initialize hooks manager
        $hooks = $this->container->get( 'hooks' );
        $hooks->setup();

        // Initialize assets
        $assets = $this->container->get( 'assets' );
        $assets->setup();

        // Initialize i18n
        $i18n = $this->container->get( 'i18n' );
        $i18n->setup();

        // Initialize admin components if in admin area
        if ( is_admin() ) {
            $this->boot_admin();
        }

        // Initialize frontend components
        $this->boot_frontend();

        // Initialize REST API
        $this->boot_rest();

        // Initialize features
        $this->boot_features();

        /**
         * Fire action after plugin bootstrap
         *
         * @param Bootstrap $this Bootstrap instance
         */
        do_action( 'dokan_kits_after_bootstrap', $this );
    }

    /**
     * Boot admin components
     *
     * @return void
     */
    protected function boot_admin() {
        // Init admin components
        $admin = $this->container->get( 'admin' );
        $admin->init();

        /**
         * Fire action after admin bootstrap
         *
         * @param Bootstrap $this Bootstrap instance
         */
        do_action( 'dokan_kits_after_admin_bootstrap', $this );
    }

    /**
     * Boot frontend components
     *
     * @return void
     */
    protected function boot_frontend() {
        // Init frontend components
        $frontend = $this->container->get( 'frontend' );
        $frontend->init();

        /**
         * Fire action after frontend bootstrap
         *
         * @param Bootstrap $this Bootstrap instance
         */
        do_action( 'dokan_kits_after_frontend_bootstrap', $this );
    }

    /**
     * Boot REST API
     *
     * @return void
     */
    protected function boot_rest() {
        // Init REST API components
        $rest = $this->container->get( 'rest' );
        $rest->init();

        /**
         * Fire action after REST API bootstrap
         *
         * @param Bootstrap $this Bootstrap instance
         */
        do_action( 'dokan_kits_after_rest_bootstrap', $this );
    }

    /**
     * Boot features
     *
     * @return void
     */
    protected function boot_features() {
        // Init feature registry
        $this->init_vendor_features();
        $this->init_product_features();
        $this->init_shipping_features();
        $this->init_cart_features();

        /**
         * Fire action after features bootstrap
         *
         * @param Bootstrap $this Bootstrap instance
         */
        do_action( 'dokan_kits_after_features_bootstrap', $this );
    }

    /**
     * Initialize vendor features
     *
     * @return void
     */
    protected function init_vendor_features() {
        // Vendor registration
        $vendor_registration = $this->container->get( 'features.vendor.registration' );
        $vendor_registration->init();

        // Vendor capabilities
        $vendor_capabilities = $this->container->get( 'features.vendor.capabilities' );
        $vendor_capabilities->init();

        // Account settings
        $account_settings = $this->container->get( 'features.vendor.account' );
        $account_settings->init();

        /**
         * Fire action after vendor features bootstrap
         *
         * @param Bootstrap $this Bootstrap instance
         */
        do_action( 'dokan_kits_after_vendor_features_bootstrap', $this );
    }

    /**
     * Initialize product features
     *
     * @return void
     */
    protected function init_product_features() {
        // Product types
        $product_types = $this->container->get( 'features.product.types' );
        $product_types->init();

        // Product fields
        $product_fields = $this->container->get( 'features.product.fields' );
        $product_fields->init();

        // Product options
        $product_options = $this->container->get( 'features.product.options' );
        $product_options->init();

        // Image restrictions
        $image_restrictions = $this->container->get( 'features.product.image-restrictions' );
        $image_restrictions->init();

        /**
         * Fire action after product features bootstrap
         *
         * @param Bootstrap $this Bootstrap instance
         */
        do_action( 'dokan_kits_after_product_features_bootstrap', $this );
    }

    /**
     * Initialize shipping features
     *
     * @return void
     */
    protected function init_shipping_features() {
        // Lite shipping
        $lite_shipping = $this->container->get( 'features.shipping.lite' );
        $lite_shipping->init();

        // Pro shipping
        $pro_shipping = $this->container->get( 'features.shipping.pro' );
        $pro_shipping->init();

        /**
         * Fire action after shipping features bootstrap
         *
         * @param Bootstrap $this Bootstrap instance
         */
        do_action( 'dokan_kits_after_shipping_features_bootstrap', $this );
    }

    /**
     * Initialize cart features
     *
     * @return void
     */
    protected function init_cart_features() {
        // Cart buttons
        $cart_buttons = $this->container->get( 'features.cart.buttons' );
        $cart_buttons->init();

        /**
         * Fire action after cart features bootstrap
         *
         * @param Bootstrap $this Bootstrap instance
         */
        do_action( 'dokan_kits_after_cart_features_bootstrap', $this );
    }
}