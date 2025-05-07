<?php
namespace DokanKits\Core;

/**
 * Internationalization Class
 *
 * @package DokanKits\Core
 */
class Internationalization {
    /**
     * Container instance
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
     * Setup internationalization
     *
     * @return void
     */
    public function setup() {
        add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
        
        /**
         * Action after internationalization setup
         *
         * @param Internationalization $this Internationalization instance
         */
        do_action( 'dokan_kits_i18n_setup', $this );
    }

    /**
     * Load plugin text domain
     *
     * @return void
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'dokan-kits',
            false,
            dirname( DOKAN_KITS_BASENAME ) . '/languages/'
        );
        
        /**
         * Action after loading text domain
         *
         * @param Internationalization $this Internationalization instance
         */
        do_action( 'dokan_kits_textdomain_loaded', $this );
    }
}