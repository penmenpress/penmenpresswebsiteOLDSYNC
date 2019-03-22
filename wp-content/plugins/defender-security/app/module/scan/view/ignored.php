<div class="sui-box">
    <div class="sui-box-header">
        <!-- Box title with icon -->
        <h3 class="sui-box-title">
			<?php _e( "Ignored", "defender-security" ) ?>
        </h3>
    </div>
    <div class="sui-box-body">
		<?php $table = new \WP_Defender\Module\Scan\Component\Result_Table();
		$table->type = \WP_Defender\Module\Scan\Model\Result_Item::STATUS_IGNORED;
		$table->prepare_items();
		if ( $table->get_pagination_arg( 'total_items' ) ) {
			?>
            <p class="line"><?php _e( "Here is a list of the suspicious files you have chosen to ignore.", "defender-security" ) ?></p>
			<?php
			$table->display();
		} else {
			?>
            <div class="sui-notice sui-notice-info">
                <p> <?php _e( "You haven't ignored any suspicious files yet. Ignored files appear here and can be restored at any times.", "defender-security" ) ?> </p>
            </div>
			<?php
		}
		?>
    </div>
</div>