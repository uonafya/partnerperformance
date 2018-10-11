<div class="table-responsive">
	<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
		<thead>
			<tr class="colhead">
				<th>No</th>
				@include('partials.columns')
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
				@continue($row->tests == 0)
				<?php
					$i++;
					$linked_to_treatment = $get_val($groupby, $row, $linked, 'newtx');
					$target = $get_val($groupby, $row, $targets, ['tests', 'pos']);
				?>
				<tr>
					<td> {{ $i }} </td>
					@include('partials.rows', ['row' => $row])

					<td> {{ number_format($row->tests) }} </td>
					<td> {{ number_format($row->pos) }} </td>
					<td> {{ $calc_percentage($row->pos, $row->tests) }} </td>
					<td> {{ number_format($linked_to_treatment) }} </td>			
					<td> {{ $calc_percentage($linked_to_treatment, $row->pos)  }} </td>
					<td> {{ number_format($target['tests']) }} </td>
					<td> {{ number_format($target['pos']) }} </td>			
					<td> {{ $calc_percentage($target['pos'], $target['tests'])  }} </td>
				</tr>
			@endforeach
		</tbody>	
	</table>
</div>

@include('partials.table_footer', ['div' => $div])
