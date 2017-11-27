<form name="adminForm" id="adminForm" class="form-validate" method="post">
	<div class="row">
		<div class="report-c">
			<h1>
				<?php echo JText::_('COM_TJDASHBOARD_TITLE_DASHBOARD');?>
			</h1>

			<div class="span12">
				<table width=100%>
					<tr class="">
						<th>
							<div class="table">
								<?php
									echo JHTML::_( 'grid.sort', 'COM_TJDASHBOARD_FORM_LBL_DASHBOARD_TITLE', 'title', $this->sortDirection, $this->sortColumn);
								?>
							</div>
						</th>
						<th>
							<div class="">
								<?php
									echo JHTML::_( 'grid.sort', 'COM_TJDASHBOARD_FORM_LBL_DASHBOARD_CREATED_ON', 'created_on', $this->sortDirection, $this->sortColumn);
								?>
							</div>
						</th>
						<th>
							<div class="">
								<?php
									echo JHTML::_( 'grid.sort', 'COM_TJDASHBOARD_FORM_LBL_DASHBOARD_CREATED_BY', 'created_on', $this->sortDirection, $this->sortColumn);
								?>
							</div>
						</th>
						<th>
							<div class="">
								<?php
									echo JHTML::_( 'grid.sort', 'COM_TJDASHBOARD_FORM_LBL_DASHBOARD_CONTEXT', 'context', $this->sortDirection, $this->sortColumn);
								?>
							</div>
						</th>
						<th>
							<div class="">
								<?php
									echo JHTML::_( 'grid.sort', 'COM_TJDASHBOARD_FORM_LBL_DASHBOARD_ID', 'dashboard_id', $this->sortDirection, $this->sortColumn);
								?>
							</div>
						</th>
					</tr>
					<?php
						if (!empty($this->items))
						{
							foreach ($this->items as $dashboard)
							{ ?>
								<tr class="">
									<td class="paddingh10">
										<div class="">
											<?php echo $dashboard->title . "<br>(alias:" . $dashboard->alias . ")";?>
										</div>
									</td>
									<td class="paddingh10">
										<div class="">
											<?php echo $dashboard->created_on; ?>
										</div>
									</td>
									<td class="paddingh10">
										<div class="">
											<?php echo $dashboard->created_by; ?>
										</div>
									</td>
									<td class="paddingh10">
										<div class="">
											<?php echo !empty($dashboard->context) ? $dashboard->context : "-"; ?>
										</div>
									</td>
									<td class="paddingh10">
										<div class="">
											<?php echo $dashboard->dashboard_id; ?>
										</div>
									</td>
								</tr>
							<?php
							}
						}
						else
						{ ?>
							<tr>
								<table width=100%>
									<tr>
										<td>
											<div class="alert alert-warning text-center margin15">
												<?php
													echo JText::_('NO_DATA');
												?>
											</div>
										</td>
									</tr>
								</table>
							</tr>
						<?php
						}
					?>
				</table>
			</div>
			<div>
				<div class="pager">
					<?php echo $this->pagination->getListFooter(); ?>
				</div>
			</div>
		</div>
	</div>

	<input type="hidden" name="option" value="com_tjdashboard" />
	<input type="hidden" name="task" id="task" value=""/>
	<input type="hidden" name="view" value="dashboard" />
	<input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
</form>
