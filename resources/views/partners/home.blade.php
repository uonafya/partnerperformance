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
		$("#hiv_testing").load("{{ url('partner/tested') }}");

		$("select").change(function(){
			em = $(this).val();
			var posting = $.post( "{{ url('filter/partner') }}", { partner: em } );



		});
	});
	
	function date_filter(criteria, id)
	{
		if (criteria === "monthly") {
 			year = null;
 			month = id;
 		}else {
 			year = id;
 			month = null;
 		}

 		var posting = $.post("{{ url('filter/date') }}", { 'year': year, 'month': month } );
	    var all = localStorage.getItem("my_var");

 		// Put the results in a div
		posting.done(function( data ) {
			obj = $.parseJSON(data);
			
			if(obj['month'] == "null" || obj['month'] == null){
				obj['month'] = "";
			}
			$(".display_date").html("( "+obj['year']+" "+obj['month']+" )");
			$(".display_range").html("( "+obj['prev_year']+" - "+obj['year']+" )");
			
			$.get("<?php echo base_url();?>partner/check_partner_select", function (data) {
				partner = data;
				console.log(partner);
				if (partner==0) {
					$("#second").hide();
					$("#first").show();
				
					// fetching the partner outcomes
					$("#partner").html("<center><div class='loader'></div></center>");
					$("#partner").load("<?php echo base_url('charts/partner_summaries/partner_outcomes'); ?>/"+year+"/"+month);
					$("#partner_test_analysis").html("<center><div class='loader'></div></center>");
					$("#partner_test_analysis").load("<?= @base_url('charts/partner_summaries/tests_analysis'); ?>/"+year+"/"+month);
				} else {
					partner = $.parseJSON(partner);
					$("#first").hide();
					$("#second").show();
					// Loader displaying
		        	$("#testing_trends").html("<center><div class='loader'></div></center>");
		        	$("#eidOutcomes").html("<center><div class='loader'></div></center>");
		        	$("#hei_outcomes").html("<center><div class='loader'></div></center>");
			        $("#hei_follow_up").html("<center><div class='loader'></div></center>");
					$("#ageGroups").html("<center><div class='loader'></div></center>");
					$("#entry_point").html("<center><div class='loader'></div></center>");
					$("#mprophilaxis").html("<center><div class='loader'></div></center>");
					$("#iprophilaxis").html("<center><div class='loader'></div></center>");
					$("#county_outcomes").html("<center><div class='loader'></div></center>");

					$("#pat_stats").html("<center><div class='loader'></div></center>");
					$("#pat_out").html("<center><div class='loader'></div></center>");
					$("#pat_graph").html("<center><div class='loader'></div></center>");

					// Actual graphs being loaded
					$("#testing_trends").load("<?php echo base_url('charts/partner_summaries/testing_trends'); ?>/"+year+"/"+all+"/"+partner);
					$("#eidOutcomes").load("<?php echo base_url('charts/partner_summaries/eid_outcomes');?>/"+year+"/"+month+"/"+partner);
					$("#hei_outcomes").load("<?php echo base_url('charts/partner_summaries/hei_validation');?>/"+year+"/"+month+"/"+partner);
					$("#hei_follow_up").load("<?php echo base_url('charts/partner_summaries/hei_follow');?>/"+year+"/"+month+"/"+partner);
					$("#ageGroups").load("<?php echo base_url('charts/partner_summaries/agegroup');?>/"+year+"/"+month+"/"+partner);

					$("#entry_point").load("<?php echo base_url('charts/partner_summaries/entry_points');?>/"+year+"/"+month+"/"+partner);
					$("#mprophilaxis").load("<?php echo base_url('charts/partner_summaries/mprophyalxis');?>/"+year+"/"+month+"/"+partner);
					$("#iprophilaxis").load("<?php echo base_url('charts/partner_summaries/iprophyalxis');?>/"+year+"/"+month+"/"+partner);
					// $("#feeding").load("<?php //echo base_url('charts/summaries/agegroup');?>");
					
					$("#county_outcomes").load("<?php echo base_url('charts/partner_summaries/partner_outcomes'); ?>/"+year+"/"+month+"/"+partner);

					$("#pat_stats").load("<?php echo base_url('charts/partner_summaries/get_patients');?>/"+year+"/"+month+"/"+null+"/"+partner);
					$("#pat_out").load("<?php echo base_url('charts/partner_summaries/get_patients_outcomes');?>/"+year+"/"+month+"/"+null+"/"+partner);
					$("#pat_graph").load("<?php echo base_url('charts/partner_summaries/get_patients_graph');?>/"+year+"/"+month+"/"+null+"/"+partner);
				}
			});
		});
	}
</script>

@endsection