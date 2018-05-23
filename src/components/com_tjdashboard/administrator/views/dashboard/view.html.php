<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
/**
 * View to edit
 *
 * @since  1.6
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

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$user       = JFactory::getUser();
		$userId     = $user->id;
		$isNew      = ($this->item->dashboard_id == 0);
		JLoader::import('administrator.components.com_tjdashboard.helpers.dashboard', JPATH_SITE);

		$this->tjdashboardHelper = new DashboardHelper;
		$checkedOut = $this->isCheckedOut($userId);

		// Built the actions for new and existing records.
		$canDo = $this->canDo;
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

			if ($isNew)
			{
				JToolbarHelper::save('dashboard.save');
				JToolbarHelper::cancel('dashboard.cancel');
			}
			else
			{
				$itemEditable = $this->isEditable($canDo, $userId);

				// Can't save the record if it's checked out and editable
				$this->canSave($checkedOut, $itemEditable);
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
}
