<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');


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
	JHtml::_('sortablelist.sortable', 'dashboardsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>

<div class="tj-page">
	<div class="row-fluid">
		<form action="<?php echo JRoute::_('index.php?option=com_tjdashboard&view=dashboards'); ?>" method="post" name="adminForm" id="adminForm">
			<?php if (!empty( $this->sidebar)) : ?>
				<div id="j-sidebar-container" class="span2">
					<?php echo $this->sidebar; ?>
				</div>
				<div id="j-main-container" class="span10">
			<?php else : ?>
				<div id="j-main-container">
			<?php endif; ?>

					<?php
					// Search tools bar
					echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
					?>

					<table class="table table-striped" id="dashboardsList">
						<thead>
							<tr>
								<th width="1%" class="nowrap center hidden-phone">
									<?php echo JHtml::_('searchtools.sort', '', 'dash.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
								</th>

								<th width="1%" class="center">
									<?php echo JHtml::_('grid.checkall'); ?>
								</th>

								<?php if (isset($this->items[0]->state)): ?>
									<th width="1%" class="nowrap center">
										<?php echo JHtml::_('grid.sort', 'JSTATUS', 'dash.state', $listDirn, $listOrder); ?>
									</th>
								<?php endif; ?>

								<th>
									<?php echo JHtml::_('searchtools.sort', 'COM_TJDASHBOARD_LIST_VIEW_TITLE', 'dash.title', $listDirn, $listOrder); ?>
								</th>
								<th>
									<?php echo JHtml::_('searchtools.sort', 'COM_TJDASHBOARD_LIST_VIEW_ID', 'dash.dashboard_id', $listDirn, $listOrder); ?>
								</th>
							</tr>
						</thead>

						<tbody>
							<?php
							if(!empty($this->items))
							{
								foreach ($this->items as $i => $item)
								{
									$item->max_ordering = 0;
									$ordering   = ($listOrder == 'dash.ordering');
									$canCreate  = $this->canCreate; //user->authorise('core.create',     'com_content.category.' . $item->catid);
									$canEdit    = $this->canEdit; // $user->authorise('core.edit',       'com_content.article.' . $item->id);

									$canCheckin = $this->canCheckin; //$user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
									// $canEditOwn = $user->authorise('core.edit.own',   'com_content.article.' . $item->id) && $item->created_by == $userId;

									$canChange  = $this->canChangeStatus; // $user->authorise('core.edit.state', 'com_content.article.' . $item->id) && $canCheckin;
									?>

									<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->dashboard_id; ?>">
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
											<?php if ($canChange && $saveOrder) : ?>
												<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order" />
											<?php endif; ?>
										</td>

										<td class="center">
											<?php echo JHtml::_('grid.id', $i, $item->dashboard_id); ?>
										</td>

										<?php if (isset($this->items[0]->state)): ?>
											<td class="center">
												<?php echo JHtml::_('jgrid.published', $item->state, $i, 'dashboards.', $canChange, 'cb'); ?>
											</td>
										<?php endif; ?>

										<td class="has-context">
											<div class="pull-left break-word">
												<?php if ($item->checked_out) : ?>
													<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'dashboards.', $canCheckin); ?>
												<?php endif; ?>

												<?php if ($canEdit || $canEditOwn) : ?>
													<a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_tjdashboard&task=dashboard.edit&id=' . $item->dashboard_id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
														<?php echo $this->escape($item->title); ?></a>
												<?php else : ?>
													<span><?php echo $this->escape($item->title); ?></span>
												<?php endif; ?>
											</div>
										</td>

										<td><?php echo $item->dashboard_id; ?></td>
									</tr>

									<?php
								}
							}
							else
							{ ?>
							<tfoot>
							<tr>
								<!--td-->
								<div class="alert alert-no-items">
									<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
								</div>
								<!--/td-->
							</tr>
							<tr>
								
								<td colspan="10">
									<?php //echo $this->pagination->getListFooter(); ?>
								</td>
							</tr>
							</tfoot>
							<?php
							}
							?>
						<tbody>
					</table>

					<?php //echo $this->pagination->getListFooter(); ?>

					<input type="hidden" name="task" value="" />
					<input type="hidden" name="boxchecked" value="0" />
					<?php echo JHtml::_('form.token'); ?>
			</div>
		</form>
	</div>
</div>

