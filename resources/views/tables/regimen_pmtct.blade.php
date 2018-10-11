<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
	<thead>
		<tr class="colhead">
			<th>No</th>
			@include('partials.columns')
			<th>ART</th>
			<th>PMTCT</th>		
		</tr>
	</thead>
	<tbody>
		@foreach($rows as $key => $row)
			<tr>
				<td> {{ $key+1 }} </td>
				@include('partials.rows', ['row' => $row])
				<td> {{ number_format($row->art + $row->pmtct) }} </td>
				<td> {{ number_format($row->pmtct) }} </td>
			</tr>
		@endforeach
	</tbody>	
</table>

@include('partials.table_footer', ['div' => $div])
