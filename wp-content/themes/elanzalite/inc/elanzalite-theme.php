<?php


/**
 * Add admin notice when active theme, just show one time
 *
 * @return bool|null
 */
add_action( 'admin_notices', 'elanzalite_admin_notice' );

function elanzalite_admin_notice() {
  global $current_user;
  $user_id   = $current_user->ID;
  $theme_data  = wp_get_theme();
  if ( !get_user_meta( $user_id, esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore' ) ) {
    ?>
    <div class="notice thunk-notice">

      <h1>
        <?php
        /* translators: %1$s: theme name, %2$s theme version */
        printf( esc_html__( 'Welcome to %1$s - Version %2$s', 'elanzalite' ), esc_html( $theme_data->Name ), esc_html( $theme_data->Version ) );
        ?>
      </h1>
      <p>
        <?php
        /* translators: %1$s: theme name, %2$s link */
        printf( __( 'Welcome! Thank you for choosing %1$s! To fully take advantage of the best our theme can offer please make sure you visit our <a href="%2$s">Welcome page</a>', 'elanzalite' ), esc_html( $theme_data->Name ), esc_url( admin_url( 'themes.php?page=th_elanzalite' ) ) );
        printf( '<a href="%1$s" class="notice-dismiss dashicons dashicons-dismiss dashicons-dismiss-icon"></a>', '?' . esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore=0' );
        ?>
      </p>
      <p>
        <a href="<?php echo esc_url( admin_url( 'themes.php?page=th_elanzalite' ) ) ?>" class="button button-primary button-hero" style="text-decoration: none;">
          <?php
          /* translators: %s theme name */
          printf( esc_html__( 'Get started with %s', 'elanzalite' ), esc_html( $theme_data->Name ) )
          ?>
        </a>
      </p>
    </div>
    <?php
  }
}

add_action( 'admin_init', 'elanzalite_notice_ignore' );
function elanzalite_notice_ignore() {
  global $current_user;
  $theme_data  = wp_get_theme();
  $user_id   = $current_user->ID;
  /* If user clicks to ignore the notice, add that to their user meta */
  if ( isset( $_GET[ esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore' ] ) && '0' == $_GET[ esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore' ] ) {
    add_user_meta( $user_id, esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore', 'true', true );
  }
}


// plugin install

class Elanzalite_Plugin {
  function active(){
    if(!get_option( "thunk_customizer_disable_popup")):
    add_action('customize_controls_print_styles', array($this,'elanzalite_popup_styles'));
    add_action('customize_controls_print_footer_scripts',array(__CLASS__,'popup_message'));
    add_action('wp_ajax_customizer_disable_popup', array(__CLASS__,'customizer_disable_popup'));
    add_action( 'customize_controls_enqueue_scripts', array($this,'elanzalite_popup_scripts'));
    endif;

  }

function elanzalite_active_plugin(){
        $plugin_slug = 'themehunk-customizer';
        $active_file_name =  $plugin_slug.'/'.$plugin_slug.'.php';
        $button_class = 'install-now button button-primary button-large';

                $button_txt = esc_html__( 'Install Now', 'elanzalite' );
                $status     = is_dir( WP_PLUGIN_DIR . '/'.$plugin_slug );

                if ( ! $status ) {
                    $install_url = wp_nonce_url(
                        add_query_arg(
                            array(
                                'action' => 'install-plugin',
                                'plugin' => $plugin_slug
                            ),
                            network_admin_url( 'update.php' )
                        ),
                        'install-plugin_'.$plugin_slug
                    );

                } else {
                    $install_url = add_query_arg(array(
                        'action' => 'activate',
                        'plugin' => rawurlencode( $active_file_name ),
                        'plugin_status' => 'all',
                        'paged' => '1',
                        '_wpnonce' => wp_create_nonce('activate-plugin_' . $active_file_name ),
                    ), network_admin_url('plugins.php'));
                    $button_class = 'activate-now button-primary button-large';
                    $button_txt = esc_html__( 'Active Now', 'elanzalite' );
                }
    return '<a href="'.esc_url( $install_url ).'" data-slug="'.esc_attr( $plugin_slug ).'" class="'.esc_attr( $button_class ).'">'.$button_txt.'</a>';
}

function popup_message() {
    require get_parent_theme_file_path('/inc/customizer-popup.php');
} 

function elanzalite_popup_styles() {
    wp_enqueue_style('elanzalite_customizer_popup', get_template_directory_uri() . '/inc/customizer_popup_styles.css');
}

function elanzalite_popup_scripts() {
    wp_enqueue_script( 'elanzalite_customizer_popup', get_template_directory_uri() . '/inc/customizer.js', array("jquery"), '', true  );
}


function customizer_disable_popup(){
      $value = intval(@$_POST['value']);
      update_option( "thunk_customizer_disable_popup", $value );
      die();
  }

}

function elanzalite_tab_config($theme_data){
    $config = array(
        'theme_brand' => __('ThemeHunk','elanzalite'),
        'theme_brand_url' => esc_url($theme_data->get( 'AuthorURI' )),
        'welcome'=>sprintf(esc_html__('Welcome to Elanzalite - Version %1s', 'elanzalite'), $theme_data->get( 'Version' ) ),
        'welcome_desc' => esc_html__( 'Elanzalite is a powerful theme made with love for Magazine, Newspaper and Personal blog.', 'elanzalite' ),
        'tab_one' =>esc_html__('Get Started with Elanzalite', 'elanzalite' ),
        'tab_two' =>esc_html__( 'Recommended Actions', 'elanzalite' ),
        'tab_three' =>esc_html__( 'Free VS Pro', 'elanzalite' ),

        'plugin_title' => esc_html__( 'Step 1 - Do recommended actions', 'elanzalite' ),
        'plugin_link' => '?page=th_elanzalite&tab=actions_required',
        'plugin_text' => esc_html__('To enjoy full featured elanzalite install Themehunk customizer plugin. This plugin will enable lots of amazing features in your customize panel.', 'elanzalite'),
        'plugin_button' => esc_html__('Go To Recommended Action', 'elanzalite'),

        'desire_title' => esc_html__( 'Step 2 - Select Desired Layout', 'elanzalite' ),
		
		'customizer_title' => esc_html__( 'Step 3 - Customize Your Website', 'elanzalite' ),
        'customizer_text' =>  sprintf(esc_html__('%s theme support live customizer for home page set up. Everything visible at home page can be changed through customize panel', 'elanzalite'), $theme_data->Name),
        'customizer_button' => sprintf( esc_html__('Start Customize', 'elanzalite')),

        'support_title' => esc_html__( 'Step 4 - Theme Support', 'elanzalite' ),
        'support_link' => esc_url('//www.themehunk.com/support/'),
        'support_forum' => sprintf(esc_html__('Support Forum', 'elanzalite'), $theme_data->get( 'Name' )),
        'doc_link' => esc_url('//themehunk.com/docs/elanza-lite-theme/'),
        'doc_link_text' => sprintf(esc_html__('Theme Documentation', 'elanzalite'), $theme_data->get( 'Name' )),

        'support_text' => sprintf(esc_html__('If you need any help you can contact to our support team, our team is always ready to help you.', 'elanzalite'), $theme_data->get( 'Name' )),
        'support_button' => sprintf( esc_html__('Create a support ticket', 'elanzalite'), $theme_data->get( 'Name' )),
        );
    return $config;
}


if ( ! function_exists( 'elanzalite_admin_scripts' ) ) :
    /**
     * Enqueue scripts for admin page only: Theme info page
     */
    function elanzalite_admin_scripts( $hook ) {
    wp_enqueue_style( 'elanzalite-admin-css', get_template_directory_uri() . '/css/admin.css' );
        if ($hook === 'appearance_page_th_elanzalite'  ) {
            // Add recommend plugin css
            wp_enqueue_style( 'plugin-install' );
            wp_enqueue_script( 'plugin-install' );
            wp_enqueue_script( 'updates' );
            add_thickbox();
        }
    }
endif;
add_action( 'admin_enqueue_scripts', 'elanzalite_admin_scripts' );

function elanzalite_count_active_plugins() {
   $i = 2;
       if (shortcode_exists( 'themehunk-customizer-elanzalite' ) ):
           $i--;
       endif;
       if (shortcode_exists( 'lead-form' ) ):
           $i--;
       endif;
       return $i;
}
function elanzalite_tab_count(){
       $count = '';
       $number_count = elanzalite_count_active_plugins();
           if( $number_count >0):
           $count = "<span class='update-plugins count-".esc_attr( $number_count )."' title='".esc_attr( $number_count )."'><span class='update-count'>" . number_format_i18n($number_count) . "</span></span>";
           endif;
           return $count;
}

/**
    * Menu tab
    */
function elanzalite_tab() {
               $number_count = elanzalite_count_active_plugins();
               $menu_title = esc_html__('Get Started with Elanzalite', 'elanzalite');
           if( $number_count >0):
           $count = "<span class='update-plugins count-".esc_attr( $number_count )."' title='".esc_attr( $number_count )."'><span class='update-count'>" . number_format_i18n($number_count) . "</span></span>";
               $menu_title = sprintf( esc_html__('Get Started with Elanzalite %s', 'elanzalite'), $count );
           endif;


   add_theme_page( esc_html__( 'elanzalite Lite', 'elanzalite' ), $menu_title, 'edit_theme_options', 'th_elanzalite', 'elanzalite_tab_page');
}
add_action('admin_menu', 'elanzalite_tab');


function elanzalite_theme(){ ?>
<div class="freeevspro-img">
<img src="<?php echo get_template_directory_uri(); ?>/images/freevspro.png" alt="free vs pro" />
<p>
 <a href="//themehunk.com/product/elanza-blogging-theme/"><?php esc_html_e('Check Pro version for more features','elanzalite'); ?></a>
                           </p></div>
<?php }


function elanzalite_tab_page() {
    $theme_data = wp_get_theme();
    $theme_config = elanzalite_tab_config($theme_data);


    // Check for current viewing tab
    $tab = null;
    if ( isset( $_GET['tab'] ) ) {
        $tab = $_GET['tab'];
    } else {
        $tab = null;
    }

    $actions_r = elanzalite_get_actions_required();
    $actions = $actions_r['actions'];

    $current_action_link =  admin_url( 'themes.php?page=th_elanzalite&tab=actions_required' );

    $recommend_plugins = get_theme_support( 'recommend-plugins' );
    if ( is_array( $recommend_plugins ) && isset( $recommend_plugins[0] ) ){
        $recommend_plugins = $recommend_plugins[0];
    } else {
        $recommend_plugins[] = array();
    }
    ?>
    <div class="wrap about-wrap theme_info_wrapper">
        <h1><?php  echo $theme_config['welcome']; ?></h1>
        <div class="about-text"><?php echo $theme_config['welcome_desc']; ?></div>

        <a target="_blank" href="<?php echo $theme_config['theme_brand_url']; ?>/?wp=elanzalite" class="themehunkhemes-badge wp-badge"><span><?php echo $theme_config['theme_brand']; ?></span></a>
        <h2 class="nav-tab-wrapper">
            <a href="?page=th_elanzalite" class="nav-tab<?php echo is_null($tab) ? ' nav-tab-active' : null; ?>"><?php  echo $theme_config['tab_one']; ?></a>
            <a href="?page=th_elanzalite&tab=actions_required" class="nav-tab<?php echo $tab == 'actions_required' ? ' nav-tab-active' : null; ?>"><?php echo $theme_config['tab_two'];  echo elanzalite_tab_count();?></a>
            <!-- <a href="?page=th_elanzalite&tab=theme-pro" class="nav-tab<?php echo $tab == 'theme-pro' ? ' nav-tab-active' : null; ?>"><?php echo $theme_config['tab_three']; ?></span></a> -->
        </h2>

        <?php if ( is_null( $tab ) ) { ?>
            <div class="theme_info info-tab-content">
                <div class="theme_info_column clearfix">
                    <div class="theme_info_left">
                    <div class="theme_link">
                            <h3><?php echo $theme_config['plugin_title']; ?></h3>
                            <p class="about"><ul>
                                <li><p>
                                <?php echo $theme_config['plugin_text'];?></p></li></ul></p>
                            <p>
                                <a href="<?php echo esc_url($theme_config['plugin_link'] ); ?>" class="button button-secondary"><?php echo $theme_config['plugin_button']; ?></a>
                            </p>
                        </div>
                        

                          <div class="theme_link">
                            <h3><?php echo $theme_config['desire_title']; ?></h3>
                            <p class="about"><ol>
 <li>
                                <p><?php esc_html_e('To create a magazine layout :-
Go to wp Dashboard > Pages > Add new page. Here create a new page and select Magazine Template from Page attribute and Publish it .
After that open customize panel. Go to Home Page Settings and set newly created page as a "Static front page" and save it.
For more help please check this doc.','elanzalite')?></p>
 <p>
                                <a target="_blank" href="<?php echo esc_url('//themehunk.com/docs/elanza-lite-theme/#create-magazine'); ?>" class="button button-primary"><?php echo esc_html_e('Create magazine','elanzalite'); ?></a>
                            </p>
</li>
<li><p><?php esc_html_e('To create a blog layout :-
Go to Appearance > Customize and set "Your latest posts" as "Your homepage displays" and Publish it.
','elanzalite')?></p>
<p>
                                <a target="_blank" href="<?php echo esc_url('//themehunk.com/docs/elanza-lite-theme/#create-blog'); ?>" class="button button-primary"><?php echo esc_html_e('Create Blog','elanzalite'); ?></a>
                            </p>
</li>
</ol>
</p>
 </div>

						  <div class="theme_link">
                            <h3><?php echo $theme_config['customizer_title']; ?></h3>
                            <p class="about"><?php  echo $theme_config['customizer_text']; ?></p>
                            <p>
                                <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary"><?php echo $theme_config['customizer_button']; ?></a>
                            </p>
                        </div>
                        <div class="theme_link">
                            <h3><?php echo $theme_config['support_title']; ?></h3>

                            <p class="about"><?php  echo $theme_config['support_text']; ?></p>
                            <p>
            <a target="_blank" href="<?php echo $theme_config['support_link']; ?>"><?php echo $theme_config['support_forum']; ?></a>
            </p>
            <p><a target="_blank" href="<?php echo $theme_config['doc_link']; ?>"><?php  echo $theme_config['doc_link_text']; ?></a></p>
                            <p>
                                <a href="<?php echo $theme_config['support_link']; ?>" target="_blank" class="button button-secondary"><?php echo $theme_config['support_button']; ?></a>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ( $tab == 'actions_required' ) { ?>
            <div class="action-required-tab info-tab-content">

                <?php if ( is_child_theme() ){
                    $child_theme = wp_get_theme();
                    ?>
                    <form method="post" action="<?php echo esc_attr( $current_action_link ); ?>" class="demo-import-boxed copy-settings-form">
                        <p>
                           <strong> <?php printf( esc_html__(  'You\'re using %1$s theme, It\'s a child theme', 'elanzalite' ) ,  $child_theme->Name ); ?></strong>
                        </p>
                        <p><?php printf( esc_html__(  'Child theme uses it’s own theme setting name, would you like to copy setting data from parent theme to this child theme?', 'elanzalite' ) ); ?></p>
                        <p>

                        <?php

                        $select = '<select name="copy_from">';
                        $select .= '<option value="">'.esc_html__( 'From Theme', 'elanzalite' ).'</option>';
                        $select .= '<option value="elanzalite">elanzalite</option>';
                        $select .= '<option value="'.esc_attr( $child_theme->get_stylesheet() ).'">'.( $child_theme->Name ).'</option>';
                        $select .='</select>';

                        $select_2 = '<select name="copy_to">';
                        $select_2 .= '<option value="">'.esc_html__( 'To Theme', 'elanzalite' ).'</option>';
                        $select_2 .= '<option value="elanzalite">elanzalite</option>';
                        $select_2 .= '<option value="'.esc_attr( $child_theme->get_stylesheet() ).'">'.( $child_theme->Name ).'</option>';
                        $select_2 .='</select>';

                        echo $select . ' to '. $select_2;

                        ?>
                        <input type="submit" class="button button-secondary" value="<?php esc_attr_e( 'Copy now', 'elanzalite' ); ?>">
                        </p>
                        <?php if ( isset( $_GET['copied'] ) && $_GET['copied'] == 1 ) { ?>
                            <p><?php esc_html_e( 'Your settings copied.', 'elanzalite' ); ?></p>
                        <?php } ?>
                    </form>

                <?php } ?>
      
                    <?php if ( isset($actions['recommend_plugins']) && $actions['recommend_plugins'] == 'active' ) {  ?>
                        <div id="plugin-filter" class="recommend-plugins action-required">
                        <h3><?php esc_html_e( 'Recommended Plugins', 'elanzalite' ); ?></h3>
                            <?php elanzalite_plugin_api(); ?>
                        </div>
                    <?php } ?>                            
            </div>
        <?php } ?>

        <?php if ( $tab == 'theme-pro' ) { ?>

            <?php elanzalite_theme(); ?>

        <?php } ?>

    </div> <!-- END .theme_info -->
    <?php

}

 function elanzalite_plugin_api() {
 if ( ! function_exists( 'plugins_api' ) ) {
      require ABSPATH . 'wp-admin/includes/plugin-install.php';
}

        $recommend_plugins = get_theme_support( 'recommend-plugins' );
    if ( is_array( $recommend_plugins ) && isset( $recommend_plugins[0] ) ){

        foreach($recommend_plugins[0] as $slug=>$plugin){
            
            $plugin_info = plugins_api( 'plugin_information', array(
                    'slug' => $slug,
                    'fields' => array(
                        'sections'          => true,
                        'homepage'          => true,
                        'added'             => false,
                        'last_updated'      => false,
                        'compatibility'     => false,
                        'tested'            => false,
                        'requires'          => false,
                        'downloadlink'      => false,
                        'icons'             => true,
                    )
                ) );
                //foreach($plugin_info as $plugin_info){
                    $plugin_name = $plugin_info->name;
                    $plugin_slug = $plugin_info->slug;
                    $version = $plugin_info->version;
                    $author = $plugin_info->author;
                    $download_link = $plugin_info->download_link;
                    $icons = (isset($plugin_info->icons['1x']))?$plugin_info->icons['1x']:$plugin_info->icons['default'];
            

        $status = is_dir( WP_PLUGIN_DIR . '/' . $plugin_slug );
        $active_file_name = $plugin_slug . '/' . $plugin_slug . '.php';
        $button_class = 'install-now button';

            if ( ! is_plugin_active( $active_file_name ) ) {
                $button_txt = esc_html__( 'Install Now', 'elanzalite' );
                if ( ! $status ) {
                    $install_url = wp_nonce_url(
                        add_query_arg(
                            array(
                                'action' => 'install-plugin',
                                'plugin' => $plugin_slug
                            ),
                            network_admin_url( 'update.php' )
                        ),
                        'install-plugin_'.$plugin_slug
                    );

                } else {
                    $install_url = add_query_arg(array(
                        'action' => 'activate',
                        'plugin' => rawurlencode( $active_file_name ),
                        'plugin_status' => 'all',
                        'paged' => '1',
                        '_wpnonce' => wp_create_nonce('activate-plugin_' . $active_file_name ),
                    ), network_admin_url('plugins.php'));
                    $button_class = 'activate-now button-primary';
                    $button_txt = esc_html__( 'Active Now', 'elanzalite' );
                }


                    $detail_link = add_query_arg(
                    array(
                        'tab' => 'plugin-information',
                        'plugin' => $plugin_slug,
                        'TB_iframe' => 'true',
                        'width' => '772',
                        'height' => '349',

                    ),
                    network_admin_url( 'plugin-install.php' )
                );
				$detail = '';
                echo '<div class="rcp">';
                echo '<h4 class="rcp-name">';
                echo esc_html( $plugin_name );
                echo '</h4>';
                echo '<img src="'.$icons.'" />';
if($plugin_slug=='lead-form-builder'){
		$detail='Lead form builder is a contact form as well as lead generator plugin. This plugin will allow you create
unlimited contact forms and to generate unlimited leads on your site.';
} elseif($plugin_slug=='themehunk-customizer'){
		$detail= 'ThemeHunk customizer – 
ThemeHunk customizer plugin will allow you to add  unlimited number of columns for services, Testimonial, and Team with widget support. It will add slider section, Ribbon section, latest post, Contact us and Woocommerce section. These will be visible on front page of your site.';

}

			echo '<p class="rcp-detail">'.$detail.' </p>';

                echo '<p class="action-btn plugin-card-'.esc_attr( $plugin_slug ).'"><span>Version:'.$version.'</span>
                        '.$author.'<a href="'.esc_url( $install_url ).'" data-slug="'.esc_attr( $plugin_slug ).'" class="'.esc_attr( $button_class ).'">'.$button_txt.'</a>
                </p>';
                echo '<a class="plugin-detail thickbox open-plugin-details-modal" href="'.esc_url( $detail_link ).'">'.esc_html__( 'Details', 'elanzalite' ).'</a>';
                echo '</div>';

            }

        }

    }

}


function elanzalite_get_actions_required( ) {

    $actions = array();

    $recommend_plugins = get_theme_support( 'recommend-plugins' );
    if ( is_array( $recommend_plugins ) && isset( $recommend_plugins[0] ) ){
        $recommend_plugins = $recommend_plugins[0];
    } else {
        $recommend_plugins[] = array();
    }

    if ( ! empty( $recommend_plugins ) ) {

        foreach ( $recommend_plugins as $plugin_slug => $plugin_info ) {
            $plugin_info = wp_parse_args( $plugin_info, array(
                'name' => '',
                'active_filename' => '',
            ) );
            if ( $plugin_info['active_filename'] ) {
                $active_file_name = $plugin_info['active_filename'] ;
            } 
            else {
                $active_file_name = $plugin_slug . '/' . $plugin_slug . '.php';
            }
            if ( ! is_plugin_active( $active_file_name ) ) {
                $actions['recommend_plugins'] = 'active';
            }
        }

    }

    $actions = apply_filters( 'elanzalite_get_actions_required', $actions );

    $return = array(
        'actions' => $actions,
        'number_actions' => count( $actions ),
    );

    return $return;
}