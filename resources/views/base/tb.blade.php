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
			    Newly Tested HIV Testing Outcomes Of TB Patients(*) <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="newly_tested">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Known HIV Statuses Of TB Patients(*) <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="known_status">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    TB Screening <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="tb_screening">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Starting IPT(*) <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="ipt">
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
		$("#known_status").html("<center><div class='loader'></div></center>");
		$("#newly_tested").html("<center><div class='loader'></div></center>");
		$("#tb_screening").html("<center><div class='loader'></div></center>");
		$("#ipt").html("<center><div class='loader'></div></center>");

		$("#known_status").load("{{ url('tb/known_status') }}");
		$("#newly_tested").load("{{ url('tb/newly_tested') }}");
		$("#tb_screening").load("{{ url('tb/tb_screening') }}");
		$("#ipt").load("{{ url('tb/ipt') }}");
	}


	$().ready(function(){
		
		date_filter('financial_year', {{ date('Y') }}, '{{ $date_url }}');

	});

</script>

@endsection


