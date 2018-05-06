(function ($) {
	'use strict';

	var TCWiralLite = {

		initReady: function() {
			this.menuAnimate();
			this.searchAnimate();
			this.banner();
			this.mobileMenu();
			this.scrollTop();
			
		},
		menuAnimate: function() {
			var self = this;
			var catcher = $('#catcher'),
				sticky  = $('#sticky'),
				bodyTop = $('body').offset().top;

			if ( sticky.length ) {
				$(window).scroll(function() {
					TCWiralLite.stickThatMenu(sticky,catcher,bodyTop);
				});
				$(window).resize(function() {
					TCWiralLite.stickThatMenu(sticky,catcher,bodyTop);
				});
			}
		},
		isScrolledTo: function(elem,top) {
			var docViewTop = $(window).scrollTop(); //num of pixels hidden above current screen
			var docViewBottom = docViewTop + $(window).height();

			var elemTop = $(elem).offset().top - top; //num of pixels above the elem
			var elemBottom = elemTop + $(elem).height();

			return ((elemTop <= docViewTop));
		},
		stickThatMenu: function(sticky,catcher,top) {
			var self = this;

			if(self.isScrolledTo(sticky,top)) {
				sticky.addClass('sticky-nav');
				catcher.height(sticky.height());
			} 
			var stopHeight = catcher.offset().top;
			if ( stopHeight > sticky.offset().top) {
				sticky.removeClass('sticky-nav');
				catcher.height(0);
			}
		},
		searchAnimate: function() {
			var header = $('.site-header');
			var trigger = $('#trigger-overlay');
			var overlay = header.find('.overlay');
			var input = header.find('.hideinput, .header-search .fa-search');
			trigger.click(function(e){
				var searchHeight = $('.site-header .header').height();
				$(this).hide();
				$('.search-style-one .search-row input#s').css({
					'height' : searchHeight + 'px'
				});

				overlay.addClass('open').find('input').focus();
			});

			$('.overlay-close').click(function(e) {
				$('.site-header .overlay').addClass('closed').removeClass('open');
				setTimeout(function() { $('.site-header .overlay').removeClass('closed'); }, 400);
				$('#trigger-overlay').show();
			});

			$(document).on('click', function(e) {
				var target = $(e.target);
				
				if (target.is('.overlay') || target.closest('.overlay').length) return true;

				
				$('.site-header .overlay').addClass('closed').removeClass('open');
				setTimeout(function() { $('.site-header .overlay').removeClass('closed'); }, 400);
				$('#trigger-overlay').show();
			});

			$('#trigger-overlay').click(function(e) {
				e.preventDefault();
				e.stopPropagation();
			});
		},
		banner: function() {
			$('.single-item-rtl').slick({
				rtl : true,
				'accessibility' : false
			});
		},
		mobileMenu: function() {
			var $top_menu = $('#primary-navigation');
			var $secondary_menu = $('.secondary-navigation');
			var $first_menu = '';
			var $second_menu = '';

			if ($top_menu.length == 0 && $secondary_menu.length == 0) {
				return;
			} else {
				if ($top_menu.length) {
					$first_menu = $top_menu;
					if($secondary_menu.length) {
						$second_menu = $secondary_menu;
						$('.top-nav').addClass('has-second-menu');
					}
				} else {
					$first_menu = $secondary_menu;
				}
			}
			var menu_wrapper = $first_menu
			.clone().attr('class', 'mobile-menu')
			.wrap('<div id="mobile-menu-wrapper" class="mobile-only"></div>').parent().hide()
			.appendTo('body');
			
			// Add items from the other menu
			if ($second_menu.length) {
				$second_menu.find('ul.menu').clone().appendTo('.mobile-menu .inner');
			}

			$('.header .toggle-mobile-menu').click(function(e) {
				e.preventDefault();
				e.stopPropagation();
				$('#mobile-menu-wrapper').show(); // only required once
				$('body').toggleClass('mobile-menu-active');
			});

			$('.container').click(function() {
				if ($('body').hasClass('mobile-menu-active')) {
					$('body').removeClass('mobile-menu-active');
				}
				if($('.menu-item-has-children .arrow-sub-menu').hasClass('fa-chevron-down')) {
					$('.menu-item-has-children .arrow-sub-menu').removeClass('fa-chevron-down').addClass('fa-chevron-right');
				}
			});

			if($('#wpadminbar').length) {
				$('#mobile-menu-wrapper').addClass('wpadminbar-active');
			}

			$('.category-navigation .toggle-mobile-menu').on('click', function(e) {
				e.preventDefault();

				if($(this).hasClass('active')) {
					$(this).removeClass('active');
					$(this).find('.fa-angle-up').removeClass('fa-angle-up').addClass('fa-angle-down');
					//$(this).next().removeClass('main-nav-open');
					$(this).next().slideUp();
				} else {
					$(this).addClass('active');
					$(this).find('.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-up');
					//$(this).next().addClass('main-nav-open');
					$(this).next().slideDown();
				}
			});
		},
		
		scrollTop: function() {
			
			var scrollDes = 'html,body';  
			// Opera does a strange thing if we use 'html' and 'body' together so my solution is to do the UA sniffing thing
			if(navigator.userAgent.match(/opera/i)){
				scrollDes = 'html';
			}
			// Show ,Hide
			$(window).scroll(function () {
				if ($(this).scrollTop() > 130) {
					$('.back-to-top').addClass('filling').removeClass('hiding');
					//$('.sharing-top-float').fadeIn();
				} else {
					$('.back-to-top').removeClass('filling').addClass('hiding');
					//$('.sharing-top-float').fadeOut();
				}
			});
			// Scroll to top when click
			$('.back-to-top').click(function () {
				$(scrollDes).animate({ 
					scrollTop: 0
				},{
					duration :500
				});

			});
		}
		
	};

	$(document).ready(function () {
		TCWiralLite.initReady();
	});

	$(window).resize(function() {
		var windowWidth = $(window).width();
		if( windowWidth >= '1024' ) {
			$('.category-navigation .toggle-mobile-menu').find('.fa-angle-up').removeClass('fa-angle-up').addClass('fa-angle-down');
			$('.category-navigation .toggle-mobile-menu').removeClass('active');
			$('.category-navigation .toggle-mobile-menu').next().removeAttr('style');
		}
	});

})(jQuery);