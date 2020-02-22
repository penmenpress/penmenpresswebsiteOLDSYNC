<?php
/*
 * class for Tools Services
 *
 * @copyright   Copyright (c) 2017, Nugget Solutions, Inc
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       5.3
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
   exit();
}

/**
 * OW_Tools_Service Class
 *
 * @since 4.0
 */
class OW_Tools_Service {
   
   public function __construct() {

      // only add_action for AJAX actions
      // empty
	}
   

   /**
    * Function - Build oasis workflow settings export data
    * @since 4.0
    */
   private function get_owf_settings() {
      // Get workflow settings
      $workflow_settings = array(
         "oasiswf_activate_workflow"               => get_option("oasiswf_activate_workflow"),
         "oasiswf_default_due_days"                => get_option("oasiswf_default_due_days"),
         "oasiswf_show_wfsettings_on_post_types"   => get_option("oasiswf_show_wfsettings_on_post_types"),
         "oasiswf_priority_setting"                => get_option("oasiswf_priority_setting"),
         "oasiswf_publish_date_setting"            => get_option("oasiswf_publish_date_setting")
      );
      
      $email_settings = array(
         "oasiswf_email_settings"                     => get_option("oasiswf_email_settings"),
         "oasiswf_reminder_days"                      => get_option("oasiswf_reminder_days"),
         "oasiswf_reminder_days_after"                => get_option("oasiswf_reminder_days_after")
      );
      

      $terminology_settings = array(
         "oasiswf_custom_workflow_terminology"  => get_option("oasiswf_custom_workflow_terminology")
      );
      
      $owf_settings = array(
         "workflow_settings"           => $workflow_settings,
         "email_settings"              => $email_settings,
         "terminology_settings"        => $terminology_settings
      );
      
      return $owf_settings;
   }


   /**
    * Function - API to fetch all workflow settings
    * @since 4.0
    */
   public function api_get_plugin_settings( $criteria ) {
      if ( ! wp_verify_nonce( $criteria->get_header('x_wp_nonce'), 'wp_rest' ) ) {
         wp_die( __( 'Unauthorized access.', 'oasisworkflow' ) );
      }
      if ( ! current_user_can( 'ow_submit_to_workflow' ) && ! current_user_can( 'ow_sign_off_step' ) ) {
         wp_die( __( 'You are not allowed to fetch workflow settings.', 'oasisworkflow' ) );
      }

      return $this->get_owf_settings();
   }

}

$ow_tools_service = new OW_Tools_Service();
?>