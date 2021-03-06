<?php
add_action('show_user_profile', array(new UpiCRMUsers,'action_add_meta_user_profile'));
add_action('edit_user_profile', array(new UpiCRMUsers,'action_add_meta_user_profile'));

add_action('personal_options_update', array(new UpiCRMUsers,'action_save_meta_user_profile'));
add_action('edit_user_profile_update', array(new UpiCRMUsers,'action_save_meta_user_profile'));

class UpiCRMUsers {
    function action_add_meta_user_profile($user) {
         ?>
        <h3><?php _e('UpiCRM options','upicrm'); ?></h3>

        <table class="form-table">
            <tr>
                <th><label for="upicrm_user_permission"><?php _e('Permission','upicrm'); ?></label></th>
                <td>
                    <select id="upicrm_user_permission" name="upicrm_user_permission">
                        <option value=""><?php _e('None','upicrm'); ?></option>
                        <option value="1" <?php selected(get_the_author_meta('upicrm_user_permission', $user->ID), 1);?>><?php _e('UpiCRM User','upicrm'); ?></option>
                        <option value="2" <?php selected(get_the_author_meta('upicrm_user_permission', $user->ID), 2);?>><?php _e('UpiCRM Admin','upicrm'); ?></option>
                    </select>
                </td>
            </tr>
        </table>
    <?php
        
    }
    
    function action_save_meta_user_profile( $user_id )
    {
        update_user_meta( $user_id,'upicrm_user_permission', sanitize_text_field( $_POST['upicrm_user_permission']));
    }
    
    function get_permission() {
       return get_the_author_meta( 'upicrm_user_permission', get_current_user_id() );
    }
    
    function select_list($lead, $callback) {
        $text ='<select name="user_id" data-lead_id="'.$lead->lead_id.'" data-callback="'.$callback.'">';
            foreach ($this->get_as_array() as $user_id => $user_name) { 
                $selected = selected( $lead->user_id, $user_id, false);
                $text.='<option value="'.$user_id.'" '.$selected.'>'.$user_name.'</option>';
                
            }
        $text.='</select>';
        return $text;                         
    }
    
    function get_by_id($id) {
        if ($id != 0) {
            $user = get_user_by('id', $id);
            $displayName = $user->display_name;
        }
        else {
            $user = get_users( array( 'role' => '' ) ); //Editor, Administrator
            $displayName = $user[0]->display_name;
        }
        
        return $displayName;
    }
    
    function is_have_permission_to_lead($user_id,$lead_id) {
        $UpiCRMLeads = new UpiCRMLeads();
        $permission = false;
        if ($this->get_permission() == 1) {
            $leadObj = $UpiCRMLeads->get_by_id($lead_id);
            if ($leadObj->user_id == $user_id) {
                $permission = true;
            }
        }
        if ($this->get_permission() == 2) {
            $permission = true;
        }
        
        return $permission;
    }
    
    function get_as_array() {
         $get_users = get_users( array( 'role' => '' ) ); //Editor, Administrator
            foreach ($get_users as $user) { 
                if (get_the_author_meta('upicrm_user_permission', $user->ID) > 0 ) {
                    $arr[$user->ID] = $user->display_name;
                }
            }
        return $arr;        
    }
    
    function get() {
         $get_users = get_users( array( 'role' => '' ) ); //Editor, Administrator
            foreach ($get_users as $user) { 
                if (get_the_author_meta('upicrm_user_permission', $user->ID) > 0 ) {
                    $arr[] = $user;
                }
            }
        return $arr;        
    }
}
?>