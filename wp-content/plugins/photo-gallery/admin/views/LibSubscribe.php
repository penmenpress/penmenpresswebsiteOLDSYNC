<?php
$admin_data = wp_get_current_user();

$user_first_name = get_user_meta($admin_data->ID, "first_name", true);
$user_last_name = get_user_meta($admin_data->ID, "last_name", true);

$name = $user_first_name || $user_last_name ? $user_first_name . " " . $user_last_name : $admin_data->data->user_login;
$bwg_subscribe = get_option("bwg_subscribe_email");
?>
<script>
    jQuery(document).on("ready", function () {
        jQuery("#tenweb_subscribe_submit").on("click", function () {
            var error = 0;
            var inputs = {
                "user_name" : "<?php _e("Name is required.", BWG()->prefix); ?>",
                "user_email" : "<?php _e("Please enter a valid email.", BWG()->prefix); ?>"
            };
            for (var i in inputs) {
                var input =  jQuery("#<?php echo BWG()->prefix; ?>_" + i);
                if (input.val() == "" || (i == "user_email" && !tenWebSubscrineIsEmail(input.val()))) {
                    input.closest("p").addClass("error");
                    input.closest("p").find(".error_msg").html(inputs[i]);
                    error++;
                }
                else {
                    input.closest("p").removeClass("error");
                    input.closest("p").find(".error_msg").html("");
                }
            }
            if (error == 0 ) {
                jQuery(this).closest("form").submit();
            } else {
                return false;
            }
        });
    });
    function tenWebSubscrineIsEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
</script>
<div id="tenweb_new_subscribe" class="tenweb_subscribe">
    <div class="tenweb_subscribe_container">
        <div class="tenweb_subscribe_content">
            <a id="tenweb_logo"></a>
            <h1 id="tenweb_title"><?php _e( "Get Free Ebook for Creating an Beautiful Gallery in 5 Minutes", BWG()->prefix ); ?></h1>
            <p id="tenweb_description"><?php _e( "Your gallery will look beautiful, load fast <br>and attract visitors.", BWG()->prefix ); ?></p>
            <div id="tablet_book">
                <img src="<?php echo BWG()->plugin_url; ?>/images/subscribe/book_768.png">
            </div>
            <form id="tenweb_form" method="get" action="admin.php?page=<?php echo BWG()->prefix; ?>_subscribe">
                <p>
                    <label for="user_name"><?php _e( "NAME", BWG()->prefix ); ?> <span>*</span></label>
                    <input type="text" name="<?php echo BWG()->prefix; ?>_user_name"  id="<?php echo BWG()->prefix; ?>_user_name" placeholder="Fill Your Name" value="<?php echo $name; ?>">
                    <span class='error_msg'></span>
                </p>
                <p>
                    <label for="user_name"><?php _e( "EMAIL ADDRESS", BWG()->prefix ); ?> <span>*</span></label>
                    <input type="text" name="<?php echo BWG()->prefix; ?>_user_email"  id="<?php echo BWG()->prefix; ?>_user_email" placeholder="Fill Your Email" value="<?php echo $admin_data->data->user_email; ?>">
                    <span class='error_msg'></span>
                </p>
                <div class="form_desc"><?php _e( "Submitting this form you agree that 10Web stores your name, email, and site URL. We won’t share your info with third parties. We are GDPR compliant.", BWG()->prefix ); ?></div>
                <div id="form_buttons">
                    <input type="hidden" name="<?php echo BWG()->prefix; ?>_sub_action" value="allow" />
                    <input type="hidden" name="page" value="bwg_subscribe" />
                    <input type="button" id="tenweb_subscribe_submit" value="RECEIVE EBOOK" />
                    <a href="<?php echo "admin.php?page=" . BWG()->prefix . "_subscribe&" . BWG()->prefix . "_sub_action=skip" ;?>" class="skip more" ><?php _e( "Skip", BWG()->prefix ); ?></a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php if ($bwg_subscribe !== false && isset($_GET["bwg_sub_action"]) && $_GET["bwg_sub_action"] == "allow") : ?>
<div id="tenweb_subscribe_popup" class="subscribed">
    <div class="subscribe_popup_content">
        <div class="subscribe_email"><?php echo $bwg_subscribe; ?></div>
        <h3>We've just sent you the eBook!</h3>
        <p class="bold">If you are a Gmail user check:</p>
        <p>Primary, Promotions, Social, Updates or Forums.</p>
        <img src="<?php echo BWG()->plugin_url; ?>/images/subscribe/popup_img.png">
        <p class="bold">Warning: also check your spam or junk!</p>
        <p><b>1.</b> Select the message and click Not Spam.</p>
        <p><b>2.</b> Recheck all inbox tabs.</p>
        <a href="<?php echo admin_url('admin.php?page=galleries_bwg'); ?>" class="got_it">GOT IT</a>
    </div>
</div>
<?php endif; ?>
