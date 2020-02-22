( function( $ ) {

	'use strict';

	$(document).ready(function($){

		$('.editorial-message .notice-dismiss').on('click', function(e) {

			e.preventDefault();

			var $this = $(this);

			var nonce = $(this).parent().data('nonce');

			$.ajax({
				type     : 'GET',
				dataType : 'json',
				url      : ajaxurl,
				data     : {
					'action'   : 'editorial_notice_dissmiss',
					'_wpnonce' : nonce
				},
				success  : function (response) {
					if ( true === response.status ) {
						$this.parents('#mt-theme-message').fadeOut('slow');
					}
				}
			});
		});
	});

} )( jQuery );