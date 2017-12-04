<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

JLoader::import("/components/com_tjdashboard/includes/tjdashboard", JPATH_ADMINISTRATOR);
/**
 * Class TjdashboardController
 *
 * @since  1.6
 */
class TjdashboardController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   mixed    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController   This object to support chaining.
	 *
	 * @since    1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT . '/helpers/dashboard.php';
		$app  = JFactory::getApplication();
		$view = $app->input->getCmd('view', 'dashboards');
		$app->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
}
