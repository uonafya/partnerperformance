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
			    Gender <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="gender">
				<center><div class="loader"></div></center>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
			    Age <div class="display_date"></div>
		    </div>
			<div class="panel-body" id="age">
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
		$("#gender").html("<center><div class='loader'></div></center>");
		$("#age").html("<center><div class='loader'></div></center>");

		$("#gender").load("{{ url('tx_curr/gender') }}");
		$("#age").load("{{ url('tx_curr/age') }}");
	}


	$().ready(function(){
		$("#filter_agency").val(1).change();
		$(".display_date").html("{{ $display_date }}");
	});

</script>

@endsection


