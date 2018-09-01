@if(isset($counties))

<div class="row" id="filter">
	<div class="col-md-3">
		<select class="btn js-example-basic-single" style="width:220px;background-color: #C5EFF7;">
			{!! $select_options !!}
		</select>
	</div>

	<div class="col-md-2">
		<div id="breadcrum" class="alert" style="background-color: #1BA39C;/*display:none;*/"></div>
	</div>

	<div class="col-md-4" id="year-month-filter">
	    <div class="filter">
			Year: 

			@for($i=8; $i > -1; $i--)
				@php
					$year = (int) Date('Y') - $i;
				@endphp

				<a href="javascript:void(0)" onclick="date_filter('yearly', {{$year}}, '{{ $date_url }}')" class="alert-link"> {{ $year }} </a>|
			@endfor
		</div>

		<div class="filter">
			Month: 
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
		</div>
    </div>


	<div class="col-md-2" id="date-range-filter">
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
	</div>

    <center>
    	<div id="errorAlertDateRange">
    		<div id="errorAlert" class="alert alert-danger" role="alert">...</div>
    	</div>
    </center>
</div>
@endif