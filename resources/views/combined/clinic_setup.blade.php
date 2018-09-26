<div class="table-responsive">
	<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
		<thead>
			<tr class="colhead">
				<th rowspan="2">No</th>
				<th rowspan="2">Name</th>
				@if(session('filter_groupby') == 5)
					<th rowspan="2">MFL Code</th>
					<th rowspan="2">DHIS Code</th>
				@endif
				<th colspan="3">Viremia</th>
				<th colspan="3">DSD</th>
				<th colspan="3">OTZ</th>
				<th colspan="3">Men Clinic</th>
			</tr>
			<tr>
				<th>Clinics</th>
				@isset($targets)
					<th>Target</th>
					<th>(%)</th>
				@endisset

				<th>Clinics</th>
				@isset($targets)
					<th>Target</th>
					<th>(%)</th>
				@endisset

				<th>Clinics</th>
				@isset($targets)
					<th>Target</th>
					<th>(%)</th>
				@endisset

				<th>Clinics</th>
				@isset($targets)
					<th>Target</th>
					<th>(%)</th>
				@endisset					
			</tr>

			
		</thead>
		<tbody>
			@foreach($dsd as $key => $row)
			<?php
				$calc_percentage = function($num, $den, $roundby=1)
									{
										if(!$den){
											$val = null;
										}else{
											$val = round(($num / $den * 100), $roundby) . "%";
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
