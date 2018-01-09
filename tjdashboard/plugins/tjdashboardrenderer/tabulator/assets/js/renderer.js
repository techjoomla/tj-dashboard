//@ Todo This Should be Object Oriented 
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
			pagination:"local",
			paginationSize:5,
			movableColumns:true,
			columns:renderData.columns,
		});

		jQuery("#"+sourceData.element).tabulator("setData", renderData.data);
	}
}
