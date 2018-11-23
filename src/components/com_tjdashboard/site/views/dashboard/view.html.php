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

jimport('joomla.application.component.view');

/**
 * View to edit
 *
 * @since  1.0.0
 */
class TjdashboardViewDashboard extends JViewLegacy
{
	protected $item;

	protected $state;

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
		$this->item = $this->get('Item');
		$this->state = $this->get('State');
		$this->item->dashboard_id = $this->state->get('dashboard.dashboard_id');

		$this->getLanguageConstant();

		// @Todo - Add permission based view accessing code
		parent::display($tpl);
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
