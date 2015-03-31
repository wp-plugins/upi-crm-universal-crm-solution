<?php
if ( !class_exists('UpiCRMAdminAdminLists') ):
class UpiCRMAdminAdminLists{
public function RenderLists() {	
    global $SourceTypeName;
    
    $UpiCRMLeads = new UpiCRMLeads();
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
          Data Tables
        </span>
      </h1>
    </div>
    <select id="ChooseInputs" multiple="multiple">
        <option value="leads[lead_id]">ID</option>
        <option value="leads[user_agent]">User Agent</option>
        <option value="leads[user_referer]">Referer</option>
        <option value="leads[user_ip]">IP</option>
        <option value="leads[time]">Time</option>
        <option value="leads_campaign[utm_source]">UTM Source</option>
        <option value="leads_campaign[utm_medium]">UTM Medium</option>
        <option value="leads_campaign[utm_term]">UTM Term</option>
        <option value="leads_campaign[utm_content]">UTM Content</option>
        <option value="leads_campaign[utm_campaign]">UTM Campaign</option>
        <?php foreach ($UpiCRMLeads->get_all_content_keys() as $input) { ?>
            <option value="content[<?php echo $input; ?>]"><?php echo $input; ?></option>
        <?php } ?>
    </select>

    <script type="text/javascript">
        $j(document).ready(function() {
            $j('#ChooseInputs').multiselect({
                onChange: function(options) {
                    var brands = $j('#ChooseInputs option:selected');
                    var selected = [];
                    $j(brands).each(function(index, brand){
                         obj = {input: $j(this).val(), value: $j(this).text()};
                        selected[index] = obj;
                    });
                    //console.log(selected);
                    
                    $j.post(ajaxurl, {'inputs': selected, 'action': 'upicrm_lead_table'} , function( data ) {
                        $j("#LeadTable").html(data);
                    });
                }
            });
        });
    </script>
 </div>
  <!-- widget grid -->
  <section id="widget-grid" class="">
    
    <!-- row -->
    <div id="LeadTable" class="row">
      

              
    </div>
          
          <!-- end row -->
          
          <!-- end row -->
          
          
              </section>
              <!-- end widget grid -->
              
</div>

<?php
}
}

add_action( 'wp_ajax_upicrm_lead_table', 'upicrm_lead_table_callback' );

function upicrm_lead_table_callback() {
    
    $UpiCRMLeads = new UpiCRMLeads();
    $i=0;
    foreach ($_POST['inputs'] as $arr) {
        $input[$i] = $arr['input'];
        $i++;
    }
    
    $getLeads = $UpiCRMLeads->get_by_inputs($input);
    //print_r($getLeads);
?>
<!-- NEW WIDGET START -->
      <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        
        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
          <!-- widget options:
usage: 
<div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

data-widget-colorbutton="false"
data-widget-editbutton="false"
data-widget-togglebutton="false"
data-widget-deletebutton="false"
data-widget-fullscreenbutton="false"
data-widget-custombutton="false"
data-widget-collapsed="true"
data-widget-sortable="false"

-->
                      <header>
                        <span class="widget-icon">
                          
                          <i class="fa fa-table">
                          </i>
                          
                        </span>
                        <h2>
                          Leads Table
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
                                foreach ($_POST['inputs'] as $arr) { ?>
                                  <th class="hasinput">
                                      <input type="text" class="form-control" placeholder="Filter <?php echo $arr['value']; ?>" />
                                  </th>
                                <?php } ?>
                              </tr>
                              <tr>
                                <?php 
                                foreach ($_POST['inputs'] as $arr) { ?>
                                    <th data-class="expand">
                                      <?php echo stripslashes($arr['value']); ?>
                                    </th>
                                <?php } ?>
                            </thead>
                            
                            <tbody>
                                <tr>
                                <?php 
                                //@TODO Code Optimization Here:
                                $count_inputs = count($_POST['inputs']); 
                                $i=0;
                                //echo count($_POST['inputs']);
                                foreach ($getLeads as $arr) {
                                    foreach ($arr as $key => $value) {
                                            if ($i % $count_inputs == 0)
                                                echo "</tr><tr>"; 
                                        $t=0;   
                                        
                                        foreach ($_POST['inputs'] as $postInputs) { 
                                            $exp = explode("[",$postInputs["input"],2);
                                            $exp2 = explode("]",$exp[1],2);
                                            if (stripslashes($key) == stripslashes($exp2[0])) {
                                                ?>
                                                <td data-class="expand">
                                                   <?php echo $value; ?>
                                                </td>
                                                <?php
                                                $i++;
                                                break;
                                            }
                                        }
                                    } 
                                }
                                ?>
                                </tr>
                            </tbody>
							
                          </table>
                          
                        </div>
                        <!-- end widget content -->
                        
                      </div>
                      <!-- end widget div -->
                      
                  </div>
                  <!-- end widget -->
                  
              </article>
              <!-- WIDGET END -->
             <!-- <script type="text/javascript">            
                  var responsiveHelper_datatable_fixed_column = undefined;

                  var breakpointDefinition = {
                                      tablet : 1024,
                                      phone : 480
                              };

                  pageSetUp();
                  // Apply the filter
                  /* COLUMN FILTER  */
                  var otable = $j('#datatable_fixed_column').DataTable({
                    //"bFilter": false,
                    //"bInfo": false,
                    //"bLengthChange": false
                    //"bAutoWidth": false,
                    //"bPaginate": false,
                    //"bStateSave": true // saves sort state using localStorage
                    "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
                    "t"+
                    "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
                    "autoWidth" : true,
                    "preDrawCallback" : function() {
                      // Initialize the responsive datatables helper once.
                      if (!responsiveHelper_datatable_fixed_column) {
                        responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($j('#datatable_fixed_column'), breakpointDefinition);
                      }
                    }
                    ,
                    "rowCallback" : function(nRow) {
                      responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
                    }
                    ,
                    "drawCallback" : function(oSettings) {
                      responsiveHelper_datatable_fixed_column.respond();
                    }


                  }
                                                                      );

                  // custom toolbar
                  $j("div.toolbar").html('<div class="text-right"></div>');

                  // Apply the filter
                  $j("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

                    otable
                      .column( $j(this).parent().index()+':visible' )
                      .search( this.value )
                      .draw();

                  }
                                                                            );
                  /* END COLUMN FILTER */ 


              </script>-->
              <?php
	die(); // this is required to terminate immediately and return a proper response
}

endif;