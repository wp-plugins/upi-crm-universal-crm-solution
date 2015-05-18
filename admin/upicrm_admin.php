<?php

if ( !class_exists('UpiCRMAdmin') ):
    class UpiCRMAdmin{
        public function __construct() {   
            add_action( 'admin_menu', array( $this, 'onWpAdminMenu' ) );
  wp_register_style( 'upicrm_css_bootstrap', UPICRM_URL.'resources/css/bootstrap.css', FALSE, '0.1' );
            wp_register_style( 'upicrm_css_bootstrap_rtl', UPICRM_URL.'resources/css/bootstrap-rtl.min.css', FALSE, '0.1' );
            wp_register_style( 'upicrm_css', UPICRM_URL.'resources/css/smartadmin-production.css', FALSE, '0.1' );
            wp_register_style( 'upicrm_css_smart_admin_skins', UPICRM_URL.'resources/css/smartadmin-skins.css', FALSE, '0.1' );
            wp_register_style( 'upicrm_css_font', UPICRM_URL.'resources/css/font-awesome.min.css', FALSE, '0.1' ); 
            wp_register_style( 'upicrm_css_bootstrap_multiselect', UPICRM_URL.'resources/css/bootstrap-multiselect.css', FALSE, '0.1' ); 
            wp_register_style( 'upicrm_main_style', UPICRM_URL.'css/style.css', FALSE, '0.1' ); 
            
  
            
            wp_enqueue_style( 'upicrm_css_bootstrap' );
            wp_enqueue_style( 'upicrm_css' );
            wp_enqueue_style( 'upicrm_css_smart_admin_skins' );
            wp_enqueue_style( 'upicrm_css_font' ); 
            wp_enqueue_style( 'upicrm_css_bootstrap_multiselect' );
            wp_enqueue_style( 'upicrm_main_style' );
            

            wp_register_script('upicrm_js_sparkline',  UPICRM_URL.'resources/js/plugin/sparkline/jquery.sparkline.min.js', array('jquery'), '1.0');
            wp_register_script('upicrm_js_jarvis',  UPICRM_URL.'resources/js/plugin/smartwidgets/jarvis.widget.min.js', array('jquery'), '1.0');

            wp_register_script('upicrm_js_dataTable',  UPICRM_URL.'resources/js/plugin/datatables/jquery.dataTables.min.js', array('jquery'), '1.0');
            wp_register_script('upicrm_js_colVis',  UPICRM_URL.'resources/js/plugin/datatables/dataTables.colVis.min.js', array('jquery'), '1.0');
            wp_register_script('upicrm_js_tableTools',  UPICRM_URL.'resources/js/plugin/datatables/dataTables.tableTools.min.js', array('jquery'), '1.0');
            wp_register_script('upicrm_js_tablebootstrap',  UPICRM_URL.'resources/js/plugin/datatables/dataTables.bootstrap.min.js', array('jquery'), '1.0');
            wp_register_script('upicrm_js_responsive',  UPICRM_URL.'resources/js/plugin/datatable-responsive/datatables.responsive.min.js', array('jquery'), '1.0');

            wp_register_script('upicrm_js_app',  UPICRM_URL.'resources/js/app.js', array('jquery'), '1.1');
            wp_register_script('upicrm_js_bootstrap',  UPICRM_URL.'resources/js/bootstrap.min.js', array('jquery'), '1.1');
            wp_register_script('upicrm_js_bootstrap_multiselect',  UPICRM_URL.'resources/js/bootstrap-multiselect.js', array('jquery'), '1.1');
            wp_register_script('upicrm_js_main',  UPICRM_URL.'resources/js/main.js', array('jquery'), '1.1');
            
            
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-widget');
            wp_enqueue_script('jquery-ui-tabs');            
            wp_enqueue_script('upicrm_js_sparkline'); 
            wp_enqueue_script('upicrm_js_app'); 
            wp_enqueue_script('upicrm_js_bootstrap');
            wp_enqueue_script('upicrm_js_bootstrap_multiselect');
            wp_enqueue_script('upicrm_js_jarvis');  
            wp_enqueue_script('upicrm_js_dataTable'); 
            wp_enqueue_script('upicrm_js_colVis');
            wp_enqueue_script('upicrm_js_tableTools'); 
            wp_enqueue_script('upicrm_js_tablebootstrap');
            wp_enqueue_script('upicrm_js_responsive');
            wp_enqueue_script('upicrm_js_main');    
            
        } 
        public function onWpAdminMenu() {   
            $UpiCRMUsers = new UpiCRMUsers();
            if (1 <= $UpiCRMUsers->get_permission()) {
                add_object_page('UpiCRM', 'UpiCRM', 'read', 'upicrm_index', array( $this, 'onDisplayDashboard' ), UPICRM_URL . 'resources/images/icon_crm.gif');
                
                add_submenu_page( 'upicrm_index', __('Show Leads','upicrm'), __('Show Leads','upicrm'), 'read', 'upicrm_allitems', array( $this, 'onDisplayMainMenu' ) );
                add_submenu_page( 'upicrm_dont_show', '', '', 'read', 'upicrm_edit_lead', array( $this, 'onDisplayAdminEditLead' ) );
                add_submenu_page( 'upicrm_dont_show', '', '', 'read', 'upicrm_api', array( $this, 'onDisplayAdminAPI' ) );
                
                
            }
            if (1 < $UpiCRMUsers->get_permission()) {
                add_submenu_page( 'upicrm_index', __('General Settings','upicrm'), __('General Settings','upicrm'), 'read', 'upicrm_settings', array( $this, 'onDisplayCommonSettings' ) );
                add_submenu_page( 'upicrm_index', __('Existing Fields','upicrm'), __('Existing Fields','upicrm'), 'read', 'upicrm_existing_fields', array( $this, 'onDisplayExistingFields' ) );
                add_submenu_page( 'upicrm_index', __('Existing Statuses','upicrm'), __('Existing Statuses','upicrm'), 'read', 'upicrm_existing_statuses', array( $this, 'onDisplayExistingStatuses' ) );
                add_submenu_page( 'upicrm_index', __('Email Notifications','upicrm'), __('Email Notifications','upicrm'), 'read', 'upicrm_email_notifications', array( $this, 'onDisplayEmailNotifications' ) );
            }
            
        }
        
        private function beforeAllAdminPages() {
            global $wp_version;
            if ($wp_version >= 5) {
                ?>
                <div class="alert alert-warning fade in">
                    <i class="fa-fw fa fa-warning"></i>
                    <strong>Warning</strong> Your UpiCRM version is not compatible with WordPress 5.X . <a href="http://www.upicrm.com/?utm_source=upicrmvf">Please upgrade your UpiCRM WordPress CRM solution here</a>.
                </div>
                <?php
            }
            
        }
        
        public function onDisplayDashboard(){
            $this->beforeAllAdminPages();
            
            $UpiCRMAdminIndex = new UpiCRMAdminIndex();
            $UpiCRMAdminIndex->Render();    
        }
        public function onDisplayCommonSettings(){
            $this->beforeAllAdminPages();
            
            $UpiCRMAdminSettings = new UpiCRMAdminSettings();
            $UpiCRMAdminSettings->Render();   
        }
	    public function onDisplayMainMenu() {	
            global $title;           
            $this->beforeAllAdminPages();
            
            $UpiCRMAdminAdminLists = new UpiCRMAdminAdminLists();
            $UpiCRMAdminAdminLists->RenderLists();  
	    } 
        
        public function onDisplayExistingFields(){
            $this->beforeAllAdminPages();
            
            $UpiCRMAdminExistingFields = new UpiCRMAdminExistingFields();
            $UpiCRMAdminExistingFields->Render();
        }
        
        public function onDisplayExistingStatuses(){
            $this->beforeAllAdminPages();
            
            $UpiCRMAdminExistingStatuses = new UpiCRMAdminExistingStatuses();
            $UpiCRMAdminExistingStatuses->Render();
        }
        
        public function onDisplayEmailNotifications(){
            $this->beforeAllAdminPages();
            
            $UpiCRMAdminEmailNotifications = new UpiCRMAdminEmailNotifications();
            $UpiCRMAdminEmailNotifications->Render();
        }
        
        public function onDisplayAdminEditLead() {
            $this->beforeAllAdminPages();
            
            $UpiCRMAdminEditLead = new UpiCRMAdminEditLead();
            $UpiCRMAdminEditLead->Render();
        }
        
        public function onDisplayAdminAPI() {
            $this->beforeAllAdminPages();
            
            $UpiCRMAdminAPI = new UpiCRMAdminAPI();
            $UpiCRMAdminAPI->Render();
        }
        
    }
endif;