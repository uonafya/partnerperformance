@if(isset($counties))
<div class="row" id="filter">
	<div class="col-md-3">
		<select class="btn filters form-control" id="filter_county">
			<option disabled='true' selected='true'>Select County</option>
			<option value='null' selected='true'>All Counties</option>

			@foreach($counties as $county)
				<option value="{{ $county->id }}"> {{ $county->name }} </option>
			@endforeach
		</select>		
	</div>	

	<div class="col-md-3">
		<select class="btn filters form-control" id="filter_subcounty">
			<option disabled='true' selected='true'>Select Subcounty</option>
			<option value='null' selected='true'>All Subcounties</option>

			@foreach($subcounties as $subcounty)
				<option value="{{ $subcounty->id }}"> {{ $subcounty->name }} </option>
			@endforeach
		</select>		
	</div>		

	<div class="col-md-3">
		<select class="btn filters form-control" id="filter_ward">
			<option disabled='true' selected='true'>Select Ward</option>
			<option value='null' selected='true'>All Wards</option>

			@foreach($wards as $ward)
				<option value="{{ $ward->id }}"> {{ $ward->name }} </option>
			@endforeach
		</select>		
	</div>

	<div class="col-md-3">
		<select class="btn filters form-control" id="filter_partner">
			<option disabled='true' selected='true'>Select Partner</option>
			<option value='null' selected='true'>All Partners</option>

			@foreach($partners as $partner)
				<option value="{{ $partner->id }}"> {{ $partner->name }} </option>
			@endforeach
		</select>		
	</div>		

	<div class="col-md-3">
		<select class="btn form-control" id="filter_facility">
			<option disabled='true' selected='true'>Select Facility</option>
			<option value='null' selected='true'>All Facilities</option>

		</select>		
	</div>	

	<div class="col-md-3">
		<select class="btn filters form-control" id="filter_agency">
			<option disabled='true' selected='true'>Select Funding Agency</option>
			<option value='null' selected='true'>All Funding Agencies</option>

			@foreach($agencies as $agency)
				<option value="{{ $agency->id }}"> {{ $agency->name }} </option>
			@endforeach
		</select>		
	</div>		

	<div class="col-md-2">
		<select class="btn filters form-control" id="filter_groupby">
			<option disabled='true' selected='true'>Group By:</option>

			@foreach($divisions as $division)
				<option value="{{ $division->id }}"> {{ $division->name }} </option>
			@endforeach
		</select>		
	</div>	

	<div class="col-md-4">
		<center>
			<a href="javascript:void(0)" onclick="date_filter('financial_year', 2018, '{{ $date_url }}')" class="alert-link"> FY 2018 </a>|
			<a href="javascript:void(0)" onclick="date_filter('financial_year', 2019, '{{ $date_url }}')" class="alert-link"> FY 2019 </a>|
			<br />

			<a href="javascript:void(0)" onclick="date_filter('quarter', 1, '{{ $date_url }}')" class="alert-link"> Quarter 1 </a>|
			<a href="javascript:void(0)" onclick="date_filter('quarter', 2, '{{ $date_url }}')" class="alert-link"> Quarter 2 </a>|
			<a href="javascript:void(0)" onclick="date_filter('quarter', 3, '{{ $date_url }}')" class="alert-link"> Quarter 3 </a>|
			<a href="javascript:void(0)" onclick="date_filter('quarter', 4, '{{ $date_url }}')" class="alert-link"> Quarter 4 </a>|

			@if(ends_with(url()->current(), 'art'))
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

			@endif

		</center>		
	</div>
	
</div>
@endif