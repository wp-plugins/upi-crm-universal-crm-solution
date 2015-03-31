<?php 
class UpiCRMMails extends WP_Widget {
    var $wpdb;
    
    public function __construct() {
	global $wpdb;
	$this->wpdb = &$wpdb;
    }
    
    
    function get() {
        //get mails
        $query = "SELECT * FROM ".upicrm_db()."mails";
        $rows = $this->wpdb->get_results($query);
	return $rows;
    }
    
    function update($arr) {
        //update mail by arr - key = mail_event
        
        foreach ($arr as $key => $mail) {
           if ($key != "submit") {
               foreach ($mail as $key2 => $value) {
                   $updateArr[$key2] = $mail[$key2];
               }
                $this->wpdb->update(upicrm_db()."mails", $updateArr, array("mail_event" => $key));
           }
           
        }
    }
    
    function send($lead_id, $event,$to="") {
        //send mail
        
        add_filter('wp_mail_from_name', array($this,'filter_change_mail_from_name'));
        $UpiCRMUIBuilder = new UpiCRMUIBuilder();
        $UpiCRMFieldsMapping = new UpiCRMFieldsMapping();
        $UpiCRMLeads = new UpiCRMLeads();
        $UpiCRMUsers = new UpiCRMUsers();
        
        $lead = $UpiCRMLeads->get_by_id($lead_id);
        $getNamesMap = $UpiCRMFieldsMapping->get_all_by($lead->source_id, $lead->source_type); 
        $list_option = $UpiCRMUIBuilder->get_list_option();
        $mail = $this->get_by_event($event);
        
        $message = nl2br($mail->mail_content);
        $subject = $mail->mail_subject;
        $default_email = get_option('upicrm_default_email');
        $extra_email = get_option('upicrm_extra_email');

        $LeadVarText="";
        foreach ($list_option as $key => $arr) { 
            foreach ($arr as $key2 => $value) {
                $getValue = $UpiCRMUIBuilder->lead_routing($lead,$key,$key2,$getNamesMap,true);
                if ($getValue != "") {
                    $LeadVarText.= "<strong>{$value}</strong>: {$getValue}<br />";
                }
            }
        }
        $LeadVarText.="<br /><br />";
        $LeadVarText.='Please manage this lead here: <a href="'.get_admin_url().'admin.php?page=upicrm_allitems">'.get_admin_url().'admin.php?page=upicrm_allitems</a><br />';
        $LeadVarText.='<br /><br /><a href="http://www.upicrm.com/?utm_source=upicrm_email">This mail was sent by UpiCRM - Universal Wordpress Plugin</a><br />';
        
        $LeadVarTextNoHTML="";
        foreach ($list_option as $key => $arr) { 
            foreach ($arr as $key2 => $value) {
                $getValue = $UpiCRMUIBuilder->lead_routing($lead,$key,$key2,$getNamesMap,true);
                if ($getValue != "") {
                    $LeadVarTextNoHTML.= "{$value}: {$getValue}" . "\r\n";
                }
            }
        }
        
        $message = str_replace("[lead-plaintext]", $LeadVarTextNoHTML, $message);
        $message = str_replace("[lead]", $LeadVarText, $message);
        $message = str_replace("[url]", get_site_url(), $message);
        $message = str_replace("[assigned-to]", $UpiCRMUsers->get_by_id($lead->user_id), $message);
        
        $headers = "From: UpiCRM". "\r\n";
        $headers.= 'MIME-Version: 1.0' . "\r\n";
        if (get_option('upicrm_email_format') == 1)
            $headers.= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        if (get_option('upicrm_email_format') == 2)
            $headers.= 'Content-type: text/plain; charset=UTF-8' . "\r\n";
        $cc = "";
        
        if ($extra_email != "")
            $cc.= $extra_email.", ";
        
        if ($to != "") 
            $cc.= $default_email.", ";
        else
            $to = $default_email;

        if ($mail->mail_cc != "")
            $cc.= $mail->mail_cc;
            
        $headers.= "Cc: {$cc}" . "\r\n"; 
                
        /*add_filter( 'wp_mail_from', 'custom_wp_mail_from' );      
        add_filter( 'wp_mail_from', function($email){
            return $mail->mail_from;
        });*/
        
        wp_mail($to, $subject, $message,$headers);
        
    }
    
    function get_by_event($mail_event) {
        $query = "SELECT * FROM ".upicrm_db()."mails WHERE `mail_event`='{$mail_event}'";
        $rows = $this->wpdb->get_results($query);
        return $rows[0];
    }
    
    function filter_change_mail_from_name($old) {
        return get_option('upicrm_sender_email');
        //return 'UpiCRM';
    }
  
}

?>