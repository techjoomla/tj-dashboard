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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'dashboard.cancel')
		{
			console.log(task);
			Joomla.submitform(task, document.getElementById('dashboard-form'));
		}
		else
		{
			if (task != 'dashboard.cancel' && document.formvalidator.isValid(document.id('dashboard-form')))
			{
				Joomla.submitform(task, document.getElementById('dashboard-form'));
			}
			else
			{
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<div class="">
	<form
		action="<?php echo JRoute::_('index.php?option=com_tjdashboard&view=dashboard&layout=edit&id=' . (int) $this->item->dashboard_id, false);?>"
		method="post" enctype="multipart/form-data" name="adminForm" id="dashboard-form" class="form-validate">

		<div class="form-horizontal">
			<div class="row-fluid">
				<div class="span12 form-horizontal">
				</div>
			</div>
			<?php
				echo $this->form->renderFieldset();
			?>
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>

