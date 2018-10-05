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

			<th>Known Positives</th>
			<th>New PMTCT</th>
			<th>Positive PMTCT</th>
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

				<td> {{ number_format($row->known_positive ) }} </td>
				<td> {{ number_format($row->new_pmtct ) }} </td>
				<td> {{ number_format($row->positive_pmtct ) }} </td>
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
