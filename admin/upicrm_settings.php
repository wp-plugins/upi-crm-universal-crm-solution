<?php
if ( !class_exists('UpiCRMAdminSettings') ):
    class UpiCRMAdminSettings{
        public function Render() {
            global $SourceTypeID;
            $UpiCRMgform = new UpiCRMgform();
            $UpiCRMwpcf7 = new UpiCRMwpcf7();            
            
            switch ($_GET['action']) {
                case 'save_email':
                    $this->updateEmails();
                    $msg = "changes saved successfully";
                    break;
            }
            $tabs_html = '';
            $content_html = '';
            if($UpiCRMgform->is_active()) {                
                foreach ($UpiCRMgform->get_all_form() as $key => $value) {                    
                    $tabs_html .= '<li><a href="#f'.strval($key).'">'.$value.'</a></li>';
                    $content_html .= '<div id="f'.$key.'"><div class="table-responsive"><table class="table"><thead><tr><th>Form Field</th><th>UPiCRM Field</th></tr></thead><tbody>';
                    foreach ($UpiCRMgform->get_all_form_fields($key,true) as $inputName => $inputValue) {
                        $arr = array();
                        $arr["name"] = $inputName;
                        $arr["value"] = $inputValue;
                        $arr["source_id"] = $key;
                        $arr["item_id"] = 'f'.$key;
                        $arr["source_type"] = $SourceTypeID['gform'];
                        $content_html .= $this->TabContentTemplate($arr);
                    }
                    $content_html .= '</tbody></table></div></div>';
                }
            }
            if ($UpiCRMwpcf7->is_active()) {
                foreach ($UpiCRMwpcf7->get_all_form() as $key => $value) {                    
                    $tabs_html .= '<li><a href="#f7'.strval($key).'">'.$value.'</a></li>';
                    $content_html .= '<div id="f7'.$key.'"><div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>Form Field</th><th>UPiCRM Field</th></tr></thead><tbody>';
                    foreach ($UpiCRMwpcf7->get_all_form_fields($key)  as $inputValue => $inputName) {
                        $arr = array();
                        $arr["name"] = $inputName;
                        $arr["value"] = $inputName;
                        $arr["source_id"] = $key;
                        $arr["source_type"] = $SourceTypeID['wpcf7'];
                        $arr["item_id"] = 'f7'.$key;
                        $content_html .= $this->TabContentTemplate($arr);
                    }
                    $content_html .= '</tbody></table></div></div>';
                }
            }
            
?>
<script type="text/javascript">
    $j(document).ready(function () {
        pageSetUp();
    })
</script>
<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-home"></i>&nbsp;UpiCRM&nbsp;<span>&nbsp;>&nbsp;<b>General Settings</b></span>
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
            <h2>Lead Management</h2>
            <form method="post" action="admin.php?page=upicrm_settings&action=save_email">
                Send all leads and updates to the following user: 
                            <select name="default_email">
                                <?php 
            $default_email = get_option('upicrm_default_email');
            $get_users = get_users( array( 'role' => '' ) ); //Editor, Administrator
            foreach ($get_users as $user) { 
                                ?>
                                <option value="<?php echo $user->user_email; ?>" <?php selected( $default_email, $user->user_email ); ?>><?php echo $user->display_name; ?></option>
                                <?php } ?>
                            </select>
                <br />
                Leads are by default assigned to: 
                            <select name="default_lead">
                                <?php 
            $default_lead = get_option('upicrm_default_lead');
            $get_users = get_users( array( 'role' => '' ) ); //Editor, Administrator
            foreach ($get_users as $user) { 
                                ?>
                                <option value="<?php echo $user->ID; ?>" <?php selected( $default_lead, $user->ID ); ?>><?php echo $user->display_name; ?></option>
                                <?php } ?>
                            </select>
                <br />
                <?php $email_format =  get_option('upicrm_email_format');?>
                Email format: 
                <select name="email_format">
                    <option value="1" <?php selected( $email_format, 1); ?>>HTML</option>
                    <option value="2" <?php selected( $email_format, 2); ?>>Text</option>
                </select><br />
                Distribute all leads and updated to additional email address (or multiple addresses separated by comma (,):
                <input type="text" name="extra_email" value="<?php echo get_option('upicrm_extra_email'); ?>" /><br />
                Change default "from" field for emails sent from UpiCRM: <input type="text" name="sender_email" value="<?php echo get_option('upicrm_sender_email'); ?>" /><br />
                Email will be sent in the following format: &lt;name&gt; no-reply@yourdomain.com
                <br />
                
                <?php submit_button(); ?>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h2>Map existing forms fields to UpiCRM structured database field</h2>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
            <div class="table-responsive">
                <div id="tabs">
                    <table id="table-tabs" class="table table-bordered">
                        <thead style="display: none;">
                            <tr>
                                <th>Form Name</th>
                                <th>Fields</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width: 25%;">
                                    <ul>
                                        <?php                     
            echo $tabs_html;
                                        ?>
                                    </ul>
                                </td>
                                <td class="fields-container"><?php                     
            echo $content_html;
                                                             ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div>
        <?php
            if (isset($msg)) {
        ?>
        <div class="updated">
            <p><?php echo $msg; ?></p>
        </div>
        <?php
            }
        ?>
    </div>
    <script type="text/javascript">
        $j(document).ready(function () {
            $j("select[data-callback='save_field']").change(function () {
                var _this = $j(this);
                if (_this.val() != 0) {
                    var data = {
                        'action': 'save_field_mapping_ajax',
                        'fm_name': $j(this).attr("data-name"),
                        'source_id': $j(this).attr("data-source_id"),
                        'source_type': $j(this).attr("data-source_type"),
                        'field_id': $j(this).val(),
                        'fm_id': $j(this).attr("data-fm_id"),

                    };
                    $j.post(ajaxurl, data, function (response) {
                        if (response != 0) {
                            _this.attr("data-fm_id", response);
                            $j.bigBox({
                                title: "Field has been mapped succesfully!",
                                content: "Your new mapping pair is:<br/><h4 style='font-size: 1.3em;'>" + _this.attr("data-value") + " >> " + _this[0].options[_this[0].selectedIndex].text + "</h4>",
                                color: "#739E73",
                                timeout: 5000,
                                icon: "fa fa-check-square-o",
                                number: ""
                            }, function () {
                                closedthis();
                            });
                        }
                        else {
                            _this.val(0);
                            $j.bigBox({
                                title: "Field mapping attempt failed!",
                                content: "Please, select another UPiCRM field.",
                                color: "#C46A69",
                                icon: "fa fa-warning shake animated",
                                number: "",
                                timeout: 4500
                            });
                        }
                    });
                }
            });
        })
    </script>

    <?php
        }        
        function updateEmails() {
            update_option('upicrm_default_email', $_POST['default_email']);
            update_option('upicrm_extra_email', $_POST['extra_email']);
            update_option('upicrm_sender_email', $_POST['sender_email']);
            update_option('upicrm_default_lead', $_POST['default_lead']);
            update_option('upicrm_email_format', $_POST['email_format']);
        }        
        function TabContentTemplate($arr) {
            $UpiCRMFields = new UpiCRMFields();
            $UpiCRMFieldsMapping = new UpiCRMFieldsMapping();
            $fm_obj = $UpiCRMFieldsMapping->get_by($arr["name"],  $arr["source_id"], $arr["source_type"]);
            $content_html = '<tr><td><label class="control-label">'.$arr["value"].'</label></td><td>';
            
            $content_html .= '<fieldset><section><select data-callback="save_field" data-value="'.$arr["value"].'" data-name="';
            $content_html .= $arr["name"].'" data-source_id="'.$arr["source_id"].'" ';
            $content_html .= 'data-source_type="'.$arr["source_type"].'" data-fm_id="'.$fm_obj->fm_id.'"><option value="0"></option>';
            foreach ($UpiCRMFields->get() as $field) {
                $content_html .= '<option value="'.$field->field_id.'" '.selected( $field->field_id, $fm_obj->field_id ,false).'>'.$field->field_name.'</option>';
            }
            $content_html .= '</select></section></fieldset></td></tr>';
            
            return $content_html;
        }
        
        function InputsTemplate($arr) {
            $UpiCRMFields = new UpiCRMFields();
            $UpiCRMFieldsMapping = new UpiCRMFieldsMapping();
            $fm_obj = $UpiCRMFieldsMapping->get_by($arr["name"],  $arr["source_id"], $arr["source_type"]);
            echo $arr["value"];
    ?>
    >>>
            <select data-callback="save_field" data-name="<?php echo $arr["name"];?>" data-source_id="<?php echo $arr["source_id"];?>" data-source_type="<?php echo $arr["source_type"];?>"  data-fm_id="<?php echo $fm_obj->fm_id;?>">
                <option value="0"></option>
                <?php 
            foreach ($UpiCRMFields->get() as $field) { 
                ?>
                <option value="<?php echo $field->field_id; ?>" <?php selected( $field->field_id, $fm_obj->field_id ); ?>><?php echo $field->field_name; ?></option>
                <?php } ?>
            </select>
    <br />
<?php
        }        
        function wp_ajax_save_field_mapping_ajax_callback() {
            $UpiCRMFieldsMapping = new UpiCRMFieldsMapping();
            if (!$UpiCRMFieldsMapping->is_exists($_POST['field_id'], $_POST['source_id'], $_POST['source_type'])) {
                echo $UpiCRMFieldsMapping->add_or_update($_POST['fm_id'],$_POST['field_id'], $_POST['fm_name'], $_POST['source_id'], $_POST['source_type']);
            }
            else {
                echo 0;
            }
            die();
        }        
    }    
endif;
add_action( 'wp_ajax_save_field_mapping_ajax', array(new UpiCRMAdminSettings,'wp_ajax_save_field_mapping_ajax_callback'));

