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
		$("#summary").html("<center><div class='loader'></div></center>");

		$("#summary").load("{{ url('dispensing/summary') }}");
	}


	$().ready(function(){
		$("#filter_agency").val(1).change();
		$(".display_date").html("{{ $display_date }}");
	});

</script>

@endsection


