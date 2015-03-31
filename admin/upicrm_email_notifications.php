<?php
if ( !class_exists('UpiCRMAdminEmailNotifications') ):
    class UpiCRMAdminEmailNotifications{
        public function Render() {

            switch ($_GET['action']) {
                case 'save_field':
                    $this->saveField();
                    $msg = "changes Save successfully";
                break;
            }
            
            $UpiCRMMails = new UpiCRMMails();
            $getMails = $UpiCRMMails->get();

            ?>
            <script type="text/javascript">            
                $j(document).ready(function() {
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
                                                                    <b>Email Notifications</b>
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
                <form method="post" action="admin.php?page=upicrm_email_notifications&action=save_field">
                    <?php foreach ($getMails as $mail) { ?>                  
                        <div class="row">
                           <h2><?php echo $mail->mail_event_name; ?></h2>
                           <div class="col-xs-12 col-sm-5 col-md-5 col-lg-6">
                               <label>Content: </label><br />
                               <textarea name="<?php echo $mail->mail_event; ?>[mail_content]" rows="11" cols="50"><?php echo $mail->mail_content; ?></textarea>
                           </div>
                           <div class="col-xs-12 col-sm-5 col-md-5 col-lg-6">
                               <label>Subject: </label><br />
                               <input type="text" name="<?php echo $mail->mail_event; ?>[mail_subject]" value="<?php echo $mail->mail_subject; ?>" />
                               <br /><br />
                               <label>CC: </label><br />
                               <input type="text" name="<?php echo $mail->mail_event; ?>[mail_cc]" value="<?php echo $mail->mail_cc; ?>" />
                               <br /><br />
                               <strong>Variables:</strong> <br />
                               [lead]<br />
                               [url]<br />
                               [assigned-to]<br />
                               [lead-plaintext]
                           </div>
                        </div>
                     <?php } ?>
                    <?php submit_button(); ?>
                </form>
            </div>
        <?php
        }
        
        function saveField() {
            $UpiCRMMails = new UpiCRMMails();
            $UpiCRMMails->update($_POST);
        }
    }
endif;
?>