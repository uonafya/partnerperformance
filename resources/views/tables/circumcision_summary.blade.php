<div class="table-responsive">
	<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
		<thead>
			<tr class="colhead">
				<th>No</th>
				@include('partials.columns')
				<th>&lt; 1</th>
				<th>1-9</th>
				<th>10-14 </th>
				<th>15-19 </th>
				<th>20-24 </th>
				<th>&gt; 25 </th>
				<th>Sum Total </th>
				<th>Reported Total </th>
				<th>Discrepancy </th>
			</tr>
		</thead>
		<tbody>
			@foreach($rows as $key => $row)
				@continue($row->total == 0)
				<?php
					$i++;
					$sum_total= $row->below1 + $row->below10 + $row->below15 + $row->below20 + $row->below25 + $row->above25;
				?>
				<tr>
					<td> {{ $i }} </td>
					@include('partials.rows', ['row' => $row])

					<td> {{ number_format($row->below1) }} </td>
					<td> {{ number_format($row->below10) }} </td>
					<td> {{ number_format($row->below15) }} </td>
					<td> {{ number_format($row->below20) }} </td>
					<td> {{ number_format($row->below25) }} </td>
					<td> {{ number_format($row->above25) }} </td>
					<td> {{ number_format($sum_total) }} </td>
					<td> {{ number_format($row->total) }} </td>
					<td> {{ number_format($sum_total - $row->total) }} </td>
				</tr>
			@endforeach
		</tbody>	
	</table>
</div>

@isset($paragraph)
	<p>{!! $paragraph !!}</p> 
@endisset

@include('partials.table_footer', ['div' => $div])
