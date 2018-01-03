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
$script  = 'var root_url = "' . Juri::root() . '";';
$document->addScriptDeclaration($script, 'text/javascript');

$document->addScript('components/com_tjdashboard/assets/js/tjDashboardService.js');
$document->addScript('components/com_tjdashboard/assets/js/tjDashboardUI.js');

?>
<script>
jQuery(document).ready(function() {
		TJDashboardUI.initDashboard(<?php echo $this->item->dashboard_id; ?>);
	});
</script>

<div class="row-fluid">
	<div class="span12 tjdashboard" style="margin-left:10px;">
	</div>
</div>
