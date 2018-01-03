//@ Todo This Must be Object Oriented 
function renderData(method,sourceData)
{
	console.log("Sudhir Heere"+method);
	console.log("========="+sourceData);
	this[method](sourceData);
}
function plainhtml(data)
{
	var renderData = JSON.parse(data.data);
	console.log("=====********"+renderData);
	jQuery('<div class="alert alert-danger">Enrolled Courses '+renderData+'</div>').appendTo('.tjdashboard');
}
function iconhtml(data)
{
	var renderData = JSON.parse(data.data);
	console.log("=====********"+renderData);
	jQuery('<div class="alert alert-danger fa-address-card">Enrolled Courses '+renderData+'</div>').appendTo('.tjdashboard');
}
