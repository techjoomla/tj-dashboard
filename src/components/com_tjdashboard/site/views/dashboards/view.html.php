<?php
/**
 * @package     TJDashboard
 * @subpackage  com_tjdashboard
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Dashboards view
 *
 * @since  1.0.0
 */
class TjdashboardViewDashboards extends HtmlView
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
		$this->user            = Factory::getUser();

		$this->canCreate       = $this->user->authorise('core.content.create', 'com_tjdashboard');
		$this->canEdit         = $this->user->authorise('core.content.edit', 'com_tjdashboard');
		$this->canCheckin      = $this->user->authorise('core.content.manage', 'com_tjdashboard');
		$this->canChangeStatus = $this->user->authorise('core.content.edit.state', 'com_tjdashboard');
		$this->canDelete       = $this->user->authorise('core.content.delete', 'com_tjdashboard');

		// Display the view
		parent::display($tpl);
	}

	/**
	 * Method to order fields
	 *
	 * @return ARRAY
	 */
	protected function getSortFields()
	{
		return array(
			'dash.dashboard_id' => Text::_('JGRID_HEADING_ID'),
			'dash.title' => Text::_('COM_DASHBOARD_LIST_DASHBOARDS_TITLE'),
			'dash.ordering' => Text::_('JGRID_HEADING_ORDERING'),
			'dash.state' => Text::_('JSTATUS'),
		);
	}
}
