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
			    Reporting Status 
		    </div>
			<div class="panel-body" id="reporting">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
</div>

<div class="row">
	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Cumulative Achievement vs Target (1a) <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="cumulative_pie">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
	<div class="col-md-9 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Achievement (1b) <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="monthly_achievement">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Partner Performance (1c) <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="performance">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Monthly Violence Cases Reported By Type (2a & b) <div class="display_date"></div>
		    </div>
			<div class="panel-body row" id="monthly_cases">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    PEP Provision to Sexual Violence Victims (3) <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="pep">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Violence Case Reports Disaggregated by Age & Sex (4) <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="age_gender">
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
		$("#reporting").html("<center><div class='loader'></div></center>");
		$("#cumulative_pie").html("<center><div class='loader'></div></center>");
		$("#monthly_achievement").html("<center><div class='loader'></div></center>");
		$("#performance").html("<center><div class='loader'></div></center>");
		$("#monthly_cases").html("<center><div class='loader'></div></center>");
		$("#pep").html("<center><div class='loader'></div></center>");
		$("#age_gender").html("<center><div class='loader'></div></center>");

		$("#reporting").load("{{ url('violence/reporting') }}");
		$("#cumulative_pie").load("{{ url('violence/cumulative_pie') }}");
		$("#monthly_achievement").load("{{ url('violence/monthly_achievement') }}");
		$("#performance").load("{{ url('violence/performance') }}");
		$("#monthly_cases").load("{{ url('violence/monthly_cases') }}");
		$("#pep").load("{{ url('violence/pep') }}");
		$("#age_gender").load("{{ url('violence/age_gender') }}");
	}


	$().ready(function(){
		
		date_filter('financial_year', {{ date('Y') }}, '{{ $date_url }}');

		// $("#filter_agency").val(1).change();
	});

</script>

@endsection


