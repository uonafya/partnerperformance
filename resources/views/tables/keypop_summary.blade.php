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
			</tr>
		</thead>
		<tbody>
			@foreach($rows as $key => $row)
				@continue($row->tests == 0 && $row->pos == 0)
				<?php
					$i++;
				?>
				<tr>
					<td> {{ $i }} </td>
					@include('partials.rows', ['row' => $row])

					<td> {{ number_format($row->tests) }} </td>
					<td> {{ number_format($row->pos) }} </td>
					<td> {{ $calc_percentage($row->pos, $row->tests) }} </td>
					<td> {{ number_format($row->new_tx) }} </td>			
					<td> {{ $calc_percentage($row->new_tx, $row->pos)  }} </td>
				</tr>
			@endforeach
		</tbody>	
	</table>
</div>

@include('partials.table_footer', ['div' => $div])
