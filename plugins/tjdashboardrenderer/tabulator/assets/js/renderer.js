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

		jQuery("#"+sourceData.element).tabulator("setData", renderData.data);
	}
}
