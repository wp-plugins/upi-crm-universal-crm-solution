<?php
function upicrm_setup_plugin() {
    global $wpdb;
    $charset_collate = '';

    if ( ! empty( $wpdb->charset ) ) {
      $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    }

    if ( ! empty( $wpdb->collate ) ) {
      $charset_collate .= " COLLATE {$wpdb->collate}";
    }
    $sql = "CREATE TABLE ".upicrm_db()."leads (
            `lead_id` INT NOT NULL AUTO_INCREMENT,
            `source_type` INT NOT NULL,
            `source_id` INT NOT NULL,
            `lead_content` TEXT,
            `user_ip` TEXT,
            `user_agent` TEXT,
            `user_referer` TEXT,
            `old_user_lead_id` INT NOT NULL,
            `user_id` INT NOT NULL,
            `lead_status_id` INT NOT NULL,
            `lead_management_comment` TEXT,
            `time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`lead_id`)
   ) $charset_collate;";
    $wpdb->query($sql);
    
    $sql = "CREATE TABLE ".upicrm_db()."leads_campaign (
            `lead_id` INT,
            `utm_source` TEXT,
            `utm_medium` TEXT,
            `utm_term` TEXT,
            `utm_content` TEXT,
            `utm_campaign` TEXT
   ) $charset_collate;";
    $wpdb->query($sql);
    
   $sql = "CREATE TABLE IF NOT EXISTS ".upicrm_db()."fields_mapping (
  `fm_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `fm_name` text NOT NULL,
  `source_id` int(11) NOT NULL,
  `source_type` int(11) NOT NULL,
  PRIMARY KEY (`fm_id`)
   ) $charset_collate;";
    $wpdb->query($sql);
    
   $sql = "CREATE TABLE IF NOT EXISTS ".upicrm_db()."fields (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_name` text NOT NULL,
  PRIMARY KEY (`field_id`)
   ) $charset_collate;";
    $wpdb->query($sql);
    
    $sql = "INSERT INTO ".upicrm_db()."fields (`field_id`, `field_name`) VALUES
    (1, 'Name'),
    (2, 'Last name'),
    (3, 'Date'),
    (4, 'Message subject'),
    (5, 'Phone number mobile'),
    (6, 'Phone number work'),
    (7, 'Phone number home'),
    (8, 'Email'),
    (9, 'Role'),
    (10, 'Company'),
    (11, 'Industry'),
    (12, 'Website'),
    (13, 'Product'),
    (14, 'Service'),
    (15, 'City'),
    (16, 'Street'),
    (17, 'Country'),
    (18, 'Zip code'),
    (19, 'Address'),
    (20, 'Fax number'),
    (21, 'Future contact allowed'),
    (22, 'Message details/Remarks')
    ;";
    $wpdb->query($sql);
    
   $sql = "CREATE TABLE IF NOT EXISTS ".upicrm_db()."leads_status (
  `lead_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `lead_status_name` varchar(100) NOT NULL,
  PRIMARY KEY (`lead_status_id`),
  UNIQUE (`lead_status_name`)
   ) $charset_collate;";
    $wpdb->query($sql);
    
    $sql = "INSERT INTO ".upicrm_db()."leads_status (`lead_status_id`, `lead_status_name`) VALUES
    (1, 'Received'),
    (2, 'Qualified'),
    (3, 'Assigned'),
    (4, 'In process'),
    (5, 'Quote'),
    (6, 'Closing'),
    (7, 'Revenue')
    ;";
    $wpdb->query($sql);
    
    $sql = "CREATE TABLE ".upicrm_db()."mails (
            `mail_id` INT NOT NULL AUTO_INCREMENT,
            `mail_event` TEXT,
            `mail_content` TEXT,
            `mail_subject` TEXT,
            `mail_cc` TEXT,
            `mail_event_name` TEXT,
            PRIMARY KEY (`mail_id`)
   ) $charset_collate;";
    $wpdb->query($sql);

    $sql = "INSERT INTO ".upicrm_db()."mails (`mail_id`, `mail_event`, `mail_content`, `mail_subject`, `mail_cc`, `mail_event_name`) VALUES
    (1, 'new_lead','[lead]','New Lead','','New Lead'),
    (2, 'change_user','[lead]','Change User','','Change User'),
    (3, 'change_lead_status','[lead]','Change Lead Status','','Change Lead Status'),
    (4, 'request_status','[lead]','Request status update','','Request status update from lead owner')
    ;";
    $wpdb->query($sql);
    
    
    //update all admins permissions to UpiCRM Admin
     $users = get_users( array( 'role' => 'Administrator' ));
     foreach ($users as $user) {
         update_user_meta( $user->id,'upicrm_user_permission', 2);
     } 

    
    if (!get_option('upicrm_default_email')) {
        $default_email = get_option( 'admin_email' );
        add_option('upicrm_default_email', $default_email);
    } 

}

/*function upicrm_remove_plugin() {
    global $wpdb;
    $sql = "DROP TABLE ".upicrm_db()."leads";
    $wpdb->query($sql);
    $sql = "DROP TABLE ".upicrm_db()."leads_campaign";
    $wpdb->query($sql);
    $sql = "DROP TABLE ".upicrm_db()."fields_mapping";
    $wpdb->query($sql);
    $sql = "DROP TABLE ".upicrm_db()."fields";
    $wpdb->query($sql);
    $sql = "DROP TABLE ".upicrm_db()."leads_status";
    $wpdb->query($sql);
    $sql = "DROP TABLE ".upicrm_db()."mails";
    $wpdb->query($sql);
}*/

function upicrm_update_db_check() {
    global $upicrm_db_version, $wpdb;
    if (get_option("upicrm_db_version") != $upicrm_db_version) {
        
        $sql = "ALTER TABLE `".upicrm_db()."leads_status` ADD UNIQUE( `lead_status_name`);";
        $wpdb->query($sql);
        
        $sql = "ALTER TABLE `".upicrm_db()."leads_status` CHANGE `lead_status_name` `lead_status_name` VARCHAR(100);";
        $wpdb->query($sql);
        
        $sql = "INSERT INTO ".upicrm_db()."leads_status (`lead_status_name`) VALUES
        ('Not relevant')
        ;";
        $wpdb->query($sql);
        
        $sql = "UPDATE ".upicrm_db()."fields SET `field_name` = 'Phone number' WHERE `field_name` = 'Phone number home';";
        $wpdb->query($sql);
        
        update_option( "upicrm_db_version", $upicrm_db_version );

    }
    
    if (!get_option('upicrm_sender_email')) {
        add_option('upicrm_sender_email', 'no-reply');
    } 
    
    if (!get_option('upicrm_default_lead')) {
       $users = get_users( array( 'role' => 'Administrator' ));
        add_option('upicrm_default_lead', $users[0]->ID);
    } 
    if (!get_option('upicrm_email_format')) {
        add_option('upicrm_email_format', 1);
    } 
    
    
}
?>