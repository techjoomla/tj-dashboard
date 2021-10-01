<?php
/**
 * @package     TJDashboard
 * @subpackage  com_tjdashboard
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;
use Joomla\CMS\Factory;

JHTML::_('behavior.modal');


$document = Factory::getDocument();

$document->addStylesheet('components/com_tjdashboard/assets/css/dashboard.css');
$document->addStylesheet('media/techjoomla_strapper/css/bootstrap.j3.min.css');
$document->addScript('components/com_tjdashboard/assets/js/tjDashboardService.min.js');
$document->addScript('components/com_tjdashboard/assets/js/tjDashboardUI.min.js');
$document->addStylesheet('media/com_tjdashboard/css/tjdashboard-sb-admin.css');
?>

<script>
jQuery(document).ready(function() {
		TJDashboardUI.initDashboard(<?php echo ($this->item->state == 1? $this->item->dashboard_id : 0); ?>);
	});
</script>

<div class="<?php echo COM_TJDASHBOARD_WRAPPER_DIV;?>">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 tjdashboard">
				<h3>
					<div data-dashboard-id="<?php echo $this->item->dashboard_id;?>" class="tjdashboard-title"><?php echo htmlspecialchars($this->item->title);?></div>
				</h3>
			</div>
		</div>
	</div>
</div>
