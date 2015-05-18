<?php
if ( !class_exists('UpiCRMAdminAPI') ):
    class UpiCRMAdminAPI{
        public function Render() {
            global $SourceTypeID;
            
            switch ($_GET['action']) {
                case 'change_lead_status':
                   //admin.php?page=upicrm_api&action=change_lead_status&lead_id=38&lead_status_id=2
                   $this->change_lead_status($_GET['lead_id'],$_GET['lead_status_id']);
                break;
                case 'change_lead_user_id':
                   //admin.php?page=upicrm_api&action=change_lead_user_id&lead_id=38&user_id=1
                   $this->change_user_id($_GET['lead_id'],$_GET['user_id']);
                break;
                case 'save_comment':
                   //admin.php?page=upicrm_api&action=save_comment
                   $this->save_comment($_POST['lead_id'],$_POST['lead_management_comment']);
                break;
                default:
                    echo "Error!";
            }
        }
        
        public function change_lead_status($lead_id,$lead_status_id) {
            $UpiCRMLeads = new UpiCRMLeads();
            $UpiCRMUsers = new UpiCRMUsers();

            if ($UpiCRMUsers->is_have_permission_to_lead(get_current_user_id(),$lead_id)) {
                $updateArr['lead_status_id'] = $lead_status_id;
                $UpiCRMLeads->update_by_id($lead_id, $updateArr);
                $this->show_comment($lead_id, __('status saved successfully','upicrm'));
            }
            else {
                echo "Error! No permissions!";
            }
        }
        
        public function change_user_id($lead_id,$user_id) {
            $UpiCRMLeads = new UpiCRMLeads();
            $UpiCRMUsers = new UpiCRMUsers();

            if ($UpiCRMUsers->is_have_permission_to_lead(get_current_user_id(),$lead_id)) {
                $updateArr['user_id'] = $user_id;
                $UpiCRMLeads->update_by_id($lead_id, $updateArr);
                $this->show_comment($lead_id, __('user change successfully','upicrm'));
            }
            else {
                echo "Error! No permissions!";
            }
        }
        
        public function show_comment($lead_id,$msg) {
            $UpiCRMLeads = new UpiCRMLeads();
            $leadObj = $UpiCRMLeads->get_by_id($lead_id);
            if ($msg != "") {
            ?>
                <div class="updated">
                    <p><?php echo $msg; ?></p>
                </div>
            <?php
            }
            ?>
            <br /><br />
            <form action="admin.php?page=upicrm_api&action=save_comment" method="post">
                <p>Lead Management Comment:</p>
                <textarea rows="11" cols="50" name="lead_management_comment"><?php echo $leadObj->lead_management_comment;?></textarea>
                <input type="hidden" value="<?php echo $lead_id;?>" name="lead_id" />
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Comment">
                </p>
            </form>
            <?php
        }
        
        function save_comment($lead_id,$lead_management_comment) {
            $UpiCRMLeads = new UpiCRMLeads();
            $UpiCRMUsers = new UpiCRMUsers();

            if ($UpiCRMUsers->is_have_permission_to_lead(get_current_user_id(),$lead_id)) {
                $updateArr['lead_management_comment'] = $lead_management_comment;
                $UpiCRMLeads->update_by_id($lead_id, $updateArr);
                $this->show_comment($lead_id, __('comment saved successfully','upicrm'));
            }
            else {
                echo "Error! No permissions!";
            }
        }

    }    
endif;

