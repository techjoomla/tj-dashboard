function renderData(method,sourceData)
{
	this[method](sourceData);
}
function bar(data)
{
	var renderData = JSON.parse(data.data);
	Morris.Bar({
		element: data.element,
		data: renderData,
		xkey: 'x',
		ykeys: ['y'],
		labels: ['Y'],
		barColors: 'rgb(0, 84, 141)',
		barSize: '30',
		yLabelMargin: 10,
	});
}
function donut(data)
{
	var renderData = JSON.parse(data.data);

	//actual code
	Morris.Donut({
		element: data.element,
		data: renderData
	});
}
