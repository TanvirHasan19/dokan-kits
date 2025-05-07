<?php
/**
 * Plugin Name: Dokan Kits
 * Plugin URI: https://wordpress.org/plugins/dokan-kits
 * Description: The Helper Toolkits plugin for Dokan is a feature-packed add-on designed to streamline and enhance the functionality of your Dokan-powered multi-vendor marketplace.
 * Version: 3.0.0
 * Author: Tanvir Hasan
 * Author URI: https://profiles.wordpress.org/tanvirh/
 * Dokan requires at least: 3.9.7
 * Dokan tested up to: 3.14.3
 * Text Domain: dokan-kits
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package DokanKits
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Dokan_Kits class
 *
 * @class Dokan_Kits The class that holds the entire Dokan_Kits plugin
 */
final class Dokan_Kits {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '3.0.0';

    /**
     * Plugin slug
     *
     * @var string
     */
    public $slug = 'dokan-kits';

    /**
     * Plugin container
     *
     * @var DokanKits\Core\Container
     */
    private $container;

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Initialize the plugin
     */
    private function __construct() {
        $this->define_constants();
        $this->include_files();
        $this->init_hooks();
    }

    /**
     * Return an instance of this class.
     *
     * @return Dokan_Kits A single instance of this class.
     */
    public static function instance() {
        // If the single instance hasn't been set, set it now.
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Define the constants
     *
     * @return void
     */
    private function define_constants() {
        $this->define( 'DOKAN_KITS_VERSION', $this->version );
        $this->define( 'DOKAN_KITS_FILE', __FILE__ );
        $this->define( 'DOKAN_KITS_BASENAME', plugin_basename( __FILE__ ) );
        $this->define( 'DOKAN_KITS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
        $this->define( 'DOKAN_KITS_PLUGIN_URL', plugins_url( '', __FILE__ ) );
        $this->define( 'DOKAN_KITS_ASSETS_URL', DOKAN_KITS_PLUGIN_URL . '/assets' );
        $this->define( 'DOKAN_KITS_TEMPLATE_PATH', DOKAN_KITS_PLUGIN_PATH . '/templates' );
    }

    /**
     * Define constant if not already set
     *
     * @param string      $name  Constant name
     * @param string|bool $value Constant value
     *
     * @return void
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Include the required files
     *
     * @return void
     */
    private function include_files() {
        // Register autoloader
        spl_autoload_register( [ $this, 'autoload' ] );

        // Include common functions
        require_once DOKAN_KITS_PLUGIN_PATH . 'includes/functions.php';
    }

    /**
     * Initialize plugin hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Initialize the container
        add_action( 'plugins_loaded', [ $this, 'init_container' ], 1 );
        
        // Bootstrap the plugin after all plugins are loaded
        add_action( 'plugins_loaded', [ $this, 'boot' ], 20 );
        
        // Register activation and deactivation hooks
        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );
    }

    /**
     * Autoload classes based on namespace
     *
     * @param string $class Class name
     *
     * @return void
     */
    public function autoload( $class ) {
        // Project-specific namespace prefix
        $prefix = 'DokanKits\\';

        // Does the class use the namespace prefix?
        $len = strlen( $prefix );
        if ( strncmp( $prefix, $class, $len ) !== 0 ) {
            // No, move to the next registered autoloader
            return;
        }

        // Get the relative class name
        $relative_class = substr( $class, $len );

        // Replace namespace separator with directory separator
        $file = str_replace( '\\', '/', $relative_class );
        
        // Build the file path
        $path = DOKAN_KITS_PLUGIN_PATH . 'includes/' . $file . '.php';

        // If the file exists, require it
        if ( file_exists( $path ) ) {
            require_once $path;
        }
    }

    /**
     * Initialize the container
     *
     * @return void
     */
    public function init_container() {
        require_once DOKAN_KITS_PLUGIN_PATH . 'includes/Core/Container.php';
        require_once DOKAN_KITS_PLUGIN_PATH . 'includes/Core/ServiceProvider.php';
        require_once DOKAN_KITS_PLUGIN_PATH . 'includes/Core/DefaultServiceProvider.php';

        $this->container = new DokanKits\Core\Container();

        // Register the container instance
        $this->container->set( 'container', $this->container );
        $this->container->set( 'plugin', $this );

        // Register default service provider
        $default_provider = new DokanKits\Core\DefaultServiceProvider();
        $default_provider->register( $this->container );
    }

    /**
     * Get container instance
     *
     * @return DokanKits\Core\Container
     */
    public function container() {
        return $this->container;
    }

    /**
     * Boot the plugin
     *
     * @return void
     */
    public function boot() {
        // Load textdomain
        $this->load_textdomain();

        // Check dependencies
        $dependencies = $this->container->get( 'dependencies' );
        
        if ( $dependencies->check() ) {
            // Bootstrap the plugin
            $bootstrap = $this->container->get( 'bootstrap' );
            $bootstrap->boot();

            /**
             * Fire an action when the plugin is fully loaded
             *
             * @param Dokan_Kits $this Plugin instance
             */
            do_action( 'dokan_kits_loaded', $this );
        }
    }

    /**
     * Load textdomain
     *
     * @return void
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'dokan-kits', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    /**
     * Plugin activation
     *
     * @return void
     */
    public function activate() {
        // Initialize default settings
        $settings = get_option( 'dokan_kits_settings', [] );
        update_option( 'dokan_kits_settings', $settings );

        /**
         * Fire an action when the plugin is activated
         *
         * @param Dokan_Kits $this Plugin instance
         */
        do_action( 'dokan_kits_activated', $this );
    }

    /**
     * Plugin deactivation
     *
     * @return void
     */
    public function deactivate() {
        /**
         * Fire an action when the plugin is deactivated
         *
         * @param Dokan_Kits $this Plugin instance
         */
        do_action( 'dokan_kits_deactivated', $this );
    }
}

/**
 * Initialize the main plugin
 *
 * @return Dokan_Kits
 */
function dokan_kits() {
    return Dokan_Kits::instance();
}

// Kick off the plugin
dokan_kits();