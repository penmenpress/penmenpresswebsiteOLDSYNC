<?php
/**
 * Custom Controls for the Customizer
 *
 * @package wiral
 */


/**
 * Make sure that custom controls are only defined in the Customizer
 */
if ( class_exists( 'WP_Customize_Control' ) ) :

	/**
	 * Label text.
	 *
	 */
	class wiral_lite_Customize_Header_Control extends WP_Customize_Control {

		public function render_content() {  ?>
			
			<label>
				<span class="customize-control-title"><?php echo wp_kses_post( $this->label ); ?></span>
			</label>
			
			<?php
		}
	}

	/**
	 * Description text
	 *
	 */
	class wiral_lite_Customize_Description_Control extends WP_Customize_Control {

		public function render_content() {  ?>
			
			<span class="description"><?php echo wp_kses_post( $this->label ); ?></span>
			
			<?php
		}
	}

	/**
	 * Category dropdown control for the Customizer
	 *
	 */
	class wiral_lite_Customize_Category_Dropdown_Control extends WP_Customize_Control {
		
		public function render_content() {
				
			$categories = get_categories( array( 'hide_empty' => false ) );
			
			if( !empty( $categories ) ) : ?>
					
					<label>
					
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						
						<select <?php $this->link(); ?>>
							<option value="0"><?php esc_html_e( 'All Categories', 'wiral-lite' ); ?></option>
						<?php
							foreach ( $categories as $category ) :
								
								printf(	'<option value="%s" %s>%s</option>', 
									$category->term_id, 
									selected( $this->value(), $category->term_id, false ), 
									$category->name . ' (' . $category->count . ')'
								);
								
							endforeach;
						?>
						</select>
					  
					</label>
					
				<?php
			endif;
		
		}
		
	}
	
	/**
	 * Upgrade to Pro Version
	 *
	 */
	class wiral_lite_Customize_Upgrade_Control extends WP_Customize_Control {
	
		public function render_content() {  ?>
			
			<div class="upgrade-pro-version">
			
				<span class="customize-control-title"><?php esc_html_e( 'Pro Version', 'wiral-lite' ); ?></span>
				
				<span class="textfield">
					<?php printf( esc_html__( 'Purchase the Pro Version of %s to get additional features and advanced customization options.', 'wiral-lite' ), 'WiralLite'); ?>
				</span>
				
				<p>
					<a href="<?php echo esc_url( __( 'https://themecountry.com/themes/wiral', 'wiral-lite' ) ); ?>" target="_blank" class="button button-secondary">
						<?php _e( 'Learn more about Wiral Pro', 'wiral-lite'); ?>
					</a>
				</p>
				
			</div>
			<?php
        }
	}
	
endif;