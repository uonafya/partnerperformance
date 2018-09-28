<div class="table-responsive">
	<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
		<thead>
			<tr class="colhead">
				<th colspan="2">No</th>
				<th colspan="2">Name</th>
				@if(session('filter_groupby') != 1 && session('filter_groupby') != 6)
					<th colspan="2">MFL Code</th>
					<th colspan="2">DHIS Code</th>
				@endif
				<th rowspan="2">Below 1</th>
				<th rowspan="2">Below 15</th>
				<th rowspan="2">Above 15</th>
				<th rowspan="2">Sum Total</th>			
				<th rowspan="2">Reported Total</th>			
				<th colspan="2">Old Form Duplicate Total</th>			
			</tr>
			<tr>
				<th>Old Form</th>
				<th>New Form</th>
				<th>Old Form</th>
				<th>New Form</th>
				<th>Old Form</th>
				<th>New Form</th>

				<th>Old Form</th>
				<th>New Form</th>
				<th>Old Form</th>
				<th>New Form</th>			
			</tr>
		</thead>
		<tbody>
			@foreach($rows as $key => $row)
				<?php
					$old = $others->where('div_id', $row->div_id)->first();
					$duplicate = $duplicates->where('div_id', $row->div_id)->first();
					$below_1 = $row->below_1 + $old->below_1 - ($duplicate->below_1 ?? 0);
					$below_15 = $row->below_10 + $row->below_15 + $old->below_15 - ($duplicate->below_15 ?? 0);
					$above_15 =  $row->below_20 + $row->below_25 + $row->above_25 + $old->above_15 - ($duplicate->above_15 ?? 0);
					$total = $below_1 + $below_15 + $above_15;
					$reported_total = $row->total + $old->total - ($duplicate->total ?? 0);
					$discrepancy = $reported_total - $total;
					// @continue($reported_total == 0 && $total == 0)
				?>
				
				<tr>
					<td> {{ $key+1 }} </td>
					<td> {{ $row->name ?? '' }} </td>
					@if(session('filter_groupby') != 1 && session('filter_groupby') != 6)
						<td> {{ $row->mfl_code ?? '' }} </td>
						<td> {{ $row->dhis_code ?? '' }} </td>
					@endif


					<td> {{ number_format($below_1) }} </td>
					<td> {{ number_format($below_15) }} </td>
					<td> {{ number_format($above_15 ) }} </td>
					<td> {{ number_format($total) }} </td>			
					<td> {{ number_format($reported_total) }} </td> 
					<td> {{ number_format($discrepancy) }} </td>
				</tr>
			@endforeach
		</tbody>	
	</table>
</div>


<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {

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
