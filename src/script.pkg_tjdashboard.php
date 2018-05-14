<?php
/**
 * @version    SVN: <svn_id>
 * @package    Com_Tjlms
 * @copyright  Copyright (C) 2005 - 2014. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * Shika is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.application.component.controller');

if (!defined('DS'))
{
	define('DS', '/');
}

/**
 * Tjlms Installer
 *
 * @since  1.0.0
 */
class Pkg_TjdashboardInstallerScript
{
	/** @var array The list of extra modules and plugins to install */
	private $oldversion = "";

	private $installation_queue = array(
									'plugins' => array(
											'tjdashboardrenderer' => array(
																		'chartjs' => 1,
																		'tabulator' => 1,
																		'countbox' => 1,
																		'numbercardbox' => 1,
																	),
											'api' => array('tjdashboard' => 1),
										),
									);

	private $uninstall_queue = array(
		'plugins' => array(
						'tjdashboardrenderer' => array(
														'chartjs' => 1,
														'tabulator' => 1,
															'countbox' => 1,
															'numbercardbox' => 1,
													),
						'api' => array('tjdashboard' => 1),
					)
				);

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @param   JInstaller  $type    type
	 * @param   JInstaller  $parent  parent
	 *
	 * @return void
	 */
	public function preflight($type, $parent)
	{
	}

	/**
	 * method to install the component
	 *
	 * @param   JInstaller  $parent  parent
	 *
	 * @return  void
	 */
	public function install($parent)
	{
		// $parent is the class calling this method
	}

	/**
	 * Runs after install, update or discover_update
	 *
	 * @param   string      $type    install, update or discover_update
	 * @param   JInstaller  $parent  parent
	 *
	 * @return  void
	 */
	public function postflight($type, $parent)
	{
		// Install subextensions
		$status = $this->_installSubextensions($parent);

		// Install Techjoomla Straper
		$straperStatus = $this->_installStraper($parent);

		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root() . '/media/techjoomla_strapper/css/bootstrap.min.css');

		$this->_removeFiles();

