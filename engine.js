var base_url = "http://data.jakarta.go.id/api/3/action/";
var ahay;
$.ajax({
	url: base_url + "organization_list",
	dataType: 'jsonp',
	success: function(results){
		orgList = results.result;
		ahay = orgList;
	}
});