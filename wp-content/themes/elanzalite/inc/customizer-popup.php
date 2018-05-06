<div id="elanzalite_homepage" style="display:none; ">
    <div class="elanzalite-popup">
        <div>
            <div class="elanzalite_cp_column">
                <h3 class="elanzalite_title"><?php esc_html_e('To enjoy full featured elanzalite install Themehunk customizer plugin. This plugin will enable lot of amazing features in your customize panel', 'elanzalite') ?></h3>
                <h4><?php esc_html_e('Salient Features -', 'elanzalite'); ?></h4>
                <ul class="elanzalite-features-list">
                    <li><?php esc_html_e('Magazine layouts with lots of post style widgets', 'elanzalite'); ?></li>
                    <li><?php esc_html_e('Post layouts (Standard and Two grid)', 'elanzalite'); ?></li>
                    <li><?php esc_html_e('Unlimited colors (Primary, Multi color and Category color option)', 'elanzalite'); ?></li>
                    <li><?php esc_html_e('Dynamic sidebar option', 'elanzalite'); ?></li>
                    <li><?php esc_html_e('Hero post slider', 'elanzalite'); ?></li>
                    <li><?php esc_html_e('White label', 'elanzalite'); ?></li>
                    <li><?php esc_html_e('Typography', 'elanzalite'); ?></li>
					<li><?php esc_html_e('Custom widgets (AdSense, About Me, Social Media and Recent post with thumbnail)', 'elanzalite'); ?></li>
					<li><?php esc_html_e('And many more....', 'elanzalite'); ?></li>
                </ul>
            </div>
        </div>
        <div class="footer">
            <label class="disable-popup-cb">
                <input type="checkbox" id="disable-popup-cb"/>
                <?php esc_html_e("Don't show this popup in refresh page", 'elanzalite'); ?>
            </label>
            <a class="button-link-cb" onclick="tb_remove();"> <?php esc_html_e('Maybe later', 'elanzalite') ?> </a>
            <?php 
                $obj = New Elanzalite_Plugin();

            echo $obj->elanzalite_active_plugin(); ?>
        </div>
    </div>
</div>