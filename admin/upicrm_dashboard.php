<?php
if ( !class_exists('UpiCRMAdminIndex') ):
    class UpiCRMAdminIndex{
        public function __construct() {
            wp_register_script('upicrm_js_flot',  UPICRM_URL.'resources/js/plugin/flot/jquery.flot.cust.min.js', array('jquery'), '1.0');
            wp_register_script('upicrm_js_vectormap',  UPICRM_URL.'resources/js/plugin/vectormap/jquery-jvectormap-1.2.2.min.js', array('jquery'), '1.0');
            wp_register_script('upicrm_js_chartjs',  UPICRM_URL.'resources/js/plugin/chartjs/chart.min.js', array('jquery'), '1.0');
            
            wp_enqueue_script('upicrm_js_flot');
            wp_enqueue_script('upicrm_js_vectormap');
            wp_enqueue_script('upicrm_js_chartjs');
        }
        public function Render() {
            $UpiCRMStatistics = new UpiCRMStatistics();
            $UpiCRMUsers = new UpiCRMUsers();
            $user_id = get_current_user_id();
            if ($UpiCRMUsers->get_permission() == 1) {
                $is_admin = false;
                $totalLeads = $UpiCRMStatistics->get_total_leads_by_user_id($user_id);
                $totalLeadStatus = $UpiCRMStatistics->get_total_leads_status_by_user_id($user_id);
            }
            if ($UpiCRMUsers->get_permission() == 2) {
                $is_admin = true;
                $totalLeads = $UpiCRMStatistics->get_total_leads();
                $totalLeadsMe = $UpiCRMStatistics->get_total_leads_by_user_id($user_id);
                $totalLeadStatus = $UpiCRMStatistics->get_total_leads_status_by_user_id();
                for ($i=0; $i <= 5; $i++) {
                    $weeksArr[] = $UpiCRMStatistics->get_total_leads_by_weeks($i);
                }
                $weeksArr = array_reverse($weeksArr);
            }
            
            
            
?>
<script type="text/javascript">
    $j(document).ready(function () {
        pageSetUp();
        var lineOptions = {
            ///Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: true,
            //String - Colour of the grid lines
            scaleGridLineColor: "rgba(0,0,0,.05)",
            //Number - Width of the grid lines
            scaleGridLineWidth: 1,
            //Boolean - Whether the line is curved between points
            bezierCurve: true,
            //Number - Tension of the bezier curve between points
            bezierCurveTension: 0.4,
            //Boolean - Whether to show a dot for each point
            pointDot: true,
            //Number - Radius of each point dot in pixels
            pointDotRadius: 4,
            //Number - Pixel width of point dot stroke
            pointDotStrokeWidth: 1,
            //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius: 20,
            //Boolean - Whether to show a stroke for datasets
            datasetStroke: true,
            //Number - Pixel width of dataset stroke
            datasetStrokeWidth: 2,
            //Boolean - Whether to fill the dataset with a colour
            datasetFill: true,
            //Boolean - Re-draw chart on page resize
            responsive: true,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
        };
        <?php if ($is_admin) { ?>
        var lineData = {
            labels: ["5 Weeks Ago", "4 Weeks Ago", "3 Weeks Ago", "2 Weeks Ago", "1 Week Ago", "This Week"],
            datasets: [
                {
                    label: "My Second dataset",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: [<?php foreach ($weeksArr as $arr) echo $arr.", "; ?>]
                }
            ]
        };
        
        // render chart
        var ctx = document.getElementById("lineChart").getContext("2d");
        var myNewChart = new Chart(ctx).Line(lineData, lineOptions);
        <?php } ?>
        if ($j("#site-stats").length) {
            /* chart colors default */
            var $chrt_border_color = "#efefef";
            var $chrt_grid_color = "#DDD"
            var $chrt_main = "#E24913";
            /* red       */
            var $chrt_second = "#6595b4";
            /* blue      */
            var $chrt_third = "#FF9F01";
            /* orange    */
            var $chrt_fourth = "#7e9d3a";
            /* green     */
            var $chrt_fifth = "#BD362F";
            /* dark red  */
            var $chrt_mono = "#000";
            var pageviews = [[1, 75], [3, 87], [4, 93], [5, 127], [6, 116], [7, 137], [8, 135], [9, 130], [10, 167], [11, 169], [12, 179], [13, 185], [14, 176], [15, 180], [16, 174], [17, 193], [18, 186], [19, 177], [20, 153], [21, 149], [22, 130], [23, 100], [24, 50]];
            var visitors = [[1, 65], [3, 50], [4, 73], [5, 100], [6, 95], [7, 103], [8, 111], [9, 97], [10, 125], [11, 100], [12, 95], [13, 141], [14, 126], [15, 131], [16, 146], [17, 158], [18, 160], [19, 151], [20, 125], [21, 110], [22, 100], [23, 85], [24, 37]];
            //console.log(pageviews)
            var plot = $j.plot($j("#site-stats"), [{
                data: pageviews,
                label: "Leads Received"
            }, {
                data: visitors,
                label: "Lead Accepted"
            }], {
                series: {
                    lines: {
                        show: true,
                        lineWidth: 1,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 0.1
                            }, {
                                opacity: 0.15
                            }]
                        }
                    },
                    points: {
                        show: true
                    },
                    shadowSize: 0
                },
                xaxis: {
                    mode: "time",
                    tickLength: 10
                },

                yaxes: [{
                    min: 20,
                    tickLength: 5
                }],
                grid: {
                    hoverable: true,
                    clickable: true,
                    tickColor: $chrt_border_color,
                    borderWidth: 0,
                    borderColor: $chrt_border_color,
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%s for <b>%x:00 hrs</b> was %y",
                    dateFormat: "%y-%0m-%0d",
                    defaultTheme: false
                },
                colors: [$chrt_main, $chrt_second],
                xaxis: {
                    ticks: 15,
                    tickDecimals: 2
                },
                yaxis: {
                    ticks: 15,
                    tickDecimals: 0
                },
            });

        }
    })
