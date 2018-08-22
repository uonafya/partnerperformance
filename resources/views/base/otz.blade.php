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
			    Breakdown <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="breakdown">
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


		$("#facilities_count").load("{{ secure_url('otz/facilities_count') }}");
		$("#clinics").load("{{ secure_url('otz/clinics') }}");
		$("#achievement").load("{{ secure_url('otz/achievement') }}");
		$("#breakdown").load("{{ secure_url('otz/breakdown') }}");
	}


	$().ready(function(){
		
		date_filter('financial_year', {{ date('Y') }}, '{{ $date_url }}');

	});

</script>

@endsection


