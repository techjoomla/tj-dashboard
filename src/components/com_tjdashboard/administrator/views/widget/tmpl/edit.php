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

use Joomla\Registry\Registry;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
$app = JFactory::getApplication();
$input = $app->input;
$document = JFactory::getDocument();
$document->addScript(Juri::root() . 'components/com_tjdashboard/assets/js/tjDashboardService.js');
$document->addScript(Juri::root() . 'components/com_tjdashboard/assets/js/tjDashboardUI.js');

// In case of modal
$isModal = $input->get('layout') == 'modal' ? true : false;
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';

JFactory::getDocument()->addScriptDeclaration('
	jQuery(document).ready(function(){TJDashboardUI._setRenderers()});
	Joomla.submitbutton = function(task)
	{
		if (task == "widget.cancel" || document.formvalidator.isValid(document.getElementById("widget-form")))
		{
			if (task != "widget.cancel" && jQuery("#jform_params").val())
			{
				try{
					var params = JSON.stringify(JSON.parse(jQuery("#jform_params").val()));
					jQuery("#jform_params").val(params)
				}catch(e){
					alert(Joomla.JText._("COM_TJDASHBOARD_WIDGET_INVALID_JSON_VALUE"));
					return false;
				}
			}
			jQuery("#permissions-sliders select").attr("disabled", "disabled");
			Joomla.submitform(task, document.getElementById("widget-form"));
		}
	};
');
?>
<div class="">
	<form action="<?php echo JRoute::_('index.php?option=com_tjdashboard&view=dashboard&layout=edit&dashboard_widget_id=
	' . (int) $this->item->dashboard_widget_id, false
	);?>" method="post" enctype="multipart/form-data" name="adminForm" id="widget-form" class="form-validate tjdashForm">
		<div class="form-horizontal">
		<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<div class="row-fluid">
			<div class="span9">
				<fieldset class="adminform">
					<?php
						echo $this->form->renderField('dashboard_id');
						echo $this->form->renderField('state');

						echo $this->form->renderField('data_plugin');

						echo $this->form->renderField('renderer_plugin');

						echo $this->form->renderField('primary_text', null, null, ['class' => 'hidden']);

						echo $this->form->renderField('secondary_text', null, null, ['class' => 'hidden']);

						echo $this->form->renderField('color', null, null, ['class' => 'hidden']);

						echo $this->form->renderField('size');

						echo $this->form->renderField('autorefresh');

						echo $this->form->renderField('params');

						echo $this->form->renderField('created_by');
						echo $this->form->getInput('ordering');
						echo $this->form->getInput('modified_on');
						echo $this->form->getInput('modified_by');
						echo $this->form->getInput('checked_out');
						echo $this->form->getInput('checked_out_time');
						?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
		<input type="hidden" id="task" name="task" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	</form>
</div>

