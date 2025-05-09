<?php

namespace DokanKits\Admin\Settings\Tabs;

use DokanKits\Core\Container;

/**
 * Abstract Tab
 *
 * @package DokanKits\Admin\Settings\Tabs
 */
abstract class AbstractTab implements TabInterface {
	/**
	 * Container instance
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Tab ID
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Tab title
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * Tab icon
	 *
	 * @var string
	 */
	protected $icon;

	/**
	 * Tab settings
	 *
	 * @var array
	 */
	protected $settings = [];

	/**
	 * Constructor
	 *
	 * @param Container $container Container instance
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
		$this->init();
	}

	/**
	 * Initialize tab
	 *
	 * @return void
	 */
	abstract protected function init();

	/**
	 * Get tab ID
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get tab title
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * Get tab icon
	 *
	 * @return string
	 */
	public function get_icon() {
		return $this->icon;
	}

	/**
	 * Get tab settings
	 *
	 * @return array
	 */
	public function get_settings() {
		/**
		 * Filter tab settings
		 *
		 * @param array       $settings Tab settings
		 * @param AbstractTab $this     Tab instance
		 */
		return apply_filters( 'dokan_kits_settings_tab_' . $this->get_id() . '_settings', $this->settings, $this );
	}

	/**
	 * Render tab content
	 *
	 * @return void
	 */
	public function render() {
		// Start the grid
		echo '<div id="dokan-kits-body-content" class="tab-container">';

		// Render settings
		$this->render_settings();

		echo '</div>';
	}

	/**
	 * Render settings
	 *
	 * @return void
	 */
	protected function render_settings() {
		$settings = $this->get_settings();

		if ( empty( $settings ) ) {
			echo '<div class="dokan-kits-empty-tab">';
			echo '<p>' . __( 'No settings available for this tab.', 'dokan-kits' ) . '</p>';
			echo '</div>';

			return;
		}

		foreach ( $settings as $setting ) {
			$this->render_setting( $setting );
		}
	}

	/**
	 * Render a setting
	 *
	 * @param array $setting Setting data
	 *
	 * @return void
	 */
	protected function render_setting( $setting ) {
		// Get setting type
		$type = isset( $setting['type'] ) ? $setting['type'] : 'toggle';

		// Get renderer method
		$renderer = 'render_' . $type . '_setting';

		// Check if renderer exists
		if ( method_exists( $this, $renderer ) ) {
			$this->$renderer( $setting );
		} else {
			/**
			 * Action to render custom setting type
			 *
			 * @param array       $setting Setting data
			 * @param AbstractTab $this    Tab instance
			 */
			do_action( 'dokan_kits_render_setting_' . $type, $setting, $this );
		}
	}

	/**
	 * Render toggle setting
	 *
	 * @param array $setting Setting data
	 *
	 * @return void
	 */
	protected function render_toggle_setting( $setting ) {
		// Get option value
		$option_name  = $setting['id'];
		$option_value = get_option( $option_name, 0 );
		$disabled     = isset( $setting['disabled'] ) && $setting['disabled'] ? 'disabled' : '';

		?>
        <div class="dokan-kits-style-box">
            <i class="fa <?php echo esc_attr( $setting['icon'] ); ?> fa-3x"></i>
            <div class="toggle-label">
                <label for="<?php echo esc_attr( $option_name ); ?>" class="for_title_label">
					<?php echo esc_html( $setting['title'] ); ?>
                </label>
                <label class="switch">
                    <input
                            type="checkbox"
                            id="<?php echo esc_attr( $option_name ); ?>"
                            name="<?php echo esc_attr( $option_name ); ?>"
                            value="1"
						<?php checked( $option_value, 1 ); ?>
						<?php echo $disabled; ?>
                    >
                    <span class="slider"></span>
                </label>
                <span class="status-text"><?php echo $option_value ? __( 'Active', 'dokan-kits' ) : __( 'Inactive', 'dokan-kits' ); ?></span>
            </div>
            <p class="additional-text"><?php echo esc_html( $setting['description'] ); ?></p>
        </div>
		<?php
	}

