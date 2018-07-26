
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta name="csrf-token" content="{{ csrf_token() }}">
		<!-- about this site -->
		<meta name="description" content="A web platform for partner performance.">
		<meta name="keywords" content="EID, VL, Early infant diagnosis, Viral Load, HIV, AIDS, HIV/AIDS, adults, paedeatrics, infants, partners">
		<meta name="author" content="Star Sarifi Tours">
		<meta name="Resource-type" content="Document">



		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />


		<link rel='stylesheet' href='//cdn.datatables.net/1.10.12/css/jquery.dataTables.css' type='text/css' />
		<link rel='stylesheet' href='//cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css' type='text/css' />
		<link rel='stylesheet' href='//cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.css' type='text/css' />

	    @yield('css_scripts')

		<link rel="stylesheet" href="{{ secure_asset('css/custom.css') }}" />
		<link rel="stylesheet" href="{{ secure_asset('css/custom-2.css') }}" />



		<link rel=icon href="{{ secure_asset('img/kenya-coat-of-arms.png') }}" type="image/png" />
		<title> Dashboard </title>
	</head>
	<body>
		<!-- Begining of Navigation Bar -->
		<div class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="javascript:void(0)" style="padding:0px;padding-top:4px;padding-left:4px;">
						<img src="{{ secure_url('img/nascop_pepfar_logo.jpg') }}" style="width:280px;height:52px;"/>
					</a>
				</div>
				<div class="navbar-collapse collapse navbar-responsive-collapse">
					<ul class="nav navbar-nav">
						
					</ul>
					<!-- <form class="navbar-form navbar-left" id="1267192336">
						<div class="form-group">
							<input type="text" class="form-control col-md-8" placeholder="Search">
						</div>
					</form> -->
					<ul class="nav navbar-nav navbar-right">
						<li><a href="{{ secure_url('/') }} ">Home</a></li>

						

					</ul>
				</div>
			</div>
		</div>
		<!-- End of Navigation Bar -->
		<!-- Begining of Dashboard area -->
		<div class="container-fluid">

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
								$date_url = secure_url('filter/date');
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

			@yield('content')
		</div>

		<div id="errorModal">
			
		</div>
		<!-- End of Dashboard area -->
	</body>


	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>


	<script src='https://code.highcharts.com/highcharts.js' type='text/javascript'></script>
	<script src='https://code.highcharts.com/highcharts-more.js' type='text/javascript'></script>
	<script src='https://code.highcharts.com/modules/exporting.js' type='text/javascript'></script>
	<script src='https://code.highcharts.com/modules/export-data.js' type='text/javascript'></script>
	<script src='https://code.highcharts.com/maps/modules/map.js' type='text/javascript'></script>
	<script src='//cdn.datatables.net/1.10.12/js/jquery.dataTables.js' type='text/javascript'></script>
	<script src='//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.js' type='text/javascript'></script>
	<script src='//cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js' type='text/javascript'></script>
	<script src='//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js' type='text/javascript'></script>
	<script src='//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js' type='text/javascript'></script>


    @yield('scripts')

	<script src="{{ secure_url('js/customFunctions.js') }}"></script>

	<script type="text/javascript">
	    $(function() {
		    $('.date-picker').datepicker( {
		        changeMonth: true,
		        changeYear: true,
		        showButtonPanel: true,
		        dateFormat: 'MM yy',
		        onClose: function(dateText, inst) { 
		            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
		            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
		            $(this).datepicker('setDate', new Date(year, month, 1));
		        }
		    });

	    	
	        $.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            }
	        });

		    $('#errorAlertDateRange').hide();
		    $(".js-example-basic-single").select2();
		    $("#breadcrum").html("{!! $default_breadcrumb !!}");

		      //Getting the URL dynamically
			/*var url = $(location).attr('href');
			// Getting the file name i.e last segment of URL (i.e. example.html)
			var fn = url.split('/').indexOf("partner");

			if (fn > -1) {
				var trends = url.split('/').indexOf("trends");
				if (trends > -1) {
					$('#year-month-filter').hide();
					$('#date-range-filter').hide();
				}
			}*/
	    });
	</script>
</html>
		