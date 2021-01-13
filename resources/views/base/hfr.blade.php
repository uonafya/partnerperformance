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
			    HTS Testing & Yield <div class="display_date"></div>
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
			    Linkage (HTS POS & TX NEW) <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="linkage">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    TX CURR <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="tx_curr">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    TX MMD <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="tx_mmd">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    PrEP NEW <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="prep_new">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    VMMC CIRC <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="vmmc_circ">
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
		$("#testing").html("<center><div class='loader'></div></center>");
		$("#linkage").html("<center><div class='loader'></div></center>");
		$("#tx_curr").html("<center><div class='loader'></div></center>");
		$("#tx_mmd").html("<center><div class='loader'></div></center>");
		$("#prep_new").html("<center><div class='loader'></div></center>");
		$("#vmmc_circ").html("<center><div class='loader'></div></center>");
		
		$("#target_donut_hts").html("<center><div class='loader'></div></center>");
		$("#target_donut_pos").html("<center><div class='loader'></div></center>");
		$("#target_donut_tx_new").html("<center><div class='loader'></div></center>");
		$("#target_donut_vmmc_circ").html("<center><div class='loader'></div></center>");
		$("#target_donut_prep_new").html("<center><div class='loader'></div></center>");
		$("#target_donut_tx_curr").html("<center><div class='loader'></div></center>");

		$("#testing").load("{{ url('hfr/testing') }}");
		$("#linkage").load("{{ url('hfr/linkage') }}");
		$("#tx_curr").load("{{ url('hfr/tx_curr') }}");
		$("#tx_mmd").load("{{ url('hfr/tx_mmd') }}");
		$("#prep_new").load("{{ url('hfr/prep_new') }}");
		$("#vmmc_circ").load("{{ url('hfr/vmmc_circ') }}");

		$("#target_donut_hts").load("{{ url('hfr/target_donut/hts_tst') }}");
		$("#target_donut_pos").load("{{ url('hfr/target_donut/hts_tst_pos') }}");
		$("#target_donut_tx_new").load("{{ url('hfr/target_donut/tx_new') }}");
		$("#target_donut_vmmc_circ").load("{{ url('hfr/target_donut/vmmc_circ') }}");
		$("#target_donut_prep_new").load("{{ url('hfr/target_donut/prep_new') }}");
		$("#target_donut_tx_curr").load("{{ url('hfr/target_donut/tx_curr') }}");
	}


	$().ready(function(){
		
		date_filter('financial_year', 2021, '{{ $date_url }}');

	});

</script>

@endsection

