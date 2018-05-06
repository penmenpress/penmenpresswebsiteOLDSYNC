<?php
/**
 * The default template for displaying content
 */
?>
<?php if (is_single()) : ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
<?php 
if(get_theme_mod('single_post_ftured_hide')=='' || get_theme_mod('single_post_ftured_hide')=='0'){ ?>
	<div class="post-img">
	<?php
	  	if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) {
				the_post_thumbnail('post-thumbnails'); 
		  }
	   ?>	
	</div>
	<?php } ?>
	<div class="single-meta"><!-- Single Meta Start -->
<?php if(get_theme_mod('single_post_cat_hide')=='' || get_theme_mod('single_post_cat_hide')=='0'){ ?>
	<span class="post-category"><?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'elanzalite' ) ); ?></span>
<?php } ?>
	<div class="post-title"><h1><span><?php the_title(); ?></span></h1></div>
<?php if(get_theme_mod('single_post_meta_hide')=='' || get_theme_mod('single_post_meta_hide')=='0'){ ?>
	<div class="post-meta">
		<span class="post-author"><?php the_author_posts_link() ?></span>
		<span class="post-author-pic"><?php echo elanzalite_userPic(); ?></span>
		<span class="post-date"><?php the_time( get_option('date_format') ); ?></span>
	</div>
	<?php } ?>
	</div><!-- Single Meta End -->
	<div class="post-content clearfix"><!-- Content Start -->
	<div class="description">
		<?php the_content(); ?>
	</div>
	<div class="clearfix"></div>
	<div class="multipage-links">
		<?php
			wp_link_pages( array(
						'before'      => '<span class="meta-nav">' . __( 'Pages:', 'elanzalite' ) . '</span>',
						'after'       => '',
						'link_before' => '<span class="active">',
						'link_after'  => '</span>',
					) );
		?>
	</div>
	<div class="single-bottom-meta">
		<?php if(get_theme_mod('post_tag_hide')=='' || get_theme_mod('post_tag_hide')=='0'){ ?>
		<div class="tagcloud"><?php echo get_the_tag_list( '', __( ' ', 'elanzalite' ) ); ?>
		</div>
        <?php } ?>
		<?php if(get_theme_mod('post_share_hide')=='' || get_theme_mod('post_share_hide')=='0'){ 
		if ( shortcode_exists( 'themehunk-customizer-elanzalite' ) ){
                    do_shortcode( '[themehunk-customizer-elanzalite page="social-share"]' );
        }  }?>
	</div>
	</div><!-- Content End -->
	<?php 
	if(get_theme_mod('post_nav_hide')=='' || get_theme_mod('post_nav_hide')=='0'){
	elanzalite_post_nav();
      }?>
	<div class="clearfix"></div>
<?php if(get_theme_mod('post_related_hide')=='' || get_theme_mod('post_related_hide')=='0'){ ?>
	<div class="related-post">
		<?php elanzalite_get_related_sigle_post(); ?>
	</div>
	<?php } ?>
	<div class="clearfix"></div>
	<?php edit_post_link( __( 'Edit', 'elanzalite' ), '<span class="edit-link">', '</span>' );
	?>
</article>
<?php else: ?>
<li id="post-<?php the_ID(); ?>" <?php post_class('post'); ?> >
	<div class="post-img">
		<?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
		<a href="<?php the_permalink(); ?>"> <?php the_post_thumbnail('post-thumbnails'); ?> </a>
		<?php  } ?>
	</div>
	<div class="post-header">
		<?php if(get_theme_mod('post_cat_hide')=='' || get_theme_mod('post_cat_hide')=='0'){ ?>
		<span class="post-category">
			<?php echo $category_list = get_the_category_list( __( ' / ', 'elanzalite' ) ); ?>
		</span>
		<?php } ?>
		<div class="post-title">
			<a href="<?php the_permalink(); ?>">
				<h2><?php the_title(); ?></h2>
			</a>
		</div>
		<?php if(get_theme_mod('post_meta_hide')=='' || get_theme_mod('post_meta_hide')=='0'){ ?>
		<div class="post-meta">
			<span class="post-author"><?php the_author_posts_link(); ?></span>
			<span class="post-author-pic"> <?php echo elanzalite_userPic(); ?></span>
			<span class="post-date"><?php the_time( get_option('date_format') ); ?></span>
		</div>
		<?php } ?>
	</div>
	<?php if(get_theme_mod('stndrd_post_excerpt_data_hide')=='' || get_theme_mod('stndrd_post_excerpt_data_hide')=='0'){ ?>
	<div class="description"><?php the_content(__('Continue Reading...','elanzalite'),true); ?></div>
	<?php } ?>
	<div class="clearfix"></div>
	<div class="standard-bottom-meta">
		<?php if(get_theme_mod('post_meta_hide')=='' || get_theme_mod('post_meta_hide')=='0'){ ?>
		<?php elanzalite_comment_number(); ?>
		<?php } if(get_theme_mod('blog_post_share_hide')=='' || get_theme_mod('blog_post_share_hide')=='0'){?>
		<?php  if ( shortcode_exists( 'themehunk-customizer-elanzalite' ) ){
                    do_shortcode( '[themehunk-customizer-elanzalite page="social-share"]' );
        } } ?>
</li>
<?php endif; ?>