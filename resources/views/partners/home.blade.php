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
			    HIV Testing <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="hiv_testing">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Positive Results <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="pos_results">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Linked <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="linked">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Prevention of Mother-To-Child Transmission (PMTCT) <div class="display_date"></div>
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
			    Starting Art <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="new_art">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Currently On Art <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="current_art">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')


<script type="text/javascript">
	$().ready(function(){
		date_filter('yearly', {{ date('Y') }}, '{{ $date_url }}');
		// reload_page();

		$("select").change(function(){
			em = $(this).val();
			var posting = $.post( "{{ secure_url('filter/partner') }}", { partner: em } );

			posting.done(function( data ) {
			    $("#breadcrum").html(data.crumb);
				reload_page();
			});
		});
	});

	function reload_page()
	{
		$("#summary").html("<center><div class='loader'></div></center>");		
		$("#hiv_testing").html("<center><div class='loader'></div></center>");
		$("#pos_results").html("<center><div class='loader'></div></center>");
		$("#linked").html("<center><div class='loader'></div></center>");
		$("#pmtct").html("<center><div class='loader'></div></center>");
		$("#new_art").html("<center><div class='loader'></div></center>");
		$("#current_art").html("<center><div class='loader'></div></center>");

		$("#summary").load("{{ secure_url('partner/summary') }}");
		$("#hiv_testing").load("{{ secure_url('partner/tested') }}");
		$("#pos_results").load("{{ secure_url('partner/positive') }}");
		$("#linked").load("{{ secure_url('partner/linked') }}");
		$("#pmtct").load("{{ secure_url('partner/pmtct') }}");
		$("#new_art").load("{{ secure_url('partner/new_art') }}");
		$("#current_art").load("{{ secure_url('partner/current_art') }}");
	}

</script>

@endsection