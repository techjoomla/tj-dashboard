<?php
/**
 * @version    CVS: 1.0.0
 * @package    Plg_Tjdash
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * plugin of TJDashboardRendererTjdash
 *
 * @since  1.0.0
 */
class PlgTjdashboardRendererTabulator
{
	/**
	 * Get the widget JS files
	 *
	 * @return	Array JS files paths
	 *
	 * @since 	1.0.0
	 **/
	public function getJS()
	{
		$JS1 = Juri::root() . 'plugins/tjdashboardrenderer/tabulator/assets/js/jquery-ui.min.js';
		$JS2 = Juri::root() . 'plugins/tjdashboardrenderer/tabulator/assets/js/tabulator.min.js';
		$JS3 = Juri::root() . 'plugins/tjdashboardrenderer/tabulator/assets/js/renderer.js';

		return array($JS1,$JS2,$JS3);
	}

	/**
	 * Get the widget CSS files
	 *
	 * @return	Array CSS files paths
	 *
	 * @since 	1.0.0
	 **/
	public function getCSS()
	{
		$CS1 = Juri::root() . 'plugins/tjdashboardrenderer/tabulator/assets/css/tabulator_semantic-ui.min.css';
		$CS2 = Juri::root() . 'plugins/tjdashboardrenderer/tabulator/assets/css/custom.css';
		return array($CS1,$CS2);
	}
}
