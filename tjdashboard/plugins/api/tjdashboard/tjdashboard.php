<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
JLoader::import("/components/com_tjdashboard/includes/tjdashboard", JPATH_SITE);

/**
 * Tjdashboard API plugin
 *
 * @since  1.0
 */
class PlgAPITjdashboard extends ApiPlugin
{
	/**
	 * Constructor
	 *
	 * @param   STRING  &$subject  subject
	 * @param   array   $config    config
	 *
	 * @since 1.0
	 */
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config = array());

		// Set resource path
		ApiResource::addIncludePath(dirname(__FILE__) . '/tjdashboard');

		// Load language files
		$lang = JFactory::getLanguage();
		$lang->load('plg_api_tjdashboard', JPATH_ADMINISTRATOR, '', true);

		// Set the resource to be public
		$this->setResourceAccess('widget', 'public', 'get');
		$this->setResourceAccess('dashboard', 'public', 'get');
	}
}
