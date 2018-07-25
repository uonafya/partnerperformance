@extends('layouts.master')

@section('content')

<style type="text/css">
	.display_date {
		width: 130px;
		display: inline;
	}
	.display_range {
		width: 130px;
		display: inline;
	}
</style>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    HIV Testing <div class="display_range"></div>
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
			    Positive Results <div class="display_range"></div>
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
			    Linked <div class="display_range"></div>
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
			    Prevention of Mother-To-Child Transmission (PMTCT) <div class="display_range"></div>
		    </div>
			<div class="panel-body" id="pmtct">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')


<script type="text/javascript">
	$().ready(function(){
		reload_page();

		$("select").change(function(){
			em = $(this).val();
			var posting = $.post( "{{ url('filter/partner') }}", { partner: em } );

			posting.done(function( data ) {

				reload_page();


			});
		});
	});

	function reload_page()
	{
		$("#hiv_testing").load("{{ url('partner/tested') }}");
		alert("this");
	}

</script>

@endsection