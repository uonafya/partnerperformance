{{ $slot ?? '' }}

					@if(session('filter_groupby') == 5)
						<td> {{ $row->name ?? $row->new_name ?? '' }} </td>
						<td> {{ $row->mfl_code ?? '' }} </td>
						<td> {{ $row->dhis_code ?? '' }} </td>
					@elseif(session('filter_groupby') == 10)
						<td> {{ $row->year ?? '' }} </td>
					@elseif(session('filter_groupby') == 11)
						<td> {{ $row->financial_year ?? '' }} </td>
					@elseif(session('filter_groupby') == 12)
						<td> {{ $row->year ?? '' }} </td>
						<td> {{ $row->month ?? '' }} </td>
					@elseif(session('filter_groupby') == 13)
						<td> {{ $row->financial_year ?? '' }} </td>
						<td> {{ $row->quarter ?? '' }} </td>
					@else
						<td> {{ $row->name ?? '' }} </td>
					@endif