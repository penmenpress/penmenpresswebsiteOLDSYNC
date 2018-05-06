<?php
/**
* Comments form and comment feature
*/
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
die (esc_html__('Please do not load this page directly. Thanks!','elanzalite'));

if ( post_password_required() ) { ?>
     <p class="nocomments">
          <?php esc_html__('This post is password protected. Enter the password to view comments.','elanzalite'); 
          ?>
     </p>
<?php return; } ?>

<!-- You can start editing here. -->
<div class="comment-section" id="commentsbox">
     <?php if ( have_comments() ) : ?>
     <h1 class="post-info" id="comments">
     <?php comments_number(__('No Responses', 'elanzalite'), __('One Response', 'elanzalite'),  __('% Responses', 'elanzalite') ); ?>
     </h1>
     <ol class="commentlist">
          <?php wp_list_comments(); ?>
     </ol>
     <div class="comment-nav">
          <div class="alignleft">
               <?php previous_comments_link(); ?>
          </div>
          <div class="alignright">
               <?php next_comments_link(); ?>
          </div>
     </div>
     <?php else : // this is displayed if there are no comments so far ?>
     <?php if ( comments_open() ) : ?>
     <!-- If comments are open, but there are no comments. -->
     <?php else : // comments are closed ?>
     <!-- If comments are closed. -->
     <p class="nocomments"><?php esc_html__('Comments are closed.', 'elanzalite'); ?></p>
     <?php endif; ?>
     <?php endif; ?>
     <?php if ( comments_open() ) : 
          echo "<div id='comments_pagination'>";
               paginate_comments_links(array('prev_text' => __('&laquo;','elanzalite'), 'next_text' => __('&raquo;','elanzalite')));
          echo "</div>";

     $custom_comment_field = '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';  //label removed for cleaner layout
          comment_form(array(
               'comment_field'               => $custom_comment_field,
               'comment_notes_after'         => '',
               'logged_in_as'                => '',
               'comment_notes_before'        => '',
               'title_reply'                 => __('Leave a Reply', 'elanzalite'),
               'cancel_reply_link'           => __('Cancel Reply', 'elanzalite'),
               'label_submit'                => __('Post Comment', 'elanzalite')
          ));
 endif; // if you delete this the sky will fall on your head ?>
</div>