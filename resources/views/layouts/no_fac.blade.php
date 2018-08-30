
<div class="row" id="filter">	


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
		</center>		
	</div>
	
</div>