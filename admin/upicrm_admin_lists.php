<?php
if ( !class_exists('UpiCRMAdminAdminLists') ):
class UpiCRMAdminAdminLists{
public function RenderLists() {
    global $SourceTypeName;
    
    $pageNum = (int)$_GET['page_num'];
    if($pageNum == 0)
        $pageNum = 1;
    if($perPage == NULL)
        $perPage = 30;

    $UpiCRMLeads = new UpiCRMLeads();
    $UpiCRMFields = new UpiCRMFields();
    $UpiCRMUIBuilder = new UpiCRMUIBuilder();
    $UpiCRMFieldsMapping = new UpiCRMFieldsMapping();
    $UpiCRMUsers = new UpiCRMUsers();
    
             
    switch ($_GET['action']) {
        case 'import_all':
            $this->importAll();
        break;
        case 'reset':
            $msg = __('Reset all settings successfully','upicrm');
            $UpiCRMFieldsMapping->empty_all();
        break;
        case 'delete_all':
            $msg = __('Delete all leads successfully','upicrm');
            $UpiCRMLeads->empty_all();
        break;
    }  
    

    $list_option = $UpiCRMUIBuilder->get_list_option();
    $getNamesMap = $UpiCRMFieldsMapping->get(); 
    
    if ($UpiCRMUsers->get_permission() == 1) {
        $userID = get_current_user_id();
        $getLeads = $UpiCRMLeads->get($userID,$pageNum,$perPage);
        $getTotalLeads = $UpiCRMLeads->get_total($userID);
    }
    if ($UpiCRMUsers->get_permission() == 2) {
        $upicrm_is_admin = true;
        $getLeads = $UpiCRMLeads->get(0,$pageNum,$perPage);
        $getTotalLeads = $UpiCRMLeads->get_total();
    }
        $totalPage = $getTotalLeads / $perPage;
        if(intval($totalPage) != $totalPage)
            $totalPage = intval($totalPage) + 1;
    
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
          <?php _e('Show Leads','upicrm'); ?>
        </span>
      </h1>
    </div>
<?php _e('Choose Fields to display:','upicrm'); ?> 
    <select id="ChooseInputs" multiple="multiple">
        <?php  
        foreach ($list_option as $key => $arr) { 
             foreach ($arr as $key2 => $value) { 
            ?>
            <option value="<?php echo $key; ?>[<?php echo $key2; ?>]"><?php echo $value; ?></option>
        <?php 
            }
        } ?>
    </select>
      <style type="text/css">
          #LeadTable td, #LeadTable th{
              display: none;
          }
      </style>

    <script type="text/javascript">
        $j(document).ready(function() {
            
            //show default options

            if (upicrm_get_cookie('upicrm_lead_table_fields') != "") {
                var show_option = JSON.parse(upicrm_get_cookie('upicrm_lead_table_fields'));
            } else {
                var show_option = [
                    "content[1]", 
                    "content[2]",
                    "content[8]",
                    "special[user_id]", 
                    "leads[time]", 
                    "leads[lead_id]",
                    "special[actions]",
                    "special[lead_management_comment]",
                    "special[lead_status_id]"
                ]; 
            }
            show_option.forEach(function(entry) {
                 $j("#ChooseInputs option[value='"+entry+"']").prop('selected', true);
                 $j("#LeadTable *[data-belongs='"+entry+"']").show();
            });
            
            $j('#ChooseInputs').multiselect({
                onChange: function(options) {
                    var brands = $j('#ChooseInputs option:selected');
                    var selected = [];
                    $j("#LeadTable td, #LeadTable th").hide();
                    var remember_me = new Array();
                    $j(brands).each(function(index, brand){
                        val = $j(this).val();
                        $j("#LeadTable *[data-belongs='"+val+"']").show();
                        remember_me[index] = val;
                    });
                    upicrm_set_cookie('upicrm_lead_table_fields', JSON.stringify(remember_me),30);
                }
            });
            
            $j("a[data-callback='excel_output']").click(function() {
                var data = {
                    'action': 'excel_output',
                }
                $j.post(ajaxurl, data , function(response) {
                    if (response == 1)
                        location = "<?php echo home_url();?>/wp-content/uploads/upicrm/leads.xlsx";
                    else {
                        alert("Oh no! Error!");
                        console.log(response);
                    }
                });
            });
            
            $j("*[data-callback='remove']").click(function() {
                if (confirm("<?php _e('Remove this lead?','upicrm'); ?>")) {
                    GetSelect = $j(this);
                    var data = {
                        'action': 'remove_lead',
                        'lead_id': $j(this).attr("data-lead_id"),
                    };
                    $j.post(ajaxurl, data , function(response) {
                        GetSelect.closest("tr").fadeOut();
                        //console.log(response);
                    });
                }
            });
            
            $j("*[data-callback='save']").click(function() {
                lead_id = $j(this).attr("data-lead_id");
                user_id = $j("select[name='user_id'][data-lead_id='"+lead_id+"']").val();
                lead_status_id = $j("select[name='lead_status_id'][data-lead_id='"+lead_id+"']").val();
                remarks = $j("textarea[name='lead_remarks'][data-lead_id='"+lead_id+"']").val();
                var data = {
                    'action': 'save_lead',
                    'lead_id': lead_id,
                    'user_id': user_id,
                    'lead_status_id': lead_status_id,
                    'remarks': remarks,
                };
                
                $j.post(ajaxurl, data , function(response) {
                   //console.log(response);
                   if (response == 1)
                       alert("<?php _e('Saved successfully!','upicrm'); ?>");
                   else 
                        alert("Oh no! Error!");
                });
            });
            
            $j("*[data-callback='edit']").click(function() {
                window.location = "admin.php?page=upicrm_edit_lead&id="+$j(this).attr("data-lead_id");
            });
            
            $j("*[data-callback='request_status']").click(function() {
                lead_id = $j(this).attr("data-lead_id");
                var data = {
                    'action': 'request_status',
                    'lead_id': lead_id,
                };
                
                $j.post(ajaxurl, data , function(response) {
                   //console.log(response);
                   if (response == 1)
                       alert("<?php _e('Request status update from lead owner successfully!','upicrm'); ?>");
                   else 
                        alert("Oh no! Error!");
                });
            });
            
            
            
        });
    </script>
    
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
    <?php if ($upicrm_is_admin) { ?>
        <a href="javascript:void(0);" data-callback="excel_output"><?php _e('Export all leads data to Excel format','upicrm'); ?></a> | 
        <a href="admin.php?page=upicrm_allitems&action=import_all"><?php _e('import all existing data into','upicrm'); ?> UpiCRM</a> |
        <a href="admin.php?page=upicrm_allitems&action=reset" onclick="return confirm('<?php _e('are you sure?','upicrm'); ?>');"><?php _e('reset all','upicrm'); ?></a> |
        <a href="admin.php?page=upicrm_allitems&action=delete_all" onclick="return confirm('<?php _e('are you sure?','upicrm'); ?>');"><?php _e('delete all','upicrm'); ?></a>
    <?php } ?>
  <!-- widget grid -->
  <section id="widget-grid" class="">
    
