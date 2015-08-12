<?php
/*
Plugin Name: UpiCRM - Universal WordPress CRM Solution
Text Domain: upicrm
Domain Path: /languages
Plugin URI: http://www.upicrm.com?utm_source=plpage
Description: UpiCRM is a universal WordPress CRM solution can interface and extend the most popular WordPress contact forms plugins, and provide a complete CRM solution

Version: 1.9.1
Author URI: http://www.upicrm.com

Copyright 2014  UpiCRM.com, Inc.    (email : uri@focusweb.co.il)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; version 3 of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
session_start();
/** Plugin Version */
define('UPICRM_VERSION', '1.9');
define('UPICRM_PATH', trailingslashit(dirname(__FILE__)) );
define('UPICRM_DIR', trailingslashit(dirname(plugin_basename(__FILE__))) );
define('UPICRM_URL', plugin_dir_url(dirname(__FILE__)) . UPICRM_DIR );

$upicrm_db_version = 4;

/* Source type name:  */
$SourceTypeName[1] = "Gravity Forms";
$SourceTypeName[2] = "Contact Form 7";
$SourceTypeName[3] = "Ninja Forms";

/* Source type IDs:  */
$SourceTypeID['gform'] = 1;
$SourceTypeID['wpcf7'] = 2;
$SourceTypeID['ninja'] = 3;

require_once( plugin_dir_path( __FILE__ ) . 'upicrm_setup.php' );

function upicrm_db() {
	global $wpdb; 
	return $wpdb->prefix."upicrm_";
}

add_action('plugins_loaded', 'upicrm_load_textdomain' );
function upicrm_load_textdomain() {
  load_plugin_textdomain( 'upicrm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

/* Setup */
register_activation_hook(__FILE__,'upicrm_setup_plugin');
add_action( 'plugins_loaded', 'upicrm_update_db_check' );
//register_deactivation_hook(__FILE__,'upicrm_remove_plugin'); //delete this line!

require_once( plugin_dir_path( __FILE__ ) . 'functions.php' ); 

/* Classes */
require_once( plugin_dir_path( __FILE__ ) . 'classes/upicrm_leads.php' ); 
require_once( plugin_dir_path( __FILE__ ) . 'classes/upicrm_fields.php' ); 
require_once( plugin_dir_path( __FILE__ ) . 'classes/upicrm_fields_mapping.php' ); 
require_once( plugin_dir_path( __FILE__ ) . 'classes/upicrm_leads_status.php' ); 
require_once( plugin_dir_path( __FILE__ ) . 'classes/upicrm_users.php' ); 
require_once( plugin_dir_path( __FILE__ ) . 'classes/upicrm_ui_builder.php');
require_once( plugin_dir_path( __FILE__ ) . 'classes/upicrm_mails.php');
require_once( plugin_dir_path( __FILE__ ) . 'classes/upicrm_statistics.php' ); 
require_once( plugin_dir_path( __FILE__ ) . 'classes/upicrm_leads_route.php' ); 

/* Libraries */
require_once( plugin_dir_path( __FILE__ ) . 'libraries/upicrm_gravity_forms.php' );
require_once( plugin_dir_path( __FILE__ ) . 'libraries/upicrm_contact_form_7.php' );
require_once( plugin_dir_path( __FILE__ ) . 'libraries/upicrm_ninja_forms.php' );

/* Smart Admin UI */
require_once( plugin_dir_path( __FILE__ ) . 'resources/includes/smartui/class.smartutil.php' );
require_once( plugin_dir_path( __FILE__ ) . 'resources/includes/smartui/class.smartui.php' );
require_once( plugin_dir_path( __FILE__ ) . 'resources/includes/smartui/class.smartui-widget.php' );
require_once( plugin_dir_path( __FILE__ ) . 'resources/includes/smartui/class.smartui-datatable.php' );
require_once( plugin_dir_path( __FILE__ ) . 'resources/includes/smartui/class.smartui-button.php' );
require_once( plugin_dir_path( __FILE__ ) . 'resources/includes/smartui/class.smartui-tab.php' );
require_once( plugin_dir_path( __FILE__ ) . 'resources/includes/smartui/class.smartui-accordion.php' );
require_once( plugin_dir_path( __FILE__ ) . 'resources/includes/smartui/class.smartui-carousel.php' );
require_once( plugin_dir_path( __FILE__ ) . 'resources/includes/smartui/class.smartui-smartform.php' );

SmartUI::$icon_source = 'fa';
// register our UI plugins
SmartUI::register('widget', 'Widget');
//SmartUI::register('datatable', 'DataTable');
//SmartUI::register('button', 'Button');
SmartUI::register('tab', 'Tab');
//SmartUI::register('accordion', 'Accordion');
//SmartUI::register('carousel', 'Carousel');
SmartUI::register('smartform', 'SmartForm');
//SmartUI::register('nav', 'Nav');

if ( is_admin() ) {  
     require_once( plugin_dir_path( __FILE__ ) . 'admin/upicrm_admin.php' );
     require_once( plugin_dir_path( __FILE__ ) . 'admin/upicrm_dashboard.php' );
     require_once( plugin_dir_path( __FILE__ ) . 'admin/upicrm_admin_lists.php' );
     require_once( plugin_dir_path( __FILE__ ) . 'admin/upicrm_admin_sparks.php' );
     require_once( plugin_dir_path( __FILE__ ) . 'admin/upicrm_settings.php' );
     require_once( plugin_dir_path( __FILE__ ) . 'admin/upicrm_existing_fields.php' );
     require_once( plugin_dir_path( __FILE__ ) . 'admin/upicrm_existing_statuses.php' );
     require_once( plugin_dir_path( __FILE__ ) . 'admin/upicrm_email_notifications.php' );
     require_once( plugin_dir_path( __FILE__ ) . 'admin/upicrm_edit_lead.php' );
     require_once( plugin_dir_path( __FILE__ ) . 'admin/upicrm_api.php' );
     require_once( plugin_dir_path( __FILE__ ) . 'admin/upicrm_lead_route.php' );
     $UpiCRMAdmin = new UpiCRMAdmin();
}

if (!isset($_SESSION['upicrm_referer']))
    $_SESSION['upicrm_referer'] = upicrm_get_referer();

if (!isset($_SESSION['utm_source']) && isset($_GET['utm_source']))
    $_SESSION['utm_source'] = $_GET['utm_source'];

if (!isset($_SESSION['utm_medium']) && isset($_GET['utm_medium']))
    $_SESSION['utm_medium'] = $_GET['utm_medium'];

if (!isset($_SESSION['utm_term']) && isset($_GET['utm_term']))
    $_SESSION['utm_term'] = $_GET['utm_term'];

if (!isset($_SESSION['utm_content']) && isset($_GET['utm_content']))
    $_SESSION['utm_content'] = $_GET['utm_content'];

if (!isset($_SESSION['utm_campaign']) && isset($_GET['utm_campaign']))
    $_SESSION['utm_campaign'] = $_GET['utm_campaign'];
