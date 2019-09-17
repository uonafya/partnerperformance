<div class="table-responsive">
	<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
		<thead>
			<tr class="colhead">
				<th>No</th>
				@include('partials.columns')
				<th>Unknown M</th>
				<th>Unknown F</th>
				<th>&lt; 1</th>
				<th>1-9</th>
				<th>10-14 M</th>
				<th>10-14 F</th>
				<th>15-19 M</th>
				<th>15-19 F</th>
				<th>20-24 M</th>
				<th>20-24 F</th>
				<th>25-29 M</th>
				<th>25-29 F</th>
				<th>30-49 M</th>
				<th>30-49 F</th>
				<th>&gt; 50 M</th>
				<th>&gt; 50 F</th>
				<th>Total </th>
			</tr>
		</thead>
		<tbody>
			@foreach($rows as $row)
				@continue($row->total == 0)
				<?php
					$i++;
					// @continue($row->total == 0)
				?>
				<tr>
					<td> {{ $i }} </td>
					@include('partials.rows', ['row' => $row])

					@foreach($ages_array as $key => $value)
						<td> {{ number_format($row->$key) }} </td>
					@endforeach

					<td> {{ number_format($row->total) }} </td>
				</tr>
			@endforeach
		</tbody>	
	</table>
</div>

@include('partials.table_footer', ['div' => $div])
