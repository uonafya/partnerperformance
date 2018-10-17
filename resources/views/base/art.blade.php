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
</style>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Facilities Reporting ART per month <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="reporting">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Currently On Treatment (Age Breakdown) <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="current_age_breakdown">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    New On Treatment (Age Breakdown) <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="new_age_breakdown">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Enrolled On Treatment (Age Breakdown) <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="enrolled_age_breakdown">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    New On Treatment <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="new_art">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Currently On Treatment <span id="current_art_title"></span>
		    </div>
			<div class="panel-body" id="current_art">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Current Suppression (FY 2018)
		    </div>
			<div class="panel-body" id="current_suppression">
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
		$("#current_age_breakdown").html("<center><div class='loader'></div></center>");
		$("#new_age_breakdown").html("<center><div class='loader'></div></center>");
		$("#enrolled_age_breakdown").html("<center><div class='loader'></div></center>");
		$("#new_art").html("<center><div class='loader'></div></center>");
		$("#current_art").html("<center><div class='loader'></div></center>");
		$("#current_suppression").html("<center><div class='loader'></div></center>");

		$("#reporting").load("{{ url('art/reporting') }}");
		$("#current_age_breakdown").load("{{ url('art/current_age_breakdown') }}");
		$("#new_age_breakdown").load("{{ url('art/new_age_breakdown') }}");
		$("#enrolled_age_breakdown").load("{{ url('art/enrolled_age_breakdown') }}");
		$("#new_art").load("{{ url('art/new_art') }}");
		$("#current_art").load("{{ url('art/current_art') }}");
		$("#current_suppression").load("{{ url('art/current_suppression') }}");
	}


	$().ready(function(){
		
		date_filter('financial_year', {{ date('Y') }}, '{{ $date_url }}');

	});

</script>

@endsection


