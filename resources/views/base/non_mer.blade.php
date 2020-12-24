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
			    Facilities Count (That have at least one beneficiary)<div class="display_date"></div>
		    </div>
			<div class="panel-body" id="facilities_count">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Clinics<div class="display_date"></div>
		    </div>
			<div class="panel-body" id="clinics">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Number of Beneficiaries <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="achievement">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Beneficiaries Breakdown <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="breakdown">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Clinic Setup Breakdown <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="clinic_setup">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    OTZ Impact <div class="display_current_range"></div>
		    </div>
			<div class="panel-body" id="otz_breakdown">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    DSD Coverage <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="dsd_impact">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Men Clinic Coverage <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="mens_impact">
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
		$("#facilities_count").html("<center><div class='loader'></div></center>");
		$("#clinics").html("<center><div class='loader'></div></center>");
		$("#achievement").html("<center><div class='loader'></div></center>");
		$("#breakdown").html("<center><div class='loader'></div></center>");
		$("#clinic_setup").html("<center><div class='loader'></div></center>");
		$("#otz_breakdown").html("<center><div class='loader'></div></center>");
		$("#dsd_impact").html("<center><div class='loader'></div></center>");
		$("#mens_impact").html("<center><div class='loader'></div></center>");


		$("#facilities_count").load("{{ url('non_mer/facilities_count') }}");
		$("#clinics").load("{{ url('non_mer/clinics') }}");
		$("#achievement").load("{{ url('non_mer/achievement') }}");
		$("#breakdown").load("{{ url('non_mer/breakdown') }}");
		$("#clinic_setup").load("{{ url('non_mer/clinic_setup') }}");
		$("#otz_breakdown").load("{{ url('non_mer/otz_breakdown') }}");
		$("#dsd_impact").load("{{ url('non_mer/dsd_impact') }}");
		$("#mens_impact").load("{{ url('non_mer/mens_impact') }}");
	}


	$().ready(function(){
		
		date_filter('financial_year', {{ date('Y') }}, '{{ $date_url }}');

	});

</script>

@endsection


