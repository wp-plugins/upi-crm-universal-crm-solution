<?php
if ( !class_exists('UpiCRMAdminSparks') ):
    class UpiCRMAdminSparks{
        public function RenderSparks() {
            $UpiCRMStatistics = new UpiCRMStatistics();
            $UpiCRMUsers = new UpiCRMUsers();
            if ($UpiCRMUsers->get_permission() == 1) {
                $user_id = get_current_user_id();
                $totalLeads = $UpiCRMStatistics->get_total_leads_by_user_id($user_id);
            }
            if ($UpiCRMUsers->get_permission() == 2) {
                $totalLeads = $UpiCRMStatistics->get_total_leads();
            }
            ?>

            <ul id="sparks" class="">				
				<!--<li class="sparks-info">
					<h5>Site Visits<span class="txt-color-purple"><i class="fa fa-user" data-rel="bootstrap-tooltip" title="Increased"></i>&nbsp;1230</span></h5>
					<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
						110,150,300,130,400,240,220,310,220,300, 270, 210
					</div>
				</li> -->
				<li class="sparks-info">
					<h5>Site Leads<span class="txt-color-greenDark"><i class="fa fa-envelope-o"></i>&nbsp;<?php echo $totalLeads; ?></span></h5>
					<!--<div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm">
						2,5,13,9,4,7,3,10,12,9, 8, 5
					</div>-->
				</li>
			</ul>

            <?php
        }
    }
endif;