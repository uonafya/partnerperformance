<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
	<thead>
		<tr class="colhead">
			<th>No</th>
			<th>Name</th>
			@if(session('filter_groupby') == 5)
				<th>MFL Code</th>
				<th>DHIS Code</th>
			@endif
			<th>DSD Facilities</th>
			<th>DSD Beneficiaries</th>
			<th>Current TX {{ $current_range }}</th>
			<th>DSD Coverage</th>					
		</tr>
	</thead>
	<tbody>
		@foreach($rows as $key => $row)
			<?php

				$new = $art->where('div_id', $row->div_id)->first()->total ?? 0;
				$old = $others->where('div_id', $row->div_id)->first()->total ?? 0;
				$dup = $duplicates->where('div_id', $row->div_id)->first()->total ?? 0;

				$tx = $new + $old - $dup;

				$calc_percentage = function($num, $den, $roundby=2)
									{
										if(!$den){
											$val = null;
										}else{
											$val = round(($num / $den * 100), $roundby) . "%";
										}
										return $val;
									};

			?>
			<tr>
				<td> {{ $key+1 }} </td>
				<td> {{ $row->name ?? '' }} </td>
				@if(session('filter_groupby') == 5)
					<td> {{ $row->mfl_code ?? '' }} </td>
					<td> {{ $row->dhis_code ?? '' }} </td>
				@endif


				<td> {{ number_format($row->facilities) }} </td>
				<td> {{ number_format($row->dsd_beneficiaries) }} </td>
				<td> {{ number_format($tx) }} </td>
				<td> {{ $calc_percentage($row->dsd_beneficiaries, $tx) }} </td>
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
