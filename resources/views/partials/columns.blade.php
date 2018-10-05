{{ $slot ?? '' }}
				@if(session('filter_groupby') == 5)
					<th {{ $cols ?? '' }}>Name</th>
					<th {{ $cols ?? '' }}>MFL Code</th>
					<th {{ $cols ?? '' }}>DHIS Code</th>
				@elseif(session('filter_groupby') == 10)
					<th {{ $cols ?? '' }}>Calendar Year</th>
				@elseif(session('filter_groupby') == 11)
					<th {{ $cols ?? '' }}>Financial Year</th>
				@elseif(session('filter_groupby') == 12)
					<th {{ $cols ?? '' }}>Year</th>
					<th {{ $cols ?? '' }}>Month</th>
				@elseif(session('filter_groupby') == 13)
					<th {{ $cols ?? '' }}>Financial Year</th>
					<th {{ $cols ?? '' }}>Quarter</th>
				@else
					<th {{ $cols ?? '' }}>Name</th>					
				@endif
