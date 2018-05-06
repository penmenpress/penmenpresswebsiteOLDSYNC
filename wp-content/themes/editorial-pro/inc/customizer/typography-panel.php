<?php
/**
 * Typography panel in customizer section
 *
 * @package Mystery Themes
 * @subpackage Editorial Pro
 * @since 1.0.0
 */

add_action( 'customize_register', 'editorial_pro_typography_panel_register' );

if( !function_exists( 'editorial_pro_typography_panel_register' ) ):
	function editorial_pro_typography_panel_register( $wp_customize ) {

		//Register the custom class for typography
		$wp_customize->register_control_type( 'Editorial_Pro_Typography_Customizer_Control' );

		/**
		 * Add Header Settings panel
		 */
		$wp_customize->add_panel(
	        'editorial_pro_typography_panel',
        	array(
        		'priority'       => 25,
            	'capability'     => 'edit_theme_options',
            	'theme_supports' => '',
            	'title'          => esc_html__( 'Typography', 'editorial-pro' ),
            ) 
	    );

/*------------------------------------------------------------------------------------*/
		/**
		 * Body/Paragraph section
		 */
		$wp_customize->add_section(
	        'editorial_pro_body_typo_section',
	        array(
	            'title'		=> esc_html__( 'Paragraph', 'editorial-pro' ),
	            'panel'     => 'editorial_pro_typography_panel',
	            'priority'  => 5,
	        )
	    );

	    /**
	     * Settings for paragraph typography 
	     */
	    $wp_customize->add_setting( 'p_font_family', array( 'default' => 'Titillium Web', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'p_font_style', array( 'default' => '400', 'sanitize_callback' => 'sanitize_key', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'p_text_decoration', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'p_text_transform', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'p_font_size', array( 'default' => '14', 'sanitize_callback' => 'absint', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'p_line_height', array( 'default' => '1.5', 'sanitize_callback' => 'editorial_pro_floatval', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'p_color', array( 'default' => '#656565', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ) );

		/**
	     * Controls for paragraph typography 
	     */
		$wp_customize->add_control( new Editorial_Pro_Typography_Customizer_Control (
			$wp_customize,
				'p_typography',
				array(
					'label'       => esc_html__( 'Paragraph Typography', 'editorial-pro' ),
					'description' => __( 'Select how you want your paragraphs to appear.', 'editorial-pro' ),
					'section'     => 'editorial_pro_body_typo_section',
					'settings'    => array(
						'family'      => 'p_font_family',
						'style'       => 'p_font_style',
						'text_decoration' => 'p_text_decoration',
						'text_transform' => 'p_text_transform',
						'size'        => 'p_font_size',
						'line_height' => 'p_line_height',
						'typocolor'  => 'p_color'
					),
					// Pass custom labels. Use the setting key (above) for the specific label.
					'l10n'        => array(),
				)
			)
		);
/*------------------------------------------------------------------------------------*/
		/**
		 * H1 section
		 */
		$wp_customize->add_section(
	        'editorial_pro_h1_typo_section',
	        array(
	            'title'		=> esc_html__( 'Heading 1', 'editorial-pro' ),
	            'panel'     => 'editorial_pro_typography_panel',
	            'priority'  => 10,
	        )
	    );

	    /**
	     * Settings for Heading 1 typography 
	     */
	    $wp_customize->add_setting( 'h1_font_family', array( 'default' => 'Titillium Web', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h1_font_style', array( 'default' => '700', 'sanitize_callback' => 'sanitize_key', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h1_text_decoration', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h1_text_transform', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h1_font_size', array( 'default' => '34', 'sanitize_callback' => 'absint', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h1_line_height', array( 'default' => '1.5', 'sanitize_callback' => 'editorial_pro_floatval', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h1_color', array( 'default' => '#333333', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ) );

		/**
	     * Controls for Header 1 typography
	     */
		$wp_customize->add_control( new Editorial_Pro_Typography_Customizer_Control (
			$wp_customize,
				'h1_typography',
				array(
					'label'       => esc_html__( 'Heading 1 Typography', 'editorial-pro' ),
					'description' => __( 'Select how you want your Heading 1 to appear.', 'editorial-pro' ),
					'section'     => 'editorial_pro_h1_typo_section',
					'settings'    => array(
						'family'      => 'h1_font_family',
						'style'       => 'h1_font_style',
						'text_decoration' => 'h1_text_decoration',
						'text_transform' => 'h1_text_transform',
						'size'        => 'h1_font_size',
						'line_height' => 'h1_line_height',
						'typocolor'  => 'h1_color'
					),
					// Pass custom labels. Use the setting key (above) for the specific label.
					'l10n'        => array(),
				)
			)
		);

/*------------------------------------------------------------------------------------*/
		/**
		 * H2 section
		 */
		$wp_customize->add_section(
	        'editorial_pro_h2_typo_section',
	        array(
	            'title'		=> esc_html__( 'Heading 2', 'editorial-pro' ),
	            'panel'     => 'editorial_pro_typography_panel',
	            'priority'  => 15,
	        )
	    );

	    /**
	     * Settings for Heading 2 typography 
	     */
	    $wp_customize->add_setting( 'h2_font_family', array( 'default' => 'Titillium Web', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h2_font_style', array( 'default' => '700', 'sanitize_callback' => 'sanitize_key', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h2_text_decoration', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h2_text_transform', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h2_font_size', array( 'default' => '28', 'sanitize_callback' => 'absint', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h2_line_height', array( 'default' => '1.5', 'sanitize_callback' => 'editorial_pro_floatval', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h2_color', array( 'default' => '#333333', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ) );

		/**
	     * Controls for Header 2 typography 
	     */
		$wp_customize->add_control( new Editorial_Pro_Typography_Customizer_Control (
			$wp_customize,
				'h2_typography',
				array(
					'label'       => esc_html__( 'Heading 2 Typography', 'editorial-pro' ),
					'description' => __( 'Select how you want your Heading 2 to appear.', 'editorial-pro' ),
					'section'     => 'editorial_pro_h2_typo_section',
					'settings'    => array(
						'family'      => 'h2_font_family',
						'style'       => 'h2_font_style',
						'text_decoration' => 'h2_text_decoration',
						'text_transform' => 'h2_text_transform',
						'size'        => 'h2_font_size',
						'line_height' => 'h2_line_height',
						'typocolor'  => 'h2_color'
					),
					// Pass custom labels. Use the setting key (above) for the specific label.
					'l10n'        => array(),
				)
			)
		);

/*------------------------------------------------------------------------------------*/
		/**
		 * H3 section
		 */
		$wp_customize->add_section(
	        'editorial_pro_h3_typo_section',
	        array(
	            'title'		=> esc_html__( 'Heading 3', 'editorial-pro' ),
	            'panel'     => 'editorial_pro_typography_panel',
	            'priority'  => 15,
	        )
	    );

	    /**
	     * Settings for Heading 3 typography 
	     */
	    $wp_customize->add_setting( 'h3_font_family', array( 'default' => 'Titillium Web', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h3_font_style', array( 'default' => '700', 'sanitize_callback' => 'sanitize_key', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h3_text_decoration', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h3_text_transform', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h3_font_size', array( 'default' => '22', 'sanitize_callback' => 'absint', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h3_line_height', array( 'default' => '1.5', 'sanitize_callback' => 'editorial_pro_floatval', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h3_color', array( 'default' => '#333333', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ) );

		/**
	     * Controls for Header 3 typography 
	     */
		$wp_customize->add_control( new Editorial_Pro_Typography_Customizer_Control (
			$wp_customize,
				'h3_typography',
				array(
					'label'       => esc_html__( 'Heading 3 Typography', 'editorial-pro' ),
					'description' => __( 'Select how you want your Heading 3 to appear.', 'editorial-pro' ),
					'section'     => 'editorial_pro_h3_typo_section',
					'settings'    => array(
						'family'      => 'h3_font_family',
						'style'       => 'h3_font_style',
						'text_decoration' => 'h3_text_decoration',
						'text_transform' => 'h3_text_transform',
						'size'        => 'h3_font_size',
						'line_height' => 'h3_line_height',
						'typocolor'  => 'h3_color'
					),
					// Pass custom labels. Use the setting key (above) for the specific label.
					'l10n'        => array(),
				)
			)
		);

/*------------------------------------------------------------------------------------*/
		/**
		 * H4 section
		 */
		$wp_customize->add_section(
	        'editorial_pro_h4_typo_section',
	        array(
	            'title'		=> esc_html__( 'Heading 4', 'editorial-pro' ),
	            'panel'     => 'editorial_pro_typography_panel',
	            'priority'  => 20,
	        )
	    );

	    /**
	     * Settings for Heading 4 typography 
	     */
	    $wp_customize->add_setting( 'h4_font_family', array( 'default' => 'Titillium Web', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h4_font_style', array( 'default' => '700', 'sanitize_callback' => 'sanitize_key', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h4_text_decoration', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h4_text_transform', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h4_font_size', array( 'default' => '18', 'sanitize_callback' => 'absint', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h4_line_height', array( 'default' => '1.5', 'sanitize_callback' => 'editorial_pro_floatval', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h4_color', array( 'default' => '#333333', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ) );

		/**
	     * Controls for Header 4 typography 
	     */
		$wp_customize->add_control( new Editorial_Pro_Typography_Customizer_Control (
			$wp_customize,
				'h4_typography',
				array(
					'label'       => esc_html__( 'Heading 4 Typography', 'editorial-pro' ),
					'description' => __( 'Select how you want your Heading 4 to appear.', 'editorial-pro' ),
					'section'     => 'editorial_pro_h4_typo_section',
					'settings'    => array(
						'family'      => 'h4_font_family',
						'style'       => 'h4_font_style',
						'text_decoration' => 'h4_text_decoration',
						'text_transform' => 'h4_text_transform',
						'size'        => 'h4_font_size',
						'line_height' => 'h4_line_height',
						'typocolor'  => 'h4_color'
					),
					// Pass custom labels. Use the setting key (above) for the specific label.
					'l10n'        => array(),
				)
			)
		);

/*------------------------------------------------------------------------------------*/
		/**
		 * H5 section
		 */
		$wp_customize->add_section(
	        'editorial_pro_h5_typo_section',
	        array(
	            'title'		=> esc_html__( 'Heading 5', 'editorial-pro' ),
	            'panel'     => 'editorial_pro_typography_panel',
	            'priority'  => 25,
	        )
	    );

	    /**
	     * Settings for Heading 5 typography 
	     */
	    $wp_customize->add_setting( 'h5_font_family', array( 'default' => 'Titillium Web', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h5_font_style', array( 'default' => '700', 'sanitize_callback' => 'sanitize_key', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h5_text_decoration', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h5_text_transform', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h5_font_size', array( 'default' => '16', 'sanitize_callback' => 'absint', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h5_line_height', array( 'default' => '1.5', 'sanitize_callback' => 'editorial_pro_floatval', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h5_color', array( 'default' => '#333333', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ) );

		/**
	     * Controls for Header 5 typography 
	     */
		$wp_customize->add_control( new Editorial_Pro_Typography_Customizer_Control (
			$wp_customize,
				'h5_typography',
				array(
					'label'       => esc_html__( 'Heading 5 Typography', 'editorial-pro' ),
					'description' => __( 'Select how you want your Heading 5 to appear.', 'editorial-pro' ),
					'section'     => 'editorial_pro_h5_typo_section',
					'settings'    => array(
						'family'      => 'h5_font_family',
						'style'       => 'h5_font_style',
						'text_decoration' => 'h5_text_decoration',
						'text_transform' => 'h5_text_transform',
						'size'        => 'h5_font_size',
						'line_height' => 'h5_line_height',
						'typocolor'  => 'h5_color'
					),
					// Pass custom labels. Use the setting key (above) for the specific label.
					'l10n'        => array(),
				)
			)
		);

/*------------------------------------------------------------------------------------*/
		/**
		 * H6 section
		 */
		$wp_customize->add_section(
	        'editorial_pro_h6_typo_section',
	        array(
	            'title'		=> esc_html__( 'Heading 6', 'editorial-pro' ),
	            'panel'     => 'editorial_pro_typography_panel',
	            'priority'  => 30,
	        )
	    );

	    /**
	     * Settings for Heading 6 typography 
	     */
	    $wp_customize->add_setting( 'h6_font_family', array( 'default' => 'Titillium Web', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h6_font_style', array( 'default' => '700', 'sanitize_callback' => 'sanitize_key', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h6_text_decoration', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h6_text_transform', array( 'default' => 'none', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h6_font_size', array( 'default' => '14', 'sanitize_callback' => 'absint', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h6_line_height', array( 'default' => '1.5', 'sanitize_callback' => 'editorial_pro_floatval', 'transport' => 'postMessage' ) );
		$wp_customize->add_setting( 'h6_color', array( 'default' => '#333333', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ) );

		/**
	     * Controls for Header 6 typography 
	     */
		$wp_customize->add_control( new Editorial_Pro_Typography_Customizer_Control (
			$wp_customize,
				'h6_typography',
				array(
					'label'       => esc_html__( 'Heading 6 Typography', 'editorial-pro' ),
					'description' => __( 'Select how you want your Heading 6 to appear.', 'editorial-pro' ),
					'section'     => 'editorial_pro_h6_typo_section',
					'settings'    => array(
						'family'      => 'h6_font_family',
						'style'       => 'h6_font_style',
						'text_decoration' => 'h6_text_decoration',
						'text_transform' => 'h6_text_transform',
						'size'        => 'h6_font_size',
						'line_height' => 'h6_line_height',
						'typocolor'  => 'h6_color'
					),
					// Pass custom labels. Use the setting key (above) for the specific label.
					'l10n'        => array(),
				)
			)
		);

	} //close function
endif;