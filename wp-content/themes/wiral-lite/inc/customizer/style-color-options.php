<?php
	  $wp_customize->add_panel( 'panel_styles_colors', array(
			'priority' 			=> 31,
			'capability' 		=> 'edit_theme_options',
			'theme_supports' 	=> '',
			'title' 			=> __( 'Style & Color Options', 'wiral-lite' )
		));

		/************************
		* Section: Header Color *
		*************************/
		$wp_customize->add_section( 'wiral_lite_header_color_section' , array(
				'title'      	 	=> __( 'Header Color', 'wiral-lite' ),
				'priority'    		=> 1,
				'panel' 			=> 'panel_styles_colors'
		));

		/* Header Bg Color*/
		
		
		/* Header Logo/Slogan Text color */
		$wp_customize->add_setting( 'wiral_lite_header_logo_text_color' , array(
		    'default' 				=> '#cc3366',
		    'sanitize_callback' 	=> 'sanitize_hex_color',
		));
		$wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'wiral_lite_header_logo_text_color', array(
		    'label'    				=> __( 'Logo/Slogan Text Color', 'wiral-lite' ),
		    'section'  				=> 'wiral_lite_header_color_section',
		    'settings' 				=> 'wiral_lite_header_logo_text_color',
		    'priority'    			=> 3,
		)));

		
		

		/**********************
		* Section: Link Color *
		***********************/
		$wp_customize->add_section( 'wiral_lite_anchor_text_color_section' , array(
				'title'       	=> __( 'Anchor Text Color (Color Links)', 'wiral-lite' ),
				'priority'    	=> 2,
				'panel' 		=> 'panel_styles_colors'
		));

		/* Anchor Text Color*/
		$wp_customize->add_setting( 'wiral_lite_anchor_text_color' , array(
		    'default' 			=> '#333333',
		    'sanitize_callback' => 'sanitize_hex_color',
		));
		$wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'wiral_lite_anchor_text_color', array(
		    'label'    			=> __( 'Anchor Text Color', 'wiral-lite' ),
		    'section'  			=> 'wiral_lite_anchor_text_color_section',
		    'settings' 			=> 'wiral_lite_anchor_text_color',
		    'priority'    		=> 3,
		)));

		/* Anchor Text Color Hover (Color Links Hover) */
		$wp_customize->add_setting( 'wiral_lite_anchor_text_color_hover' , array(
		    'default' 			=> '#cc3366',
		    'sanitize_callback' => 'sanitize_hex_color',
		));
		$wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'wiral_lite_anchor_text_color_hover', array(
		    'label'    			=> __( 'Anchor Text Color Hover (Color Links Hover)', 'wiral-lite' ),
		    'section'  			=> 'wiral_lite_anchor_text_color_section',
		    'settings' 			=> 'wiral_lite_anchor_text_color_hover',
		    'priority'    		=> 4,
		)));
