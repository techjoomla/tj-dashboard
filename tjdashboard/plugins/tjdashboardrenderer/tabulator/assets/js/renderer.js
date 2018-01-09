//@ Todo This Should be Object Oriented 
var TJDashboardTabulator = {
	renderData: function(method,sourceData)
	{
		this[method](sourceData);
	},
	tjdashtable: function(sourceData)
	{
		//console.log(sourceData.data);
		//var renderData = JSON.parse(sourceData.data);
		jQuery("#"+sourceData.element).tabulator({
			layout:"fitColumns",
			tooltips:true,
			addRowPos:"top",
			history:true,
			pagination:"local",
			paginationSize:5,
			movableColumns:true,
			columns:[
				{title:"id", field:"id", editor:"input", width:10},
				{title:"Course Title", field:"title", editor:"input", width:130},
				{title:"Course Progress", field:"module_data.completionPercent",width:160 ,align:"left", formatter:"progress", editor:true},
				{title:"Due Date", field:"assign_due_date", width:130, sorter:"date", align:"center"},
			],
		});

		jQuery("#"+sourceData.element).tabulator("setData", sourceData.data);
	}
}
