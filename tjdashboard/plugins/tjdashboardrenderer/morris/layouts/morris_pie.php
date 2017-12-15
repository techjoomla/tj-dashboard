 <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<div id="pie-chart-<?php echo $widgetDetails->dashboard_widget_id ;?>" style="height: 250px;width: 250px;" ></div>

<script>
jQuery(document).ready(function(){
	morris_donut();
});
function morris_donut()
{
	Morris.Donut({
	  element: 'pie-chart-<?php echo $widgetDetails->dashboard_widget_id ;?>',
	  data: [{"label":"Test Course 3","value":"1"},{"label":"Test Course 5","value":"3"},{"label":"Test Course 2","value":"4"},{"label":"Test Course 4","value":"5"}]
	});
};
</script>
