<?php
defined('ABSPATH') || exit;

@include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$controls = new NewsletterControls();
$module = NewsletterSubscription::instance();

if (!$controls->is_action()) {
    $controls->data = $module->get_options('profile');
} else {
    if ($controls->is_action('save')) {
        $module->merge_options($controls->data, 'profile');
        // In the near future
        $module->save_options($controls->data, 'lists');
        $controls->add_message_saved();
    }
}

for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
    if (!isset($controls->data['list_' . $i . '_forced'])) {
        $controls->data['list_' . $i . '_forced'] = empty($module->options['preferences_' . $i]) ? 0 : 1;
    }
}


$status = array(0 => 'Disabled/Private use', 1 => 'Only on profile page', 2 => 'Even on subscription forms', '3' => 'Hidden');
?>
<script>
    jQuery(function () {
        jQuery(".tnp-notes").tooltip({
            content: function () {
                // That activates the HTML in the tooltip
                return this.title;
            }
        });
    });
</script>
<div class="wrap tnp-lists" id="tnp-wrap">

    <?php include NEWSLETTER_DIR . '/tnp-header.php'; ?>

    <div id="tnp-heading">

        <h2><?php _e('Lists', 'newsletter') ?></h2>

    </div>

    <div id="tnp-body">

        <form method="post" action="">
            <?php $controls->init(); ?>
            <p>
                <?php $controls->button_save(); ?>
            </p>
            <table class="widefat">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php _e('Name', 'newsletter')?></th>
                        <th><?php _e('Visibility', 'newsletter')?></th>
                        <th><?php _e('Pre-checked', 'newsletter')?></th>
                        <th><?php _e('Pre-assigned', 'newsletter')?></th>
                        <th><?php _e('Subscribers', 'newsletter')?></th>
                        <th><?php _e('Notes', 'newsletter') ?></th>
                    </tr>
                </thead>
                <?php for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) { ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php $controls->text('list_' . $i, 50); ?></td>
                        <td><?php $controls->select('list_' . $i . '_status', $status); ?></td>
                        <td><?php $controls->select('list_' . $i . '_checked', array(0 => 'No', 1 => 'Yes')); ?></td>
                        <td><?php $controls->select('list_' . $i . '_forced', array(0 => 'No', 1 => 'Yes')); ?></td>
                        <td><?php echo $wpdb->get_var("select count(*) from " . NEWSLETTER_USERS_TABLE . " where list_" . $i . "=1 and status='C'"); ?></td>
                        <td>
                            <?php $notes = apply_filters('newsletter_lists_notes', array(), $i); ?>
                            <?php
                            $text = '';
                            foreach ($notes as $note) {
                                $text .= $note . '<br>';
                            }
                            if (!empty($text)) {
                            echo '<i class="fa fa-info-circle tnp-notes" title="', esc_attr($text), '"></i>';
                            }
                            ?> 

                        </td>
                    </tr>
                <?php } ?>
            </table>

            <p>
                <?php $controls->button_save(); ?>
            </p>
        </form>
    </div>

    <?php include NEWSLETTER_DIR . '/tnp-footer.php'; ?>

</div>