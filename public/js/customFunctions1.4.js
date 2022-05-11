function format_date(data)
	{
		date = data.split(" ");
		var month = date[0];
	    month = month.toLowerCase();
	    var months = ["january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december"];
	    month = months.indexOf(month)+1;
		
		return [month, date[1]];
	}




function set_select_facility(div_name, url, minimum_length, placeholder) {
	div_name = '#' + div_name;		

	$(div_name).select2({
		minimumInputLength: minimum_length,
		placeholder: placeholder,
		allowClear: true,
		ajax: {
			delay	: 100,
			type	: "POST",
			dataType: 'json',
			data	: function(params){
				return {
					search : params.term
				}
			},
			url		: function(params){
				params.page = params.page || 1;
				return  url + "?page=" + params.page;
			},
			processResults: function(data, params){
				return {
					results 	: $.map(data.data, function (row){
						return {
							text	: row.facilitycode + ' - ' + row.name, 
							id		: row.id		
						};
					}),
					pagination	: {
						more: data.to < data.total
					}
				};
			}
		}
	});
}

function set_multiple_date(first, second){
	var f = first.split(" ");
	var s = second.split(" ");
	// return f[0] + "-" + s[0] + " " + f[1];
	return f[0] + ", " + f[1] + " - " + s[0] + ", " + s[1];
}

function check_error_date_range (first, second) {
	firstMonth = first[0];
	firstYear = first[1];

	secondMonth = second[0];
	secondYear = second[1];

	var returnVal = true;

	var content = "";

	if (firstYear == secondYear) {
		if (firstMonth <= secondMonth) {
			returnVal = false;
		} else {
			content = "<strong>Warning!</strong>The from month is greater than the to month!";
			doAlert('errorAlert', content);
		}
	} else {
		if(firstYear > secondYear){
			content = "<strong>Warning!</strong>The from year is greater than the to year!";
			doAlert('errorAlert', content);
		}else{
			returnVal = false;
		}
	}

	return returnVal;
}

function doAlert(placementId, Content)
{
	$("#"+placementId).html(Content);
    $("#errorAlertDateRange").show();
    setTimeout(function(){$('#errorAlertDateRange').hide(); }, 5000);
}

	
function date_filter(criteria, id, date_url)
{
	var date_object;
	if (criteria === "monthly") {
		date_object = { 'month': id };
	}else if(criteria === "yearly"){
		date_object = { 'year': id };
	}else if(criteria == 'financial_year'){
		date_object = { 'financial_year': id };
	}else if(criteria == 'quarter'){
		date_object = { 'quarter': id };
	}else{
		date_object = id;
	}

	var posting = $.post(date_url, date_object);
    var all = localStorage.getItem("my_var");

		// Put the results in a div
	posting.done(function( obj ) {

		if(obj.month == "null" || obj.month == null){
			obj.month = "";
		}
		console.log(obj);

		if(typeof obj.display_date !== 'undefined' && criteria != 'date_range'){
			$(".display_date").html(obj.display_date);
			$(".detail_date").html(obj.detail_date);
		}
		
		$(".display_range").html("( "+obj.prev_year +" - "+obj.year +" )");

		reload_page();
		
	});
	
	posting.fail(function( data ) {		
		location.reload(true);
	});
}