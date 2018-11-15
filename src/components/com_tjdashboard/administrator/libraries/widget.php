<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <contact@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Unauthorized Access');

/**
 * Widget class.  Handles all application interaction with a Widget
 *
 * @since  1.0.0
 */
class TjdashboardWidget extends JObject
{
	public $dashboard_widget_id = null;

	public $dashboard_id = 0;

	public $title = "";

	public $ordering = 0;

	public $state = 1;

	public $data_plugin = "";

	public $renderer_plugin = "";

	public $size = null;

	public $params = "";

	public $autorefresh = 0;

	public $created_on = null;

	public $created_by = 0;

	public $modified_on = null;

	public $modified_by = 0;

	public $core = 0;

	public $widget_render_data = array();

	public $widget_js = array();

	public $widget_css = array();

	protected static $widgetObj = array();

	/**
	 * Constructor activating the default information of the Widget
	 *
	 * @param   int  $id  The unique event key to load.
	 *
	 * @since   1.0.0
	 */
	public function __construct($id = 0)
	{
		if (!empty($id))
		{
			$this->load($id);
		}
	}

	/**
	 * Returns the global widget object
	 *
	 * @param   integer  $id  The primary key of the widget_id to load (optional).
	 *
	 * @return  Object  Widget object.
	 *
	 * @since   1.0.0
	 */
	public static function getInstance($id = 0)
	{
		// @Todo- Check the comments for this function
		if (!$id)
		{
			return new TjdashboardWidget;
		}

		if (empty(self::$widgetObj[$id]))
		{
			$widget = new TjdashboardWidget($id);
			self::$widgetObj[$id] = $widget;
			self::$widgetObj[$id]->widget_render_data = $widget->getWidgetData($id);
			self::$widgetObj[$id]->widget_js = $widget->getWidgetJS($id);
			self::$widgetObj[$id]->widget_css = $widget->getWidgetCSS($id);
		}

		return self::$widgetObj[$id];
	}

	/**
	 * Method to load a widget object by widget id
	 *
	 * @param   int  $id  The widget id
	 *
	 * @return  boolean  True on success
	 *
	 * @since 1.0.0
	 */
	public function load($id)
	{
		$table = TjdashboardFactory::table("widgets");

		if (!$table->load($id))
		{
			return false;
		}

		$widgetData = $this->getWidgetData($id);

		$this->setProperties($table->getProperties());
		$this->set('widget_render_data', $widgetData);

		return true;
	}

