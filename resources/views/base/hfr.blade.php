@extends('layouts.master')

@section('content')

<style type="text/css">
	.display_date {
		width: 130px;
		display: inline;
	}
</style>

<div class="row">
	<div class="panel-heading">
	<a href="javascript:downloadPDF()" class="button" id="cmd">Download PDF</a>
	</div>		
</div>

<div class="content-body" id="body">
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
<div class="row ">
	<div class="col-md-12 col-sm-12 col-xs-12 detail_tables">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    HTS Testing & Yield Details (Last Month) <div class=""></div>
		    </div>
			<div class="panel-body" id="testing_dis">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>
<div class="html2pdf__page-break"></div>

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

<div class="row ">
	<div class="col-md-12 col-sm-12 col-xs-12 detail_tables">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Linkage (HTS POS & TX NEW) Details (Last Month)<div class=""></div>
		    </div>
			<div class="panel-body" id="linkage_dis">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="html2pdf__page-break"></div>

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
	<div class="html2pdf__page-break"></div>
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
<div class="html2pdf__page-break"></div>

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
	<div class="html2pdf__page-break"></div>
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
<div class="row ">
<div class="col-md-12 col-sm-12 col-xs-12 detail_tables">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    TX CURR Detail (Last Month) <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="tx_curr_details">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>	
<div class="html2pdf__page-break"></div>

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
<div class="html2pdf__page-break"></div>

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
	<!-- <div class="col-md-12 col-sm-12 col-xs-12 detail_tables">
		<div class="panel panel-default">
		    <div class="panel-heading">
			Net New Detail <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="net_new_detail">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div> -->
</div>
<div class="html2pdf__page-break"></div>

<div class=" row col-sm-12 col-xs-12">
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
	<div class="col-md-12 col-sm-12 col-xs-12 detail_tables">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    TX MMD Detail (Last Month) <div class=""></div>
		    </div>
			<div class="panel-body" id="tx_mmd_detail">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>
<div class="html2pdf__page-break"></div>

<div class="row col-sm-12 col-xs-12 ">
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
	<div class="html2pdf__page-break"></div>
	<div class="col-md-9 col-sm-12 col-xs-12">
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

<div class="row ">
	<div class="col-md-12 col-sm-12 col-xs-12 detail_tables">
		<div class="panel panel-default">
			<div class="panel-heading">
				Prep New Details (Last Month)  <div class=""></div>
			</div>
			<div class="panel-body" id="prep_new_last_rpt_period">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>
<div class="html2pdf__page-break"></div>

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

<div class="row ">
<div class="col-md-12 col-sm-12 col-xs-12 detail_tables">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    VMMC CIRC Details (Last Month) <div class=""></div>
		    </div>
			<div class="panel-body" id="vmmc_circ_details">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>

</div>

<!-- <div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">Facilities With HFR Data that are not assigned to USAID Partners <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="misassigned_facilities">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div> -->
</div>
@endsection


@section('scripts')

<script type="text/javascript">

	function reload_page()
	{
		$("#testing").html("<center><div class='loader'></div></center>");
		$("#testing_dis").html("<center><div class='loader'></div></center>");
		$("#linkage").html("<center><div class='loader'></div></center>");
		$("#linkage_dis").html("<center><div class='loader'></div></center>");
		$("#tx_curr").html("<center><div class='loader'></div></center>");
		$("#tx_mmd").html("<center><div class='loader'></div></center>");
		$("#tx_mmd_detail").html("<center><div class='loader'></div></center>");
		$("#tx_crude").html("<center><div class='loader'></div></center>");
		$("#net_new").html("<center><div class='loader'></div></center>");
		$("#net_new_detail").html("<center><div class='loader'></div></center>");
		$("#prep_new").html("<center><div class='loader'></div></center>");
		$("#vmmc_circ").html("<center><div class='loader'></div></center>");
        $("#prep_new_last_rpt_period").html("<center><div class='loader'></div></center>");
		$("#tx_curr_details").html("<center><div class='loader'></div></center>");
		$("#vmmc_circ_details").html("<center><div class='loader'></div></center>");
		
		
       
		$("#target_donut_hts").html("<center><div class='loader'></div></center>");
		$("#target_donut_pos").html("<center><div class='loader'></div></center>");
		$("#target_donut_tx_new").html("<center><div class='loader'></div></center>");
		$("#target_donut_vmmc_circ").html("<center><div class='loader'></div></center>");
		$("#target_donut_prep_new").html("<center><div class='loader'></div></center>");
		$("#target_donut_tx_curr").html("<center><div class='loader'></div></center>");

		$("#prep_new_last_rpt_period").load("{{ url('hfr/prep_new_last_rpt_period') }}");
		$("#testing_dis").load("{{ url('hfr/testing_dis') }}");
		$("#vmmc_circ_details").load("{{ url('hfr/vmmc_circ_details') }}");
		$("#tx_curr_details").load("{{ url('hfr/tx_curr_details') }}");
		$("#net_new").load("{{ url('hfr/net_new') }}");
		$("#net_new_detail").load("{{ url('hfr/net_new_detail') }}");
		$("#tx_crude").load("{{ url('hfr/tx_crude') }}");
		$("#testing").load("{{ url('hfr/testing') }}");
		$("#linkage").load("{{ url('hfr/linkage') }}");
		$("#linkage_dis").load("{{ url('hfr/linkage_dis') }}");
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    function downloadPDF() {
		var element = document.getElementById('body');
		var opt = {
		margin:       1,
		filename:     'hts.pdf',
		image:        { type: 'jpg', quality: 0.5 },
		html2canvas:  { scale: 2 },
		jsPDF:        { unit: 'mm', format: 'a2', orientation: 'landscape',precision:16 }
		};

		// Old monolithic-style usage:
		// html2pdf(element, opt);
		html2pdf().set(opt).from(element).save();

    }
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0/jspdf.min.js"></script>

<script>
	$(".detail_tables").addClass("hidden");
	$(document).ready(function(){
		setTimeout(() => {
			document.querySelectorAll('.dt-buttons').forEach(function(e){
				if(e != null && e.props != undefined){
					e.props['data-html2canvas-ignore'] = "true";
				}
			});
			document.querySelectorAll('.dataTables_filter').forEach(function(e){
				if(e != null && e.props != undefined){
					e.props['data-html2canvas-ignore'] = "true";
				}
			});
			document.querySelectorAll('.dataTables_paginate').forEach(function(e){
				if(e != null && e.props != undefined){
					e.props['data-html2canvas-ignore'] = "true";
				}
			});
			document.querySelectorAll('.dataTables_length').forEach(function(e){
				if(e != null && e.props != undefined){
					e.props['data-html2canvas-ignore'] = "true";
				}
			});
			document.querySelectorAll('.navbar.navbar-default').forEach(function(e){
				if(e != null && e.props != undefined){
					e.props['data-html2canvas-ignore'] = "true";
				}
			});
			document.querySelectorAll('.dt-button').forEach(function(e){
				if(e != null && e.props != undefined){
					e.props['data-html2canvas-ignore'] = "true";
				}
			});
		}, 1200);
	});
</script>


@endsection


