<?php
add_action('show_user_profile', array(new UpiCRMUsers,'action_add_meta_user_profile'));
add_action('edit_user_profile', array(new UpiCRMUsers,'action_add_meta_user_profile'));

add_action('personal_options_update', array(new UpiCRMUsers,'action_save_meta_user_profile'));
add_action('edit_user_profile_update', array(new UpiCRMUsers,'action_save_meta_user_profile'));

class UpiCRMUsers {
    function action_add_meta_user_profile($user) {
         ?>
        <h3>UpiCRM options</h3>

        <table class="form-table">
            <tr>
                <th><label for="upicrm_user_permission">Permission</label></th>
                <td>
                    <select id="upicrm_user_permission" name="upicrm_user_permission">
                        <option value="">None</option>
                        <option value="1" <?php selected(get_the_author_meta('upicrm_user_permission', $user->ID), 1);?>>UpiCRM User</option>
                        <option value="2" <?php selected(get_the_author_meta('upicrm_user_permission', $user->ID), 2);?>>UpiCRM Admin</option>
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
        $get_users = get_users( array( 'role' => '' ) ); //Editor, Administrator
        $text ='<select name="user_id" data-lead_id="'.$lead->lead_id.'" data-callback="'.$callback.'">';
            foreach ($get_users as $user) { 
                $selected = selected( $lead->user_id, $user->ID, false);
                $text.='<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.'</option>';
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
}
?>