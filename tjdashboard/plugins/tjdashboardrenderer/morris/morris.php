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
 * plugin of TJDashboardRendererMorris
 *
 * @since  1.0.0
 */
class PlgTjdashboardRendererMorris
{
	/**
	 * Get renderer html
	 * 
	 * @param   ARRAY  $widgetData  widgetData
	 * 
	 * @return STRING html 
	 *
	 * @since   1.0
	 * */
	public function getRendererHtml($widgetData)
	{
		if ($widgetData)
		{
			$rendererInfo = explode(".", $widgetData->renderer_plugin);

			require_once JPATH_SITE . '/plugins/tjdashboardrenderer/' . $rendererInfo[0] . '/' . $rendererInfo[0] . '/' . $rendererInfo[1] . '.php';

			$rendererClass = 'PlgTjdashboardRenderer' . ucfirst($rendererInfo[0]) . ucfirst($rendererInfo[1]);

			$renderePlugin = new $rendererClass;
			$html = $renderePlugin->display($widgetData);

			return $html;
		}
	}
}
