<?php

/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;

jimport('joomla.application.component.view');

/**
 * View to edit
 *
 * @since 1.0.0
 */
class TjdashboardViewWidget extends HtmlView
{
	/**
	 * The JForm object
	 *
	 * @var  JForm
	 */
	protected $form;

	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected $item;

	/**
	 * The model state
	 *
	 * @var  object
	 */
	protected $state;

	/**
	 * The actions the user is authorised to perform
	 *
	 * @var  JObject
	 */
	protected $canDo;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->item  = $this->get('Item');
		$this->form  = $this->get('Form');
		$this->canDo = ContentHelper::getActions('com_tjdashboard', 'widget', $this->item->dashboard_widget_id);

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();
		$this->getLanguageConstant();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);
		$user       = Factory::getUser();
		$userId     = $user->id;
		$isNew      = ($this->item->dashboard_widget_id == 0);
		$checkedOut = $this->isCheckedOut($userId);

		// Built the actions for new and existing records.
		$canDo = $this->canDo;

		ToolbarHelper::title(
			Text::_('COM_TJDASHBOARD_PAGE_' . ($checkedOut ? 'VIEW_WIDGET' : ($isNew ? 'ADD_WIDGET' : 'EDIT_WIDGET'))),
			'pencil-2 dashboard-add'
		);

		// For new records, check the create permission.
		if ($isNew)
		{
			ToolbarHelper::save('widget.save');
			ToolbarHelper::cancel('widget.cancel');
		}
		else
		{
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			$itemEditable = $this->isEditable($canDo, $userId);

			// Can't save the record if it's checked out and editable
			$this->canSave($checkedOut, $itemEditable);

			ToolbarHelper::cancel('widget.cancel', 'JTOOLBAR_CLOSE');
		}

		ToolbarHelper::divider();
	}

	/**
	 * Can't save the record if it's checked out and editable
	 *
	 * @param   boolean  $checkedOut    Checked Out
	 *
	 * @param   boolean  $itemEditable  Item editable
	 *
	 * @return void
	 */
	protected function canSave($checkedOut, $itemEditable)
	{
		if (!$checkedOut && $itemEditable)
		{
			ToolbarHelper::save('widget.save');
		}
	}

	/**
	 * Is editable
	 *
	 * @param   Object   $canDo   Checked Out
	 *
	 * @param   integer  $userId  User ID
	 *
	 * @return boolean
	 */
	protected function isEditable($canDo, $userId)
	{
		// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
		return $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId);
	}

	/**
	 * Is Checked Out
	 *
	 * @param   integer  $userId  User ID
	 *
	 * @return boolean
	 */
	protected function isCheckedOut($userId)
	{
		return !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
	}

	/**
	 * Get all jtext for javascript
	 *
	 * @return   void
	 *
	 * @since   1.0
	 */
	public static function getLanguageConstant()
	{
		Text::script('COM_TJDASHBOARD_WIDGET_INVALID_JSON_VALUE');
		Text::script('COM_TJDASHBOARD_WIDGET_FORM_RENDERER_PLUGIN');
		Text::script('COM_TJDASHBOARD_WIDGET_FORM_FULL_WIDTH');
		Text::script('COM_TJDASHBOARD_WIDGET_FORM_HALF_WIDTH');
		Text::script('COM_TJDASHBOARD_WIDGET_FORM_ONE_THIRD_WIDTH');
		Text::script('COM_TJDASHBOARD_WIDGET_FORM_ONE_FOURTH_WIDTH');
		Text::script('COM_TJDASHBOARD_WIDGETS_NOTSHOW_ERROR_MESSAGE');
	}
}