	/**
	 * Method to save the Widget object to the database
	 *
	 * @return  boolean  True on success
	 *
	 * @since 1.0.0
	 * @throws  \RuntimeException
	 */
	public function save()
	{
		// Create the widget table object
		$table = TjdashboardFactory::table("widgets");
		$table->bind($this->getProperties());

		// Allow an exception to be thrown.
		try
		{
			// Check and store the object.
			if (!$table->check())
			{
				$this->setError($table->getError());

				return false;
			}

			// Store the user data in the database
			if (!($result = $table->store()))
			{
				$this->setError($table->getError());

				return false;
			}
		}
		catch (\Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		$this->dashboard_widget_id = $table->dashboard_widget_id;

		return $result;
	}

	/**
	 * Get the widget data
	 *
	 * @param   integer  $id  The primary key of the widget_id to load (optional).
	 *
	 * @return	Array
	 *
	 * @since 	1.0.0
	 **/
	protected function getWidgetData($id)
	{
		$widgetModel = TjdashboardFactory::model("widget", array("ignore_request" => 1));
		/** @scrutinizer ignore-call */
		$widgetData = $widgetModel->getItem($id);
		$result = $this->getWidgetRendererData($widgetData);

		if (count($result) && $result['status'])
		{
			return $result['data'];
		}

		return $result;
	}

	/**
	 * Method to bind an associative array of data to a widget object
	 *
	 * @param   array  &$array  The associative array to bind to the object
	 *
	 * @return  boolean  True on success
	 *
	 * @since 1.0.0
	 */
	public function bind(&$array)
	{
		if (empty ($array))
		{
			$this->setError(JText::_('COM_TJDASHBOARD_EMPTY_DATA'));

			return false;
		}

		// Bind the array
		if (!$this->setProperties($array))
		{
			$this->setError(JText::_('COM_TJDASHBOARD_BINDING_ERROR'));

			return false;
		}

		// Make sure its an integer
		$this->dashboard_widget_id = (int) $this->dashboard_widget_id;

		return true;
	}

	/**
	 * Get the widget JS files
	 *
	 * @param   integer  $id  The primary key of the widget_id to load (optional).
	 *
	 * @return	Array JS files paths
	 *
	 * @since 	1.0.0
	 **/
	protected function getWidgetJS($id)
	{
		$widgetModel = TjdashboardFactory::model("widget", array("ignore_request" => 1));
		/** @scrutinizer ignore-call */
		$widgetData = $widgetModel->getItem($id);

		$renderer = explode('.', $widgetData->renderer_plugin);
		$rendererClass = 'PlgTjdashboardRenderer' . ucfirst($renderer[0]);
		$path = "plugins.tjdashboardrenderer.";
		$folderPath = $path . $renderer[0] . '.' . $renderer[0];
		JLoader::import($folderPath, JPATH_SITE);
		$rendererObj = new $rendererClass;

		return $rendererObj->getJS();
	}

	/**
	 * Get the widget CSS files
	 *
	 * @param   integer  $id  The primary key of the widget_id to load (optional).
	 *
	 * @return	Array CSS files paths
	 *
	 * @since 	1.0.0
	 **/
	protected function getWidgetCSS($id)
	{
		$widgetModel = TjdashboardFactory::model("widget", array("ignore_request" => 1));

		/** @scrutinizer ignore-call */
		$widgetData = $widgetModel->getItem($id);

		$renderer = explode('.', $widgetData->renderer_plugin);
		$rendererClass = 'PlgTjdashboardRenderer' . ucfirst($renderer[0]);
		$path = "plugins.tjdashboardrenderer.";
		$folderPath = $path . $renderer[0] . '.' . $renderer[0];
		JLoader::import($folderPath, JPATH_SITE);
		$rendererObj = new $rendererClass;

		return $rendererObj->getCSS();
	}

	/**
	 * Get the Widgets Renderers data files
	 *
	 * @param   array  $widgetDetails  to load (optional).
	 *
	 * @return	Array
	 *
	 * @since 	1.0.0
	 **/
	protected function getWidgetRendererData($widgetDetails)
	{
		$response = array();

		if ((!$widgetDetails->data_plugin) && (!$widgetDetails->renderer_plugin))
		{
			return $response;
		}

		try
		{
			$dataPluginClass = $this->getClassNameForDataPlugin($widgetDetails->data_plugin);
			$methodName = $this->getMethodNameForRenderer($widgetDetails->renderer_plugin);
			$pluginObj = new $dataPluginClass;
			$widgetRealData = $pluginObj->$methodName();
			$response['status'] = 1;
			$response['msg'] = JText::_("COM_TJDASHBOARD_SUCCESS_TEXT");
			$response['data'] = $widgetRealData;
		}
		catch (Exception $e)
		{
			$response['status'] = 0;
			$response['msg'] = JText::_("COM_TJDASHBOARD_FAILURE_TEXT");
		}

		return $response;
	}

	/**
	 * Get the Class Name
	 *
	 * @param   string  $dataPlugin  .
	 *
	 * @return	string
	 *
	 * @since 	1.0.0
	 **/
	public function getClassNameForDataPlugin($dataPlugin)
	{
		$dataPlugin = explode(".", $dataPlugin);
		$path = "/plugins/tjdashboardsource/";
		$folderPath = $path . $dataPlugin[0] . "/" . $dataPlugin[0];
		JLoader::import($folderPath . "/" . $dataPlugin[1], JPATH_SITE);

		return ucfirst($dataPlugin[0]) . ucfirst($dataPlugin[1]) . 'Datasource';
	}

	/**
	 * Get the Method Name
	 *
	 * @param   string  $renderPlugin  .
	 *
	 * @return	string
	 *
	 * @since 	1.0.0
	 **/
	public function getMethodNameForRenderer($renderPlugin)
	{
		$rendererPlugin = explode(".", $renderPlugin);

		return 'getData' . ucfirst($rendererPlugin[0]) . ucfirst($rendererPlugin[1]);
	}
}
