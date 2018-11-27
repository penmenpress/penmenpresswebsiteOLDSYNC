/*
 * Settings of the sticky menu
 */

/*jQuery(document).ready(function(){
   var wpAdminBar = jQuery('#wpadminbar');
   if (wpAdminBar.length) {
      jQuery("#mt-menu-wrap,.header-layout-2 .logo-ads-wrapper").sticky({topSpacing:wpAdminBar.height()});
   } else {
      jQuery("#mt-menu-wrap,.header-layout-2 .logo-ads-wrapper").sticky({topSpacing:0});
   }
});*/

/*
 * Settings of the sticky menu
 */

jQuery(document).ready(function(){
   var wpAdminBar = jQuery('#wpadminbar');
   if (wpAdminBar.length) {
      jQuery("#mt-menu-wrap").sticky({topSpacing:wpAdminBar.height()});
   } else {
      jQuery("#mt-menu-wrap").sticky({topSpacing:0});
   }
});