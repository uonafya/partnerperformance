<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
	<thead>
		<tr class="colhead">
			<th>No</th>
			<th>Name</th>
			@if(session('filter_groupby') == 5)
				<th>MFL Code</th>
				<th>DHIS Code</th>
			@endif
			<th>Viremia Clinics</th>
			@isset($targets)
				<th>Viremia Target</th>
				<th>Viremia Achievement</th>
			@endisset
			<th>DSD Clinics</th>
			@isset($targets)
				<th>DSD Target</th>
				<th>DSD Achievement</th>
			@endisset
			<th>OTZ Clinics</th>
			@isset($targets)
				<th>OTZ Target</th>
				<th>OTZ Achievement</th>
			@endisset
			<th>Men Clinic Clinics</th>
			@isset($targets)
				<th>Men Clinic Target</th>	
				<th>Men Clinic Achievement</th>	
			@endisset	
		</tr>
	</thead>
	<tbody>
		@foreach($dsd as $key => $row)
		<?php
			$calc_percentage = function($num, $den, $roundby=2)
								{
									if(!$den){
										$val = 0;
									}else{
										$val = round(($num / $den * 100), $roundby);
									}
									return $val;
								};

			$v = $viremia->where('div_id', $row->div_id)->first()->total ?? 0; 
			$o = $otz->where('div_id', $row->div_id)->first()->total ?? 0; 
			$m = $men_clinic->where('div_id', $row->div_id)->first()->total ?? 0; 

			if(isset($targets)) $target = $targets->where('div_id', $row->div_id)->first();
		?>

			<tr>
				<td> {{ $key+1 }} </td>
				<td> {{ $row->name ?? '' }} </td>
				@if(session('filter_groupby') == 5)
					<td> {{ $row->mfl_code ?? '' }} </td>
					<td> {{ $row->dhis_code ?? '' }} </td>
				@endif


				<td> {{ number_format($v) }} </td>
				@isset($targets)
					<td> {{ $target->viremia }} </td>
					<td> {{ $calc_percentage($v, $target->viremia) }} </td>
				@endisset

				<td> {{ number_format($row->total) }} </td>
				@isset($targets)
					<td> {{ $target->dsd }} </td>
					<td> {{ $calc_percentage($row->total, $target->dsd) }} </td>
				@endisset

				<td> {{ number_format($o) }} </td>
				@isset($targets)
					<td> {{ $target->otz }} </td>
					<td> {{ $calc_percentage($o, $target->otz) }} </td>
				@endisset

				<td> {{ number_format($m) }} </td>
				@isset($targets)
					<td> {{ $target->men_clinic }} </td>
					<td> {{ $calc_percentage($m, $target->men_clinic) }} </td>
				@endisset
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
