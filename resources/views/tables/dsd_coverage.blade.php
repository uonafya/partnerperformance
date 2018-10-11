<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
	<thead>
		<tr class="colhead">
			<th>No</th>
			@include('partials.columns')
			<th>DSD Facilities</th>
			<th>DSD Beneficiaries</th>
			<th>Current TX {{ $current_range }}</th>
			<th>DSD Coverage</th>					
		</tr>
	</thead>
	<tbody>
		@foreach($rows as $key => $row)
			<?php
				$tx = $get_val($groupby, $row, $art, 'total');
			?>
			<tr>
				<td> {{ $key+1 }} </td>
				@include('partials.rows', ['row' => $row])
				<td> {{ number_format($row->facilities) }} </td>
				<td> {{ number_format($row->dsd_beneficiaries) }} </td>
				<td> {{ number_format($tx) }} </td>
				<td> {{ $calc_percentage($row->dsd_beneficiaries, $tx) }} </td>
			</tr>
		@endforeach
	</tbody>	
</table>

@include('partials.table_footer', ['div' => $div])