	/**
	 * Render group setting
	 *
	 * @param array $setting Setting data
	 *
	 * @return void
	 */
	protected function render_group_setting( $setting ) {
		?>
        <div class="dokan-kits-style-box">
            <i class="fa <?php echo esc_attr( $setting['icon'] ); ?> fa-3x"></i>
            <div class="toggle-label">
                <label class="for_title_label"><?php echo esc_html( $setting['title'] ); ?></label>
                <div class="additional-text"><?php echo esc_html( $setting['description'] ); ?></div>

                <div class="toggle-group re_product_toggle">
					<?php foreach ( $setting['fields'] as $field ) :
						$option_name = $field['id'];
						$option_value = get_option( $option_name, isset( $field['default'] ) ? $field['default'] : 0 );
						$type = isset( $field['type'] ) ? $field['type'] : '';

						// Check if field has dependency
						$show_field = true;
						if ( isset( $field['dependency'] ) ) {
							$dependency_id           = $field['dependency']['id'];
							$dependency_value        = $field['dependency']['value'];
							$dependency_option_value = get_option( $dependency_id, 0 );
							$show_field              = $dependency_option_value == $dependency_value;
						}

						if ( ! $show_field ) {
							// Add hidden field to preserve field in form
							echo '<input type="hidden" name="' . esc_attr( $option_name ) . '" value="' . esc_attr( $option_value ) . '">';
							continue;
						}

						// Build dependency attribute for JavaScript
						$dependency_attr = '';
						if ( isset( $field['dependency'] ) ) {
							$dependency_attr = 'data-dependency=\'' . json_encode( $field['dependency'] ) . '\'';
						}
						?>
                        <div class="type-bu-si" <?php echo $dependency_attr; ?>>
							<?php if ( isset( $field['title'] ) && '' !== $field['title'] ) : ?>
                                <label for="<?php echo esc_attr( $option_name ); ?>" class='for_title_label'>
									<?php echo esc_html( $field['title'] ); ?>
                                </label>
							<?php endif; ?>

							<?php if ( $type === 'number' ) : ?>
                                <div class="number-input-container">
                                    <input
                                            type="number"
                                            id="<?php echo esc_attr( $option_name ); ?>"
                                            name="<?php echo esc_attr( $option_name ); ?>"
                                            value="<?php echo esc_attr( $option_value ); ?>"
										<?php if ( isset( $field['min'] ) ) {
											echo 'min="' . esc_attr( $field['min'] ) . '"';
										} ?>
										<?php if ( isset( $field['max'] ) ) {
											echo 'max="' . esc_attr( $field['max'] ) . '"';
										} ?>
										<?php if ( isset( $field['step'] ) ) {
											echo 'step="' . esc_attr( $field['step'] ) . '"';
										} ?>
                                    >
									<?php if ( isset( $field['suffix'] ) ) : ?>
                                        <span class="input-suffix"><?php echo esc_html( $field['suffix'] ); ?></span>
									<?php endif; ?>
                                </div>
							<?php else : ?>
                                <label class="switch">
                                    <input
                                            type="checkbox"
                                            id="<?php echo esc_attr( $option_name ); ?>"
                                            name="<?php echo esc_attr( $option_name ); ?>"
                                            value="1"
										<?php checked( $option_value, 1 ); ?>
                                    >
                                    <span class="slider"></span>
                                </label>
                                <span class="status-text"><?php echo $option_value ? __( 'Active', 'dokan-kits' ) : __( 'Inactive', 'dokan-kits' ); ?></span>
							<?php endif; ?>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
        </div>
		<?php
	}

	/**
	 * Render custom setting
	 *
	 * @param array $setting Setting data
	 *
	 * @return void
	 */
	protected function render_custom_setting( $setting ) {
		if ( isset( $setting['renderer'] ) && is_callable( $setting['renderer'] ) ) {
			call_user_func( $setting['renderer'], $setting );
		} else {
			echo '<div class="dokan-kits-empty-tab">';
			echo '<p>' . __( 'No renderer available for this setting.', 'dokan-kits' ) . '</p>';
			echo '</div>';
		}
	}
}