<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
	<thead>
		<tr class="colhead">
			<th>No</th>
			<th>Name</th>
			<th>MFL Code</th>
			<th>DHIS Code</th>
			<th>Below 1</th>
			<th>Below 15</th>
			<th>Above 15</th>
			<th>Actual Total</th>			
			<th>Reported Total</th>			
			<th>Discrepancy</th>			
		</tr>
	</thead>
	<tbody>
		@foreach($rows as $key => $row)
			<tr>
				<td> {{ $key+1 }} </td>
				<td> {{ $row->name ?? '' }} </td>
				<td> {{ $row->mfl_code ?? '' }} </td>
				<td> {{ $row->dhis_code ?? '' }} </td>

				@php
					$total = $row->below_1 +  $row->below_15 + $row->above_15;
				@endphp

				<td> {{ number_format($row->below_1 ) }} </td>
				<td> {{ number_format($row->below_15 ) }} </td>
				<td> {{ number_format($row->above_15 ) }} </td>
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
