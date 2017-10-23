<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access.
defined('_JEXEC') or die;

JHTML::_('behavior.modal');

$document = JFactory::getDocument();
$document->addScript('components/com_tjdashboard/assets/js/tjdashboard.js');
?>

<?php if (!$this->userid):	?>

	<div class="alert alert-warning">
		<?php echo JText::_('COM_TJLMS_LOGIN_MESSAGE'); ?>
	</div>

	<?php return false;	?>

<?php endif; ?>

<script>
jQuery(document).ready(function() {
		tjdashboard.getDaashboardData(<?php echo $this->dashboardId; ?>);
	});

</script>

<div class="row-fluid">
	<div class="span12 tjdashboard">
	</div>
</div>
