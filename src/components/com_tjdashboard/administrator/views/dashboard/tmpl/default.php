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

$document->addScript(Juri::root() . 'plugins/tjdashboardrenderer/tabulator/assets/js/jquery-ui.min.js');
$document->addStyleSheet(JUri::root() . '/media/techjoomla_strapper/bs3/css/bootstrap.css');
$document->addStyleSheet(JUri::root() . 'media/com_tjdashboard/css/font-awesome.min.css');
$document->addStylesheet(Juri::root() . 'plugins/tjdashboardrenderer/tabulator/assets/css/tabulator_semantic-ui.min.css');
$document->addStylesheet(Juri::root() . 'components/com_tjdashboard/assets/css/dashboard.css');
$document->addStylesheet(Juri::root() . 'plugins/tjdashboardrenderer/countbox/assets/css/countbox.css');

$document->addScript(Juri::root() . 'components/com_tjdashboard/assets/js/tjDashboardService.min.js');
$document->addScript(Juri::root() . 'components/com_tjdashboard/assets/js/tjDashboardUI.min.js');
$document->addStylesheet(Juri::root() . 'media/com_tjdashboard/css/tjdashboard-sb-admin.css');
?>

<script>
jQuery(document).ready(function() {
	TJDashboardUI.initDashboard(<?php echo $this->item->dashboard_id;?>);
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
				<div class="tjdashboard" >
				</div>
			</div>
	</div>
</div>