<div class="table-responsive">
	<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
		<thead>
			<tr class="colhead">
				<th>No</th>
				@include('partials.columns')
				<th>Below 1</th>
				<th>Below 15</th>
				<th>Above 15</th>
				<th>Sum Total</th>			
				<th>Reported Total</th>			
				<th>Discrepancy</th>		
			</tr>
		</thead>
		<tbody>
			@foreach($rows as $key => $row)				
				<tr>
					<td> {{ $key+1 }} </td>
					@include('partials.rows', ['row' => $row])
					<td> {{ number_format($row->below1) }} </td>
					<td> {{ number_format($row->below15) }} </td>
					<td> {{ number_format($row->above15 ) }} </td>
					<td> {{ number_format($row->actual_total) }} </td>			
					<td> {{ number_format($row->reported_total) }} </td> 
					<td> {{ number_format($row->reported_total - $row->actual_total) }} </td> 
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

		@isset($period_name)
			$('#current_art_title').html("{{ $period_name }}");
		@endisset
	});
</script>
