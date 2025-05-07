<?php
namespace DokanKits\Admin;

use DokanKits\Core\Container;

/**
 * Notices Class
 *
 * @package DokanKits\Admin
 */
class Notices {
    /**
     * Container instance
     *
     * @var Container
     */
    protected $container;

    /**
     * Notices
     *
     * @var array
     */
    protected $notices = [];

    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        $this->container = $container;
    }

    /**
     * Initialize notices
     *
     * @return void
     */
    public function init() {
        // Check for transient notices
        add_action( 'admin_notices', [ $this, 'show_notices' ] );
        
        // Add ajax handler for dismissing notices
        add_action( 'wp_ajax_dokan_kits_dismiss_notice', [ $this, 'dismiss_notice' ] );
    }

    /**
     * Add notice
     *
     * @param string $message    Notice message
     * @param string $type       Notice type (info, success, warning, error)
     * @param bool   $dismissible Whether the notice is dismissible
     * @param string $id         Notice ID (optional)
     *
     * @return void
     */
    public function add( $message, $type = 'info', $dismissible = true, $id = '' ) {
        // Generate ID if not provided
        if ( empty( $id ) ) {
            $id = 'dokan_kits_' . md5( $message );
        }
        
        // Check if notice is dismissed
        if ( $this->is_dismissed( $id ) ) {
            return;
        }
        
        $this->notices[ $id ] = [
            'message'     => $message,
            'type'        => $type,
            'dismissible' => $dismissible,
        ];
        
        // Store notices in transient
        set_transient( 'dokan_kits_notices', $this->notices, DAY_IN_SECONDS );
    }

    /**
     * Show notices
     *
     * @return void
     */
    public function show_notices() {
        // Get notices from transient
        $notices = get_transient( 'dokan_kits_notices' );
        
        if ( ! $notices ) {
            $notices = [];
        }
        
        $this->notices = $notices;
        
        // Display notices
        foreach ( $this->notices as $id => $notice ) {
            $this->display_notice( $id, $notice );
        }
        
        // Clear notices
        delete_transient( 'dokan_kits_notices' );
    }

    /**
     * Display notice
     *
     * @param string $id     Notice ID
     * @param array  $notice Notice data
     *
     * @return void
     */
    protected function display_notice( $id, $notice ) {
        $type = isset( $notice['type'] ) ? $notice['type'] : 'info';
        $dismissible = isset( $notice['dismissible'] ) ? $notice['dismissible'] : true;
        $message = isset( $notice['message'] ) ? $notice['message'] : '';
        
        // Convert type to WP notice class
        $class = 'notice';
        
        switch ( $type ) {
            case 'success':
                $class .= ' notice-success';
                break;
                
            case 'warning':
                $class .= ' notice-warning';
                break;
                
            case 'error':
                $class .= ' notice-error';
                break;
                
            default:
                $class .= ' notice-info';
                break;
        }
        
        if ( $dismissible ) {
            $class .= ' is-dismissible';
        }
        
        ?>
        <div class="<?php echo esc_attr( $class ); ?>" data-notice-id="<?php echo esc_attr( $id ); ?>">
            <p><?php echo wp_kses_post( $message ); ?></p>
        </div>
        <?php
    }

    /**
     * Dismiss notice
     *
     * @return void
     */
    public function dismiss_notice() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'dokan_kits_dismiss_notice' ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid nonce', 'dokan-kits' ) ] );
        }
        
        // Check notice ID
        if ( ! isset( $_POST['id'] ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid notice ID', 'dokan-kits' ) ] );
        }
        
        $id = sanitize_text_field( $_POST['id'] );
        
        // Get dismissed notices
        $dismissed = get_option( 'dokan_kits_dismissed_notices', [] );
        
        // Add current notice
        $dismissed[ $id ] = time();
        
        // Update option
        update_option( 'dokan_kits_dismissed_notices', $dismissed );
        
        wp_send_json_success();
    }

    /**
     * Check if notice is dismissed
     *
     * @param string $id Notice ID
     *
     * @return bool
     */
    public function is_dismissed( $id ) {
        // Get dismissed notices
        $dismissed = get_option( 'dokan_kits_dismissed_notices', [] );
        
        return isset( $dismissed[ $id ] );
    }

    /**
     * Add success notice
     *
     * @param string $message    Notice message
     * @param bool   $dismissible Whether the notice is dismissible
     * @param string $id         Notice ID (optional)
     *
     * @return void
     */
    public function success( $message, $dismissible = true, $id = '' ) {
        $this->add( $message, 'success', $dismissible, $id );
    }

    /**
     * Add error notice
     *
     * @param string $message    Notice message
     * @param bool   $dismissible Whether the notice is dismissible
     * @param string $id         Notice ID (optional)
     *
     * @return void
     */
    public function error( $message, $dismissible = true, $id = '' ) {
        $this->add( $message, 'error', $dismissible, $id );
    }

    /**
     * Add warning notice
     *
     * @param string $message    Notice message
     * @param bool   $dismissible Whether the notice is dismissible
     * @param string $id         Notice ID (optional)
     *
     * @return void
     */
    public function warning( $message, $dismissible = true, $id = '' ) {
        $this->add( $message, 'warning', $dismissible, $id );
    }

    /**
     * Add info notice
     *
     * @param string $message    Notice message
     * @param bool   $dismissible Whether the notice is dismissible
     * @param string $id         Notice ID (optional)
     *
     * @return void
     */
    public function info( $message, $dismissible = true, $id = '' ) {
        $this->add( $message, 'info', $dismissible, $id );
    }
}