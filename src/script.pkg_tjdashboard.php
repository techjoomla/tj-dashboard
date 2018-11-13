<?php
/**
 * @package    Com.TjDashboard
 *
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.application.component.controller');

if (! defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}

/**
 * TjDashboard Installer
 *
 * @since  1.0.0
 */
class Pkg_TjdashboardInstallerScript
{
	private $installationQueue = array(
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

	private $uninstallQueue = array(
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
	 * Obsolete files and folders to remove.
	 *
	 * @var   array
	 */
	protected $deprecatedFiles = array(
			'files'   => array(),
			'folders' => array()
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
		$status = $this->installSubextensions($parent);
		$this->removeFilesAndFolders($this->deprecatedFiles);
	}

	/**
	 * Installs subextensions (modules, plugins) bundled with the main extension
	 *
	 * @param   JInstaller  $parent  parent
	 *
	 * @return  JObject The subextension installation status
	 */
	private function installSubextensions($parent)
	{
		$src = $parent->getParent()->getPath('source');

		$db  = JFactory::getDbo();

		$status          = new JObject;
		$status->plugins = array();

		// Plugins installation
		if (count($this->installationQueue['plugins']))
		{
			foreach ($this->installationQueue['plugins'] as $folder => $plugins)
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
	private function uninstallSubextensions($parent)
	{
		jimport('joomla.installer.installer');

		$db = JFactory::getDBO();

		$status          = new JObject;
		$status->modules = array();
		$status->plugins = array();

		$src = $parent->getParent()->getPath('source');

		// Plugins uninstallation
		if (count($this->uninstallQueue['plugins']))
		{
			foreach ($this->uninstallQueue['plugins'] as $folder => $plugins)
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
	 * renderPostUninstallation
	 *
	 * @param   STRING  $status  status of installed extensions
	 * @param   ARRAY   $parent  parent item
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	private function renderPostUninstallation($status, $parent)
	{
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
		$status = $this->uninstallSubextensions($parent);

		// Show the post-uninstallation page
		$this->renderPostUninstallation($status, $parent);
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
	}

	/**
	 * Removes obsolete files and folders
	 *
	 * @param   array  $removeList  The files and directories to remove
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function removeFilesAndFolders($removeList)
	{
		if (!empty($removeList['files']) && count($removeList['files']))
		{
			foreach ($removeList['files'] as $file)
			{
				$file = JPATH_ROOT . '/' . $file;

				if (!JFile::exists($file))
				{
					continue;
				}

				JFile::delete($file);
			}
		}

		if (!empty($removeList['folders']) && count($removeList['folders']))
		{
			foreach ($removeList['folders'] as $folder)
			{
				$folder = JPATH_ROOT . '/' . $folder;

				if (!JFolder::exists($folder))
				{
					continue;
				}

				JFolder::delete($folder);
			}
		}
	}
}
