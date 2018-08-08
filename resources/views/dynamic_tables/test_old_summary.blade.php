<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
	<thead>
		<tr class="colhead">
			<th>No</th>
			<th>Name</th>
			<th>MFL Code</th>
			<th>DHIS Code</th>
			<th>Tested</th>
			<th>Positives</th>
			<th>Positivity (%)</th>
			<th>First Test</th>
		</tr>
	</thead>
	<tbody>
		@foreach($rows as $key => $row)
			<tr>
				<td> {{ $key+1 }} </td>
				<td> {{ $row->name ?? '' }} </td>
				<td> {{ $row->mfl_code ?? '' }} </td>
				<td> {{ $row->dhis_code ?? '' }} </td>

				<?php
					if($row->tests){
						$positivity = round(($row->pos / $row->tests * 100), 2);
					}
					else{
						$positivity = 0;
					}
				?>

				<td> {{ number_format($row->tests ) }} </td>
				<td> {{ number_format($row->pos ) }} </td>
				<td> {{ number_format($positivity ) }} </td>
				<td> {{ number_format($row->first_testing_hiv ) }} </td>

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
