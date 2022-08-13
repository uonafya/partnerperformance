@extends('layouts.master-lte')

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
			    Treatment <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="treatment">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Current on Treatment Trend <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="currenttx">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    New on Treatment Trend <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="newtx">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Positive Outcomes Gender <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="gender">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>

	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Positive Outcomes Age <div class="display_date"></div>
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
			    PMTCT <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="pmtct">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    EID Initial PCR <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="eid">
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
			    New On Treatment <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="art_new">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Currently On Treatment <span id="current_art_title"></span>
		    </div>
			<div class="panel-body" id="art_current">
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
		$("#treatment").html("<center><div class='loader'></div></center>");		
		$("#currenttx").html("<center><div class='loader'></div></center>");
		$("#newtx").html("<center><div class='loader'></div></center>");

		$("#gender").html("<center><div class='loader'></div></center>");
		$("#age").html("<center><div class='loader'></div></center>");
		$("#pmtct").html("<center><div class='loader'></div></center>");
		$("#eid").html("<center><div class='loader'></div></center>");

		$("#summary").html("<center><div class='loader'></div></center>");
		$("#art_new").html("<center><div class='loader'></div></center>");
		$("#art_current").html("<center><div class='loader'></div></center>");




		$("#treatment").load("{{ url('art/treatment') }}");
		$("#currenttx").load("{{ url('art/current_age_breakdown') }}");
		$("#newtx").load("{{ url('art/new_age_breakdown') }}");

		$("#gender").load("{{ url('testing/pos_gender') }}");
		$("#age").load("{{ url('testing/pos_age') }}");
		$("#pmtct").load("{{ url('pmtct/testing') }}");
		$("#eid").load("{{ url('pmtct/eid') }}");

		$("#summary").load("{{ url('testing/summary') }}");
		$("#art_new").load("{{ url('art/new_art') }}");
		$("#art_current").load("{{ url('art/current_art') }}");
		
	}


	$().ready(function(){		
		date_filter('financial_year', {{ date('Y') }}, '{{ $date_url }}');
	});

</script>

@endsection


