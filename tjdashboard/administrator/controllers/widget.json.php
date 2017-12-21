<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');
/**
 * The widget controller
 *
 * @since  1.6
 */
class TjDashboardControllerWidget extends JControllerForm
{
	/**
	 * Constructor
	 */

	public function __construct()
	{
		$this->view_list = 'widgets';
		parent::__construct();
	}

	/**
	 * Function to get all the respective renderers for given data source
	 *
	 * @return  object  object
	 */
	public function getSupportedRenderers()
	{
		try
		{
			$app     = JFactory::getApplication();
			$jinput  = $app->input;
			$jformData   = $jinput->post->get('jform', array(), 'ARRAY');
			$dataPlugin = explode('.', $jformData['data_plugin']);
			$pluginFolder = $dataPlugin[0] . '/' . $dataPlugin[0];
			$pluginFileName = $dataPlugin[1];
			require_once JPATH_PLUGINS . '/tjdashboardsource/' . $pluginFolder . '/' . $pluginFileName . '.php';
			$className = ucfirst($dataPlugin[0]) . ucfirst($pluginFileName) . 'Datasource';
			$dataSourceObject = new $className;
			$renderers = $dataSourceObject->getSupportedRenderers();
			echo new JResponseJson($renderers);
		}
		catch (Exception $e)
		{
			echo new JResponseJson($e);
		}
	}
}