    <!-- row -->
    <div id="LeadTable" class="row">
      <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        
        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
             <header>
                        <span class="widget-icon">
                          
                          <i class="fa fa-table">
                          </i>
                          
                        </span>
                        <h2>
                          <?php _e('Leads Table','upicrm'); ?>
                        </h2>
                        
                      </header>
                      
                      <!-- widget div-->
                      <div>
                        
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                          <!-- This area used as dropdown edit box -->
                          
                        </div>
                        <!-- end widget edit box -->
                        
                        <!-- widget content -->
                        <div class="widget-body no-padding">
                          
                          <table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">
                            
                            <thead>
                              <tr>
                                <?php 
                                foreach ($list_option as $key => $arr) { 
                                    foreach ($arr as $key2 => $value) {  ?>
                                  <th class="hasinput" data-belongs="<?php echo $key;?>[<?php echo $key2; ?>]">
                                      <input type="text" class="form-control" placeholder="<?php _e('Filter','upicrm'); ?> <?php echo $value; ?>" />
                                  </th>
                                <?php 
                                    }
                                } 
                                ?>
                              </tr>
                              <tr>
                                <?php 
                                 foreach ($list_option as $key => $arr) { 
                                    foreach ($arr as $key2 => $value) {  ?>
                                    <th data-class="expand" data-belongs="<?php echo $key;?>[<?php echo $key2; ?>]">
                                      <?php echo $value; ?>
                                    </th>
                                <?php 
                                    }
                                } ?>
                            </thead>
                            
                            <tbody>
                                <?php 
                                foreach ($getLeads as $leadObj) { 
                                    ?>
                                    <tr>
                                    <?php
                                    foreach ($list_option as $key => $arr) { 
                                        foreach ($arr as $key2 => $value) {  ?>   
                                            <td data-belongs="<?php echo $key;?>[<?php echo $key2; ?>]">
                                              <?php echo $UpiCRMUIBuilder->lead_routing($leadObj,$key,$key2,$getNamesMap); ?>
                                            </td>
                                    <?php 
                                        }
                                   }
                                   ?>
                                   </tr>             
                               <?php    
                               } 
                               ?>
                            </tbody>
							
                          </table>
                          
                        </div>
                        <!-- end widget content -->
                        
                      </div>
                      <!-- end widget div -->
                      
                  </div>
                  <!-- end widget -->
                  
              </article>    
    </div>
    
    
          
          <!-- end row -->
          
