var TJDashboardTabulator = {
	renderData: function(method,sourceData)
	{
		this[method](sourceData);
	},
	tjdashtable: function(sourceData)
	{
		var renderData = JSON.parse(sourceData.data);
		console.log(sourceData);
		jQuery("#"+sourceData.element).tabulator({
			layout:"fitColumns",
			addRowPos:"top",
			placeholder:"No Data Available",
			columns:renderData.columns,
		});

		if (renderData.columns.length==1) {
			jQuery("#"+sourceData.element+" > .tabulator-header").addClass('hide');
		}
		jQuery("#"+sourceData.element).removeClass('panel-body');
		jQuery(" .tabulator").css('margin',0);

		jQuery("#"+sourceData.element).tabulator("setData", renderData.data);
	}
}
