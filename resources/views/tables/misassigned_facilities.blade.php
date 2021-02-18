<div class="table-responsive">
	<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
		<thead>
			<tr class="colhead">
				<th>No</th>
				<th>Name</th>
				<th>MFL Code</th>
				<th>DHIS Code</th>
				<th>Facility UID</th>
				<th> Subcounty </th>
				<th> County </th>
				<th> Partner </th>
				<th> Tests </th>
				<th> Positive </th>
				<th> TX New </th>
			</tr>
		</thead>
		<tbody>
			@foreach($rows as $key => $row)				
				<tr>
					<td> {{ $key+1 }} </td>
					<td> {{ $row->name ?? $row->new_name ?? '' }} </td>
					<td> {{ $row->mfl_code ?? '' }} </td>
					<td> {{ $row->dhis_code ?? '' }} </td>
					<td> {{ $row->facility_uid ?? '' }} </td>
					<td> {{ $row->subcounty ?? '' }} </td>
					<td> {{ $row->countyname ?? '' }} </td>
					<td> {{ $row->partnername ?? '' }} </td>
					<td> {{ number_format($row->tests ) }} </td>
					<td> {{ number_format($row->pos ) }} </td>
					<td> {{ number_format($row->tx_new ) }} </td>
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
