<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
	<thead>
		<tr class="colhead">
			<th>No</th>
			<th>Name</th>
			@if(session('filter_groupby') == 5)
				<th>MFL Code</th>
				<th>DHIS Code</th>

				<th>Is Viremia</th>
				<th>Is DSD</th>
				<th>Is OTZ</th>
				<th>Is Men Clinic</th>
			@endif
			<th>Viremia Beneficiaries</th>
			<!-- <th>Viremia Target</th> -->
			<th>DSD Beneficiaries</th>
			<!-- <th>DSD Target</th> -->
			<th>OTZ Beneficiaries</th>
			<!-- <th>OTZ Target</th> -->
			<th>Men Clinic Beneficiaries</th>
			<!-- <th>Men Clinic Target</th>		 -->
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
					
					<td> {{ $row->is_viremia ? "Yes" : "No" }} </td>
					<td> {{ $row->is_dsd ? "Yes" : "No" }} </td>
					<td> {{ $row->is_otz ? "Yes" : "No" }} </td>
					<td> {{ $row->is_men_clinic ? "Yes" : "No" }} </td>
				@endif


				<td> {{ number_format($row->viremia_beneficiaries) }} </td>
				<td> {{ number_format($row->dsd_beneficiaries) }} </td>
				<td> {{ number_format($row->otz_beneficiaries) }} </td>
				<td> {{ number_format($row->men_clinic_beneficiaries) }} </td>
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
