<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
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
		<h1>
			<div data-dashboard-id="<?php echo $this->item->dashboard_id;?>" class="tjdashboard-title"><?php echo $this->item->title;?></div>
		</h1>
	</div>
</div>
