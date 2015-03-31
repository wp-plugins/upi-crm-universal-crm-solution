<?php
if ( !class_exists('UpiCRMAdminExistingFields') ):
    class UpiCRMAdminExistingFields{
        public function Render() {
            $UpiCRMFields = new UpiCRMFields();
            
            switch ($_GET['action']) {
                case 'save_field':
                    $this->saveField();
                    $msg = "changes saved successfully";
                    break;
            }
?>
<script type="text/javascript">
    $j(document).ready(function () {
        pageSetUp();
    })
</script>
<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-home"></i>
                UpiCRM
							<span>> 
                                                                    <b>Existing Fields</b>
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
        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
            <form method="post" action="admin.php?page=upicrm_existing_fields&action=save_field">
                Add additional fields and datatypes to UpiCRM:
                <input type="text" name="field_name" value="" /><br />

                <?php submit_button("Add New field"); ?>
            </form>
            <br />
            <br />
            <?php 
            foreach ($UpiCRMFields->get_as_array() as $key => $value) { ?>
            <?php echo $value; ?><br />
            <?php } ?>
        </div>
    </div>
</div>
<?php
        }
        
        function saveField() {
            $UpiCRMFields = new UpiCRMFields();
            $UpiCRMFields->add_unique($_POST['field_name']);
        }
    }
endif;
?>