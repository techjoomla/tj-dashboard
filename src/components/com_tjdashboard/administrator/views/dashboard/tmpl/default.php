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


$document = JFactory::getDocument();
$script  = 'var root_url = "' . Juri::root() . '";';
$document->addScriptDeclaration($script, 'text/javascript');
$document->addScript(Juri::root().'plugins/tjdashboardrenderer/tabulator/assets/js/jquery-ui.min.js');
$document->addStyleSheet(JUri::root().'media/com_tjdashboard/css/bootstrap3/css/bootstrap.min.css');
$document->addStylesheet(Juri::root().'plugins/tjdashboardrenderer/tabulator/assets/css/tabulator_semantic-ui.min.css');
$document->addStylesheet(Juri::root().'components/com_tjdashboard/assets/css/dashboard.css');
$document->addStylesheet(Juri::root().'plugins/tjdashboardrenderer/countbox/assets/css/countbox.css');

$document->addScript(Juri::root().'components/com_tjdashboard/assets/js/tjDashboardService.js');
$document->addScript(Juri::root().'components/com_tjdashboard/assets/js/tjDashboardUI.js');
$document->addStylesheet(Juri::root().'media/com_tjdashboard/css/tjdashboard-sb-admin.css');

?>
<!--
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
<script>
jQuery(document).ready(function() {
	TJDashboardUI.initDashboard(<?php echo $this->item->dashboard_id; ?>);
});
</script>
<div class="tjBs3">
			<!-- TJ Dashboard -->
					<div class="tjDB">
<div class="tj-page">
	<div class="row-fluid">
			<?php if (!empty( $this->sidebar)) : ?>
				<div id="j-sidebar-container" class="span2">
					<?php echo $this->sidebar; ?>
				</div>
				<div id="j-main-container" class="span10">
			<?php else : ?>
				<div id="j-main-container">
			<?php endif; ?>
			<div class="col-xs-12 tjdashboard" style="margin-left:10px;">
			</div>

			</div>
	</div>
</div>
	</div>
</div>

