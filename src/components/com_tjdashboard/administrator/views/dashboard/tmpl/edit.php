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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Language\Text;

use Joomla\Registry\Registry;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
$app = Factory::getApplication();
$input = $app->input;

// In case of modal
$isModal = $input->get('layout') == 'modal' ? true : false;
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';

// JFactory::getDocument()->addScriptDeclaration('
// 	Joomla.submitbutton = function(task)
// 	{
// 		if (task == "dashboard.cancel" || document.formvalidator.isValid(document.getElementById("dashboard-form")))
// 		{
// 			jQuery("#permissions-sliders select").attr("disabled", "disabled");
// 			' . $this->form->getField('description')->save() . '
// 			Joomla.submitform(task, document.getElementById("dashboard-form"));
// 		}
// 	};
// ');
?>
<div class="">
	<form action="<?php echo Route::_('index.php?option=com_tjdashboard&view=dashboard&layout=edit&dashboard_id=' . (int) $this->item->dashboard_id, false);?>" method="post" enctype="multipart/form-data" name="adminForm" id="dashboard-form" class="form-validate">
		<div class="form-horizontal">
		<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>
		<?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'general', Text::_('COM_TJDASHBOARD_TITLE_DASHBOARD')); ?>
		<div class="row-fluid">
			<div class="span9">
				<fieldset class="adminform">
					<?php echo $this->form->getInput('description'); ?>
				</fieldset>
			</div>
			<div class="span3">
				<?php echo $this->form->getLabel('created_by'); ?>
				<?php echo $this->form->getInput('created_by'); ?>

				<?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?>

				<?php echo $this->form->getLabel('access'); ?>
				<?php echo $this->form->getInput('access'); ?>

				<?php echo $this->form->getLabel('context'); ?>
				<?php echo $this->form->getInput('context'); ?>

				<?php echo $this->form->getLabel('parent'); ?>
				<?php echo $this->form->getInput('parent'); ?>

				<?php echo $this->form->getInput('modified_on'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>
				<?php echo $this->form->getInput('ordering'); ?>
				<?php echo $this->form->getInput('checked_out'); ?>
				<?php echo $this->form->getInput('checked_out_time'); ?>
			</div>
		</div>
		<?php echo HTMLHelper::_('bootstrap.endTab'); ?>
		<?php /*if ($this->canDo->get('core.admin')) : ?>
			<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'permissions', Text::_('COM_TJDASHBOARD_FIELDSET_RULES')); ?>
				<?php echo $this->form->getInput('rules'); ?>
			<?php echo HTMLHelper::_('bootstrap.endTab'); ?>
		<?php endif;*/ ?>

		<input type="hidden" name="task" value="" />
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
	</form>
</div>

