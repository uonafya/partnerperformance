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
			    Reporting Status 
		    </div>
			<div class="panel-body" id="new_reporting">
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
		$("#new_reporting").html("<center><div class='loader'></div></center>");

		$("#new_reporting").load("{{ url('violence/new_reporting') }}");
	}


	$().ready(function(){
		
		date_filter('financial_year', {{ date('Y') }}, '{{ $date_url }}');

		// $("#filter_agency").val(1).change();
	});

</script>

@endsection

