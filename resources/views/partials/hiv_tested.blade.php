<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
	<thead>
		<tr class="colhead">
			<th>No</th>
			@if($division == 'partner')
				<th>Partner</th>
				<th>Partner ID</th>
			@elseif($division == 'facility')
				<th>Facility</th>
				<th>MFL Code</th>
				<th>DHIS Code</th>
			@endif
			<th>Below 10</th>
			<th>Below 15</th>
			<th>Below 20</th>
			<th>Below 25</th>
			<th>Above 25</th>
			<th>Actual Total</th>			
			<th>Reported Total</th>			
			<th>Discrepancy</th>			
		</tr>
	</thead>
	<tbody>
		@foreach($rows as $key => $row)
			<tr>
				<td> {{ $key+1 }} </td>

				@if($division == 'partner')
					<td> {{ $row->partnername }} </td>
					<td> {{ $row->partner }} </td>
				@elseif($division == 'facility')
					<td> {{ $row->name }} </td>
					<td> {{ $row->facilitycode }} </td>
					<td> {{ $row->DHIScode ?? '' }} </td>
				@endif

				@php
					$total = $row->below_10 + $row->below_15 + $row->below_20 + $row->below_25 + $row->above_25;
				@endphp

				<td> {{ number_format($row->below_10 ) }} </td>
				<td> {{ number_format($row->below_15 ) }} </td>
				<td> {{ number_format($row->below_20 ) }} </td>
				<td> {{ number_format($row->below_25 ) }} </td>
				<td> {{ number_format($row->above_25 ) }} </td>
				<td> {{ number_format($total) }} </td>			
				<td> {{ number_format($row->total ) }} </td>
				<td> {{ number_format($row->total - $total ) }} </td>
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
