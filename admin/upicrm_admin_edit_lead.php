<?php
if ( !class_exists('UpiCRMAdminAdminEditLead') ):
class UpiCRMAdminAdminEditLead{
    public function Render() {
        $lead_id = (int)$_GET['id'];

        switch ($_GET['action']) {
             case 'save':
                    $this->updateContent($lead_id);
                    $msg =  __('changes saved successfully','upicrm');
            break;
        }
        
        $UpiCRMUIBuilder = new UpiCRMUIBuilder();
        $UpiCRMFieldsMapping = new UpiCRMFieldsMapping();
        $UpiCRMLeads = new UpiCRMLeads();
        $UpiCRMFields = new UpiCRMFields();
        
        $lead = $UpiCRMLeads->get_by_id($lead_id);
        $getNamesMap = $UpiCRMFieldsMapping->get_all_by($lead->source_id, $lead->source_type); 
        
               
        foreach ($UpiCRMFields->get() as $field) { 
            foreach ($getNamesMap as $map) {
                if ($map->field_id == $field->field_id)
                    $list_option[$field->field_id] = $field->field_name;  
            }
        }
        
        ?>
        <div id="content">
          <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
              <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-table fa-fw ">
                </i>

                UpiCrm 
                <span>
                  > 
                  <?php _e('Edit Lead','upicrm'); ?>
                </span>
              </h1>
            </div>
          </div>
            
                <?php
                if (isset($msg)) {
                ?>
                    <div class="updated">
                        <p><?php echo $msg; ?></p>
                    </div>
                <?php
                }
                ?>
          <div class="row">
              <form method="post" action="admin.php?page=upicrm_edit_lead&action=save&id=<?php echo $lead_id; ?>">
            <?php 
                foreach ($list_option as $key => $value) {
                   
                    ?>
                    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-6">
                    <?php
                        $LeadContent = $UpiCRMUIBuilder->return_lead_content_arr($lead,$key,$getNamesMap);
                        ?>
                        <label><?php echo $value; ?>:</label><br />
                        <input type="text" name="<?php echo $LeadContent['fm_name']; ?>" value="<?php echo $LeadContent['text']; ?>" />
                        <br /><br />
                        <?php
                    ?>
                    </div>
                    <?php
            }            
            ?>
            <?php submit_button(); ?>
            </form>               
          </div>
        </div>
        <?php
    }
    
    function updateContent($lead_id) {
        if (count($_POST) > 0) {
            $UpiCRMLeads = new UpiCRMLeads();
            $lead_content_arr = $_POST;
            $updateArr['lead_content'] = json_encode($lead_content_arr);
            $UpiCRMLeads->update_by_id($lead_id,$updateArr);
        }
    }
}
endif;