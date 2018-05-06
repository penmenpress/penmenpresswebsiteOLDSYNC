<?php
/**
* The template for displaying posts in the dynamic grid view
*/
?>
<?php $grid_layout = get_theme_mod('elanzalite_listing_layout','two-grid-layout');  ?>
<li id="post-<?php the_ID(); ?>" <?php post_class('post'); ?> >
  <div class="post-img">
    <?php
    if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) {
    ?>
    <a href="<?php the_permalink(); ?>"><?php elanzalite_grid_thumb($grid_layout); ?></a>
    <?php  } else{ ?>
    <a href="<?php the_permalink(); ?>"><?php elanzalite_grid_thumb($grid_layout,false); ?></a>
    <?php  }  ?>
  </div>
  <div class="post-content">
    <div class="post-content-inner">
      <div class="post-header">
        <?php if(get_theme_mod('post_cat_hide')=='' || get_theme_mod('post_cat_hide')=='0'){ ?>
        <?php if(!elanzalite_home_post_meta('category')): ?>
        <span class="post-category">
          <?php echo $category_list = get_the_category_list( __( ', ', 'elanzalite' ) ); ?>
        </span>
        <?php endif; }?>

        <div class="post-title">
          <a href="<?php the_permalink(); ?>">
            <h2><?php the_title(); ?></h2>
          </a>
        </div>
<?php if(get_theme_mod('post_meta_hide')=='' || get_theme_mod('post_meta_hide')=='0'){ ?>
        <div class="post-meta">
          <?php  if(!elanzalite_home_post_meta('date')): ?>
          <span class="post-date"><?php the_time('M d, Y'); ?></span>
          <?php endif; ?>
          <?php  if(!elanzalite_home_post_meta('comment')): ?>
          <?php elanzalite_comment_number(); ?>
          <?php endif; ?>
        </div>
        <?php }?>
      </div>
<?php if(get_theme_mod('post_excerpt_data_hide')=='' || get_theme_mod('post_excerpt_data_hide')=='0'){ ?>
      <div class="description"><p><?php echo elanzalite_get_custom_excerpt(); ?></p></div>
<?php if(get_theme_mod('post_read_more_hide')=='' || get_theme_mod('post_read_more_hide')=='0'){ ?>
      <div class="read-more">
        <a href="<?php the_permalink(); ?>">
          <?php $rdmoretxt = get_theme_mod('readmore_text',''); 
               if($rdmoretxt!==''){
                echo esc_attr($rdmoretxt);
               }else{
                 esc_html__('Continue Reading...','elanzalite'); 
               }
              ?>  
          </a>
      </div>

      <?php } } ?>
    </div>
  </div>
</li>