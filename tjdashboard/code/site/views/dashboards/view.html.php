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

jimport('joomla.application.component.view');

/**
 * View to edit
 *
 * @since  1.6
 */
class TjdashboardViewDashboards extends JViewLegacy
{
	protected $state;

	protected $items;

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
		$this->userid = JFactory::getUser()->id;

		$this->items = $this->get('Items');
		$this->state	= $this->get('State');
		$this->pagination = $this->get('Pagination');

		parent::display($tpl);
	}
}
