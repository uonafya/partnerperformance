<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
	<thead>
		<tr class="colhead">
			<th>No</th>
			<th>Name</th>
			@if(session('filter_groupby') == 5)
				<th>MFL Code</th>
				<th>DHIS Code</th>
			@endif
			<th>Tested</th>
			<th>Positives</th>
			<th>Positivity (%)</th>
			<th>Linked To Treatment</th>	
			<th>Linkage To Treatment (%)</th>	
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

				<?php
					if($row->tested_total){
						$positivity = round(($row->positive_total / $row->tested_total * 100), 2);
					}
					else{
						$positivity = 0;
					}

					if($row->positive_total){
						$linkage = round(($row->linked_total / $row->positive_total * 100), 2);
					}
					else{
						$linkage = 0;
					}
				?>

				<td> {{ number_format($row->tested_total ) }} </td>
				<td> {{ number_format($row->positive_total ) }} </td>
				<td> {{ number_format($positivity ) }} </td>
				<td> {{ number_format($row->linked_total ) }} </td>
				<td> {{ number_format($linkage ) }} </td>
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
