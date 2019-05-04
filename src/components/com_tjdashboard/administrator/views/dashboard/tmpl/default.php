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


$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . '/media/techjoomla_strapper/bs3/css/bootstrap.css');
$document->addStylesheet(Juri::root() . 'components/com_tjdashboard/assets/css/dashboard.css');
$document->addScript(Juri::root() . 'components/com_tjdashboard/assets/js/tjDashboardService.min.js');
$document->addScript(Juri::root() . 'components/com_tjdashboard/assets/js/tjDashboardUI.min.js');
$document->addStylesheet(Juri::root() . 'media/com_tjdashboard/css/tjdashboard-sb-admin.css');
?>

<script>
jQuery(document).ready(function() {
	TJDashboardUI.initDashboard(<?php echo ($this->item->state == 1? $this->item->dashboard_id : 0); ?>);
});
</script>

<div class="tjBs3 tjlms-wrapper">
	<div class="row-fluid">
		<?php if (!empty( $this->sidebar))
		{
		?>
			 <div id="j-sidebar-container" class="span2">
				<?php echo $this->sidebar; ?>
			</div>
			<div  id="j-main-container" class="span10">
		<?php
		}
		else
		{
		?>
			<div id="j-main-container" class="span12">
		<?php
		}
		?>
				<div class="tjdashboardloadingwidget">
					<div class="bounce1"></div>
					<div class="bounce2"></div>
					<div class="bounce3"></div>
				</div>
				<div class="tjdashboard" >
				</div>
			</div>
	</div>
</div>
