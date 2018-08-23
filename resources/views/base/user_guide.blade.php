@extends('layouts.master')

@section('content')

<style type="text/css">
	.display_date {
		width: 130px;
		display: inline;
	}
	.display_date {
		width: 130px;
		display: inline;
	}
	p {
		font-size: 18px;
	}
</style>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    User Guide 
		    </div>
			<div class="panel-body" id="user_guide">
				<p>
					This dashboard pulls data from the old and new MOH 731 forms from DHIS. These forms are however not completely identical and some fields present in one form is absent in the other. When a field in a chart or table has a single asterisk beside it e.g. Males Tested PNC (*), it means that this field is picking only from the new form. If a field has two asterisks (**), it means that this field is picking only from the old form. Otherwise, the field aggregates the data from the old and new forms.
				</p>
				<p>
					There are a number of limitations imposed by the use of 2 forms e.g. the old MOH 731 form does not have testing and positives data by age or gender disaggregations whereas the new one has those disaggregations. In order to display the data from both forms, it therefore means we limit display to totals instead of disaggregated by age or gender. 
				</p>
				<p>
					Each page has a number of dropdowns at the top. You can use them to filter to specific datasets e.g. you can select a partner, then to see how the partner is performing in a county you can select the desired county.
					The group by dropdown is used to determine in what divisions you would like to see in the tables that can be found on that page e.g. if a partner is selected, you can select group by county to see how the partner is performing in their counties. You can then select a particular county and then select group by subcounty to see how the partner is performing in the subcounties of the selected county.
				</p>
				<p>
					If you are a partner and you have been given credentials, once you login you should see some more links. Download a template e.g. a non mer template for a particular financial year, fill in the excel with your data then upload the data on the link <b> Upload Non Mer</b>.
				</p>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')

@endsection


