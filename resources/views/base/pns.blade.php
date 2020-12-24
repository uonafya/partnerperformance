@extends('layouts.master')

@section('content')

<style type="text/css">
	.display_date {
		width: 130px;
		display: inline;
	}
</style>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Summary Chart <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="summary_chart">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    PNS Contribution to DHIS Positives <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="pns_contribution">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    PNS Summary <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="summary_table">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Index Clients Screened <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="screened">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Contacts Identified <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="contacts_identified">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Known HIV Positive Contacts <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="pos_contacts">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Eligible Contacts <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="eligible_contacts">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Contacts Tested <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="contacts_tested">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Newly Identified Positives <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="new_pos">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Linked To HAART <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="linked_haart">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')

<script type="text/javascript">

	function reload_page()
	{
		$("#summary_chart").html("<center><div class='loader'></div></center>");
		$("#pns_contribution").html("<center><div class='loader'></div></center>");
		$("#summary_table").html("<center><div class='loader'></div></center>");
		$("#screened").html("<center><div class='loader'></div></center>");
		$("#contacts_identified").html("<center><div class='loader'></div></center>");
		$("#pos_contacts").html("<center><div class='loader'></div></center>");
		$("#eligible_contacts").html("<center><div class='loader'></div></center>");
		$("#contacts_tested").html("<center><div class='loader'></div></center>");
		$("#new_pos").html("<center><div class='loader'></div></center>");
		$("#linked_haart").html("<center><div class='loader'></div></center>");

		$("#summary_chart").load("{{ url('pns/summary_chart') }}");
		$("#pns_contribution").load("{{ url('pns/pns_contribution') }}");
		$("#summary_table").load("{{ url('pns/summary_table') }}");
		$("#screened").load("{{ url('pns/get_table/screened') }}");
		$("#contacts_identified").load("{{ url('pns/get_table/contacts_identified') }}");
		$("#pos_contacts").load("{{ url('pns/get_table/pos_contacts') }}");
		$("#eligible_contacts").load("{{ url('pns/get_table/eligible_contacts') }}");
		$("#contacts_tested").load("{{ url('pns/get_table/contacts_tested') }}");
		$("#new_pos").load("{{ url('pns/get_table/new_pos') }}");
		$("#linked_haart").load("{{ url('pns/get_table/linked_haart') }}");
	}


	$().ready(function(){
		
		date_filter('financial_year', {{ date('Y') }}, '{{ $date_url }}');

	});

</script>

@endsection


