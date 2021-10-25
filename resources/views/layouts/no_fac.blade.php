
<div class="row" id="filter" data-html2canvas-ignore="true">	


	<div class="col-md-4">
		<select class="btn filters form-control" id="filter_agency">
			<option disabled='true' selected='true'>Select Funding Agency</option>
			<option value='null' selected='true'>All Funding Agencies</option>

			@foreach($agencies as $agency)
				<option value="{{ $agency->id }}"> {{ $agency->name }} </option>
			@endforeach
		</select>		
	</div>		

	<div class="col-md-4">
		<select class="btn filters form-control" id="filter_partner">
			<option disabled='true' selected='true'>Select Partner</option>
			<option value='null' selected='true'>All Partners</option>

			@foreach($partners as $partner)
				<option value="{{ $partner->id }}"> {{ $partner->name }} </option>
			@endforeach
		</select>		
	</div>		


	<div class="col-md-4">
		<select class="btn filters form-control" id="filter_county">
			<option disabled='true' selected='true'>Select County</option>
			<option value='null' selected='true'>All Counties</option>

			@foreach($counties as $county)
				<option value="{{ $county->id }}"> {{ $county->name }} </option>
			@endforeach
		</select>		
	</div>	

	<div class="col-md-4">
		<select class="btn filters form-control" id="filter_groupby">
			<option disabled='true' selected='true'>Group By:</option>

			@foreach($divisions as $division)
				@continue($division->id > 2 && $division->id < 6)
				@continue(!\Str::contains(url()->current(), 'surge') && ($division->id == 14))
				<option value="{{ $division->id }}"> {{ $division->name }} </option>
			@endforeach
		</select>		
	</div>	

	<div class="col-md-8">
		<center>
			<a href="javascript:void(0)" onclick="date_filter('financial_year', 2018, '{{ $date_url }}')" class="alert-link"> FY 2018 </a>|
			<a href="javascript:void(0)" onclick="date_filter('financial_year', 2019, '{{ $date_url }}')" class="alert-link"> FY 2019 </a>|
			<br />

			<a href="javascript:void(0)" onclick="date_filter('quarter', 1, '{{ $date_url }}')" class="alert-link"> Quarter 1 </a>|
			<a href="javascript:void(0)" onclick="date_filter('quarter', 2, '{{ $date_url }}')" class="alert-link"> Quarter 2 </a>|
			<a href="javascript:void(0)" onclick="date_filter('quarter', 3, '{{ $date_url }}')" class="alert-link"> Quarter 3 </a>|
			<a href="javascript:void(0)" onclick="date_filter('quarter', 4, '{{ $date_url }}')" class="alert-link"> Quarter 4 </a>|
			<br />
			
			<a href='javascript:void(0)' onclick='date_filter("monthly", 1, "{{ $date_url }}")' class='alert-link'> Jan </a>|
			<a href='javascript:void(0)' onclick='date_filter("monthly", 2, "{{ $date_url }}")' class='alert-link'> Feb </a>|
			<a href='javascript:void(0)' onclick='date_filter("monthly", 3, "{{ $date_url }}")' class='alert-link'> Mar </a>|
			<a href='javascript:void(0)' onclick='date_filter("monthly", 4, "{{ $date_url }}")' class='alert-link'> Apr </a>|
			<a href='javascript:void(0)' onclick='date_filter("monthly", 5, "{{ $date_url }}")' class='alert-link'> May </a>|
			<a href='javascript:void(0)' onclick='date_filter("monthly", 6, "{{ $date_url }}")' class='alert-link'> Jun </a>|
			<a href='javascript:void(0)' onclick='date_filter("monthly", 7, "{{ $date_url }}")' class='alert-link'> Jul </a>|
			<a href='javascript:void(0)' onclick='date_filter("monthly", 8, "{{ $date_url }}")' class='alert-link'> Aug </a>|
			<a href='javascript:void(0)' onclick='date_filter("monthly", 9, "{{ $date_url }}")' class='alert-link'> Sep </a>|
			<a href='javascript:void(0)' onclick='date_filter("monthly", 10, "{{ $date_url }}")' class='alert-link'> Oct </a>|
			<a href='javascript:void(0)' onclick='date_filter("monthly", 11, "{{ $date_url }}")' class='alert-link'> Nov </a>|
			<a href='javascript:void(0)' onclick='date_filter("monthly", 12, "{{ $date_url }}")' class='alert-link'> Dec</a>
			
			<br />

			<div class="row" id="range">
				<div class="col-md-4">
					<input name="startDate" id="startDate" class="date-picker" placeholder="From:" />
				</div>
				<div class="col-md-4 endDate">
					<input name="endDate" id="endDate" class="date-picker" placeholder="To:" />
				</div>
				<div class="col-md-4">
					<button id="filter" class="btn btn-primary date-pickerBtn" style="color: white;background-color: #1BA39C; margin-top: 0.2em; margin-bottom: 0em; margin-left: 4em;"><center>Filter</center></button>
				</div>
			</div>

		    <center>
		    	<div id="errorAlertDateRange" style="display: none;">
		    		<div id="errorAlert" class="alert alert-danger" role="alert">...</div>
		    	</div>
		    </center>
		</center>		
	</div>
	
</div>