/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );

	//Top Header date
	wp.customize( 'editorial_pro_header_date', function( value ) {
		value.bind( function( to ) {
			if( to === 'disable' ) {
				$( '.top-header-section .date-section' ).fadeOut();
			} else {
				$( '.top-header-section .date-section' ).fadeIn();
			}
		} );
	} );

	//Top Header social
	wp.customize( 'editorial_pro_header_social_option', function( value ) {
		value.bind( function( to ) {
			if( to === 'disable' ) {
				$( '.top-header-section .top-social-wrapper' ).fadeOut();
			} else {
				$( '.top-header-section .top-social-wrapper' ).fadeIn();
			}
		} );
	} );

	//News ticker
	wp.customize( 'editorial_pro_ticker_option', function( value ) {
		value.bind( function( to ) {
			if( to === 'disable' ) {
				$( '.editorial-ticker-wrapper' ).fadeOut();
			} else {
				$( '.editorial-ticker-wrapper' ).fadeIn();
			}
		} );
	} );

	/**
	 * Post date
	 */
	wp.customize( 'editorial_widget_post_date', function( value ) {
		value.bind( function( to ) {
			if( to === 'hide' ) {
				$( '.posted-on' ).fadeOut();
			} else {
				$( '.posted-on' ).fadeIn();
			}
		} );
	} );

	/**
	 * Post author
	 */
	wp.customize( 'editorial_widget_post_author', function( value ) {
		value.bind( function( to ) {
			if( to === 'hide' ) {
				$( '.post-meta-wrapper .byline' ).fadeOut();
			} else {
				$( '.post-meta-wrapper .byline' ).fadeIn();
			}
		} );
	} );

	/**
	 * Post comments
	 */
	wp.customize( 'editorial_widget_post_comment', function( value ) {
		value.bind( function( to ) {
			if( to === 'hide' ) {
				$( '.post-meta-wrapper .comments-link' ).fadeOut();
			} else {
				$( '.post-meta-wrapper .comments-link' ).fadeIn();
			}
		} );
	} );

	/**
	 * Post categories
	 */
	wp.customize( 'editorial_widget_post_categories', function( value ) {
		value.bind( function( to ) {
			if( to === 'hide' ) {
				$( '.post-content-wrapper .post-cat-list' ).fadeOut();
			} else {
				$( '.post-content-wrapper .post-cat-list' ).fadeIn();
			}
		} );
	} );

	/**
	 * Post review
	 */
	wp.customize( 'editorial_widget_post_review', function( value ) {
		value.bind( function( to ) {
			if( to === 'hide' ) {
				$( '.post-review-wrapper' ).fadeOut();
			} else {
				$( '.post-review-wrapper' ).fadeIn();
			}
		} );
	} );

	/* === menu font === */
	wp.customize( 'menu_font_family', function( value ) {
		value.bind( function( to ) {
			if(to != 'Arial' && to != 'Verdana' && to != 'Trebuchet' && to != 'Georgia' && to != 'Tahoma' && to != 'Palatino' && to != 'Helvetica' ){
				WebFont.load({ google: { families: [to] } });
			}
			$( '#site-navigation ul li a' ).css( 'font-family', to );
		});
	});
	wp.customize( 'menu_font_style', function( value ) {
		value.bind( function( to ) {
				var weight = to.replace(/\D/g,'');
				var style = to.replace(/\d+/g, '');
				$( '#site-navigation ul li a' ).css( 'font-weight', weight );
				$( '#site-navigation ul li a' ).css( 'font-style', style );
		});
	});
	wp.customize( 'menu_text_transform', function( value ) {
		value.bind( function( to ) {
			$( '#site-navigation ul li a' ).css( 'text-transform', to );
		});
	});
	wp.customize( 'menu_text_decoration', function( value ) {
		value.bind( function( to ) {
			$( '#site-navigation ul li a' ).css( 'text-decoration', to );
		});
	});
	wp.customize( 'menu_font_size', function( value ) {
		value.bind( function( to ) {
			$( '#site-navigation ul li a' ).css( 'font-size', to + 'px' );
		});
	});
	wp.customize( 'menu_line_height', function( value ) {
		value.bind( function( to ) {
			$( '#site-navigation ul li a' ).css( 'line-height', to+'px' );
		});
	});
	wp.customize( 'menu_color', function( value ) {
		value.bind( function( to ) {
			$( '#site-navigation ul li a' ).css( 'color', to );
		});
	});

	/* === body font === */
	wp.customize( 'p_font_family', function( value ) {
		value.bind( function( to ) {
			if(to != 'Arial' && to != 'Verdana' && to != 'Trebuchet' && to != 'Georgia' && to != 'Tahoma' && to != 'Palatino' && to != 'Helvetica' ){
				WebFont.load({ google: { families: [to] } });
			}
			$( 'body' ).css( 'font-family', to );
		});
	});
	wp.customize( 'p_font_style', function( value ) {
		value.bind( function( to ) {
				var weight = to.replace(/\D/g,'');
				var style = to.replace(/\d+/g, '');
				$( 'body' ).css( 'font-weight', weight );
				$( 'body' ).css( 'font-style', style );
		});
	});
	wp.customize( 'p_text_transform', function( value ) {
		value.bind( function( to ) {
			$( 'body' ).css( 'text-transform', to );
		});
	});
	wp.customize( 'p_text_decoration', function( value ) {
		value.bind( function( to ) {
			$( 'body' ).css( 'text-decoration', to );
		});
	});
	wp.customize( 'p_font_size', function( value ) {
		value.bind( function( to ) {
			var To = to+'px';
			$( 'body' ).css( 'font-size', To );
		});
	});
	wp.customize( 'p_line_height', function( value ) {
		value.bind( function( to ) {
			$( 'body' ).css( 'line-height', to );
		});
	});
	wp.customize( 'p_color', function( value ) {
		value.bind( function( to ) {
			$( 'body' ).css( 'color', to );
		});
	});

	/* === h1 font === */
	wp.customize( 'h1_font_family', function( value ) {
		value.bind( function( to ) {
			if(to != 'Arial' && to != 'Verdana' && to != 'Trebuchet' && to != 'Georgia' && to != 'Tahoma' && to != 'Palatino' && to != 'Helvetica' ){
				WebFont.load({ google: { families: [to] } });
			}
			$( 'h1' ).css( 'font-family', to );
		});
	});
	wp.customize( 'h1_font_style', function( value ) {
		value.bind( function( to ) {
				var weight = to.replace(/\D/g,'');
				var style = to.replace(/\d+/g, '');
				$( 'h1' ).css( 'font-weight', weight );
				$( 'h1' ).css( 'font-style', style );
		});
	});
	wp.customize( 'h1_text_transform', function( value ) {
		value.bind( function( to ) {
			$( 'h1' ).css( 'text-transform', to );
		});
	});
	wp.customize( 'h1_text_decoration', function( value ) {
		value.bind( function( to ) {
			$( 'h1' ).css( 'text-decoration', to );
		});
	});
	wp.customize( 'h1_font_size', function( value ) {
		value.bind( function( to ) {
			var To = to+'px';
			$( 'body' ).css( 'font-size', To );
		});
	});
	wp.customize( 'h1_line_height', function( value ) {
		value.bind( function( to ) {
			$( 'h1' ).css( 'line-height', to );
		});
	});
	wp.customize( 'h1_color', function( value ) {
		value.bind( function( to ) {
			$( 'h1' ).css( 'color', to );
		});
	});

	/* === h2 font === */
	wp.customize( 'h2_font_family', function( value ) {
		value.bind( function( to ) {
			if(to != 'Arial' && to != 'Verdana' && to != 'Trebuchet' && to != 'Georgia' && to != 'Tahoma' && to != 'Palatino' && to != 'Helvetica' ){
				WebFont.load({ google: { families: [to] } });
			}
			$( 'h2' ).css( 'font-family', to );
		});
	});
	wp.customize( 'h2_font_style', function( value ) {
		value.bind( function( to ) {
				var weight = to.replace(/\D/g,'');
				var style = to.replace(/\d+/g, '');
				$( 'h2' ).css( 'font-weight', weight );
				$( 'h2' ).css( 'font-style', style );
		});
	});
	wp.customize( 'h2_text_transform', function( value ) {
		value.bind( function( to ) {
			$( 'h2' ).css( 'text-transform', to );
		});
	});
	wp.customize( 'h2_text_decoration', function( value ) {
		value.bind( function( to ) {
			$( 'h2' ).css( 'text-decoration', to );
		});
	});
	wp.customize( 'h2_font_size', function( value ) {
		value.bind( function( to ) {
			var To = to+'px';
			$( 'body' ).css( 'font-size', To );
		});
	});
	wp.customize( 'h2_line_height', function( value ) {
		value.bind( function( to ) {
			$( 'h2' ).css( 'line-height', to );
		});
	});
	wp.customize( 'h2_color', function( value ) {
		value.bind( function( to ) {
			$( 'h2' ).css( 'color', to );
		});
	});

	/* === h3 font === */
	wp.customize( 'h3_font_family', function( value ) {
		value.bind( function( to ) {
			if(to != 'Arial' && to != 'Verdana' && to != 'Trebuchet' && to != 'Georgia' && to != 'Tahoma' && to != 'Palatino' && to != 'Helvetica' ){
				WebFont.load({ google: { families: [to] } });
			}
			$( 'h3 a' ).css( 'font-family', to );
		});
	});
	wp.customize( 'h3_font_style', function( value ) {
		value.bind( function( to ) {
				var weight = to.replace(/\D/g,'');
				var style = to.replace(/\d+/g, '');
				$( 'h3 a' ).css( 'font-weight', weight );
				$( 'h3 a' ).css( 'font-style', style );
		});
	});
	wp.customize( 'h3_text_transform', function( value ) {
		value.bind( function( to ) {
			$( 'h3 a' ).css( 'text-transform', to );
		});
	});
	wp.customize( 'h3_text_decoration', function( value ) {
		value.bind( function( to ) {
			$( 'h3 a' ).css( 'text-decoration', to );
		});
	});
	wp.customize( 'h3_font_size', function( value ) {
		value.bind( function( to ) {
			var smallToSize = to-3;
			$( 'h3.large-size a' ).css( 'font-size', to + 'px' );
			$( 'h3.small-size a' ).css( 'font-size', smallToSize + 'px' );
		});
	});
	wp.customize( 'h3_line_height', function( value ) {
		value.bind( function( to ) {
			$( 'h3 a' ).css( 'line-height', to );
		});
	});
	wp.customize( 'h3_color', function( value ) {
		value.bind( function( to ) {
			$( 'h3 a' ).css( 'color', to );
		});
	});

	/* === h4 font === */
	wp.customize( 'h4_font_family', function( value ) {
		value.bind( function( to ) {
			if(to != 'Arial' && to != 'Verdana' && to != 'Trebuchet' && to != 'Georgia' && to != 'Tahoma' && to != 'Palatino' && to != 'Helvetica' ){
				WebFont.load({ google: { families: [to] } });
			}
			$( 'h4' ).css( 'font-family', to );
		});
	});
	wp.customize( 'h4_font_style', function( value ) {
		value.bind( function( to ) {
				var weight = to.replace(/\D/g,'');
				var style = to.replace(/\d+/g, '');
				$( 'h4' ).css( 'font-weight', weight );
				$( 'h4' ).css( 'font-style', style );
		});
	});
	wp.customize( 'h4_text_transform', function( value ) {
		value.bind( function( to ) {
			$( 'h4' ).css( 'text-transform', to );
		});
	});
	wp.customize( 'h4_text_decoration', function( value ) {
		value.bind( function( to ) {
			$( 'h4' ).css( 'text-decoration', to );
		});
	});
	wp.customize( 'h4_font_size', function( value ) {
		value.bind( function( to ) {
			var To = to+'px';
			$( 'body' ).css( 'font-size', To );
		});
	});
	wp.customize( 'h4_line_height', function( value ) {
		value.bind( function( to ) {
			$( 'h4' ).css( 'line-height', to );
		});
	});
	wp.customize( 'h4_color', function( value ) {
		value.bind( function( to ) {
			$( 'h4' ).css( 'color', to );
		});
	});

	/* === h5 font === */
	wp.customize( 'h5_font_family', function( value ) {
		value.bind( function( to ) {
			if(to != 'Arial' && to != 'Verdana' && to != 'Trebuchet' && to != 'Georgia' && to != 'Tahoma' && to != 'Palatino' && to != 'Helvetica' ){
				WebFont.load({ google: { families: [to] } });
			}
			$( 'h5' ).css( 'font-family', to );
		});
	});
	wp.customize( 'h5_font_style', function( value ) {
		value.bind( function( to ) {
				var weight = to.replace(/\D/g,'');
				var style = to.replace(/\d+/g, '');
				$( 'h5' ).css( 'font-weight', weight );
				$( 'h5' ).css( 'font-style', style );
		});
	});
	wp.customize( 'h5_text_transform', function( value ) {
		value.bind( function( to ) {
			$( 'h5' ).css( 'text-transform', to );
		});
	});
	wp.customize( 'h5_text_decoration', function( value ) {
		value.bind( function( to ) {
			$( 'h5' ).css( 'text-decoration', to );
		});
	});
	wp.customize( 'h5_font_size', function( value ) {
		value.bind( function( to ) {
			var To = to+'px';
			$( 'body' ).css( 'font-size', To );
		});
	});
	wp.customize( 'h5_line_height', function( value ) {
		value.bind( function( to ) {
			$( 'h5' ).css( 'line-height', to );
		});
	});
	wp.customize( 'h5_color', function( value ) {
		value.bind( function( to ) {
			$( 'h5' ).css( 'color', to );
		});
	});

	/* === h6 font === */
	wp.customize( 'h6_font_family', function( value ) {
		value.bind( function( to ) {
			if(to != 'Arial' && to != 'Verdana' && to != 'Trebuchet' && to != 'Georgia' && to != 'Tahoma' && to != 'Palatino' && to != 'Helvetica' ){
				WebFont.load({ google: { families: [to] } });
			}
			$( 'h6' ).css( 'font-family', to );
		});
	});
	wp.customize( 'h6_font_style', function( value ) {
		value.bind( function( to ) {
				var weight = to.replace(/\D/g,'');
				var style = to.replace(/\d+/g, '');
				$( 'h6' ).css( 'font-weight', weight );
				$( 'h6' ).css( 'font-style', style );
		});
	});
	wp.customize( 'h6_text_transform', function( value ) {
		value.bind( function( to ) {
			$( 'h6' ).css( 'text-transform', to );
		});
	});
	wp.customize( 'h6_text_decoration', function( value ) {
		value.bind( function( to ) {
			$( 'h6' ).css( 'text-decoration', to );
		});
	});
	wp.customize( 'h6_font_size', function( value ) {
		value.bind( function( to ) {
			var To = to+'px';
			$( 'body' ).css( 'font-size', To );
		});
	});
	wp.customize( 'h6_line_height', function( value ) {
		value.bind( function( to ) {
			$( 'h6' ).css( 'line-height', to );
		});
	});
	wp.customize( 'h6_color', function( value ) {
		value.bind( function( to ) {
			$( 'h6' ).css( 'color', to );
		});
	});

	/**
	 * single post review summary
	 */
	wp.customize( 'single_post_review_summary_title', function( value ) {
		value.bind( function( to ) {
			$( '.review-content-wrapper .sum-title' ).text( to );
		} );
	} );


} )( jQuery );
