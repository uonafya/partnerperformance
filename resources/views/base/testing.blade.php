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
			    Testing Outcomes <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="testing_outcomes">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Positivity <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="positivity">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Summary <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="summary">
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
		$("#testing_outcomes").html("<center><div class='loader'></div></center>");
		$("#positivity").html("<center><div class='loader'></div></center>");
		$("#summary").html("<center><div class='loader'></div></center>");

		$("#testing_outcomes").load("{{ url('testing/testing_outcomes') }}");
		$("#positivity").load("{{ url('testing/positivity') }}");
		$("#summary").load("{{ url('testing/summary') }}");
	}


	$().ready(function(){
		
		date_filter('financial_year', {{ date('Y') }}, '{{ $date_url }}');

	});

</script>

@endsection


