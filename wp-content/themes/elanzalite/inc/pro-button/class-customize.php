<?php
/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class Elanzalite_Pro_Button_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function sections( $manager ) {

		// Load custom sections.
		require trailingslashit( get_template_directory() ) . 'inc/pro-button/section-pro.php';

		// Register custom section types.
		$manager->register_section_type( 'elanzalite_Button_Customize_Section' );
        $manager->add_section(
			new elanzalite_Button_Customize_Section(
				$manager,
				'pro_button',
				array(
					'title'    => esc_html__( 'Unlock Pro Features', 'elanzalite' ),
					'pro_text' => esc_html__( 'Go Pro', 'elanzalite' ),
					'pro_url'  => '//themehunk.com/product/elanza-blogging-theme/',
					'priority' => 1,
				)
			)
		);
		$manager->add_section(
			new elanzalite_Button_Customize_Section(
				$manager,
				'Docs_button',
				array(
					'title'    => esc_html__( 'View Documentation', 'elanzalite' ),
					'pro_text' => esc_html__( 'View Docs',         'elanzalite' ),
					'pro_url'  => '//themehunk.com/docs/elanza-lite-theme/',
					'priority' => 2,
				)
			)
		);
		$manager->add_section(
			new elanzalite_Button_Customize_Section(
				$manager,
				'Magazine_button',
				array(
					'title'    => esc_html__( 'Create Magazine Template', 'elanzalite' ),
					'pro_text' => esc_html__( 'View Docs','elanzalite' ),
					'pro_url'  => '//themehunk.com/docs/elanza-lite-theme/#create-magazine',
					'priority' => 2,
					
				)
			)
		);
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'elanzalite-pro-customize-controls', trailingslashit( get_template_directory_uri() ) . 'inc/pro-button/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'elanzalite-pro-customize-controls', trailingslashit( get_template_directory_uri() ) . 'inc/pro-button/customize-controls.css' );
	}
}

// Doing this customizer thang!
Elanzalite_Pro_Button_Customize::get_instance();
