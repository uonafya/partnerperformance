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
	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Testing Gender* <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="gender">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>

	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Testing Age* <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="age">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Discordancy <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="discordancy">
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

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Testing By Age And Gender* <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="testing_summary">
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
		$("#gender").html("<center><div class='loader'></div></center>");
		$("#age").html("<center><div class='loader'></div></center>");
		$("#discordancy").html("<center><div class='loader'></div></center>");
		$("#summary").html("<center><div class='loader'></div></center>");
		$("#testing_summary").html("<center><div class='loader'></div></center>");

		$("#testing_outcomes").load("{{ url('testing/testing_outcomes') }}");
		$("#positivity").load("{{ url('testing/positivity') }}");
		$("#gender").load("{{ url('testing/testing_gender') }}");
		$("#age").load("{{ url('testing/testing_age') }}");
		$("#discordancy").load("{{ url('testing/discordancy') }}");
		$("#summary").load("{{ url('testing/summary') }}");
		$("#testing_summary").load("{{ url('testing/testing_summary') }}");
	}


	$().ready(function(){
		
		date_filter('financial_year', {{ date('Y') }}, '{{ $date_url }}');

	});

</script>

@endsection


