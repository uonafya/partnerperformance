<table id="{{ $div }}"  cellspacing="1" cellpadding="3" class="tablehead table table-striped table-bordered">
	<thead>
		<tr class="colhead">
			<th>No</th>
			@include('partials.columns')			
			<th>Contacts Identified</th>				
			<th>Eligible Contacts</th>
			<th>Percentage</th>
			<th>Contacts Tested</th>
			<th>Percentage</th>
			<th>Newly Identified Positives</th>
			<th>Percentage</th>
			<th>Linked to HAART</th>
			<th>Percentage</th>
		</tr>
	</thead>
	<tbody>
		@foreach($rows as $key => $row)
			<tr>
				<td> {{ $key+1 }} </td>
				@include('partials.rows', ['row' => $row])
				<td> {{ number_format($row->contacts_identified) }} </td>
				<td> {{ number_format($row->eligible_contacts) }} </td>
				<td> {{ $calc_percentage($row->eligible_contacts, $row->contacts_identified) }} </td>
				<td> {{ number_format($row->contacts_tested) }} </td>
				<td> {{ $calc_percentage($row->contacts_tested, $row->eligible_contacts) }} </td>
				<td> {{ number_format($row->new_pos) }} </td>
				<td> {{ $calc_percentage($row->new_pos, $row->contacts_tested) }} </td>
				<td> {{ number_format($row->linked_haart) }} </td>
				<td> {{ $calc_percentage($row->linked_haart, $row->new_pos) }} </td>
			</tr>
		@endforeach
	</tbody>	
</table>

@include('partials.table_footer', ['div' => $div])
