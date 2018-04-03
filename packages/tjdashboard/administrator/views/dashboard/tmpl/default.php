<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JHTML::_('behavior.modal');


$document = JFactory::getDocument();
JHtml::stylesheet(Juri::root().'media/techjoomla_strapper/bs3/css/bootstrap.css');

$script  = 'var root_url = "' . Juri::root() . '";';
$document->addScriptDeclaration($script, 'text/javascript');
$document->addScript(Juri::root().'plugins/tjdashboardrenderer/morris/assets/js/raphael.min.js');
$document->addScript(Juri::root().'plugins/tjdashboardrenderer/morris/assets/js/morris.min.js');
$document->addScript(Juri::root().'plugins/tjdashboardrenderer/morris/assets/js/renderer.js');
$document->addScript(Juri::root().'plugins/tjdashboardrenderer/tabulator/assets/js/jquery-ui.min.js');
$document->addScript(Juri::root().'plugins/tjdashboardrenderer/tabulator/assets/js/tabulator.min.js');
$document->addStylesheet('plugins/tjdashboardrenderer/tabulator/assets/css/tabulator_semantic-ui.min.css');
$document->addStylesheet(Juri::root().'components/com_tjdashboard/assets/css/dashboard.css');
$document->addStylesheet(Juri::root().'plugins/tjdashboardrenderer/countbox/assets/css/countbox.css');
$document->addStylesheet(Juri::root().'media/techjoomla_strapper/css/bootstrap.j3.min.css');

$document->addScript(Juri::root().'plugins/tjdashboardrenderer/tabulator/assets/js/renderer.js');
$document->addScript(Juri::root().'components/com_tjdashboard/assets/js/tjDashboardService.js');
$document->addScript(Juri::root().'components/com_tjdashboard/assets/js/tjDashboardUI.js');

$document->addScript('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js');
$document->addScript(Juri::root().'plugins/tjdashboardrenderer/chartjs/assets/js/renderer.js');
$document->addScript(Juri::root().'plugins/tjdashboardrenderer/countbox/assets/js/renderer.js');
$document->addScript(Juri::root().'plugins/tjdashboardrenderer/numbercardbox/assets/js/renderer.js');

?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<div class="<?php echo COM_TJLMS_WRAPPER_DIV;?>">
	<?php
	ob_start();
	include JPATH_BASE . '/components/com_tjlms/layouts/header.sidebar.php';
	$layoutOutput = ob_get_contents();
	ob_end_clean();
	echo $layoutOutput;
	?>

	<!-- TJ Bootstrap3 -->
	<div class="tjBs3">
		<!-- TJ Dashboard -->
		<div class="tjDB">


			<div class="row-fluid">
				<div class="span12 tjdashboard" style="margin-left:10px;">
				</div>
			</div>


		</div><!--CLASS tjDB ends-->
	</div><!--CLASS tjBs3 ends-->

	</div><!--j-main-container" // coming from header.sidebar-->
	</div><!--row-fluid // coming from header.sidebar-->
</div>


<script>
jQuery(document).ready(function() {
		TJDashboardUI.initDashboard(<?php echo $this->item->dashboard_id; ?>);
	});
</script>

<!-- <div class="row-fluid">
	<div class="span9 tjdashboard" style="margin-left:10px;">
	</div>
</div>
 -->