<?php
namespace DokanKits\Core;

/**
 * Hooks Class
 *
 * @package DokanKits\Core
 */
class Hooks {
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
     * Setup hooks
     *
     * @return void
     */
    public function setup() {
        // Register plugin action links
        add_filter( 'plugin_action_links_' . DOKAN_KITS_BASENAME, [ $this, 'plugin_action_links' ] );
        add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
        
        /**
         * Action after hooks setup
         *
         * @param Hooks $this Hooks instance
         */
        do_action( 'dokan_kits_hooks_setup', $this );
    }

    /**
     * Add plugin action links
     *
     * @param array $links Plugin action links
     *
     * @return array
     */
    public function plugin_action_links( $links ) {
        $settings_link = sprintf( 
            '<a href="%s">%s</a>', 
            admin_url( 'admin.php?page=dokan-kits' ),
            __( 'Settings', 'dokan-kits' )
        );

        array_unshift( $links, $settings_link );

        /**
         * Filter plugin action links
         *
         * @param array $links Plugin action links
         * @param Hooks $this  Hooks instance
         */
        return apply_filters( 'dokan_kits_plugin_action_links', $links, $this );
    }

    /**
     * Add plugin row meta
     *
     * @param array  $links Plugin row meta
     * @param string $file  Plugin file
     *
     * @return array
     */
    public function plugin_row_meta( $links, $file ) {
        if ( DOKAN_KITS_BASENAME === $file ) {
            $row_meta = [
                'docs'    => sprintf( 
                    '<a href="%s" target="_blank">%s</a>', 
                    esc_url( 'https://wordpress.org/plugins/dokan-kits/' ),
                    __( 'Documentation', 'dokan-kits' )
                ),
                'support' => sprintf( 
                    '<a href="%s" target="_blank">%s</a>', 
                    esc_url( 'https://wordpress.org/support/plugin/dokan-kits/' ),
                    __( 'Support', 'dokan-kits' )
                ),
            ];

            /**
             * Filter plugin row meta
             *
             * @param array  $row_meta Plugin row meta
             * @param array  $links    Plugin links
             * @param string $file     Plugin file
             * @param Hooks  $this     Hooks instance
             */
            $row_meta = apply_filters( 'dokan_kits_plugin_row_meta', $row_meta, $links, $file, $this );

            return array_merge( $links, $row_meta );
        }

        return $links;
    }
}