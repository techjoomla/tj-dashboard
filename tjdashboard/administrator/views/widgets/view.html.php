<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Dashboards view
 *
 * @since  1.6
 */
class TjdashboardViewWidgets extends JViewLegacy
{
	/**
	 * An array of items
	 *
	 * @var  array
	 */
	protected $items;

	/**
	 * The pagination object
	 *
	 * @var  JPagination
	 */
	protected $pagination;

	/**
	 * The model state
	 *
	 * @var  object
	 */
	protected $state;

	/**
	 * Form object for search filters
	 *
	 * @var  JForm
	 */
	public $filterForm;

	/**
	 * Logged in User
	 *
	 * @var  JObject
	 */
	public $user;

	/**
	 * The active search filters
	 *
	 * @var  array
	 */
	public $activeFilters;

	/**
	 * The sidebar markup
	 *
	 * @var  string
	 */
	protected $sidebar;

	/**
	 * The access varible
	 *
	 * @var  int
	 */
	protected $canCreate;

	/**
	 * The access varible
	 *
	 * @var  int
	 */
	protected $canEdit;

	/**
	 * The access varible
	 *
	 * @var  int
	 */
	protected $canCheckin;

	/**
	 * The access varible
	 *
	 * @var  int
	 */
	protected $canChangeStatus;

	/**
	 * The access varible
	 *
	 * @var  int
	 */
	protected $canDelete;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		// Get state
		$this->state = $this->get('State');

		// This calls model function getItems()
		$this->items = $this->get('Items');

		// Get pagination
		$this->pagination = $this->get('Pagination');

		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// Get ACL actions
		$this->user            = JFactory::getUser();

		$this->canCreate       = $this->user->authorise('core.content.create', 'com_tjdashboard');
		$this->canEdit         = $this->user->authorise('core.content.edit', 'com_tjdashboard');
		$this->canCheckin      = $this->user->authorise('core.content.manage', 'com_tjdashboard');
		$this->canChangeStatus = $this->user->authorise('core.content.edit.state', 'com_tjdashboard');
		$this->canDelete       = $this->user->authorise('core.content.delete', 'com_tjdashboard');

		// Add submenu
		DashboardHelper::addSubmenu('widgets');

		// Add Toolbar
		$this->addToolbar();

		// Set sidebar
		$this->sidebar = JHtmlSidebar::render();

		// Display the view
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');

		JToolBarHelper::title(JText::_('COM_TJDASHBOARD_VIEW_WIDGETS'), 'stack article');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/widget';

		if (file_exists($formPath))
		{
			if ($this->canCreate)
			{
				JToolBarHelper::addNew('widget.add', 'JTOOLBAR_NEW');
				JToolbarHelper::custom('widget.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
			}

			if ($this->canEdit && isset($this->items[0]))
			{
				JToolBarHelper::editList('widget.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($this->canChangeStatus)
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('widgets.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('widgets.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'widgets.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('widgets.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('widgets.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $this->canDelete)
			{
				JToolBarHelper::deleteList('', 'widgets.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($this->canChangeStatus)
			{
				JToolBarHelper::trash('widgets.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}
	}

	/**
	 * Method to order fields
	 *
	 * @return ARRAY
	 */
	protected function getSortFields()
	{
		return array(
			'wid.dashboard_widget_id' => JText::_('JGRID_HEADING_ID'),
			'wid.title' => JText::_('COM_DASHBOARD_LIST_DASHBOARDS_TITLE'),
			'wid.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'wid.state' => JText::_('JSTATUS'),
		);
	}
}
