

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<div id="bar-graph-<?php echo $widgetDetails->dashboard_widget_id ;?>" style="height: 250px;width: 250px;" ></div>

<style>
#bar-graph{
  min-height: 250px;
}</style>
<script>
jQuery(document).ready(function(){
	morris_bar();
});
function morris_bar()
{
Morris.Bar({
  element: 'bar-graph-<?php echo $widgetDetails->dashboard_widget_id ;?>',
  data: <?php echo json_encode($dataForChart->barData); ?>,
  xkey: 'x',
  ykeys: ['y'],
  labels: ['Y'],
  barColors: 'rgb(0, 84, 141)',
  barSize: '30',
  yLabelMargin: 10,
  //~ barSpace: '20'
});
};
</script>
