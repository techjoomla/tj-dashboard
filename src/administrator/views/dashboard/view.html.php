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
	protected $state;

	protected $item;

	protected $form;

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

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		// Get ACL actions
		$this->user            = JFactory::getUser();

		$this->canCreate       = $this->user->authorise('core.content.create', 'com_tjdashboard');
		$this->canEdit         = $this->user->authorise('core.content.edit', 'com_tjdashboard');
		$this->canCheckin      = $this->user->authorise('core.content.manage', 'com_tjdashboard');
		$this->canChangeStatus = $this->user->authorise('core.content.edit.state', 'com_tjdashboard');
		$this->canDelete       = $this->user->authorise('core.content.delete', 'com_tjdashboard');

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

		$user = JFactory::getUser();
		$isNew = ($this->item->dashboard_id == 0);

		if ($isNew)
		{
			$viewTitle = JText::_('COM_TJDASHBOARD_VIEW_ADD_DASHBOARD');
		}
		else
		{
			$viewTitle = JText::_('COM_TJDASHBOARD_VIEW_EDIT_DASHBOARD');
		}

		JToolBarHelper::title($viewTitle, 'pencil-2');

		if (isset($this->item->checked_out))
		{
			$checkedOut = ! ($this->item->checked_out == 0 || $this->item->checked_out == $user->id);
		}
		else
		{
			$checkedOut = false;
		}

		// If not checked out, can save the item.
		if (! $checkedOut && ($this->canEdit || $this->canCreate))
		{
			JToolBarHelper::apply('dashboard.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('dashboard.save', 'JTOOLBAR_SAVE');
		}

		if (empty($this->item->dashboard_id))
		{
			JToolBarHelper::cancel('dashboard.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::cancel('dashboard.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
