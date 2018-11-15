<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
 
class ampforwp_pointers {
	const DISPLAY_VERSION = 'v1.0';
	function __construct () {
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
	}
	function admin_enqueue_scripts () {
		$dismissed = explode (',', get_user_meta (wp_get_current_user ()->ID, 'dismissed_wp_pointers', true));
		$do_tour = !in_array ('ampforwp_subscribe_pointer', $dismissed);
		if ($do_tour) {
			wp_enqueue_style ('wp-pointer');
			wp_enqueue_script ('wp-pointer');

			add_action('admin_print_footer_scripts', array($this, 'admin_print_footer_scripts'));
			add_action('admin_head', array($this, 'admin_head'));  // Hook to admin head
		}
	}
	function admin_head () {
		?>
		<style type="text/css" media="screen"> #pointer-primary { margin: 0 5px 0 0; } </style>
		<?php }
	function admin_print_footer_scripts () {
		global $pagenow;
		global $current_user;
		$tour = array ();
        $tab = isset($_GET['tab']) ? sanitize_text_field( wp_unslash($_GET['tab'])) : '';
		$function = '';
		$button2 = '';
		$options = array ();
		$show_pointer = false;

        if (!array_key_exists($tab, $tour)) {

			$show_pointer = true;
			$file_error = true;

			$id = '#toplevel_page_amp_options';  // Define ID used on page html element where we want to display pointer

			$options = array (
				'content' => $content,
				'position' => array ('edge' => 'left', 'align' => 'left')
				);
		}
		if ($show_pointer) {
			$this->ampforwp_pointer_script ($id, $options, esc_html__('No Thanks', 'accelerated-mobile-pages'), $button2, $function);
		}
	}
	function get_admin_url($page, $tab) {
		$url = admin_url();
		$url .= $page.'?tab='.$tab;
		return $url;
	}
	function ampforwp_pointer_script ($id, $options, $button1, $button2=false, $function='') {
		?>
		<script type="text/javascript">
			(function ($) {
				var wp_pointers_tour_opts = <?php echo json_encode ($options); ?>, setup;
				wp_pointers_tour_opts = $.extend (wp_pointers_tour_opts, {
					buttons: function (event, t) {
						button= jQuery ('<a id="pointer-close" class="button-secondary">' + '<?php echo wp_kses_post($button1); ?>' + '</a>');
						button_2= jQuery ('#pointer-close.button');
						button.bind ('click.pointer', function () {
							t.element.pointer ('close');
						});
						button_2.on('click', function() {
							t.element.pointer ('close');
						} );
						return button;
					},
					close: function () {
						$.post (ajaxurl, {
							pointer: 'ampforwp_subscribe_pointer',
							action: 'dismiss-wp-pointer'
						});
					}
				});
				setup = function () {
					$('<?php echo esc_attr($id); ?>').pointer(wp_pointers_tour_opts).pointer('open');
					<?php if ($button2) { ?>
						jQuery ('#pointer-close').after ('<a id="pointer-primary" class="button-primary">' + '<?php echo wp_kses_post($button2); ?>' + '</a>');
						jQuery ('#pointer-primary').click (function () {
							<?php echo $function; ?>
						});
						jQuery ('#pointer-close').click (function () {
							$.post (ajaxurl, {
								pointer: 'ampforwp_subscribe_pointer',
								action: 'dismiss-wp-pointer'
							});
						})
					<?php } ?>
				};
				if (wp_pointers_tour_opts.position && wp_pointers_tour_opts.position.defer_loading) {
					$(window).bind('load.wp-pointers', setup);
				}
				else {
					setup ();
				}
			}) (jQuery);
		</script>
 	<?php
	}
}
$ampforwp_pointers = new ampforwp_pointers();
?>