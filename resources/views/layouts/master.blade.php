<?php ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- about this site -->
		<meta name="description" content="A web platform that for Viral Load">
		<meta name="keywords" content="EID, VL, Early infant diagnosis, Viral Load, HIV, AIDS, HIV/AIDS, adults, pedeatrics, infants">
		<meta name="author" content="Star Sarifi Tours">
		<meta name="Resource-type" content="Document">

		<?php      	
			$this->load->view('utils/dynamicLoads');
		?>

		

		<link rel=icon href="<?php echo base_url('assets/img/kenya-coat-of-arms.png');?>" type="image/png">
		<title>
			Dashboard
		</title>
		<style type="text/css">
			@import url("https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700");
			@import url("https://fonts.googleapis.com/css?family=Roboto:400,300,500,700");
			
			h1,
			h2,
			h3,
			h4,
			h5,
			h6 {
			  font-weight: 100;
			}
			h1 {
			  font-size: 30px;
			}
			h2 {
			  font-size: 24px;
			}
			h3 {
			  font-size: 16px;
			}
			h4 {
			  font-size: 14px;
			}
			h5 {
			  font-size: 12px;
			}
			h6 {
			  font-size: 10px;
			}
			h3,
			h4,
			h5 {
			  margin-top: 5px;
			  font-weight: 600;
			}
			.navbar-inverse {
				border-radius: 0px;
			}
			.navbar .container-fluid .navbar-header .navbar-collapse .collapse .navbar-responsive-collapse .nav .navbar-nav {
				border-radius: 0px;
			}
			.panel {
				border-radius: 0px;
			}
			.panel-primary {
				border-radius: 0px;
			}
			.panel-heading {
				border-radius: 0px;
			}
			.btn {
				margin: 0px;
			}
			.alert {
				margin-bottom: 0px;
				padding: 8px;
			}
			.filter {
				margin: 2px 20px;
			}
			#filter {
				background-color: white;
				margin-bottom: 1.2em;
				margin-right: 0.1em;
				margin-left: 0.1em;
				padding-top: 0.5em;
				padding-bottom: 0.5em;
			}
			#year-month-filter {
				font-size: 12px;
			}
			.nav {
				color: black;
			}
			.ui-datepicker-calendar {
			    display: none;
			}
			.date-picker {
			    width: 100px;
			    margin-right: 0.5em;
			    font-size: 11px;
			}
			.date-pickerBtn {
			    /*width: 80px;*/
			    font-size: 11px;
			    height: 22px;
			}
			.filter {
			    font-size: 11px;
			}
			#breadcrum {
			    font-size: 11px;
			}
			#errorAlert {
			    font-size: 11px;
			    background-color: #E08283;
			    color: #96281B;
			}
		</style>
		<!-- <script src='https://www.google.com/recaptcha/api.js'></script> -->
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
					<a class="navbar-brand" href="javascript:void(0)" style="padding:0px;padding-top:4px;padding-left:4px;"><img src="<?php echo base_url();?>assets/img/nascop_pepfar_logo.jpg" style="width:280px;height:52px;"/></a>
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
						<!-- <li><a href="<?php echo base_url();?>">Summary</a></li> -->
						<li class="dropdown">
							<a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">Summaries
							<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo base_url();?>">Summary</a></li>
								<li><a href="<?php echo base_url();?>summary/heivalidation">HEI Validation Summary</a></li>
							</ul>
						</li>
						<li><a href="<?php echo base_url();?>trends">Trends</a></li>
						<li class="dropdown">
							<a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">County/Sub-County
							<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo base_url();?>county">County</a></li>
								<li><a href="<?php echo base_url();?>county/tat">County TAT</a></li>
								<li><a href="<?php echo base_url();?>county/subCounty">Sub-County</a></li>
								<li><a href="<?php echo base_url();?>county/subCountytat">Sub-County TAT</a></li>
							</ul>
						</li>
						<!-- <li><a href="<?php echo base_url();?>age">Age</a></li> -->
						<li class="dropdown">
							<a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">Partners
							<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo base_url();?>partner">Summary</a></li>
								<li><a href="<?php echo base_url();?>partner/trends">Trends</a></li>
								<li><a href="<?php echo base_url();?>partner/sites">Partner Facilities</a></li>
								<li><a href="<?php echo base_url();?>partner/counties">Partner Counties</a></li>
								<li><a href="<?php echo base_url();?>partner/heivalidation">HEI Validation</a></li>
								<li><a href="<?php echo base_url();?>partner/tat">Partner TAT</a></li>
							</ul>
						</li>
						<li><a href="<?php echo base_url();?>labPerformance">Lab Performance</a></li>
						<!-- <li><a href="<?php echo base_url();?>rht">RHT Testing</a></li> -->
						<li><a href="<?php echo base_url();?>sites">Facilities</a></li>
						<li class="dropdown">
							<a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">Positivity
							<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo base_url();?>positivity">Positivity</a></li>
								<li><a href="<?php echo base_url();?>age">Age Analysis</a></li>
								<li><a href="<?php echo base_url();?>regimen">Regimen Analysis</a></li>
							</ul>
						</li>
						<li><a href="<?php echo base_url();?>assets/downloads/EID_LAB_REQUISITION_FORM.pdf">EID Request Form</a></li>
						<li><a href="<?php echo base_url();?>contacts">Contact Us</a></li>
						<li><a href="http://eiddash.nascop.org/login.php">Login</a></li>
						<li><a href="http://viralload.nascop.org">VL View</a></li>
						<!-- <li><a href="<?php echo base_url();?>county">County View</a></li>
						<li><a href="http://eid.nascop.org/vreports.php">Reports</a></li>
						<li><a href="http://eid.nascop.org/login.php">Login</a></li>
						<li><a href="http://eid.nascop.org">EID View</a></li> -->
						<!-- <li><a href="javascript:void(0)">Link</a></li> -->
						<li class="dropdown">
							<!-- <a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown
							<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="javascript:void(0)">Action</a></li>
								<li><a href="javascript:void(0)">Another action</a></li>
								<li><a href="javascript:void(0)">Something else here</a></li>
								<li class="divider"></li>
								<li><a href="javascript:void(0)">Separated link</a></li>
							</ul> -->
						</li>
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
					<div id="breadcrum" class="alert" style="background-color: #1BA39C;/*display:none;"></div>
				</div>

				<div class="col-md-4" id="year-month-filter">
				    <div class="filter">
						Year: 

						@for($i=9; $i > -1; $i--)
							@php
								$year = (int) Date('Y') - 1;
							@endphp

							<a href="javascript:void(0)" onclick="date_filter('yearly', {{ $year }} )" class="alert-link"> {{ $year }} </a>|
						@endforeach
					</div>

					<div class="filter">
						Month: 
						<a href='javascript:void(0)' onclick='date_filter("monthly", 1)' class='alert-link'> Jan </a>|
						<a href='javascript:void(0)' onclick='date_filter("monthly", 2)' class='alert-link'> Feb </a>|
						<a href='javascript:void(0)' onclick='date_filter("monthly", 3)' class='alert-link'> Mar </a>|
						<a href='javascript:void(0)' onclick='date_filter("monthly", 4)' class='alert-link'> Apr </a>|
						<a href='javascript:void(0)' onclick='date_filter("monthly", 5)' class='alert-link'> May </a>|
						<a href='javascript:void(0)' onclick='date_filter("monthly", 6)' class='alert-link'> Jun </a>|
						<a href='javascript:void(0)' onclick='date_filter("monthly", 7)' class='alert-link'> Jul </a>|
						<a href='javascript:void(0)' onclick='date_filter("monthly", 8)' class='alert-link'> Aug </a>|
						<a href='javascript:void(0)' onclick='date_filter("monthly", 9)' class='alert-link'> Sep </a>|
						<a href='javascript:void(0)' onclick='date_filter("monthly", 10)' class='alert-link'> Oct </a>|
						<a href='javascript:void(0)' onclick='date_filter("monthly", 11)' class='alert-link'> Nov </a>|
						<a href='javascript:void(0)' onclick='date_filter("monthly", 12)' class='alert-link'> Dec</a>
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
		            <center>
		            	<div id="errorAlertDateRange">
		            		<div id="errorAlert" class="alert alert-danger" role="alert">...</div>
		            	</div>
		            </center>
			    </div>
			</div>

			@yield('content')

			<div id="errorModal">
				
			</div>
		</div>
		<!-- End of Dashboard area -->
	</body>

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
		  });
		  $().ready(function(){
		    $('#errorAlertDateRange').hide();
		    $(".js-example-basic-single").select2();
		    $("#breadcrum").html("<a href='javascript:void(0)' class='alert-link'><strong>All Partners</strong></a>");
		      //Getting the URL dynamically
		    var url = $(location).attr('href');
		    // Getting the file name i.e last segment of URL (i.e. example.html)
		    var fn = url.split('/').indexOf("partner");
		    
		    if (fn > -1) {
		      var trends = url.split('/').indexOf("trends");
		      if (trends > -1) {
		        $('#year-month-filter').hide();
		          $('#date-range-filter').hide();
		      }
		    }
		  });
		</script>
</html>
		