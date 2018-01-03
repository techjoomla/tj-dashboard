//@ Todo This Must be Object Oriented 
var TJDashboardtjdashhtml = { 
	renderData: function (method,sourceData)
	{
		console.log('TjDashHtml RendereData Method');
		this[method](sourceData);
	},
	plainhtml: function (data)
	{
		var renderData = JSON.parse(data.data);
		jQuery('<div class="alert alert-danger">Enrolled Courses '+renderData+'</div>').appendTo('.tjdashboard');
	}
}
