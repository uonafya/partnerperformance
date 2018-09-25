<div class="table-reponsive">
	<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
		<thead>
			<tr class="colhead">
				<th rowspan="2">No</th>
				<th rowspan="2">Name</th>
				@if(session('filter_groupby') == 5)
					<th rowspan="2">MFL Code</th>
					<th rowspan="2">DHIS Code</th>
				@endif
				<th rowspan="2">Below 10</th>
				<th colspan="2">Below 15</th>
				<th colspan="2">Below 20</th>
				<th colspan="2">Below 25</th>
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
					<td> {{ $row->name ?? '' }} </td>
					@if(session('filter_groupby') == 5)
						<td> {{ $row->mfl_code ?? '' }} </td>
						<td> {{ $row->dhis_code ?? '' }} </td>
					@endif

					@php
						// $total = $row->below_1 + $row->below_10 + $row->below_15 + $row->below_20 + $row->below_25 + $row->above_25;
					@endphp

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
