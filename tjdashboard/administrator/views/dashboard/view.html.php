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
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user       = JFactory::getUser();
		$userId     = $user->id;
		$isNew      = ($this->item->dashboard_id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// Built the actions for new and existing records.
		$canDo = $this->canDo;

		JToolbarHelper::title(
			JText::_('COM_TJDASHBOARD_PAGE_' . ($checkedOut ? 'VIEW_DASHBOARD' : ($isNew ? 'ADD_DASHBOARD' : 'EDIT_DASHBOARD'))),
			'pencil-2 dashboard-add'
		);

		// For new records, check the create permission.
		if ($isNew)
		{
			//JToolbarHelper::apply('dashboard.apply');
			JToolbarHelper::save('dashboard.save');
			//JToolbarHelper::save2new('dashboard.save2new');
			JToolbarHelper::cancel('dashboard.cancel');
		}
		else
		{
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			$itemEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId);

			// Can't save the record if it's checked out and editable
			if (!$checkedOut && $itemEditable)
			{
				//JToolbarHelper::apply('dashboard.apply');
				JToolbarHelper::save('dashboard.save');

				// We can save this record, but check the create permission to see if we can return to make a new one.
				/*if ($canDo->get('core.create'))
				{
					JToolbarHelper::save2new('dashboard.save2new');
				}*/
			}

			// If checked out, we can still save
			/*if ($canDo->get('core.create'))
			{
				JToolbarHelper::save2copy('dashboard.save2copy');
			}*/

			JToolbarHelper::cancel('dashboard.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
	}
}
