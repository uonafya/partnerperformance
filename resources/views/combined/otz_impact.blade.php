<div class="table-responsive">
	<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
		<thead>
			<tr class="colhead">
				<th rowspan="2">No</th>
				<th rowspan="2">Name</th>
				@if(session('filter_groupby') == 5)
					<th rowspan="2">MFL Code</th>
					<th rowspan="2">DHIS Code</th>
				@endif
				<th rowspan="2">OTZ Facilities</th>
				<th colspan="3">Ages 15-19</th>
				<th colspan="3">Ages 19-24</th>
				<th colspan="3">Ages 15-24</th>
			</tr>
			<tr>
				<th>Currently Suppressed</th>
				<th>Currently Not Suppressed</th>
				<th>Current Suppression</th>

				<th>Currently Suppressed</th>
				<th>Currently Not Suppressed</th>
				<th>Current Suppression</th>

				<th>Currently Suppressed</th>
				<th>Currently Not Suppressed</th>
				<th>Current Suppression</th>
			</tr>
		</thead>
		<tbody>
			@foreach($rows as $key => $row)
				<?php

					$calc_percentage = function($num, $den, $roundby=2)
										{
											if(!$den){
												$val = null;
											}else{
												$val = round(($num / $den * 100), $roundby) . "%";
											}
											return $val;
										};
					$less14 = $row->less14_suppressed + $row->less14_nonsuppressed;
					$less19 = $row->less19_suppressed + $row->less19_nonsuppressed;
					$total = $less14 + $less19;

				?>

				<tr>
					<td> {{ $key+1 }} </td>
					<td> {{ $row->name ?? '' }} </td>
					@if(session('filter_groupby') == 5)
						<td> {{ $row->mfl_code ?? '' }} </td>
						<td> {{ $row->dhis_code ?? '' }} </td>
					@endif

					<td> {{ number_format($row->facilities) }} </td>

					<td> {{ number_format($row->less14_suppressed) }} </td>
					<td> {{ number_format($row->less14_nonsuppressed) }} </td>
					<td> {{ $calc_percentage($row->less14_suppressed, $less14) }} </td>

					<td> {{ number_format($row->less19_suppressed) }} </td>
					<td> {{ number_format($row->less19_nonsuppressed) }} </td>
					<td> {{ $calc_percentage($row->less19_suppressed, $less19) }} </td>

					<td> {{ number_format($row->less14_suppressed+$row->less19_suppressed) }} </td>
					<td> {{ number_format($row->less14_nonsuppressed+$row->less19_nonsuppressed) }} </td>
					<td> {{ $calc_percentage(($row->less14_suppressed+$row->less19_suppressed), $total) }} </td>
				</tr>
			@endforeach
		</tbody>	
	</table>
</div>


<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {

		$('.display_current_range').html("{{ $current_range }}");

		$('#{{ $div }}').DataTable({
			dom: '<"btn"B>lTfgtip',
			// responsive: true,
			buttons : [
				{
				  text:  'Export to CSV',
				  extend: 'csvHtml5',
				  title: 'Download'
				},
				{
				  text:  'Export to Excel',
				  extend: 'excelHtml5',
				  title: 'Download'
				}
			]
		});
	});
</script>
