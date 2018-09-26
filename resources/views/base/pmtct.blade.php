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
			    On Maternal HAART  <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="haart">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Testing PMTCT <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="testing">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Point Of Starting ART <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="starting_point">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Point of Identification of Positivity <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="discovery_positivity">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Initial PCR Test <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="eid">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Male Testing <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="male_testing">
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
		$("#haart").html("<center><div class='loader'></div></center>");		
		$("#testing").html("<center><div class='loader'></div></center>");
		$("#starting_point").html("<center><div class='loader'></div></center>");
		$("#discovery_positivity").html("<center><div class='loader'></div></center>");
		$("#eid").html("<center><div class='loader'></div></center>");
		$("#male_testing").html("<center><div class='loader'></div></center>");


		$("#haart").load("{{ url('pmtct/haart') }}");
		$("#testing").load("{{ url('pmtct/testing') }}");
		$("#starting_point").load("{{ url('pmtct/starting_point') }}");
		$("#discovery_positivity").load("{{ url('pmtct/discovery_positivity') }}");
		$("#eid").load("{{ url('pmtct/eid') }}");
		$("#male_testing").load("{{ url('pmtct/male_testing') }}");
	}


	$().ready(function(){
		
		date_filter('financial_year', {{ date('Y') }}, '{{ $date_url }}');

	});

</script>

@endsection


