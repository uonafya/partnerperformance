<div class="table-responsive">
	<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
		<thead>
			<tr class="colhead">
				<th>No</th>
				@include('partials.columns')
				<th>During Moderate</th>
				<th>During Severe</th>
				<th>Post Moderate</th>
				<th>Post Severe</th>
				<th>Total </th>
			</tr>
		</thead>
		<tbody>
			@foreach($rows as $key => $row)
				@continue($row->total == 0)
				<?php
					$i++;
				?>
				<tr>
					<td> {{ $i }} </td>
					@include('partials.rows', ['row' => $row])

					<td> {{ number_format($row->ae_during_moderate) }} </td>
					<td> {{ number_format($row->ae_during_severe) }} </td>
					<td> {{ number_format($row->ae_post_moderate) }} </td>
					<td> {{ number_format($row->ae_post_severe) }} </td>
					<td> {{ number_format($row->total) }} </td>
				</tr>
			@endforeach
		</tbody>	
	</table>
</div>

@include('partials.table_footer', ['div' => $div])
