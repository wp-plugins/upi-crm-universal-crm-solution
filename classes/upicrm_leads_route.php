<?php 
class UpiCRMLeadsRoute extends WP_Widget {
    var $wpdb;
    
    public function __construct() {
	global $wpdb;
	$this->wpdb = &$wpdb;
    }
    
    function get() { 
        //get all leads route
        $rows = $this->wpdb->get_results("SELECT * FROM ".upicrm_db()."leads_route ORDER BY `lead_route_id` DESC");
        return $rows;
    }
    
    function get_type_options() { 
        //get all leads route
        $option[1] = __('contains','upicrm');
        //$option[2] = __('does not contain','upicrm');
        $option[3] = __('equals','upicrm');
        $option[4] = __('begins with','upicrm');
        $option[5] = __('smaller than','upicrm');
        $option[6] = __('bigger than','upicrm');
        return $option;
    }
    
    function add($insertArr) { 
        //add leads route
        $this->wpdb->insert(upicrm_db()."leads_route", $insertArr);
    }
    
    function remove($lead_route_id) {
        //delete lead route
        $this->wpdb->delete(upicrm_db()."leads_route", array("lead_route_id" => $lead_route_id));
    }
    
    function get_by_id($lead_route_id) {
        $rows = $this->wpdb->get_results("SELECT * FROM ".upicrm_db()."leads_route WHERE `lead_route_id`={$lead_route_id}");
        return $rows[0];
    }
    
    function update($updateArr, $lead_route_id) { 
        //update lead route
        $this->wpdb->update(upicrm_db()."leads_route", $updateArr , array("lead_route_id" => $lead_route_id));
    }
    
    function do_route($lead_id) {
        //run the route
       $UpiCRMLeads = new UpiCRMLeads();
       $UpiCRMUIBuilder = new UpiCRMUIBuilder();
       $UpiCRMFieldsMapping = new UpiCRMFieldsMapping();
       $UpiCRMFields = new UpiCRMFields();
       $getLeads = $UpiCRMLeads->get_by_id($lead_id); //get lead data
       $listOption = $UpiCRMUIBuilder->get_list_option(); //get UI options & existing fields
       $getNamesMap = $UpiCRMFieldsMapping->get_all_by($getLeads->source_id, $getLeads->source_type); //get lead fields mapping
       $getFields = $UpiCRMFields->get_as_array();
       $getFields = array_flip($getFields);
       $is_route = false;
       
       foreach ($this->get() as $route) {
            foreach ($listOption as $key => $list_option) {
                foreach ($list_option as $key2 => $field_name) {
                    $value = $UpiCRMUIBuilder->lead_routing($getLeads, $key, $key2, $getNamesMap, true);
                    if (!$is_route && $route->field_id == $getFields[$field_name] && $value != "") {
                        switch ($route->lead_route_type) {
                            case 1:
                                //contains
                                $value_arr = explode(",", $route->lead_route_value);
                                foreach ($value_arr as $lead_route_value) {
                                    if (strpos(upicrm_string_cleaner($value), upicrm_string_cleaner($lead_route_value)) !== false ) {
                                        $is_route = true;
                                        $get_route = $route;
                                    }
                                }
                                
                            break;
                            /*case 2:
                                //does not contain
                                $value_arr = explode(",", $route->lead_route_value);
                                foreach ($value_arr as $lead_route_value) {
                                    if (strpos(upicrm_string_cleaner($value), upicrm_string_cleaner($lead_route_value)) === false ) {
                                        $is_route = true;
                                        $get_route = $route;
                                    }
                                }
                            break;*/
                            case 3:
                                //equals
                                $value_arr = explode(",", $route->lead_route_value);
                                    foreach ($value_arr as $lead_route_value) {
                                    if (upicrm_string_cleaner($value) == upicrm_string_cleaner($lead_route_value)) {
                                        $is_route = true;
                                        $get_route = $route;
                                    }
                                }
                            break;
                            case 4:
                                //begins with
                                $value_arr = explode(",", $route->lead_route_value);
                                foreach ($value_arr as $lead_route_value) {
                                    if (strpos(upicrm_string_cleaner($value), upicrm_string_cleaner($lead_route_value)) === 0) {
                                        $is_route = true;
                                        $get_route = $route;
                                    }
                                }
                            break;
                            case 5:
                                //smaller than
                                $value_arr = explode(",", $route->lead_route_value);
                                foreach ($value_arr as $lead_route_value) {
                                    if (upicrm_string_cleaner($lead_route_value) > upicrm_string_cleaner($value)) {
                                        $is_route = true;
                                        $get_route = $route;
                                    }
                                }
                            break;
                            case 6:
                                //bigger than
                                $value_arr = explode(",", $route->lead_route_value);
                                foreach ($value_arr as $lead_route_value) {
                                    if (upicrm_string_cleaner($lead_route_value) < upicrm_string_cleaner($value)) {
                                        $is_route = true;
                                        $get_route = $route;
                                    }
                                }
                            break;
                            
                        } 
                    }
                }
            }
       } 
       
       if ($is_route) {
           $updateArr = array();
           if ($get_route->user_id > 0) {
               $updateArr['user_id'] = $get_route->user_id;
           }
           if ($get_route->lead_status_id > 0) {
               $updateArr['lead_status_id'] = $get_route->lead_status_id;
           }
           $UpiCRMLeads->update_by_id($lead_id,$updateArr);
       }
    }
   
    
}
?>