<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
	<thead>
		<tr class="colhead">
			<th>No</th>
			<th>Name</th>
			@if(session('filter_groupby') == 5)
				<th>MFL Code</th>
				<th>DHIS Code</th>
			@endif
			<th>Tested</th>
			<th>Positives</th>
			<th>Positivity (%)</th>
			<th>Linked To Treatment</th>
			<th>Linkage (%)</th>
			<th>Target Tested</th>
			<th>Target Positives</th>
			<th>Target Positivity</th>	
		</tr>
	</thead>
	<tbody>
		@foreach($rows as $key => $row)
			<?php
				$old = $rows2->where('div_id', $row->div_id)->first();
				$l = $linked->where('div_id', $row->div_id)->first();
				$l2 = $linked_old->where('div_id', $row->div_id)->first();

				$target = $targets->where('div_id', $row->div_id)->first();


				$dup_tests = $duplicate_tests->where('div_id', $row->div_id)->first();
				$dup_pos = $duplicate_pos->where('div_id', $row->div_id)->first();
				$dup_linked = $duplicate_linked->where('div_id', $row->div_id)->first();

				$tests = $row->tests + $old->tests - ($dup_tests->tests ?? 0);
				$pos = $row->pos + $old->pos - ($dup_pos->pos ?? 0);
				$linked_to_treatment = $l->total + $l2->total - ($dup_linked->linked ?? 0);

				$calc_percentage = function($num, $den, $roundby=2)
									{
										if(!$den){
											$val = 0;
										}else{
											$val = round(($num / $den * 100), $roundby);
										}
										return $val;
									};

			?>
			@continue($tests == 0)
			<tr>
				<td> {{ $key+1 }} </td>
				<td> {{ $row->name ?? '' }} </td>
				@if(session('filter_groupby') == 5)
					<td> {{ $row->mfl_code ?? '' }} </td>
					<td> {{ $row->dhis_code ?? '' }} </td>
				@endif


				<td> {{ number_format($tests) }} </td>
				<td> {{ number_format($pos) }} </td>
				<td> {{ $calc_percentage($pos, $tests) }} </td>
				<td> {{ number_format($linked_to_treatment) }} </td>			
				<td> {{ $calc_percentage($linked_to_treatment, $pos)  }} </td>
				<td> {{ number_format($target->tests) }} </td>
				<td> {{ number_format($target->pos) }} </td>			
				<td> {{ $calc_percentage($target->pos, $target->tests)  }} </td>
			</tr>
		@endforeach
	</tbody>	
</table>


<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {

		$('#{{ $div }}').DataTable({
			dom: '<"btn"B>lTfgtip',
			responsive: true,
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
