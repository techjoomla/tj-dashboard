<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
/**
 * View to edit
 *
 * @since  1.0.0
 */
class TjdashboardViewDashboard extends JViewLegacy
{
	/**
	 * The JForm object
	 *
	 * @var  JForm
	 */
	protected $form;

	/**
	 * The dashboard helper
	 *
	 * @var  object
	 */
	protected $tjdashboardHelper;

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
		$this->input = JFactory::getApplication()->input;
		$this->canDo = JHelperContent::getActions('com_tjdashboard', 'dashboard', $this->item->dashboard_id);

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
		$user       = JFactory::getUser();
		$userId     = $user->id;
		$isNew      = ($this->item->dashboard_id == 0);
		JLoader::import('administrator.components.com_tjdashboard.helpers.dashboard', JPATH_SITE);

		$this->tjdashboardHelper = new DashboardHelper;

		// Check permission
		$canDo = $this->canDo;
		$checkedOut = $this->isCheckedOut($userId);
		$itemEditable = $this->isEditable($canDo, $userId);

		// Built the actions for new and existing records.
		$layout = JFactory::getApplication()->input->get("layout");

		$this->sidebar = JHtmlSidebar::render();

		// For new records, check the create permission.
		if ($layout != "default")
		{
			JFactory::getApplication()->input->set('hidemainmenu', true);

			JToolbarHelper::title(
				JText::_('COM_TJDASHBOARD_PAGE_' . ($checkedOut ? 'VIEW_DASHBOARD' : ($isNew ? 'ADD_DASHBOARD' : 'EDIT_DASHBOARD'))),
				'pencil-2 dashboard-add'
			);

			// If not checked out, can save the item.
			if (!$checkedOut && $itemEditable)
			{
				JToolBarHelper::apply('dashboard.apply');
				JToolBarHelper::save('dashboard.save');
				JToolBarHelper::save2new('dashboard.save2new');

				JToolbarHelper::cancel('dashboard.cancel');
			}
			else
			{
				JToolbarHelper::cancel('dashboard.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		else
		{
			JToolbarHelper::title(
				JText::_('COM_TJDASHBOARD_PAGE_VIEW_DASHBOARD')
			);

			$app = JFactory::getApplication();

			JLoader::import('administrator.components.com_tjdashboard.helpers.dashboard', JPATH_SITE);
			DashboardHelper::addSubmenu('dashboard');

			if ($app->isAdmin())
			{
				$this->sidebar = JHtmlSidebar::render();
			}
		}

		JToolbarHelper::divider();
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
			JToolbarHelper::save('dashboard.save');
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
		return ($canDo->get('core.create') || $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId));
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
		JText::script('COM_TJDASHBOARD_WIDGETS_NOTSHOW_ERROR_MESSAGE');
		JText::script('COM_TJDASHBOARD_NO_DATA_AVAILABLE_MESSAGE');
	}
}
