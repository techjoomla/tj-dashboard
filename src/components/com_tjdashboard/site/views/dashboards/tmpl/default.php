<?php
/**
 * @package     TJDashboard
 * @subpackage  com_tjdashboard
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Language\Text;


JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'dash.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_tjdashboard&task=dashboards.saveOrderAjax&tmpl=component';
	HTMLHelper::_('sortablelist.sortable', 'dashboardsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>

<div class="tj-page">
	<div class="row">
		<form action="<?php echo Route::_('index.php?option=com_tjdashboard&view=dashboards'); ?>" method="post" name="adminForm" id="adminForm">
			<?php
			if (!empty( $this->sidebar))
			{
				?>
				<div id="j-sidebar-container" class="span2">
					<?php echo $this->sidebar; ?>
				</div>
				<div id="j-main-container" class="span10">
			<?php
			}
			else
			{
				?>
				<div id="j-main-container">
			<?php
			}
			?>

			<?php
			// Search tools bar
			echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));
			?>
			<?php if (empty($this->items))
			{
				?>
				<div class="alert alert-no-items">
					<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
				</div>
			<?php
			}
			else
			{
				?>
			<table class="table table-striped" id="dashboardsList">
				<thead>
					<tr>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo HTMLHelper::_('searchtools.sort', '', 'dash.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
						</th>
						<th width="1%" class="center">
							<?php echo HTMLHelper::_('grid.checkall'); ?>
						</th>

						<?php if (isset($this->items[0]->state))
						{
							?>
							<th width="1%" class="nowrap center">
								<?php echo HTMLHelper::_('grid.sort', 'JSTATUS', 'dash.state', $listDirn, $listOrder); ?>
							</th>
						<?php
						}
						?>
						<th>
							<?php echo HTMLHelper::_('searchtools.sort', 'COM_TJDASHBOARD_LIST_VIEW_TITLE', 'dash.title', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo HTMLHelper::_('searchtools.sort', 'COM_TJDASHBOARD_LIST_VIEW_CREATEDBY', 'dash.created_by', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo HTMLHelper::_('searchtools.sort', 'COM_TJDASHBOARD_LIST_VIEW_ID', 'dash.dashboard_id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="10">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php
					foreach ($this->items as $i => $item)
					{
						$item->max_ordering = 0;
						$ordering   = ($listOrder == 'dash.ordering');
						$canCreate  = $this->canCreate;
						$canEdit    = $this->canEdit;
						$canCheckin = $this->canCheckin;

						$canChange  = $this->canChangeStatus;
						?>
						<tr class="
							<?php echo $i % 2; ?>" sortable-group-id="
							<?php echo $item->dashboard_id; ?>">
						<td class="order nowrap center hidden-phone">
							<?php
							$iconClass = '';

							if (!$canChange)
							{
								$iconClass = ' inactive';
							}
							elseif (!$saveOrder)
							{
								$iconClass = ' inactive tip-top hasTooltip" title="' . HTMLHelper::_('tooltipText', 'JORDERINGDISABLED');
							}
							?>
							<span class="sortable-handler<?php echo $iconClass ?>">
								<span class="icon-menu" aria-hidden="true"></span>
							</span>
							<?php if ($canChange && $saveOrder)
							{
							?>
							<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order" />
							<?php
							}
							?>
						</td>
						<td class="center">
							<?php echo HTMLHelper::_('grid.id', $i, $item->dashboard_id); ?>
						</td>
						<?php
						if (isset($this->items[0]->state))
						{
							?>
						<td class="center">
							<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'dashboards.', $canChange, 'cb'); ?>
						</td>
						<?php
						}
						?>
						<td class="has-context">
							<div class="pull-left break-word">
								<?php if ($item->checked_out)
								{
								?>
								<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->checked_out, $item->checked_out_time, 'dashboards.', $canCheckin); ?>
								<?php
								}
								?>
								<?php if ($canEdit)
								{
									?>
									<a class="hasTooltip" href="<?php echo Route::_('index.php?option=com_tjdashboard&view=dashboard&dashboard_id=' . $item->dashboard_id); ?>"
									title="<?php echo Text::_('JACTION_EDIT'); ?>">
									<?php echo $this->escape($item->title); ?></a>
									<?php
								}
								?>
								<span title=""><?php echo $this->escape($item->title); ?></span>
							</div>
						</td>
						<td><?php echo $item->created_by; ?></td>
						<td><?php echo $item->dashboard_id; ?></td>
					</tr>
					<?php
						}
					?>
				<tbody>
			</table>
			<?php
			}
			?>

					<input type="hidden" name="task" value="" />
					<input type="hidden" name="boxchecked" value="0" />
					<?php echo HTMLHelper::_('form.token'); ?>
			</div>
		</form>
	</div>
</div>

