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
class PlgTjdashboardRendererChartjs
{
	/**
	 * Get the widget JS files
	 *
	 * @return	Array JS files paths
	 *
	 * @since 	1.0
	 **/
	public function getJS()
	{
		$JS1 = 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment';
		$JS2 = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js';
		$JS3 = Juri::root() . 'plugins/tjdashboardrenderer/chartjs/assets/js/renderer.js';

		return array($JS1,$JS2,$JS3);
	}

	/**
	 * Get the widget CSS files
	 *
	 * @return	Array CSS files paths
	 *
	 * @since 	1.0
	 **/
	public function getCSS()
	{
		return array();
	}
}
