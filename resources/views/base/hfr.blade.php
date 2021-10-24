@extends('layouts.master')

@section('content')

<style type="text/css">
	.display_date {
		width: 130px;
		display: inline;
	}
</style>


<!-- <div class="row">
	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Testing Target <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="target_donut_hts">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
	<div class="col-md-9 col-sm-12 col-xs-12">
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
	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Positives Target <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="target_donut_pos">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
	<div class="col-md-9 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Linkage (HTS POS & TX NEW) <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="linkage">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div> -->



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
	<div class="col-md-4 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Testing Target <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="target_donut_hts">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Positives Target <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="target_donut_pos">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    TX New Target <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="target_donut_tx_new">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    TX CURR Target <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="target_donut_tx_curr">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
	<div class="col-md-9 col-sm-12 col-xs-12">
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
			    TX_Curr Crude Retention <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="tx_crude">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			Net New <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="net_new">
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
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    TX MMD Detail <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="tx_mmd_detail">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    PrEP New Target <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="target_donut_prep_new">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
	<div class="col-md-9 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    PrEP NEW <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="prep_new">
				<center><div class="loader"></div></center>
			</div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Prep New <div class="display_date"></div>
                        </div>
                        <div class="panel-body" id="prep_new_last_rpt_period">
                            <center><div class="loader"></div></center>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    VMMC Target <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="target_donut_vmmc_circ">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
	<div class="col-md-9 col-sm-12 col-xs-12">
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

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">Facilities With HFR Data that are not assigned to USAID Partners <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="misassigned_facilities">
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
		$("#tx_mmd_detail").html("<center><div class='loader'></div></center>");
		$("#tx_crude").html("<center><div class='loader'></div></center>");
		$("#net_new").html("<center><div class='loader'></div></center>");
		$("#prep_new").html("<center><div class='loader'></div></center>");
		$("#vmmc_circ").html("<center><div class='loader'></div></center>");
        $("#prep_new_last_rpt_period").html("<center><div class='loader'></div></center>");
       
		$("#target_donut_hts").html("<center><div class='loader'></div></center>");
		$("#target_donut_pos").html("<center><div class='loader'></div></center>");
		$("#target_donut_tx_new").html("<center><div class='loader'></div></center>");
		$("#target_donut_vmmc_circ").html("<center><div class='loader'></div></center>");
		$("#target_donut_prep_new").html("<center><div class='loader'></div></center>");
		$("#target_donut_tx_curr").html("<center><div class='loader'></div></center>");

		$("#prep_new_last_rpt_period").load("{{ url('hfr/prep_new_last_rpt_period') }}");
		$("#net_new").load("{{ url('hfr/net_new') }}");
		$("#tx_crude").load("{{ url('hfr/tx_crude') }}");
		$("#testing").load("{{ url('hfr/testing') }}");
		$("#linkage").load("{{ url('hfr/linkage') }}");
		$("#tx_curr").load("{{ url('hfr/tx_curr') }}");
		$("#tx_mmd").load("{{ url('hfr/tx_mmd') }}");
		$("#tx_mmd_detail").load("{{ url('hfr/tx_mmd_detail') }}");
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

		$("#misassigned_facilities").html("<center><div class='loader'></div></center>");
		$("#misassigned_facilities").load("{{ url('hfr/misassigned_facilities') }}");


		
		date_filter('financial_year', 2021, '{{ $date_url }}');

	});

</script>

@endsection


