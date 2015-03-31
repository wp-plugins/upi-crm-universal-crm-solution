<?php
class UpiCRMStatistics extends WP_Widget {
    var $wpdb;
    
    public function __construct() {
	global $wpdb;
	$this->wpdb = &$wpdb;
    }
    
    function get_total_leads() {
        $query = "SELECT `lead_id` FROM ".upicrm_db()."leads";
        $rows = $this->wpdb->get_results($query);
        return $this->wpdb->num_rows;
    }
    
    function get_total_leads_by_user_id($user_id) {
        $query = "SELECT `lead_id` FROM ".upicrm_db()."leads WHERE `user_id` = {$user_id}";
        $rows = $this->wpdb->get_results($query);
        return $this->wpdb->num_rows;
    }
    
    function get_total_leads_status_by_user_id($user_id=0) {
        $color = array("blue","red","green","orange","yellow","pink","purple","greenLight","greenDark","orangeDark");
        
        if ($user_id > 0) 
            $query = "SELECT count(*) AS `count` ,`lead_status_id` FROM ".upicrm_db()."leads WHERE `user_id` = {$user_id} group by `lead_status_id`";
        else 
            $query = "SELECT count(*) AS `count` ,`lead_status_id` FROM ".upicrm_db()."leads group by `lead_status_id`";
        
        //echo $query;
        $rows = $this->wpdb->get_results($query);
        
        foreach (UpiCRMLeadsStatus::get_as_array() as $key => $value) {
            $obj[$i] = new stdClass();
            $obj[$i]->lead_status_id = $key;
            $obj[$i]->lead_status_name = $value;
            $obj[$i]->count = 0;
            $obj[$i]->color = $color[$i];
            foreach ($rows as $row) {
                if ($row->lead_status_id == $key) {
                    $obj[$i]->count = $row->count;  
                }
            }
            $i++;
        }
        return $obj;
    }
    
    function get_total_leads_by_weeks($week=0) {
        if ($week == 0) {
            //$saturday = date("Y-m-d",strtotime('last saturday'));
            $weekAgo = date("Y-m-d", strtotime('-7 days'));
            $query = "SELECT count(*) AS `count` FROM ".upicrm_db()."leads 
            WHERE (`time` BETWEEN '{$weekAgo}' AND NOW())";
        }
        else {
            $a = $week * 7;
            $b = $week * 14;
            $weekAgo = date("Y-m-d", strtotime("-{$a} days"));
            $weekAgo2 = date("Y-m-d", strtotime("-{$b} days"));
            
            $query = "SELECT count(*) AS `count` FROM ".upicrm_db()."leads 
            WHERE (`time` BETWEEN '{$weekAgo2}' AND '{$weekAgo}')";
        }
        $rows = $this->wpdb->get_results($query);
        return $rows[0]->count ? $rows[0]->count : 0;
    }
    
}
