<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;
JLoader::import('components.com_tjdashboard.includes.tjdashboard', JPATH_ADMINISTRATOR);
/**
 * Item Model for an Dashboard.
 *
 * @since  1.6
 */
class TjdashboardModelDashboard extends JModelAdmin
{
	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm|boolean  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_tjdashboard.dashboard', 'dashboard', array('control' => 'jform', 'load_data' => $loadData));

		return empty($form) ? false : $form;
	}

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable    A database object
	 */
	public function getTable($type = 'Dashboards', $prefix = 'TjdashboardTable', $config = array())
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tjdashboard/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	$data  The data for the form.
	 *
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_tjdashboard.edit.dashboard.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 1.6
	 */
	public function save($data)
	{
		$pk   = (!empty($data['dashboard_id'])) ? $data['dashboard_id'] : (int) $this->getState('dashboard.dashboard_id');
		$dashboard = TjdashboardDashboard::getInstance($pk);

		// Bind the data.
		if (!$dashboard->bind($data))
		{
			$this->setError($dashboard->getError());

			return false;
		}

		$result = $dashboard->save();

		// Store the data.
		if (!$result)
		{
			$this->setError($dashboard->getError());

			return false;
		}

		$this->setState('dashboard.dashboard_id', $result);

		return true;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return   void
	 *
	 * @since    1.6
	 */

	protected function populateState()
	{
		$jinput = JFactory::getApplication()->input;
		$id = ($jinput->get('id'))?$jinput->get('id'):$jinput->get('dashboard_id');
		$this->setState('dashboard.dashboard_id', $id);
	}
}
