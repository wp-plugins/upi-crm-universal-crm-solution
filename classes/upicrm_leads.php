<?php 
class UpiCRMLeads extends WP_Widget {
    var $wpdb;
    
    public function __construct() {
	global $wpdb;
	$this->wpdb = &$wpdb;
    }
    
    function add($lead_content_arr, $source_type, $source_id, $sendEmail=true) {
        //add lead (content as array)
        
        $UpiCRMMails = new UpiCRMMails();
        
        $user = get_users( array( 'role' => 'Administrator' ));
        $user_id = get_option('upicrm_default_lead');
        
        $ins['lead_content'] = json_encode($lead_content_arr); //save this in JSON format
        $ins['source_type'] = $source_type;
        $ins['source_id'] = $source_id;
	$ins['user_ip'] = $_SERVER['REMOTE_ADDR'];
        $ins['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $ins['user_referer'] =  $_SESSION['upicrm_referer']; 
        $ins['lead_status_id'] =  1; 
        $ins['user_id'] =  $user_id; 
        
        $user_lead_id  = upicrm_get_user_lead_id();
        if ($user_lead_id != 0) {
            $ins['old_user_lead_id'] = $user_lead_id;
        }
        
        $this->wpdb->insert(upicrm_db()."leads", $ins);
        $last_id = $this->wpdb->insert_id;
        
        if ($user_lead_id == 0) 
            upicrm_set_new_user($last_id);
        
        if (isset($_SESSION['utm_source']) || isset($_SESSION['utm_medium']) || isset($_SESSION['utm_term']) || isset($_SESSION['utm_content']) || isset($_SESSION['utm_campaign'])) {
            $ins_campaign['lead_id'] = $last_id;
            $ins_campaign['utm_source'] = $_SESSION['utm_source'];
            $ins_campaign['utm_medium'] = $_SESSION['utm_medium'];
            $ins_campaign['utm_term'] = $_SESSION['utm_term'];
            $ins_campaign['utm_content'] = $_SESSION['utm_content'];
            $ins_campaign['utm_campaign'] = $_SESSION['utm_campaign'];
            $this->wpdb->insert(upicrm_db()."leads_campaign", $ins_campaign);

        }
        
        if ($sendEmail)
            $UpiCRMMails->send($last_id, "new_lead");
    }
    
    function update_by_id($lead_id,$updateArr) {
        //update lead by id 
        $this->wpdb->update(upicrm_db()."leads", $updateArr, array("lead_id" => $lead_id));
    }
    
    function get($user_id=0,$page=0,$limit=0,$orderBy="DESC") {
        //get leads
        $query = "SELECT *,  ".upicrm_db()."leads.lead_id AS `lead_id` FROM ".upicrm_db()."leads";
        $query.= " LEFT JOIN ".upicrm_db()."leads_campaign";
        $query.= " ON ".upicrm_db()."leads_campaign.lead_id = ".upicrm_db()."leads.lead_id";
        
        if ($user_id != 0)
            $query.= " WHERE ".upicrm_db()."leads.user_id = {$user_id}";
        
        $query.= " ORDER BY ".upicrm_db()."leads.`lead_id` {$orderBy}";
        if ($limit > 0) {
            $lim1 = ($page - 1) * $limit;
            $query.= " LIMIT {$lim1},{$limit}";
        }    
           
        
        $rows = $this->wpdb->get_results($query);
	return $rows;
    }
    
    function get_total($user_id=0) {
        $query = "SELECT `lead_id` FROM ".upicrm_db()."leads";
        if ($user_id > 0) {
            $query.= " WHERE `user_id` = {$user_id}"; 
        }
        $rows = $this->wpdb->get_results($query);
        return $this->wpdb->num_rows;
    }
    
    function get_source_form_name($source_id,$source_type) {
        //get the name of the form by source_id & source_type
        global $SourceTypeID;
        
        switch ($source_type) {
            case $SourceTypeID['gform']:
                $form_name = UpiCRMgform::form_name($source_id);
            break;
            case $SourceTypeID['wpcf7']:
                $form_name = UpiCRMwpcf7::form_name($source_id);
            break;
        }
        
	return $form_name;
    }
    
    function change_user($user_id,$lead_id) {
        //change lead user id
        $this->wpdb->update(upicrm_db()."leads", array("user_id" => $user_id), array("lead_id" => $lead_id));
    }
    
    function change_status($lead_status_id,$lead_id) {
        //change lead status id
        $this->wpdb->update(upicrm_db()."leads", array("lead_status_id" => $lead_status_id), array("lead_id" => $lead_id));
    }
    
    function remove_lead($lead_id) {
        //delete lead
        $this->wpdb->delete(upicrm_db()."leads", array("lead_id" => $lead_id));
        $this->wpdb->delete(upicrm_db()."leads_campaign", array("lead_id" => $lead_id));
    }
    
    function empty_all() {
        //delete all leads mapping
        $this->wpdb->query("TRUNCATE TABLE ".upicrm_db()."leads");  
        $this->wpdb->query("TRUNCATE TABLE ".upicrm_db()."leads_campaign");  
    }
    
    function get_by_id($lead_id) {
        //get lead by id
        
        $query = "SELECT *,  ".upicrm_db()."leads.lead_id AS `lead_id` FROM ".upicrm_db()."leads";
        $query.= " LEFT JOIN ".upicrm_db()."leads_campaign";
        $query.= " ON ".upicrm_db()."leads_campaign.lead_id = ".upicrm_db()."leads.lead_id";
        $query.= " WHERE ".upicrm_db()."leads.lead_id = {$lead_id}";
        
        $rows = $this->wpdb->get_results($query);
	return $rows[0];
    }
    
}
?>