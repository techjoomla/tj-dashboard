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
			placeholder:"No Data Available",
			columns:renderData.columns,
		});

		if (renderData.columns.length==1) {
			jQuery("#"+sourceData.element+" > .tabulator-header").addClass('hide');
		}

		if (renderData.data.length==0) {
			jQuery("#"+sourceData.element).html('<div class="alert alert-info"> No data Available</div>');
		}

		jQuery("#"+sourceData.element).removeClass('panel-body');
		jQuery(" .tabulator").css('margin',0);

		jQuery("#"+sourceData.element).tabulator("setData", renderData.data);
	}
}
