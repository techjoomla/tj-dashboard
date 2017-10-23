<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Tjdashboard model class for dashboard
 *
 * @since  1.0.0
 */
class TjdashboardModelDashboard extends JModelAdmin
{
	/**
	 * @var		object	The Agency data.
	 * @since   1.0
	 */
	protected $data;

	/**
	 * Method to get the job form data.
	 *
	 * @param   int  $pk  An optional array of data for the form to interogate.
	 *
	 * @return  mixed  	Data object on success, false on failure.
	 *
	 * @since   1.0
	 */
	public function getItem($pk = null)
	{
		if ($this->data === null)
		{
			return parent::getItem($pk);
		}

		return $this->data;
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
	public function getTable($type = 'dashboards', $prefix = 'TjDashboardTable', $config = array())
	{
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_tjdashboard/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML and then an event is fired
	 * for users plugins to extend the form with extra fields.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 *
	 * @since   1.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_tjdashboard.dashboard', 'dashboard', array('control' => 'jform', 'load_data' => $loadData));

		return (empty($form)) ? false : $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.0
	 */
	protected function loadFormData()
	{
		return $this->getItem();
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
		$table = $this->getTable();

		if ($table->save($data) === true)
		{
			$this->setState('dashboard_id', $table->dashboard_id);

			return true;
		}
		else
		{
			return false;
		}
	}
}
