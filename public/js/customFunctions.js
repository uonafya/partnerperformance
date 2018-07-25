function format_date(data)
	{
		date = data.split(" ");
		var month = date[0];
	    month = month.toLowerCase();
	    var months = ["january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december"];
	    month = months.indexOf(month)+1;
		
		return [month, date[1]];
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