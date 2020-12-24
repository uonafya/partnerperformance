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
			    Testing <div class="display_date"></div>
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
			    New On Treatment & Linkage <div class="display_date"></div>
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
			    Positives By Modality <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="modality_yield">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Yield By Gender <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="gender_yield">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Positives By Age <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="age_yield">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    PNS Cascade <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="pns">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    TX New Second Visit <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="tx_sv">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    LTFU Restored to Treatment <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="tx_btc">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Target Achievement <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="targets">
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
		$("#modality_yield").html("<center><div class='loader'></div></center>");
		$("#gender_yield").html("<center><div class='loader'></div></center>");
		$("#age_yield").html("<center><div class='loader'></div></center>");
		$("#pns").html("<center><div class='loader'></div></center>");
		$("#tx_sv").html("<center><div class='loader'></div></center>");
		$("#tx_btc").html("<center><div class='loader'></div></center>");
		$("#targets").html("<center><div class='loader'></div></center>");

		$("#testing").load("{{ url('surge/testing') }}");
		$("#linkage").load("{{ url('surge/linkage') }}");
		$("#modality_yield").load("{{ url('surge/modality_yield') }}");
		$("#gender_yield").load("{{ url('surge/gender_yield') }}");
		$("#age_yield").load("{{ url('surge/age_yield') }}");
		$("#pns").load("{{ url('surge/pns') }}");
		$("#tx_sv").load("{{ url('surge/tx_sv') }}");
		$("#tx_btc").load("{{ url('surge/tx_btc') }}");
		$("#targets").load("{{ url('surge/targets') }}");
	}


	$().ready(function(){
		
		// date_filter('financial_year', {{ date('Y') }}, '{{ $date_url }}');

		$("#filter_agency").val(1).change();
		$(".display_date").html("{{ $display_date }}");

		// var dt = new Date();
		// var fy = dt.getFullYear();

		// date_object = { 'financial_year': fy };
		// var posting = $.post('{{ $date_url }}', date_object);

		// posting.done(function( obj ) {

		// 	if(obj.month == "null" || obj.month == null){
		// 		obj.month = "";
		// 	}
		// 	console.log(obj);
		// 	$(".display_date").html(obj.display_date);		
		// });
	});

</script>

@endsection


