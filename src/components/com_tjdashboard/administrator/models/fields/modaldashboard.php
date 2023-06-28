<?php
/**
 * @package     TJDashboard
 * @subpackage  com_tjdashboard
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Language\Text;

JLoader::import('components.com_tjdashboard.includes.tjdashboard', JPATH_ADMINISTRATOR);

/**
 * Supports a modal dashboard picker.
 *
 * @since  1.0.0
 */
class JFormFieldModaldashboard extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 * @since   1.0.0
	 */
	protected $type = 'Modaldashboard';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0.0
	 */
	protected function getInput()
	{
		$allowClear  = ((string) $this->element['clear'] != 'false');
		$allowSelect = ((string) $this->element['select'] != 'false');

		// Load language
		Factory::getLanguage()->load('com_tjdashboard', JPATH_ADMINISTRATOR);

		// The active dashboard id field.
		$value = ($this->value > 0 ? (int) $this->value : '');

		// Create the modal id.
		$modalId = 'Dashboard_' . $this->id;

		// Add the modal field script to the document head.
		HTMLHelper::_('jquery.framework');
		HTMLHelper::_('script', 'system/modal-fields.js', array('version' => 'auto', 'relative' => true));

		 /** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
		 $wa = Factory::getApplication()->getDocument()->getWebAssetManager();

		 // Add the modal field script to the document head.
		 $wa->useScript('field.modal-fields');
 
		// Script to proxy the select modal function to the modal-fields.js file.
		$scriptSelect = array();
		
		if (!isset($scriptSelect[$this->id])) {
			$wa->addInlineScript(
				"
			window.jSelectDashboard_" . $this->id . " = function (id, title, catid, object, url, language) {
				window.processModalSelect('Dashboard', '" . $this->id . "', id, title, catid, object, url, language);
			}",
				[],
				['type' => 'module']
			);

			Text::script('JGLOBAL_ASSOCIATIONS_PROPAGATE_FAILED');

			$scriptSelect[$this->id] = true;
		}

		// Setup variables for display.
		$linkDashboards = 'index.php?option=com_tjdashboard&amp;view=dashboards&amp;layout=modal&amp;tmpl=component&amp;' . Session::getFormToken() . '=1';
		$linkDashboard  = 'index.php?option=com_tjdashboard&amp;view=dashboard&amp;' . Session::getFormToken() . '=1';
		$modalTitle   = Text::_('COM_TJDASHBOARD_CHANGE_DASHBOARD');

		if (isset($this->element['language']))
		{
			$linkDashboard   .= '&amp;forcedLanguage=' . $this->element['language'];
			$modalTitle     .= ' &#8212; ' . $this->element['label'];
		}

		$urlSelect = $linkDashboards . '&amp;function=jSelectDashboard_' . $this->id;

		if ($value)
		{
			$obj = new TjdashboardDashboard;
			$obj->load($value);

			try
			{
				$title = $obj->title;
			}
			catch (\Exception $e)
			{
				// Get a handle to the Joomla! application object
				$application = Factory::getApplication();

				// Add a message to the message queue
				$application->enqueueMessage($e->getMessage(), 'error');
			}
		}

		$title = empty($title) ? Text::_('COM_TJDASHBOARD_WIDGET_FORM_LBL_DASHBOARDID') : htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The current dashboard display field.
		$html  = '<span class="input-append">';
		$html .= '<input class="input-medium" id="' . $this->id . '_name" type="text" value="' . $title . '" disabled="disabled" size="35" />';

		// Select dashboard button
		if ($allowSelect)
		{
			$html .= '<a'
				. ' class="btn hasTooltip' . ($value ? ' hidden' : '') . '"'
				. ' id="' . $this->id . '_select"'
				. ' data-bs-toggle="modal"'
				. ' role="button"'
				. ' href="#ModalSelect' . $modalId . '"'
				. ' title="' . HTMLHelper::tooltipText('COM_TJDASHBOARD_CHANGE_DASHBOARD') . '">'
				. '<span class="icon-file" aria-hidden="true"></span> ' . Text::_('JSELECT')
				. '</a>';
		}

		// Clear dashboard button
		if ($allowClear)
		{
			$html .= '<a'
				. ' class="btn' . ($value ? '' : ' hidden') . '"'
				. ' id="' . $this->id . '_clear"'
				. ' href="#"'
				. ' onclick="window.processModalParent(\'' . $this->id . '\'); return false;">'
				. '<span class="icon-remove" aria-hidden="true"></span>' . Text::_('JCLEAR')
				. '</a>';
		}

		$html .= '</span>';

		// Select dashboard modal
		if ($allowSelect)
		{
			$html .= HTMLHelper::_(
				'bootstrap.renderModal',
				'ModalSelect' . $modalId,
				array(
					'title'       => $modalTitle,
					'url'         => $urlSelect,
					'height'      => '400px',
					'width'       => '800px',
					'bodyHeight'  => '70',
					'modalWidth'  => '80',
					'footer'      => '<a role="button" class="btn" data-dismiss="modal" aria-hidden="true">' . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>',
				)
			);
		}

		$class = $this->required ? ' class="required modal-value"' : '';

		$html .= '<input type="hidden" id="' . $this->id . '_id"' . $class . ' data-required="' . (int) $this->required . '" name="' . $this->name
			. '" data-text="' . htmlspecialchars(Text::_('COM_TJDASHBOARD_CHANGE_DASHBOARD', true), ENT_COMPAT, 'UTF-8') . '" value="' . $value . '" />';

		return $html;
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   1.0.0
	 */
	protected function getLabel()
	{
		return str_replace($this->id, $this->id . '_id', parent::getLabel());
	}
}