</script>
<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-home"></i>
                UpiCRM
							<span>> 
								<b>Dashboard</b>
                            </span>
            </h1>
        </div>
        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
            <?php
            $UpiCRMAdminSparks = new UpiCRMAdminSparks();
            $UpiCRMAdminSparks->RenderSparks();
            ?>
        </div>
    </div>

    <!-- row -->
    <div class="row">
        <article class="col-sm-12">
            <!-- new widget -->
            <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                <!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

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
                    <span class="widget-icon"><i class="glyphicon glyphicon-stats txt-color-darken"></i></span>
                    <h2>Lead Overview</h2>

                    <ul class="nav nav-tabs pull-right in" id="myTab">
                        <li class="active">
                            <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i><span class="hidden-mobile hidden-tablet">Live Stats</span></a>
                        </li>

                        <li style="display: none;">
                            <a data-toggle="tab" href="#s2"><i class="fa fa-facebook"></i><span class="hidden-mobile hidden-tablet">Social Network</span></a>
                        </li>

                        <li style="display: none">
                            <a data-toggle="tab" href="#s3"><i class="fa fa-dollar"></i><span class="hidden-mobile hidden-tablet">Revenue</span></a>
                        </li>
                    </ul>

                </header>

                <!-- widget div-->
                <div class="no-padding">
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        test
                    </div>
                    <!-- end widget edit box -->

                    <div class="widget-body">
                        <!-- content -->
                        <div id="myTabContent" class="tab-content">
                            <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1">
                                <div class="row no-space">
                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                        <span class="demo-liveupdate-1" style="display: none"><span class="onoffswitch-title">Live switch</span> <span class="onoffswitch">
                                            <input type="checkbox" name="start_interval" class="onoffswitch-checkbox" id="start_interval">
                                            <label class="onoffswitch-label" for="start_interval">
                                                <span class="onoffswitch-inner" data-swchon-text="ON" data-swchoff-text="OFF"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </span></span>
                                        <!--<div id="site-stats" class="chart has-legend"></div>-->
                                        <div class="widget-body">

                                            <!-- this is what the user will see -->
                                            <canvas id="lineChart" height="134"></canvas>

                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 show-stats">
                                        <div class="row">
                                            <?php if ($is_admin) { ?>
                                            <div class="col-xs-6 col-sm-6 col-md-12 col-lg-12">
                                                <span class="text">Assigned to Me<span class="pull-right"><?php echo $totalLeadsMe.'/'.$totalLeads; ?></span> </span>
                                                <div class="progress">
                                                    <div class="progress-bar bg-color-blueDark" style="width: <?php echo ($totalLeadsMe / $totalLeads) * 100; ?>%;"></div>
                                                </div>
                                            </div>
                                            <?php 
                                            }
                                            foreach ($totalLeadStatus as $obj) { ?> 
                                                <div class="col-xs-6 col-sm-6 col-md-12 col-lg-12">
                                                    <span class="text"><?php echo $obj->lead_status_name;?> <span class="pull-right"><?php echo $obj->count.'/'.$totalLeads; ?></span> </span>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-color-<?php echo $obj->color;?>" style="width: <?php echo ($obj->count / $totalLeads) * 100; ?>%;"></div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <!--<span class="show-stat-buttons"><span class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><a href="javascript:void(0);" class="btn btn-default btn-block hidden-xs">Generate PDF</a> </span><span class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><a href="javascript:void(0);" class="btn btn-default btn-block hidden-xs">Report a bug</a> </span></span> -->

                                        </div>

                                    </div>
                                </div>

                                <div class="show-stat-microcharts" style="display: none">
                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

                                        <div class="easy-pie-chart txt-color-orangeDark" data-percent="33" data-pie-size="50">
                                            <span class="percent percent-sign">35</span>
                                        </div>
                                        <span class="easy-pie-title">Server Load <i class="fa fa-caret-up icon-color-bad"></i></span>
                                        <ul class="smaller-stat hidden-sm pull-right">
                                            <li>
                                                <span class="label bg-color-greenLight"><i class="fa fa-caret-up"></i>97%</span>
                                            </li>
                                            <li>
                                                <span class="label bg-color-blueLight"><i class="fa fa-caret-down"></i>44%</span>
                                            </li>
                                        </ul>
                                        <div class="sparkline txt-color-greenLight hidden-sm hidden-md pull-right" data-sparkline-type="line" data-sparkline-height="33px" data-sparkline-width="70px" data-fill-color="transparent">
                                            130, 187, 250, 257, 200, 210, 300, 270, 363, 247, 270, 363, 247
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                        <div class="easy-pie-chart txt-color-greenLight" data-percent="78.9" data-pie-size="50">
                                            <span class="percent percent-sign">78.9 </span>
                                        </div>
                                        <span class="easy-pie-title">Disk Space <i class="fa fa-caret-down icon-color-good"></i></span>
                                        <ul class="smaller-stat hidden-sm pull-right">
                                            <li>
                                                <span class="label bg-color-blueDark"><i class="fa fa-caret-up"></i>76%</span>
                                            </li>
                                            <li>
                                                <span class="label bg-color-blue"><i class="fa fa-caret-down"></i>3%</span>
                                            </li>
                                        </ul>
                                        <div class="sparkline txt-color-blue hidden-sm hidden-md pull-right" data-sparkline-type="line" data-sparkline-height="33px" data-sparkline-width="70px" data-fill-color="transparent">
                                            257, 200, 210, 300, 270, 363, 130, 187, 250, 247, 270, 363, 247
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                        <div class="easy-pie-chart txt-color-blue" data-percent="23" data-pie-size="50">
                                            <span class="percent percent-sign">23 </span>
                                        </div>
                                        <span class="easy-pie-title">Transfered <i class="fa fa-caret-up icon-color-good"></i></span>
                                        <ul class="smaller-stat hidden-sm pull-right">
                                            <li>
                                                <span class="label bg-color-darken">10GB</span>
                                            </li>
                                            <li>
                                                <span class="label bg-color-blueDark"><i class="fa fa-caret-up"></i>10%</span>
                                            </li>
                                        </ul>
                                        <div class="sparkline txt-color-darken hidden-sm hidden-md pull-right" data-sparkline-type="line" data-sparkline-height="33px" data-sparkline-width="70px" data-fill-color="transparent">
                                            200, 210, 363, 247, 300, 270, 130, 187, 250, 257, 363, 247, 270
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                        <div class="easy-pie-chart txt-color-darken" data-percent="36" data-pie-size="50">
                                            <span class="percent degree-sign">36 <i class="fa fa-caret-up"></i></span>
                                        </div>
                                        <span class="easy-pie-title">Temperature <i class="fa fa-caret-down icon-color-good"></i></span>
                                        <ul class="smaller-stat hidden-sm pull-right">
                                            <li>
                                                <span class="label bg-color-red"><i class="fa fa-caret-up"></i>124</span>
                                            </li>
                                            <li>
                                                <span class="label bg-color-blue"><i class="fa fa-caret-down"></i>40 F</span>
                                            </li>
                                        </ul>
                                        <div class="sparkline txt-color-red hidden-sm hidden-md pull-right" data-sparkline-type="line" data-sparkline-height="33px" data-sparkline-width="70px" data-fill-color="transparent">
                                            2700, 3631, 2471, 2700, 3631, 2471, 1300, 1877, 2500, 2577, 2000, 2100, 3000
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- end s1 tab pane -->

                            <div class="tab-pane fade" id="s2">
                                <div class="widget-body-toolbar bg-color-white">

                                    <form class="form-inline" role="form">

                                        <div class="form-group">
                                            <label class="sr-only" for="s123">Show From</label>
                                            <input type="email" class="form-control input-sm" id="s123" placeholder="Show From">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control input-sm" id="s124" placeholder="To">
                                        </div>

                                        <div class="btn-group hidden-phone pull-right">
                                            <a class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown"><i class="fa fa-cog"></i>More <span class="caret"></span></a>
                                            <ul class="dropdown-menu pull-right">
                                                <li>
                                                    <a href="javascript:void(0);"><i class="fa fa-file-text-alt"></i>Export to PDF</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"><i class="fa fa-question-sign"></i>Help</a>
                                                </li>
                                            </ul>
                                        </div>

                                    </form>

                                </div>
                                <div class="padding-10">
                                    <div id="statsChart" class="chart-large has-legend-unique"></div>
                                </div>

                            </div>
                            <!-- end s2 tab pane -->

                            <div class="tab-pane fade" id="s3">

                                <div class="widget-body-toolbar bg-color-white smart-form" id="rev-toggles">

                                    <div class="inline-group">

                                        <label for="gra-0" class="checkbox">
                                            <input type="checkbox" name="gra-0" id="gra-0" checked="checked">
                                            <i></i>Target
                                        </label>
                                        <label for="gra-1" class="checkbox">
                                            <input type="checkbox" name="gra-1" id="gra-1" checked="checked">
                                            <i></i>Actual
                                        </label>
                                        <label for="gra-2" class="checkbox">
                                            <input type="checkbox" name="gra-2" id="gra-2" checked="checked">
                                            <i></i>Signups
                                        </label>
                                    </div>

                                    <div class="btn-group hidden-phone pull-right">
                                        <a class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown"><i class="fa fa-cog"></i>More <span class="caret"></span></a>
                                        <ul class="dropdown-menu pull-right">
                                            <li>
                                                <a href="javascript:void(0);"><i class="fa fa-file-text-alt"></i>Export to PDF</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);"><i class="fa fa-question-sign"></i>Help</a>
                                            </li>
                                        </ul>
                                    </div>

                                </div>

                                <div class="padding-10">
                                    <div id="flotcontainer" class="chart-large has-legend-unique"></div>
                                </div>
                            </div>
                            <!-- end s3 tab pane -->
                        </div>

                        <!-- end content -->
                    </div>

                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->

        </article>
    </div>
</div>
<?php
        }
    }
endif;