<div class="table-reponsive">
	<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
		<thead>
			<tr class="colhead">
				<th rowspan="2">No</th>
				@include('partials.columns', ['cols' => 'rowspan=2'])
				<th rowspan="2">Below 10</th>
				<th colspan="2">10-14</th>
				<th colspan="2">15-19</th>
				<th colspan="2">20-24</th>
				<th colspan="2">Above 25</th>		
				<th rowspan="2">Reported Total</th>			
			</tr>
			<tr>
				<th>Male</th>
				<th>Female</th>
				<th>Male</th>
				<th>Female</th>
				<th>Male</th>
				<th>Female</th>
				<th>Male</th>
				<th>Female</th>
			</tr>
		</thead>
		<tbody>
			@foreach($rows as $key => $row)
				<tr>
					<td> {{ $key+1 }} </td>
					@include('partials.rows', ['row' => $row])
					<td> {{ number_format($row->below_10 ) }} </td>
					<td> {{ number_format($row->below_15_m ) }} </td>
					<td> {{ number_format($row->below_15_f ) }} </td>
					<td> {{ number_format($row->below_20_m ) }} </td>
					<td> {{ number_format($row->below_20_f ) }} </td>
					<td> {{ number_format($row->below_25_m ) }} </td>
					<td> {{ number_format($row->below_25_f ) }} </td>
					<td> {{ number_format($row->above_25_m ) }} </td>
					<td> {{ number_format($row->above_25_f ) }} </td>
					<td> {{ number_format($row->total ) }} </td>
				</tr>
			@endforeach
		</tbody>	
	</table>
</div>

@include('partials.table_footer', ['div' => $div])
