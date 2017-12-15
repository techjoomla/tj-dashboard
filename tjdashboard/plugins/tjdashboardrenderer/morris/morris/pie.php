<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * plugin of TJDashboardRendererMorrisPie
 *
 * @since  1.0.0
 */
class PlgTjdashboardRendererMorrisPie
{
	/**
	 * Get renderer html
	 * 
	 * @param   ARRAY  $widgetDetails  widgetDetails
	 * 
	 * @return STRING html 
	 *
	 * @since   1.0
	 * */
	public function display($widgetDetails)
	{
		if ($widgetDetails)
		{
			$dataPluginInfo   = explode(".", $widgetDetails->data_plugin);
			$renderPluginInfo = explode(".", $widgetDetails->renderer_plugin);

			require_once JPATH_BASE . '/plugins/tjdashboardsource/' . $dataPluginInfo[0] . '/' . $dataPluginInfo[0] . '/' . $dataPluginInfo[1] . '.php';

			$dataPluginClass = 'TjDashboardData' . ucfirst($dataPluginInfo[0]) . ucfirst($dataPluginInfo[1]);
			$dataPlugin      = new $dataPluginClass;
			$dataForChart    = $dataPlugin->getData($widgetDetails->renderer_plugin);

			$html = '';

			// Load the layout & push variables
			ob_start();
			$layout = JPATH_BASE . '/plugins/tjdashboardrenderer/';
			$layout .= $renderPluginInfo[0] . '/layouts/' . $renderPluginInfo[0] . '_' . $renderPluginInfo[1] . '.php';
			include $layout;

			$html = ob_get_contents();
			ob_end_clean();

			return $html;
		}
	}
}
