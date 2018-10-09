<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
	<thead>
		<tr class="colhead">
			<th>No</th>
			@component('partials.columns')@endcomponent
			<th>ART</th>
			<th>PMTCT</th>		
		</tr>
	</thead>
	<tbody>
		@foreach($art_rows as $key => $row)
			<tr>
				<td> {{ $key+1 }} </td>
				@component('partials.rows', ['row' => $row])@endcomponent
				<td> {{ number_format($row->art + $row->pmtct) }} </td>
				<td> {{ number_format($row->pmtct) }} </td>
			</tr>
		@endforeach
	</tbody>	
</table>

@component('partials.table_footer', ['div' => $div])@endcomponent