		// Show the post-installation page
		$this->_renderPostInstallation($status, $straperStatus, $parent);
	}

	/**
	 * Remove Files
	 *
	 * @return  void
	 */
	public function _removeFiles()
	{
		$file1 = JPATH_SITE . '/components/com_tjlms/views/coupon/default.xml';
		$file2 = JPATH_SITE . '/components/com_tjlms/views/coupon/tmpl/default.xml';

		if (JFile::exists($file1))
		{
			JFile::delete($file1);
		}

		if (JFile::exists($file2))
		{
			JFile::delete($file2);
		}
	}

	/**
	 * Install strappers
	 *
	 * @param   JInstaller  $parent  parent
	 *
	 * @return  void
	 */
	private function _installStraper($parent)
	{
	}

	/**
	 * Renders the post-installation message
	 *
	 * @param   JInstaller  $status         parent
	 * @param   JInstaller  $straperStatus  parent
	 * @param   JInstaller  $parent         parent
	 *
	 * @return  void
	 */
	private function _renderPostInstallation($status, $straperStatus, $parent)
	{
	}
	/**
	 * Installs subextensions (modules, plugins) bundled with the main extension
	 *
	 * @param   JInstaller  $parent  parent
	 *
	 * @return  JObject The subextension installation status
	 */
	private function _installSubextensions($parent)
	{
		$src = $parent->getParent()->getPath('source');

		$db  = JFactory::getDbo();

		$status          = new JObject;
		$status->plugins = array();


		// Plugins installation
		if (count($this->installation_queue['plugins']))
		{
			foreach ($this->installation_queue['plugins'] as $folder => $plugins)
			{
				if (count($plugins))
				{
					foreach ($plugins as $plugin => $published)
					{
						$path = "$src/plugins/$folder/$plugin";

						if (!is_dir($path))
						{
							$path = "$src/plugins/$folder/plg_$plugin";
						}

						if (!is_dir($path))
						{
							$path = "$src/plugins/$plugin";
						}

						if (!is_dir($path))
						{
							$path = "$src/plugins/plg_$plugin";
						}

						if (!is_dir($path))
						{
							continue;
						}

						// Was the plugin already installed?
						$query = $db->getQuery(true)->select('COUNT(*)')
						->from($db->qn('#__extensions'))
						->where('( ' . ($db->qn('name') . ' = ' . $db->q($plugin)) . ' OR ' . ($db->qn('element') . ' = ' . $db->q($plugin)) . ' )')
						->where($db->qn('folder') . ' = ' . $db->q($folder));
						$db->setQuery($query);
						$count = $db->loadResult();

						$installer = new JInstaller;
						$result    = $installer->install($path);

						if ($count)
						{
							// Was the plugin already installed?
							$query = $db->getQuery(true)->select('enabled')
							->from($db->qn('#__extensions'))
							->where('( ' . ($db->qn('name') . ' = ' . $db->q($plugin)) . ' OR ' . ($db->qn('element') . ' = ' . $db->q($plugin)) . ' )')
							->where($db->qn('folder') . ' = ' . $db->q($folder));
							$db->setQuery($query);
							$enabled = $db->loadResult();

							$status->plugins[] = array(
								'name' => $plugin,
								'group' => $folder,
								'result' => $result,
								'status' => $enabled
							);
						}
						else
						{
							$status->plugins[] = array(
								'name' => $plugin,
								'group' => $folder,
								'result' => $result,
								'status' => $published
							);
						}

						if ($published && !$count)
						{
							$query = $db->getQuery(true)
							->update($db->qn('#__extensions'))
							->set($db->qn('enabled') . ' = ' . $db->q('1'))
							->where('( ' . ($db->qn('name') . ' = ' . $db->q($plugin)) . ' OR ' . ($db->qn('element') . ' = ' . $db->q($plugin)) . ' )')
							->where($db->qn('folder') . ' = ' . $db->q($folder));
							$db->setQuery($query);
							$db->query();
						}
					}
				}
			}
		}

		return $status;
	}

	/**
	 * Uninstalls subextensions (modules, plugins) bundled with the main extension
	 *
	 * @param   JInstaller  $parent  parent
	 *
	 * @return  JObject The subextension uninstallation status
	 */
	private function _uninstallSubextensions($parent)
	{
		jimport('joomla.installer.installer');

		$db = JFactory::getDBO();

		$status          = new JObject;
		$status->modules = array();
		$status->plugins = array();

		$src = $parent->getParent()->getPath('source');

		// Plugins uninstallation
		if (count($this->uninstall_queue['plugins']))
		{
			foreach ($this->uninstall_queue['plugins'] as $folder => $plugins)
			{
				if (count($plugins))
				{
					foreach ($plugins as $plugin => $published)
					{
						$sql = $db->getQuery(true)->select($db->qn('extension_id'))
						->from($db->qn('#__extensions'))
						->where($db->qn('type') . ' = ' . $db->q('plugin'))
						->where($db->qn('element') . ' = ' . $db->q($plugin))
						->where($db->qn('folder') . ' = ' . $db->q($folder));
						$db->setQuery($sql);

						$id = $db->loadResult();

						if ($id)
						{
							$installer         = new JInstaller;
							$result            = $installer->uninstall('plugin', $id);
							$status->plugins[] = array(
								'name' => 'plg_' . $plugin,
								'group' => $folder,
								'result' => $result
							);
						}
					}
				}
			}
		}

		return $status;
	}

	/**
	 * _renderPostUninstallation
	 *
	 * @param   STRING  $status  status of installed extensions
	 * @param   ARRAY   $parent  parent item
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	private function _renderPostUninstallation($status, $parent)
	{
?>
	   <?php
		$rows = 0;
?>
	   <h2><?php
		echo JText::_('TjLMS Uninstallation Status');
?></h2>
		<table class="adminlist">
			<thead>
				<tr>
					<th class="title" colspan="2"><?php
		echo JText::_('Extension');
?></th>
					<th width="30%"><?php
		echo JText::_('Status');
?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3"></td>
				</tr>
			</tfoot>
			<tbody>
				<tr class="row0">
					<td class="key" colspan="2"><?php
		echo 'TjLMS ' . JText::_('Component');
?></td>
					<td><strong style="color: green"><?php
		echo JText::_('Removed');
?></strong></td>
				</tr>
				<?php
		if (count($status->modules))
		{
?>
			   <tr>
					<th><?php
			echo JText::_('Module');
?></th>
					<th><?php
			echo JText::_('Client');
?></th>
					<th></th>
				</tr>
				<?php
			foreach ($status->modules as $module)
			{
?>
			   <tr class="row<?php echo ++$rows % 2;?>">
					<td class="key"><?php echo $module['name'];?></td>
					<td class="key"><?php echo ucfirst($module['client']);?></td>
					<td>
						<strong style="color: <?php echo $module['result'] ? "green" : "red";?>">
							<?php echo $module['result'] ? JText::_('Removed') : JText::_('Not removed');?>
						</strong>
					</td>
				</tr>
				<?php
			}
?>
			   <?php
		}
?>
			   <?php
		if (count($status->plugins))
		{
?>
			   <tr>
					<th><?php
			echo JText::_('Plugin');
?></th>
					<th><?php
			echo JText::_('Group');
?></th>
					<th></th>
				</tr>
				<?php
			foreach ($status->plugins as $plugin)
			{
?>
			   <tr class="row<?php
				echo ++$rows % 2;
?>">
					<td class="key"><?php
				echo ucfirst($plugin['name']);
?></td>
					<td class="key"><?php
				echo ucfirst($plugin['group']);
?></td>
					<td><strong style="color: <?php
				echo $plugin['result'] ? "green" : "red";
?>"><?php
				echo $plugin['result'] ? JText::_('Removed') : JText::_('Not removed');
?></strong></td>
				</tr>

	<?php
			}
		}

		?>
		   </tbody>
		</table>
		<?php
	}

	/**
	 * Runs on uninstallation
	 *
	 * @param   JInstaller  $parent  Parent
	 *
	 * @return void
	 *
	 * @since   1.0.0
	 */
	public function uninstall($parent)
	{
		// Uninstall subextensions
		$status = $this->_uninstallSubextensions($parent);

		// Show the post-uninstallation page
		$this->_renderPostUninstallation($status, $parent);
	}

	/**
	 * method to update the component
	 *
	 * @param   JInstaller  $parent  Parent
	 *
	 * @return void
	 */
	public function update($parent)
	{
		$db     = JFactory::getDBO();
		$config = JFactory::getConfig();

		if (JVERSION >= 3.0)
		{
			$configdb = $config->get('db');
		}
		else
		{
			$configdb = $config->getValue('config.db');
		}
		// Get dbprefix
		if (JVERSION >= 3.0)
		{
			$dbprefix = $config->get('dbprefix');
		}
		else
		{
			$dbprefix = $config->getValue('config.dbprefix');
		}
	}
}
