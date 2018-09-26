<div class="table-responsive">
	<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
		<thead>
			<tr class="colhead">
				<th>No</th>
				<th>Name</th>
				<th>Current TX {{ $recent_current_tx_date }}</th>
				<th>Current TX {{ $current_tx_date }}</th>
				<th>Net New TX</th>
				<th>Tests</th>
				<th>Positives</th>
				<th>Positivity</th>
				<th>New TX</th>
				<th>Linkage</th>
			</tr>
		</thead>
		<tbody>
			@foreach($rows as $key => $row)
				@continue($row->tests == 0)
				<?php
					$recent_current_tx = $art_recent->where('div_id', $row->div_id)->first();	
					$current_tx = $art->where('div_id', $row->div_id)->first();	

					$calc_percentage = function($num, $den, $roundby=2)
										{
											if(!$den){
												$val = 0;
											}else{
												$val = round(($num / $den * 100), $roundby) . "%";
											}
											return $val;
										};

				?>

				<tr>
					<td> {{ $key+1 }} </td>
					<td> {{ $row->name ?? '' }} </td>

					<td> {{ number_format($recent_current_tx->current_tx) }} </td>
					<td> {{ number_format($current_tx->current_tx) }} </td>

					<td> {{ number_format($row->net_new_tx) }} </td>
					<td> {{ number_format($row->tests) }} </td>
					<td> {{ number_format($row->pos) }} </td>
					<td> {{ $calc_percentage($row->pos, $row->tests) }} </td>
					<td> {{ number_format($row->new_art) }} </td>
					<td> {{ $calc_percentage($row->new_art, $row->pos) }} </td>
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
