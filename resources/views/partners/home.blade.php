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
		// reload_page();
		
		date_filter('yearly', {{ date('Y') }}, '{{ $date_url }}');

		$("select").change(function(){
			em = $(this).val();
			var posting = $.post( "{{ secure_url('filter/partner') }}", { partner: em } );

			posting.done(function( data ) {
			    $("#breadcrum").html(data.crumb);
				reload_page();
			});
		});


		$("button").click(function () {
		    var first, second;
		    first = $(".date-picker[name=startDate]").val();
		    second = $(".date-picker[name=endDate]").val();
		    
		    var all = localStorage.getItem("my_var");
		    var new_title = set_multiple_date(first, second);

		    
		    from = format_date(first);
		    /* from is an array
		     	[0] => month
		     	[1] => year*/
		    to 	= format_date(second);
		    var error_check = check_error_date_range(from, to);

		    if (!error_check) {
		    	date_filter('date_range', {'year': from[1], 'month': from[0], 'to_year': to[1], 'to_month': to[0] }, '{{ $date_url }}');
		    }
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