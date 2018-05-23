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

$document->addScript('plugins/tjdashboardrenderer/tabulator/assets/js/jquery-ui.min.js');
$document->addStylesheet('plugins/tjdashboardrenderer/tabulator/assets/css/tabulator_semantic-ui.min.css');
$document->addStylesheet('components/com_tjdashboard/assets/css/dashboard.css');
$document->addStylesheet('plugins/tjdashboardrenderer/countbox/assets/css/countbox.css');
$document->addStylesheet('media/techjoomla_strapper/css/bootstrap.j3.min.css');
$document->addScript('components/com_tjdashboard/assets/js/tjDashboardService.min.js');
$document->addScript('components/com_tjdashboard/assets/js/tjDashboardUI.min.js');
$document->addStylesheet('media/com_tjdashboard/css/tjdashboard-sb-admin.css');
?>

<script>
jQuery(document).ready(function() {
		TJDashboardUI.initDashboard(<?php echo $this->item->dashboard_id; ?>);
	});
</script>

<div class="row-fluid">
	<div class="col-xs-12 tjdashboard" style="margin-left:10px;">
	</div>
</div>