          <!-- end row -->
          
          
   </section>
            <div style="text-align: center;">
                <ul class="pagination">
                  <li>
                      <a href="admin.php?page=upicrm_allitems"><i class="fa fa-arrow-left"></i></a>
                  </li>
                  <?php 
                  for($i=1; $i<= $totalPage; $i++) {
                      $active = "";
                      if ($pageNum == $i)
                        $active = "active";
                  ?>
                  <li class="<?php echo $active; ?>">
                      <a href="admin.php?page=upicrm_allitems&page_num=<?php echo $i; ?>"><?php echo $i; ?></a>
                  </li>
                  <?php } ?>
                  <li>
                      <a href="admin.php?page=upicrm_allitems&page_num=<?php echo $totalPage; ?>"><i class="fa fa-arrow-right"></i></a>
                  </li>
              </ul>
            </div>    
              <!-- end widget grid -->
              
</div>

<?php
    }
    
    function wp_ajax_excel_output_callback() {
        upicrm_load('excel');
        $UpiCRMLeads = new UpiCRMLeads();
        $UpiCRMUIBuilder = new UpiCRMUIBuilder();
        $UpiCRMFieldsMapping = new UpiCRMFieldsMapping();
        $objPHPExcel = new PHPExcel();
        
        $list_option = $UpiCRMUIBuilder->get_list_option();
        $getLeads = $UpiCRMLeads->get();
        $getNamesMap = $UpiCRMFieldsMapping->get(); 
        $fileName = '/leads.xlsx';
        $dirName = WP_CONTENT_DIR."/uploads/upicrm"; 
        if (!file_exists($dirName)) {
            mkdir($dirName, 0777, true);
        }
        $t="A";
        foreach ($list_option as $key => $arr) { 
            foreach ($arr as $key2 => $value) { 
                $objPHPExcel->getActiveSheet()->getStyle($t.'1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->setCellValue($t.'1', $value);
                $objPHPExcel->getActiveSheet()->getColumnDimension($t)->setWidth(25);
                $t++;
            }
        } 
        
        $i=2;
        foreach ($getLeads as $leadObj) {
            $t="A";
            foreach ($list_option as $key => $arr) { 
                foreach ($arr as $key2 => $value) {
                    $getValue = $UpiCRMUIBuilder->lead_routing($leadObj,$key,$key2,$getNamesMap,true);
                    $objPHPExcel->getActiveSheet()->setCellValue($t.$i, $getValue);
                    $t++;
                }
            } 
            $i++;
        }
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($dirName.$fileName);

        echo 1;
        die();
    }
    
    function importAll() {
        $UpiCRMgform = new UpiCRMgform();
        $UpiCRMwpcf7 = new UpiCRMwpcf7();
        $UpiCRMninja = new UpiCRMninja();
        if($UpiCRMgform->is_active()) {
            $UpiCRMgform->import_all();
        }
        if($UpiCRMwpcf7->is_db_active()) {
            $UpiCRMwpcf7->import_all();
        }
        if ($UpiCRMninja->is_active()) {
            $UpiCRMninja->import_all();
        }
    }
    
    function wp_ajax_remove_lead_callback() {
        $UpiCRMLeads = new UpiCRMLeads();
        $UpiCRMLeads->remove_lead($_POST['lead_id']);
        die();
    }
    
    function wp_ajax_save_lead_callback() {
        $UpiCRMMails = new UpiCRMMails();
        $UpiCRMLeads = new UpiCRMLeads();
        $leadObj = $UpiCRMLeads->get_by_id($_POST['lead_id']);
        $updateArr = array();
        
        $updateArr['lead_management_comment'] = $_POST['remarks'];
        if ($leadObj->user_id != $_POST['user_id']) {
            $updateArr['user_id'] = $_POST['user_id'];
        }
        if ($leadObj->lead_status_id != $_POST['lead_status_id']) {
            $updateArr['lead_status_id'] = $_POST['lead_status_id'];
        }
        
        $UpiCRMLeads->update_by_id($_POST['lead_id'], $updateArr);
        $user = get_user_by('id', $_POST['user_id']);
        
        if ($leadObj->user_id != $_POST['user_id']) {
            $UpiCRMMails->send($_POST['lead_id'], "change_user", $user->user_email);
        }
        
         if ($leadObj->lead_status_id != $_POST['lead_status_id']) {
            $UpiCRMMails->send($_POST['lead_id'], "change_lead_status", $user->user_email);
        }
        
        
        echo 1;
        die();
    }
    
    function wp_ajax_request_status_callback() {
        $UpiCRMMails = new UpiCRMMails();
        $UpiCRMLeads = new UpiCRMLeads();
        
        $leadObj = $UpiCRMLeads->get_by_id($_POST['lead_id']);
        $user = get_user_by('id', $leadObj->user_id);
        $UpiCRMMails->send($_POST['lead_id'], "request_status", $user->user_email);
        echo 1;
        die();
    }
}

add_action( 'wp_ajax_excel_output', array(new UpiCRMAdminAdminLists,'wp_ajax_excel_output_callback'));
add_action( 'wp_ajax_remove_lead', array(new UpiCRMAdminAdminLists,'wp_ajax_remove_lead_callback'));
add_action( 'wp_ajax_save_lead', array(new UpiCRMAdminAdminLists,'wp_ajax_save_lead_callback'));
add_action( 'wp_ajax_request_status', array(new UpiCRMAdminAdminLists,'wp_ajax_request_status_callback'));

endif;