var TJDashboardChartjs = {
	renderData: function(method,sourceData)
	{
		this[method](sourceData);
	},
	tjdashgraph: function(sourceData)
	{
		var renderData = JSON.parse(sourceData.data);
		var elementdiv = Math.random();
		jQuery('#'+sourceData.element).html('<canvas id='+elementdiv+'></canvas>');
		var ctx = document.getElementById(elementdiv);
		new Chart(ctx, renderData.data);
	}
}
