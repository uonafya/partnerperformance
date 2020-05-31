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
			    Cumulative Achievement vs Target <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="cumulative_pie">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Achievement <div class="display_date"></div>
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
			    Performance <div class="display_date"></div>
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
			    Gender Based Sexual Violence By Gender <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="gender">
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
		$("#cumulative_pie").html("<center><div class='loader'></div></center>");
		$("#monthly_achievement").html("<center><div class='loader'></div></center>");
		$("#performance").html("<center><div class='loader'></div></center>");

		$("#cumulative_pie").load("{{ url('violence/cumulative_pie') }}");
		$("#monthly_achievement").load("{{ url('violence/monthly_achievement') }}");
		$("#performance").load("{{ url('violence/performance') }}");
	}


	$().ready(function(){
		
		date_filter('financial_year', {{ date('Y') }}, '{{ $date_url }}');

		// $("#filter_agency").val(1).change();
	});

</script>

@endsection


