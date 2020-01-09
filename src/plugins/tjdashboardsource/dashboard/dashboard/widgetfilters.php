<?php
/**
 * @package     TJDashboard
 * @subpackage  tjdashboardsource
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2019 Techjoomla. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

$lang      = Factory::getLanguage();
$lang->load('plg_tjdashboardsource_dashboard', JPATH_ADMINISTRATOR);

JLoader::import("/components/com_cluster/includes/cluster", JPATH_ADMINISTRATOR);

/**
 * Plugin for tjdashboardsource to get dashboard filters
 *
 * @since  __DEPLOY_VERSION__
 */

class DashboardWidgetFiltersDatasource
{
	public $dataSourceName = "PLG_TJDASHBOARDSOURCE_DASHBOARD_WIDGET_FILTERS";

	/**
	 * Function to get data of the whole block
	 *
	 * @return Array data.
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function getData()
	{
		try
		{
			$clusterList = array();

			// Check if com_cluster component is installed
			if (ComponentHelper::getComponent('com_cluster', true)->enabled)
			{
				$user = Factory::getUser();
				$clusterUserModel = ClusterFactory::model('ClusterUser', array('ignore_request' => true));
				$clusters = $clusterUserModel->getUsersClusters($user->id);

				if (count($clusters) > 1)
				{
					$clusterList['cluster_id'][] = HTMLHelper::_('select.option', "", Text::_('PLG_TJDASHBOARDSOURCE_DASHBOARD_SELECT_CLUSTER'));
				}

				// Get com_subusers component status
				$subUserExist = ComponentHelper::getComponent('com_subusers', true)->enabled;

				if ($subUserExist)
				{
					JLoader::import("/components/com_subusers/includes/rbacl", JPATH_ADMINISTRATOR);
				}

				// Create oprion for each cluster
				foreach ($clusters as $cluster)
				{
					// DPE - Hack to check its manager
					if ($subUserExist)
					{
						// Check user have permission to manage all clusters
						if (!$user->authorise('core.manageall', 'com_cluster'))
						{
							// Check user having permission to add staff
							if (RBACL::authorise($user->id, 'com_multiagency', 'core.adduser', $cluster->client_id))
							{
								$clusterList['cluster_id'][] = HTMLHelper::_('select.option', $cluster->cluster_id, trim($cluster->name));
							}
						}
						else
						{
							$clusterList['cluster_id'][] = HTMLHelper::_('select.option', $cluster->cluster_id, trim($cluster->name));
						}
					}
					else
					{
						$clusterList['cluster_id'][] = HTMLHelper::_('select.option', $cluster->cluster_id, trim($cluster->name));
					}
				}
			}
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}

		$recordInfo = array(Text::_('PLG_TJDASHBOARDSOURCE_DASHBOARD_CLUSTER_FILTER') => '');
		$recordInfo[Text::_('PLG_TJDASHBOARDSOURCE_DASHBOARD_CLUSTER_FILTER')] = $clusterList;

		return $recordInfo;
	}

	/**
	 * Get Data for Filter
	 *
	 * @return string dataArray
	 *
	 * @since   __DEPLOY_VERSION__
	 * */
	public function getDataFilterboxTjdashfilter()
	{
		$items = [];
		$items['data'] = ['filters' => $this->getData(),
		'title' => ''
		];

		return json_encode($items);
	}

	/**
	 * Get supported Renderers List
	 *
	 * @return array supported renderes for this data source
	 *
	 * @since   __DEPLOY_VERSION__
	 * */
	public function getSupportedRenderers()
	{
		return array('filterbox.tjdashfilter' => "PLG_TJDASHBOARDRENDERER_FILTERBOX");
	}
}
