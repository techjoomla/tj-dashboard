<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addScript('components/com_tjdashboard/assets/js/tjDashboardService.js');
$document->addScript('components/com_tjdashboard/assets/js/tjDashboardUI.js');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'wid.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_tjdashboard&task=widgets.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'widgetsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>

<div class="tj-page">
	<div class="row-fluid">
		<form action="<?php echo JRoute::_('index.php?option=com_tjdashboard&view=widgets'); ?>" method="post" name="adminForm" id="adminForm">
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
					// Search tools bar
					echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
					?>
					<?php if (empty($this->items))
					{
					?>
						<div class="alert alert-no-items">
							<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
						</div>
					<?php
					}
					else
					{
					?>
					<table class="table table-striped" id="widgetsList">
						<thead>
							<tr>
								<th width="1%" class="nowrap center hidden-phone">
									<?php echo JHtml::_('searchtools.sort', '', 'wid.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
								</th>

								<th width="1%" class="center">
									<?php echo JHtml::_('grid.checkall'); ?>
								</th>

								<th width="1%" class="nowrap center">
									<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'wid.state', $listDirn, $listOrder); ?>
								</th>

								<th>
									<?php echo JHtml::_('searchtools.sort', 'COM_TJDASHBOARD_LIST_VIEW_TITLE', 'wid.title', $listDirn, $listOrder); ?>
								</th>
								<th>
									<?php echo JHtml::_('searchtools.sort', 'COM_TJDASHBOARD_LIST_VIEW_DASHBOARD', 'dash.dashboard_name', $listDirn, $listOrder); ?>
								</th>

								<th>
									<?php echo JHtml::_('searchtools.sort', 'COM_TJDASHBOARD_WIDGET_FORM_LBL_RENDERER_PLUGIN', 'wid.renderer_plugin', $listDirn, $listOrder); ?>
								</th>

								<th>
									<?php echo JHtml::_('searchtools.sort', 'COM_TJDASHBOARD_WIDGET_FORM_LBL_DATA_PLUGIN', 'wid.data_plugin', $listDirn, $listOrder); ?>
								</th>

								<th>
									<?php echo JHtml::_('searchtools.sort', 'COM_TJDASHBOARD_LIST_VIEW_CREATEDBY', 'wid.created_by', $listDirn, $listOrder); ?>
								</th>
								<th>
									<?php echo JHtml::_('searchtools.sort', 'COM_TJDASHBOARD_LIST_VIEW_ID', 'wid.dashboard_widget_id', $listDirn, $listOrder); ?>
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
								$ordering   = ($listOrder == 'wid.ordering');
								$canCreate  = $this->canCreate;
								$canEdit    = $this->canEdit;
								$canCheckin = $this->canCheckin;
								$canChange  = $this->canChangeStatus;
								?>
								<tr class="row<?php echo $i % 2; ?>" sortable-group-id="
								<?php echo $item->dashboard_widget_id; ?>">
								<td class="order nowrap center hidden-phone">
									<?php
									$iconClass = '';

									if (!$canChange)
									{
										$iconClass = ' inactive';
									}
									elseif (!$saveOrder)
									{
										$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::_('tooltipText', 'JORDERINGDISABLED');
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
									<?php echo JHtml::_('grid.id', $i, $item->dashboard_widget_id); ?>
								</td>
								<?php if (isset($this->items[0]->state))
								{
								?>
								<td class="center">
									<?php echo JHtml::_('jgrid.published', $item->state, $i, 'widgets.', $canChange, 'cb'); ?>
								</td>
								<?php
								}?>
								<td class="has-context">
									<div class="pull-left break-word">
										<?php if ($item->checked_out)
										{
											?>
										<?php echo JHtml::_('jgrid.checkedout', $i, $item->checked_out, $item->checked_out_time, 'widgets.', $canCheckin); ?>
										<?php
										}
										?>
										<?php if ($canEdit || $canEditOwn)
										{
											?>
											<a class="hasTooltip" href="
											<?php echo JRoute::_('index.php?option=com_tjdashboard&task=widget.edit&dashboard_widget_id=' . $item->dashboard_widget_id); ?>" title="
											<?php echo JText::_('JACTION_EDIT'); ?>">
											<?php echo $this->escape($item->title); ?></a>
											<?php
											}
											else
											{
												?>
											<span title=""><?php echo $this->escape($item->title); ?></span>
										<?php
										}
										?>
									</div>
								</td>
								<td><?php echo $item->dashboard_name; ?></td>
								<td><?php echo $item->renderer_plugin; ?></td>
								<td><?php echo $item->data_plugin; ?></td>
								<td><?php echo $item->name; ?></td>
								<td><?php echo $item->dashboard_widget_id; ?></td>
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
					<?php echo JHtml::_('form.token'); ?>
			</div>
		</form>
	</div>
</div>

