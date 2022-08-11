@if(isset($counties))
<div class="row" id="filter">

	<div class="row">	

		{{-- <div class="col-md-3">
			<select class="btn filters form-control" id="filter_county">
				<option disabled='true'>Select County</option>
				<option value='null' selected='true'>All Counties</option>

				@foreach($counties as $county)
					<option value="{{ $county->id }}"> {{ $county->name }} </option>
				@endforeach
			</select>		
		</div>	

		<div class="col-md-3">
			<select class="btn filters form-control" id="filter_subcounty">
				<option disabled='true'>Select Subcounty</option>
				<option value='null' selected='true'>All Subcounties</option>
				@foreach($subcounties as $subcounty)
					<option value="{{ $subcounty->id }}"> {{ $subcounty->name }} </option>
				@endforeach
			</select>		
		</div>		

		<div class="col-md-3">
			<select class="btn filters form-control" id="filter_ward">
				<option disabled='true'>Select Ward</option>
				<option value='null' selected='true'>All Wards</option>

				@foreach($wards as $ward)
					<option value="{{ $ward->id }}"> {{ $ward->name }} </option>
				@endforeach
			</select>		
		</div>

		<div class="col-md-3">
			<select class="btn filters form-control" id="filter_partner">
				<option disabled='true'>Select Partner</option>
				<option value='null' selected='true'>All Partners</option>

				@foreach($partners as $partner)
					<option value="{{ $partner->id }}"> {{ $partner->name }} </option>
				@endforeach
			</select>		
		</div>

	</div>	

	<div class="row">

		<div class="col-md-7">

			<div class="row">

				<div class="col-md-5">
					<select class="btn form-control" id="filter_facility">
						<option disabled='true'>Select Facility</option>
						<option value='null' selected='true'>All Facilities</option>

					</select>		
				</div>	

				<div class="col-md-4">
					<select class="btn filters form-control" id="filter_agency">
						<option disabled='true'>Select Funding Agency</option>
						<option value='null' selected='true'>All Funding Agencies</option>

						@foreach($agencies as $agency)
							<option value="{{ $agency->id }}"
								@if(session('filter_agency') == $agency->id) selected @endif
								> {{ $agency->name }} </option>
						@endforeach
					</select>		
				</div>		

				<div class="col-md-3">
					<select class="btn filters form-control" id="filter_groupby">
						<option disabled='true' selected='true'>Group By:</option>

						@foreach($divisions as $division)
							@continue(\Str::contains(url()->current(), 'otz') && ($division->id > 11 || $division->id == 10))
							@continue(!\Str::contains(url()->current(), ['surge', 'vmmc_circ', 'prep', 'hfr']) && ($division->id == 14))
							<option value="{{ $division->id }}"> {{ $division->name }} </option>
						@endforeach
					</select>		
				</div>	
			</div>	 --}}

			@if(ends_with(url()->current(), 'pns'))

				<div class="row">
					<div class="col-md-12">
						<select class="btn filters form-control" multiple="multiple" id="filter_pns_age">
							<option disabled='true'>Select Age Category</option>
							<option value='null' selected='true'>All Age Categories</option>

							@foreach($ages as $key => $value)
								<option value="{{ $key }}"> {{ $value }} </option>
							@endforeach
						</select>
					</div>
				</div>

			@elseif(ends_with(url()->current(), ['surge', 'vmmc_circ',  'prep']))

				@if(ends_with(url()->current(), ['surge']))

					<div class="row">
						<div class="col-md-12">
							<select class="btn filters form-control" multiple="multiple" id="filter_modality" placeholder='Select Modality'>
								<option disabled='true'>Select Modality</option>
								<option value='null' selected='true'>All Modalities</option>

								@foreach($modalities as $key => $modality)
									@continue($modality->hts == 0)
									<option value="{{ $modality->id }}"> {{ $modality->modality_name }} </option>
								@endforeach
							</select>
						</div>
					</div>

				@endif

				<div class="row">
					<div class="col-md-12">
						<select class="btn filters form-control" multiple="multiple" id="filter_week">
							<option disabled='true'>Select Week</option>
							<option value='null' selected='true'>All Weeks</option>

							@foreach($weeks as $key => $week)
								<option value="{{ $week->id }}"> {{ $week->name }} </option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<select class="btn filters form-control" multiple="multiple" id="filter_age">
							<option disabled='true'>Select Age Group</option>
							<option value='null' selected='true'>All Ages</option>

							@foreach($ages as $key => $age)
								<option value="{{ $age->id }}"> {{ $age->age_name }} </option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="row">

					@if(!ends_with(url()->current(), ['vmmc_circ']))
						<div class="col-md-6">
							<select class="btn filters form-control" id="filter_gender">
								<option disabled='true'>Select Gender</option>
								<option value='null' selected='true'>All Genders</option>

								@foreach($genders as $key => $gender)
									<option value="{{ $gender->id }}"> {{ $gender->gender }} </option>
								@endforeach
							</select>
						</div>
					@endif

					<div class="col-md-6">
						<select class="btn filters form-control" id="filter_age_category_id">
							<option disabled='true'>Select Age Category</option>
							<option value='null' selected='true'>All Age Categories</option>

							@foreach($age_categories as $key => $age_category)
								<option value="{{ $age_category->id }}"> {{ $age_category->age_category }} </option>
							@endforeach
						</select>
					</div>
				</div>


			@elseif(ends_with(url()->current(), ['dispensing', 'tx_curr', 'gbv']))
				@if(!ends_with(url()->current(), ['dispensing']))
					<div class="row">
						<div class="col-md-12">
							<select class="btn filters form-control" multiple="multiple" id="filter_age">
								<option disabled='true'>Select Age Group</option>
								<option value='null' selected='true'>All Ages</option>

								@foreach($ages as $key => $age)
									<option value="{{ $age->id }}"> {{ $age->age_name }} </option>
								@endforeach
							</select>
						</div>
					</div>
				@endif

				@if(isset($modalities))

					<div class="row">
						<div class="col-md-4">
							<select class="btn filters form-control" id="filter_modality" placeholder='Select Modality'>
								<option disabled='true'>Select Modality</option>
								<option value='null' selected='true'>All Modalities</option>

								@foreach($modalities as $key => $modality)
									<option value="{{ $modality->id }}"> {{ $modality->modality_name }} </option>
								@endforeach
							</select>
						</div>

						<div class="col-md-4">
							<select class="btn filters form-control" id="filter_gender">
								<option disabled='true'>Select Gender</option>
								<option value='null' selected='true'>All Genders</option>

								@foreach($genders as $key => $gender)
									<option value="{{ $gender->id }}"> {{ $gender->gender }} </option>
								@endforeach
							</select>
						</div>

						<div class="col-md-4">
							<select class="btn filters form-control" id="filter_age_category_id">
								<option disabled='true'>Select Age Category</option>
								<option value='null' selected='true'>All Age Categories</option>

								@foreach($age_categories as $key => $age_category)
									<option value="{{ $age_category->id }}"> {{ $age_category->age_category }} </option>
								@endforeach
							</select>
						</div>
					</div>

				@else

					<div class="row">
						<div class="col-md-6">
							<select class="btn filters form-control" id="filter_gender">
								<option disabled='true'>Select Gender</option>
								<option value='null' selected='true'>All Genders</option>

								@foreach($genders as $key => $gender)
									<option value="{{ $gender->id }}"> {{ $gender->gender }} </option>
								@endforeach
							</select>
						</div>

						<div class="col-md-6">
							<select class="btn filters form-control" id="filter_age_category_id">
								<option disabled='true'>Select Age Category</option>
								<option value='null' selected='true'>All Age Categories</option>

								@foreach($age_categories as $key => $age_category)
									<option value="{{ $age_category->id }}"> {{ $age_category->age_category }} </option>
								@endforeach
							</select>
						</div>
					</div>

				@endif

			@elseif(ends_with(url()->current(), ['hfr']))
				<div class="row">
					<div class="col-md-12">
						<select class="btn filters form-control" multiple="multiple" id="filter_week">
							<option disabled='true'>Select Week</option>
							<option value='null' selected='true'>All Weeks</option>

							@foreach($weeks as $key => $week)
								<option value="{{ $week->id }}"> {{ $week->name }} </option>
							@endforeach
						</select>
					</div>
				</div>
			@endif
		</div>
		
		

	</div>
	
</div>
@endif