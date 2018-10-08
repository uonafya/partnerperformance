<div class="table-responsive">
	<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
		<thead>
			<tr class="colhead">
				<th>No</th>
				@component('partials.columns')@endcomponent
				<th>Current TX {{ $current_tx_date ?? '' }}</th>
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
					$i++;
					$current_tx = $get_val($groupby, $row, $art, 'current_tx');
				?>

				<tr>
					<td> {{ $i }} </td>
					@component('partials.rows', ['row' => $row])@endcomponent

					<td> {{ number_format($current_tx) }} </td>

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