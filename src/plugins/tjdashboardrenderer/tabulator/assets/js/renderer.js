var TJDashboardTabulator = {
	renderData: function(method,sourceData)
	{
		this[method](sourceData);
	},
	tjdashtable: function(sourceData)
	{
		var renderData = JSON.parse(sourceData.data);

		jQuery("#"+sourceData.element).tabulator({
			layout:"fitColumns",
			addRowPos:"top",
			placeholder:Joomla.JText._("COM_TJDASHBOARD_NO_DATA_AVAILABLE_MESSAGE"),
			columns:renderData.columns,
			selectable:false
		});

		if (renderData.columns.length==1) {
			jQuery("#"+sourceData.element+" > .tabulator-header").addClass('hide');
		}

		if (renderData.data.length==0) {
			jQuery("#"+sourceData.element).html('<div class="alert alert-info">' + Joomla.JText._("COM_TJDASHBOARD_NO_DATA_AVAILABLE_MESSAGE") + '</div>');
		}

		jQuery("#"+sourceData.element).removeClass('panel-body');
		jQuery(" .tabulator").css('margin',0);

		jQuery("#"+sourceData.element).tabulator("setData", renderData.data);
	}
}
