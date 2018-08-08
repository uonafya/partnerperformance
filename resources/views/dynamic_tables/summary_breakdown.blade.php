<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
	<thead>
		<tr class="colhead">
			<th>No</th>
			<th>Name</th>
			@if(session('filter_groupby') == 5)
				<th>MFL Code</th>
				<th>DHIS Code</th>
			@endif
			<th>Total Tests</th>
			<th>First Tests</th>
			<th>Repeat Tests</th>
			<th>Outreach Tests</th>
			<th>Static Tests</th>
			<th>Couples Testing</th>
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

				<td> {{ number_format($row->tests ) }} </td>
				<td> {{ number_format($row->first_testing_hiv ) }} </td>
				<td> {{ number_format($row->repeat_testing_hiv ) }} </td>
				<td> {{ number_format($row->outreach_testing_hiv ) }} </td>
				<td> {{ number_format($row->static_testing ) }} </td>
				<td> {{ number_format($row->couples_testing ) }} </td>

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
