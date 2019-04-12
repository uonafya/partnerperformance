
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
	    <link rel="stylesheet" href="{{ asset('css/toastr/toastr.min.css') }}" type="text/css">

	    @yield('css_scripts')

		<link rel="stylesheet" href="{{ asset('css/custom.css') }}" />
		<link rel="stylesheet" href="{{ asset('css/custom-2.css') }}" />

		<link rel=icon href="{{ asset('img/kenya-coat-of-arms.png') }}" type="image/png" />
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
						<img src="{{ url('img/nascop_pepfar_logo.jpg') }}" style="width:280px;height:52px;"/>
					</a>
				</div>
				<div class="navbar-collapse collapse navbar-responsive-collapse">
					<ul class="nav navbar-nav">
						<li><a href="{{ url('/') }} ">Home</a></li>	
						<li><a href="{{ url('testing') }} ">Testing</a></li>	
						<li><a href="{{ url('pmtct') }} ">PMTCT</a></li>	
						<li><a href="{{ url('art') }} ">ART</a></li>	
						<li><a href="{{ url('vmmc') }} ">VMMC</a></li>	
						<li><a href="{{ url('tb') }} ">TB</a></li>	
						<li><a href="{{ url('keypop') }} ">KeyPOP</a></li>	
						<li><a href="{{ url('indicators') }} ">Indicators</a></li>	
						<li><a href="{{ url('otz') }} ">Non Mer</a></li>	
						<li><a href="{{ url('pns') }} ">PNS</a></li>	
						<li><a href="{{ url('surge') }} ">Surge</a></li>	
						<li><a href="{{ url('regimen') }} ">MOH 729</a></li>	
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="{{ url('/guide') }} ">User Guide</a></li>
						@guest
							<li><a href="{{ url('/login') }} ">Login</a></li>
						@endguest	
						@auth
							<li class="dropdown">
								<a href="#" data-target="#" class="dropdown-toggle" data-toggle="dropdown">
									PNS <b class="caret"></b>
								</a>
								<ul class="dropdown-menu">									
									<li><a href="{{ url('/pns/download') }} ">Download PNS Template</a></li>
									<li><a href="{{ url('/pns/upload') }} ">PNS Upload</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" data-target="#" class="dropdown-toggle" data-toggle="dropdown">
									Surge <b class="caret"></b>
								</a>
								<ul class="dropdown-menu">									
									<li><a href="{{ url('/surge/download') }} ">Download Surge Template</a></li>
									<li><a href="{{ url('/surge/upload') }} ">Surge Upload</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" data-target="#" class="dropdown-toggle" data-toggle="dropdown">
									Download Indicators Template <b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li><a href="{{ url('indicators/download/2017') }} ">2017</a></li>
									<li><a href="{{ url('indicators/download/2018') }} ">2018</a></li>
									<li><a href="{{ url('indicators/download/2019') }} ">2019</a></li>
								</ul>
							</li>
							<li><a href="{{ url('/indicators/upload') }} ">Upload Indicators</a></li>
							<li class="dropdown">
								<a href="#" data-target="#" class="dropdown-toggle" data-toggle="dropdown">
									Download Non-mer Template <b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li><a href="{{ url('otz/download/2017') }} ">2017</a></li>
									<li><a href="{{ url('otz/download/2018') }} ">2018</a></li>
									<li><a href="{{ url('otz/download/2019') }} ">2019</a></li>
								</ul>
							</li>
							<li><a href="{{ url('/otz/upload') }} ">Upload Non-Mer</a></li>
							@if(auth()->user()->user_type_id == 1)
								<li><a href="{{ url('/user/create') }} ">Create User</a></li>
							@endif
							<li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">Logout</a></li>

                            <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
						@endauth
					</ul>
				</div>
			</div>
		</div>
		<!-- End of Navigation Bar -->
		<!-- Begining of Dashboard area -->
		<div class="container-fluid">

			@empty($no_header)

				@if(session('financial'))
					@if(isset($no_fac))
						@include('layouts.no_fac')
					@else
						@include('layouts.financial')
					@endif
				@else
					@include('layouts.year_month')
				@endif

			@endempty

			@yield('content')
		</div>

		<div id="errorModal">
			
		</div>
		<!-- End of Dashboard area -->
	</body>
	
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-124819698-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-124819698-1');
	</script>


	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

	<script src="{{ asset('js/toastr/toastr.min.js') }}"></script>

	<script src='https://code.highcharts.com/highcharts.js' type='text/javascript'></script>
	<script src='https://code.highcharts.com/highcharts-more.js' type='text/javascript'></script>
	<script src='https://code.highcharts.com/modules/exporting.js' type='text/javascript'></script>
	<script src='https://code.highcharts.com/modules/export-data.js' type='text/javascript'></script>
	<script src='https://code.highcharts.com/maps/modules/map.js' type='text/javascript'></script>
	<script src='//cdn.datatables.net/1.10.12/js/jquery.dataTables.js' type='text/javascript'></script>
	<script src='//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.js' type='text/javascript'></script>
	<script src='//cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js' type='text/javascript'></script>
	<script src='//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js' type='text/javascript'></script>
	<script src='//cdn.datatables.net/buttons/1.4.2/js/buttons.colVis.min.js' type='text/javascript'></script>
	<script src='//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js' type='text/javascript'></script>

	<script src="{{ url('js/customFunctions1.3.js') }}"></script>

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

		    $("button").click(function () {
			    var first, second;
			    first = $(".date-picker[name=startDate]").val();
			    second = $(".date-picker[name=endDate]").val();

			    if(!first) return;
		    
			    from = format_date(first);
			    /* from is an array
			     	[0] => month
			     	[1] => year*/
			    to 	= format_date(second);

			    var error_check = check_error_date_range(from, to);

			    if (!error_check){
			    	var date_range_data = {'year': from[1], 'month' : from[0], 'to_year': to[1], 'to_month' : to[0]};
			    	date_filter('', date_range_data, "{{ $date_url ?? '' }}");
			    }

		    });
	    	
	        $.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            }
	        });

	        @php
	            $toast_message = session()->pull('toast_message');
	            $toast_error = session()->pull('toast_error');
	        @endphp
	        
	        @if($toast_message)
	            setTimeout(function(){
	                toastr.options = {
	                    closeButton: false,
	                    progressBar: false,
	                    showMethod: 'slideDown',
	                    timeOut: 10000
	                };
	                @if($toast_error)
	                    toastr.error("{!! $toast_message !!}", "Warning!");
	                @else
	                    toastr.success("{!! $toast_message !!}");
	                @endif
	            });
	        @endif
		    

		    @if(session('financial'))
		    	$(".filters").select2();
		    	set_select_facility("filter_facility", "{{ url('/facility/search') }}", 3, "Search for facility");
		    @else
			    $('#errorAlertDateRange').hide();
			    $(".js-example-basic-single").select2();
			    $("#breadcrum").html("{!! $default_breadcrumb ?? '' !!}");
		    @endif		    

			$("select").change(function(){
				em = $(this).val();
				id = $(this).attr('id');

				var posting = $.post( "{{ url('filter/any') }}", { 'session_var': id, 'value': em } );

				posting.done(function( data ) {
					console.log(data);
					reload_page();
				});
			});		    

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


    @yield('scripts')
</html>
		