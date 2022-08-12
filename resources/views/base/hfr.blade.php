@extends('layouts.master')

@section('content')

<style type="text/css">
	.display_date {
		width: 130px;
		display: inline;
	}
	.detail_date {
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
			<center>
				<div class="loader">					
				</div>
			</center>


			</div>
		</div>
	</div>
</div>

<div class="row ">
	<div class="col-md-12 col-sm-12 col-xs-12 detail_tables">
		<div class="panel panel-default">
		    <div class="panel-heading">
				
			    HTS Testing & Yield Details   <div class="detail_date"></div>
		    </div>
			<div class="panel-body" id="testing_dis">
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

<div class="row ">
	<div class="col-md-12 col-sm-12 col-xs-12 detail_tables">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Linkage (HTS POS & TX NEW) Details <div class="detail_date"></div>
		    </div>
			<div class="panel-body" id="linkage_dis">
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
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			   Tx New <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="tx_new">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>
<div class="row ">
	<div class="col-md-12 col-sm-12 col-xs-12 detail_tables">
		<div class="panel panel-default">
		    <div class="panel-heading">
				
			    Tx New Details  <div class="detail_date"></div>
		    </div>
			<div class="panel-body" id="tx_new_dis">
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
<div class="row ">
<div class="col-md-12 col-sm-12 col-xs-12 detail_tables">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    TX CURR Detail  <div class="detail_date"></div>
		    </div>
			<div class="panel-body" id="tx_curr_details">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row ">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">				
			    Tx Curr Trend  <div class="detail_date"></div>
		    </div>
			<div class="panel-body" id="tx_curr_trend">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>
<div class="row ">
	<div class="col-md-12 col-sm-12 col-xs-12 detail_tables">
		<div class="panel panel-default">
		    <div class="panel-heading">				
			    Tx Curr Trend Detail  <div class="detail_date"></div>
		    </div>
			<div class="panel-body" id="tx_curr_trend_details">
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
			    TX_Curr Crude Retention Trend <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="tx_crude_trend">
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
			    TX MMD Detail <div class="detail_date"></div>
		    </div>
			<div class="panel-body" id="tx_mmd_detail">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

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
				Prep New Details   <div class="detail_date"></div>
			</div>
			<div class="panel-body" id="prep_new_last_rpt_period">
				<center><div class="loader"></div></center>
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

<div class="row ">
	<div class="col-md-12 col-sm-12 col-xs-12 detail_tables">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    VMMC CIRC Details  <div class="detail_date"></div>
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

	$().ready(function(){
		$("#misassigned_facilities").html("<center><div class='loader'></div></center>");
		$("#misassigned_facilities").load("{{ url('hfr/misassigned_facilities') }}");

		// $("#testing").load("{{ url('hfr/testing') }}");

		// $.ajax({
		// 	type: "GET",
		// 	url:"{{ url('hfr/testing') }}/",
		// 	// cache: false,
		// 	// contentType: false,
		// 	// processData: false,
		// 	dataType: 'html',
		// 	async: true,
		// 	success: function (data) {
		// 		$("#testing").html(data);

		// 		console.log(data);	

		// 	},
		// 	error: function (xhr, textStatus, errorThrown) {
		// 		console.log("XHR",xhr);
		// 		console.log("status",textStatus);
		// 		console.log("Error in",errorThrown);
		// 	}
		// });

		// $.ajax({
		// 	type: "GET",
		// 	url:"{{ url('hfr/testing_dis') }}/",
		// 	cache: true,
		// 	// contentType: false,
		// 	// processData: false,
		// 	dataType: 'html',
		// 	async: true,
		// 	success: function (data) {
		// 		$("#testing_dis").html(data);

		// 		console.log(data);	

		// 	},
		// 	error: function (xhr, textStatus, errorThrown) {
		// 		console.log("XHR",xhr);
		// 		console.log("status",textStatus);
		// 		console.log("Error in",errorThrown);
		// 	}
		// });

		// $.ajax({
		// 	type: "GET",
		// 	url:"{{ url('hfr/linkage') }}/",
		// 	cache: true,
		// 	// contentType: false,
		// 	// processData: false,
		// 	dataType: 'html',
		// 	async: true,
		// 	success: function (data) {
		// 		$("#linkage").html(data);

		// 		console.log(data);	

		// 	},
		// 	error: function (xhr, textStatus, errorThrown) {
		// 		console.log("XHR",xhr);
		// 		console.log("status",textStatus);
		// 		console.log("Error in",errorThrown);
		// 	}
		// });

		
		date_filter('financial_year', {{ date('Y') }}, '{{ $date_url }}');
	});

	function reload_page()
	{
		$.ajax({
			type: "GET",
			url:"{{ url('hfr/testing') }}/",
			cache: true,
			// contentType: false,
			// processData: false,
			dataType: 'html',
			async: true,
			success: function (data) {
				$("#testing").html(data);

				console.log(data);	

			},
			error: function (xhr, textStatus, errorThrown) {
				console.log("XHR",xhr);
				console.log("status",textStatus);
				console.log("Error in",errorThrown);
			}
		});

		$.ajax({
			type: "GET",
			url:"{{ url('hfr/testing_dis') }}/",
			cache: true,
			// contentType: false,
			// processData: false,
			dataType: 'html',
			async: true,
			success: function (data) {
				$("#testing_dis").html(data);

				console.log(data);	

			},
			error: function (xhr, textStatus, errorThrown) {
				console.log("XHR",xhr);
				console.log("status",textStatus);
				console.log("Error in",errorThrown);
			}
		});

		$.ajax({
			type: "GET",
			url:"{{ url('hfr/linkage') }}/",
			// cache: false,
			// contentType: false,
			// processData: false,
			dataType: 'html',
			async: true,
			success: function (data) {
				$("#linkage").html(data);

				console.log(data);	

			},
			error: function (xhr, textStatus, errorThrown) {
				console.log("XHR",xhr);
				console.log("status",textStatus);
				console.log("Error in",errorThrown);
			}
		});

	}

</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.js" integrity="sha512-dBB2PGgYedA6vzize7rsf//Q6iuUuMPvXCDybHtZP3hQXCPCD/YVJXK3QYZ2v0p7YCfVurqr8IdcSuj4CCKnGg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">
    function downloadPDF() {
		$(".detail_tables").removeClass("hidden");
	
		
		///////
		var element = document.getElementById('body');
		var opt = {
			margin: 0,
			filename:     'HFR-'+new Date().toLocaleString('en-GB').replaceAll('/', '_').replaceAll(' ', '_').toLowerCase().replaceAll(',','_').replaceAll(':','_')+'.pdf',
			image:        { type: 'jpeg',},
			html2canvas:  { scale: 1, },
			jsPDF:        { unit: 'mm', format: 'a2', orientation: 'landscape' },
			pagebreak: { mode: 'avoid-all' }
		};
		// html2pdf().set(opt).from(element).save();
		html2pdf(element, opt);
		$(document).ready(function(){
		setTimeout(() => { hide()}, 2300);
	});
	
		///////
    }
	function hide(){
		$(".detail_tables").addClass("hidden");
	}
</script>
{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script> --}}

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
