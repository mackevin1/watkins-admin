<?php

// must always include library.php
include_once 'library.php';

// include page header with no banner and menu
include_once 'layout_headerblank.php';

?>
<link rel="stylesheet" type="text/css" media="screen,print" href="ks_styles/jquery.easy-pie-chart.css" />
<script src="ks_scripts/jquery.easypiechart.min.js"></script>
 <div class="">
  <div class="chart">
    <div data-percent="55" class="percentage easyPieChart" style="width: 110px; height: 110px; line-height: 110px;"><span>54</span>%<canvas height="137" width="137" style="width: 110px; height: 110px;"></canvas></div>
    <div class="label">New visitors</div>
  </div>
  <div class="chart">
    <div data-percent="21" class="percentage easyPieChart" style="width: 110px; height: 110px; line-height: 110px;"><span>20</span>%<canvas height="137" width="137" style="width: 110px; height: 110px;"></canvas></div>
    <div class="label">Bounce rate</div>
  </div>
</div>

<script>
$(function() {
	var charts = $('.percentage');
	charts.easyPieChart({
		animate: 1000,
	}); 
});
</script>
<style>
.chart {
    float: left;
    margin: 10px;
}
.percentage, .label {
    color: #333333;
    font-size: 1.2em;
    font-weight: 100;
    margin-bottom: 0.3em;
    text-align: center;
}
</style>