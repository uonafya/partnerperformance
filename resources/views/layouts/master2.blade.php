<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <!--<title> Drop Down Sidebar Menu | CodingLab </title>-->
    <link rel="stylesheet" href="style.css">
    <!-- Boxiocns CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <style>
        /* Google Fonts Import Link */
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
            *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            }
            .sidebar{
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 260px;
            background: #1D5288;
            z-index: 100;
            transition: all 0.5s ease;
            }

            #sub_menu_color{
                color: #fff !important;
            }

            .sidebar.close{
            width: 78px;
            }
            .sidebar .logo-details{
            height: 60px;
            width: 100%;
            display: flex;
            align-items: center;
            }
            .sidebar .logo-details i{
            font-size: 30px;
            color: #fff;
            height: 50px;
            min-width: 78px;
            text-align: center;
            line-height: 50px;
            }
            
            .sidebar .logo-details .logo_name{
            font-size: 18px;
            color: #fff;
            font-weight: 600;
            transition: 0.3s ease;
            transition-delay: 0.1s;
            }
            .sidebar.close .logo-details .logo_name{
            transition-delay: 0s;
            opacity: 0;
            pointer-events: none;
            }
            .sidebar .nav-links{
            height: 100%;
            padding: 30px 0 150px 0;
            overflow: auto;
            }
            .sidebar.close .nav-links{
            overflow: visible;
            }
            .sidebar .nav-links::-webkit-scrollbar{
            display: none;
            }
            .sidebar .nav-links li{
            position: relative;
            list-style: none;
            transition: all 0.4s ease;
            }
            .sidebar .nav-links li:hover{
            background: #1d1b31;
            }
            .sidebar .nav-links li .iocn-link{
            display: flex;
            align-items: center;
            justify-content: space-between;
            }
            .sidebar.close .nav-links li .iocn-link{
            display: block
            }
            .sidebar .nav-links li i{
            height: 50px;
            min-width: 78px;
            text-align: center;
            line-height: 50px;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            }
            .sidebar .nav-links li.showMenu i.arrow{
            transform: rotate(-180deg);
            }
            .sidebar.close .nav-links i.arrow{
            display: none;
            }
            .sidebar .nav-links li a{
            display: flex;
            align-items: center;
            text-decoration: none;
            }
            .sidebar .nav-links li a .link_name{
            font-size: 15px;
            font-weight: 400;
            color: #fff;
            transition: all 0.4s ease;
            }
            .sidebar.close .nav-links li a .link_name{
            opacity: 0;
            pointer-events: none;
            }
            .sidebar .nav-links li .sub-menu{
            padding: 6px 6px 14px 80px;
            margin-top: -10px;
            background: #1d1b31;
            display: none;
            }
            .sidebar .nav-links li.showMenu .sub-menu{
            display: block;
            }
            .sidebar .nav-links li .sub-menu a{
            color: #fff;
            font-size: 15px;
            padding: 5px 0;
            white-space: nowrap;
            opacity: 10;
            transition: all 0.3s ease;
            }
            .sidebar .nav-links li .sub-menu a:hover{
                opacity: 0.6;
                background-color: #0f1012;
                padding: 15px;              
                transition: all 0.3s ease;
            }
            .sidebar.close .nav-links li .sub-menu{
                position: absolute;
                left: 100%;
                top: -10px;
                margin-top: 0;
                padding: 10px 20px;
                border-radius: 0 6px 6px 0;
                opacity: 0;
                display: block;
                pointer-events: none;
                transition: 0s;
            }
            .sidebar.close .nav-links li:hover .sub-menu{
            top: 0;
            opacity: 10;
            pointer-events: auto;
            transition: all 0.4s ease;
            }
            .sidebar .nav-links li .sub-menu .link_name{
            display: none;
            }
            .sidebar.close .nav-links li .sub-menu .link_name{
            font-size: 18px;
            opacity: 10;
            display: block;
            }
            .sidebar .nav-links li .sub-menu.blank{
            opacity: 1;
            pointer-events: auto;
            padding: 3px 20px 6px 16px;
            opacity: 0;
            pointer-events: none;
            }
            .sidebar .nav-links li:hover .sub-menu.blank{
            top: 50%;
            transform: translateY(-50%);
            }
            .sidebar .profile-details{
            position: fixed;
            bottom: 0;
            width: 260px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #1d1b31;
            padding: 12px 0;
            transition: all 0.5s ease;
            }
            .sidebar.close .profile-details{
            background: none;
            }
            .sidebar.close .profile-details{
            width: 78px;
            }
            .sidebar .profile-details .profile-content{
            display: flex;
            align-items: center;
            }
            .sidebar .profile-details img{
            height: 52px;
            width: 52px;
            object-fit: cover;
            border-radius: 16px;
            margin: 0 14px 0 12px;
            background: #1d1b31;
            transition: all 0.5s ease;
            }
            .sidebar.close .profile-details img{
            padding: 10px;
            }
            .sidebar .profile-details .profile_name,
            .sidebar .profile-details .job{
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            white-space: nowrap;
            }
            .sidebar.close .profile-details i,
            .sidebar.close .profile-details .profile_name,
            .sidebar.close .profile-details .job{
            display: none;
            }
            .sidebar .profile-details .job{
            font-size: 12px;
            }
            .home-section{
            position: relative;
            background: #E4E9F7;
            height: 100vh;
            left: 260px;
            width: calc(100% - 260px);
            transition: all 0.5s ease;
            }
            .sidebar.close ~ .home-section{
            left: 78px;
            width: calc(100% - 78px);
            }
            .home-section .home-content{
            height: 60px;
            display: flex;
            align-items: center;
            }
            .home-section .home-content .bx-menu,
            .home-section .home-content .text{
            color: #11101d;
            font-size: 35px;
            }
            .home-section .home-content .bx-menu{
            margin: 0 15px;
            cursor: pointer;
            }
            .home-section .home-content .text{
            font-size: 26px;
            font-weight: 600;
            }
            @media (max-width: 400px) {
            .sidebar.close .nav-links li .sub-menu{
                display: none;
            }
            .sidebar{
                width: 78px;
            }
            .sidebar.close{
                width: 0;
            }
            .home-section{
                left: 78px;
                width: calc(100% - 78px);
                z-index: 100;
            }
            .sidebar.close ~ .home-section{
                width: 100%;
                left: 0;
            }
            }

            div.floating-action-menu > .action-menu {
            visibility: hidden;
            transform: translateY(65px);
            opacity: 0;
            max-height: 0;
            overflow: hidden;

            transition: all 300ms linear;
        }
        div.floating-action-menu.active > .action-menu {
            visibility: visible;
            transform: translate(0);
            opacity: 1;
            padding-bottom: 5px;
            max-height: 1000px;
        }

        div.floating-action-menu > .action-menu > .floating-action {
            padding-right: 0.45rem;
        }

        div.floating-action-menu > .action-menu .btn-floating,
        div.floating-action-menu > .action-menu .badge
        {
            transform: scale(0.4);
            transition: all 500ms ease-in-out;
        }
        div.floating-action-menu.active > .action-menu .btn-floating,
        div.floating-action-menu.active > .action-menu .badge
        {
            transform: scale(1);
        }

        div.floating-action-menu > .action-button > .btn-floating {
            transition: all 500ms linear;
        }
        div.floating-action-menu.active > .action-button > .btn-floating {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        div.floating-action-menu.active > .action-button > .btn-floating:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        div.floating-action-menu > .action-button > .btn-floating > i {
            transition: transform 500ms ease-in-out;
        }
        div.floating-action-menu.active > .action-button > .btn-floating > i {
            transform: rotate(-315deg);

        }

        .css-after {
        position: relative;
        }
        .css-after img {
        width: 105px;
        height: 105px;
        border-radius: 50%;
        border: 2px solid #1D5288;
        }
        .css-after::after {
        content: "";
        width: 85px;
        height: 91px;
        margin-left: 2%;
        margin-top: 5%;
        border-radius: 50%;
        display: block;
        position: absolute;
        top: -6px;
        left: -3px;
        }
    
       .dropdown-menu{
        background-color: #01FF01 !important;
       }
       </style>

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


   </head>

<body style="background-color: #fff !important ;" >
  <div class="sidebar close">
    <div class="logo-details">
      {{-- <i class='bx bxl-c-plus-plus'></i> --}}

      <img style="width: 40px; height: 40px; margin: 10%;" src="img/Coat_of_arms_of_Kenya_(Official).svg.png" alt="Kenya Logo">

      <span  class="logo_name">Partner <br> Performance.</span>
    </div>
    <ul class="nav-links">
      <li>
        <a href="#">
          <i class='bx bx-grid-alt' ></i>
          <span class="link_name" href="/"  >Dashboard</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="/">Dashboard</a></li>
        </ul
      </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-line-chart' ></i>
            <span class="link_name">Individual Charts.</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul  class="sub-menu">
          <li  ><a class="link_name" href="#">Individual Charts.</a></li>
          <li id="sub_menu_color"  ><a style="color: #E4E9F7" href="{{ route("ichart_testing") }}">Testing</a></li>
          <li id="sub_menu_color" ><a style="color: #E4E9F7" href="{{ route("ichart_linkage") }}">Linkage</a></li>
          <li id="sub_menu_color" ><a style="color: #E4E9F7" href="#">More ...</a></li>
        </ul>

      </li>

       @auth
        <li>
            <div class="iocn-link">
            <a href="#">
                <i class='bx bx-line-chart' ></i>
                <span class="link_name">More Access.</span>
            </a>
            <i class='bx bxs-chevron-down arrow' ></i>
            </div>
            <ul  class="sub-menu">
                <li  ><a class="link_name" href="/">More Access.</a></li>
                {{-- @if (Request::is('/')) --}}
                        {{-- <li><a href="{{ url('/') }}">Home</a></li>	 --}}
                {{-- @elseif (Request::is('testing')) --}}
                <li id="sub_menu_color"  ><a style="color: #E4E9F7" href="{{ url('testing') }}">Testing</a></li>	
                {{-- @elseif (Request::is('pmtct')) --}}
                <li id="sub_menu_color"  ><a style="color: #E4E9F7" href="{{ url('pmtct') }}">PMTCT</a></li>	
                {{-- @elseif (Request::is('art')) --}}
                <li id="sub_menu_color"  ><a style="color: #E4E9F7" href="{{ url('art') }}">ART</a></li>	
                {{-- @elseif (Request::is('vmmc')) --}}
                <li id="sub_menu_color"  ><a style="color: #E4E9F7"  href="{{ url('vmmc') }}">VMMC</a></li>	
                {{-- @elseif (Request::is('tb')) --}}
                <li id="sub_menu_color"  ><a style="color: #E4E9F7" href="{{ url('tb') }}">TB</a></li>	
                {{-- @elseif (Request::is('keypop')) --}}
                <li id="sub_menu_color"  ><a style="color: #E4E9F7" href="{{ url('keypop') }}">KeyPOP</a></li>	
                {{-- @elseif (Request::is('indicators')) --}}
                <li id="sub_menu_color"  ><a style="color: #E4E9F7"  href="{{ url('indicators') }}">Indicators</a></li>	
                {{-- @elseif (Request::is('non_mer')) --}}
                <li id="sub_menu_color"  ><a style="color: #E4E9F7"  href="{{ url('non_mer') }}">Non Mer</a></li>	
                {{-- @elseif (Request::is('pns')) --}}
                <li id="sub_menu_color"  ><a style="color: #E4E9F7"  href="{{ url('pns') }}">PNS</a></li>	
                {{-- @elseif (Request::is('surge')) --}}
                <li id="sub_menu_color"  ><a style="color: #E4E9F7" href="{{ url('surge') }}">Surge</a></li>	
                {{-- @elseif (Request::is('violence')) --}}
                <li id="sub_menu_color"  ><a style="color: #E4E9F7"  href="{{ url('violence') }}">GBV Dashboard</a></li>
                {{-- @elseif (Request::is('gbv')) --}}
                <li id="sub_menu_color"  ><a style="color: #E4E9F7" href="{{ url('gbv') }}">GBV Deep Dive</a></li>
                {{-- @elseif(Request::is('hfr')) --}}
                <li id="hfr"><a href="{{ url('hfr') }}">HFR</a></li>	
                {{-- @elseif(Request::is('cervical_cancer')) --}}
                <li id="sub_menu_color"  ><a style="color: #E4E9F7"  id="cancer"><a href="{{ url('cervical_cancer') }}">Cervical Cancer</a></li>	
                {{-- @elseif(Request::is('regimen')) --}}
                <li id="sub_menu_color"  ><a style="color: #E4E9F7" href="{{ url('regimen') }}">MOH 729</a></li>	
                {{-- @endif --}}
            </ul>
        </li>
       @endauth

       @auth
       <li>
           <div class="iocn-link">
           <a href="#">
               <i class='bx bx-line-chart' ></i>
               <span class="link_name">Downloads.</span>
           </a>
           <i class='bx bxs-chevron-down arrow' ></i>
           </div>
           <ul  class="sub-menu">

            <li class="dropdown">
                <a href="#" data-target="#" class="dropdown-toggle" data-toggle="dropdown">
                    Download Other Templates <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="{{ url('/weekly/download/vmmc_circ') }}">Download VMMC Circ Template</a></li>
                    <li><a href="{{ url('/weekly/download/prep_new') }}">Download PREP New Template</a></li>
                    <li><a href="{{ url('/tx_curr/download') }}">Download TX Current Template</a></li>
                    <li><a href="{{ url('/dispensing/download') }}">Download Multi-Month Dispensing Template</a></li>
                    <li><a href="{{ url('/pns/download') }}">Download PNS Template</a></li>
                    <li><a href="{{ url('/surge/download') }}">Download Surge Template</a></li>
                    <li><a href="{{ url('/gbv/download') }}">Download GBV Template</a></li>
                    <li><a href="{{ url('/hfr/download') }}">Download HFR Template</a></li>
                    <li><a href="{{ url('/cervical_cancer/download') }}">Download Cervical Cancer Template</a></li>
                    @if(auth()->user()->user_type_id < 3)
                        <li><a href="{{ url('/gbv/download-report') }}">Download Quarterly GBV Report</a></li>
                        <li><a href="{{ url('/hfr/download-report') }}">Download Quarterly HFR Report</a></li>
                    @endif
                </ul>
            </li>
            
            <li class="dropdown">
                <a href="#" data-target="#" class="dropdown-toggle" data-toggle="dropdown">
                    Download Indicators Template <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li ><a href="{{ url('download/indicators/2017') }}">2017</a></li>
                    <li><a href="{{ url('download/indicators/2018') }}">2018</a></li>
                    <li><a href="{{ url('download/indicators/2019') }}">2019</a></li>
                    <li><a href="{{ url('download/indicators/2020') }}">2020</a></li>
                </ul>
            </li>							
            <li class="dropdown">
                <a href="#" data-target="#" class="dropdown-toggle" data-toggle="dropdown">
                    Download Non-mer Template <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="{{ url('download/non_mer/2017') }}">2017</a></li>
                    <li><a href="{{ url('download/non_mer/2018') }}">2018</a></li>
                    <li><a href="{{ url('download/non_mer/2019') }}">2019</a></li>
                    <li><a href="{{ url('download/non_mer/2020') }}">2020</a></li>
                </ul>
            </li>	
         

            <li><a href="{{ url('/surge/set_surge_facilities') }}">Set Surge Facilities</a></li>
            
            <li class="dropdown">
                <a href="#" data-target="#" class="dropdown-toggle" data-toggle="dropdown">
                    Upload Templates <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="{{ url('/upload/weekly/vmmc_circ') }}">Upload VMMC Circ</a></li>
                    <li><a href="{{ url('/upload/weekly/prep_new') }}">Upload PREP New</a></li>
                    <li><a href="{{ url('/upload/tx_curr') }}">Upload TX Current</a></li>
                    <li><a href="{{ url('/upload/dispensing') }}">Upload Multi-Month Dispensing</a></li>
                    <li><a href="{{ url('/upload/gbv') }}">Upload GBV</a></li>
                    <li><a href="{{ url('/upload/hfr') }}">Upload HFR</a></li>
                    <li><a href="{{ url('/upload/cervical-cancer') }}">Upload Cervical Cancer</a></li>
                    <li><a href="{{ url('/upload/surge') }}">Upload Surge</a></li>
                    <li><a href="{{ url('/upload/pns') }}">Upload PNS</a></li>
                    <li><a href="{{ url('/upload/indicators') }}">Upload Early Warning Indicators</a></li>
                    <li><a href="{{ url('/upload/non_mer') }}">Upload Non-Mer Indicators</a></li>
                </ul>
            </li>
            @if(auth()->user()->user_type_id == 1)
                <li><a href="{{ url('/user/create') }}">Create User</a></li>
            @endif
            <li><a href="{{ url('/user/change_password') }}">Change Password</a></li>
           
           </ul>
       </li>
      @endauth

      {{-- <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-book-alt' ></i>
            <span class="link_name">Posts</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Posts</a></li>
          <li><a href="#">Web Design</a></li>
          <li><a href="#">Login Form</a></li>
          <li><a href="#">Card Design</a></li>
        </ul>
      </li> --}}
      
    <div class="profile-details">
      <div class="profile-content">
        <!--<img src="image/profile.jpg" alt="profileImg">-->
      </div>
      <div class="name-job">
        <div class="profile_name">Powered By,</div>
        <div class="job">HealthIT.</div>
      </div>
      <i class='bx bx-log-out' ></i>
    </div>
  </li>
</ul>  
  </div>
  <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu' ></i>
      <span class="text">HFR: <small style="font-size: 6px" >High Frequency Reporting.</small>

      </span>
      {{-- TOP NAVIGATION --}}

      <div style="margin-left:  45%; margin-top: 20px;"  class="navbar navbar-default">
        <div style="border: 1.5px solid #1D5288; border-radius: 5%; "  onMouseOver="this.style.color='#000'"  onMouseOut="this.style.color='#fff'"  class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                
            </div>
            <div class="navbar-collapse collapse navbar-responsive-collapse">
                <ul  class="nav navbar-nav">
                    
                    
                    <!-- 
                    <li><a href="{{ url('dispensing') }}">MMD</a></li>	
                    <li><a href="{{ url('tx_curr') }}">MMD</a></li>	
                    <li><a href="{{ url('weekly') }}">MMD</a></li>	
                     -->
                    
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li  ><a href="https://partnermanagementsystem.uonbi.ac.ke/api/apps/Partner-Reporting-Dashboards/html/index.html" >Home</a></li>
                    <li   ><a href="{{ url('/guide') }}">User Guide</a></li>
                    @guest
                        <li><a href="{{ url('/login') }}">Login</a></li>
                    @endguest	
                    @auth
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

        @auth
            <div class="css-after">
                <img style="width: 40px; height: 40px; margin-top: 18px; margin-left: 20px;" src="img/user.png" alt="Kenya Logo">

                <br>
                <p  style="margin-left: 15px;">{{auth()->user()->name}}</p>
            </div>
        @endauth

        
    </div>
    

 

    </div>


    <!-- Begining of Navigation Bar -->
		
		<!-- End of Navigation Bar -->
		<!-- Begining of Dashboard area -->
		<div style="background-color: #E4E9F7;" class="container-fluid">

			@empty($no_header)

				@if(session('financial'))
					@if(isset($no_fac))
						{{-- @include('layouts.no_fac') --}}
					@else
						@include('layouts.financial')
					@endif
				@else
					@include('layouts.year_month')
				@endif

			@endempty

			@yield('content')
		</div>

  </section>

  </div>


  <script>
  let arrow = document.querySelectorAll(".arrow");
  for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e)=>{
   let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
   arrowParent.classList.toggle("showMenu");
    });
  }
  let sidebar = document.querySelector(".sidebar");
  let sidebarBtn = document.querySelector(".bx-menu");
  console.log(sidebarBtn);
  sidebarBtn.addEventListener("click", ()=>{
    sidebar.classList.toggle("close");
  });
  </script>
</body>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

	<script src="{{ asset('js/toastr/toastr.min.js') }}"></script>

	<script src="{{ asset('js/highcharts/highcharts.js') }}" type='text/javascript'></script>
	<script src="{{ asset('js/highcharts/highcharts-more.js') }}" type='text/javascript'></script>
	@if(ends_with(url()->current(), ['surge', 'gbv']) && !auth()->user())
	@else
		<script src="{{ asset('js/highcharts/exporting.js') }}" type='text/javascript'></script>
		<script src="{{ asset('js/highcharts/export-data.js') }}" type='text/javascript'></script>
	@endif
	<script src="{{ asset('js/highcharts/map.js') }}" type='text/javascript'></script>
	<script src='//cdn.datatables.net/1.10.12/js/jquery.dataTables.js' type='text/javascript'></script>
	<script src='//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.js' type='text/javascript'></script>
	<script src='//cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js' type='text/javascript'></script>
	<script src='//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js' type='text/javascript'></script>
	<script src='//cdn.datatables.net/buttons/1.4.2/js/buttons.colVis.min.js' type='text/javascript'></script>
	<script src='//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js' type='text/javascript'></script>

	<script src="{{ url('js/customFunctions1.4.js') }}"></script>

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


		    @empty($no_header)

			$("select").change(function(){
				em = $(this).val();
				id = $(this).attr('id');

				var posting = $.post( "{{ url('filter/any') }}", { 'session_var': id, 'value': em } );

				posting.done(function( data ) {
					// console.log(data);
					reload_page();
				});

				posting.fail(function( data ) {
					location.reload(true);
					/*console.log(data);
		            setTimeout(function(){
		                toastr.options = {
		                    closeButton: false,
		                    progressBar: false,
		                    showMethod: 'slideDown',
		                    timeOut: 10000
		                };
	                    toastr.error("Kindly reload the page.", "Notice!");
		            });*/
				});
			});	

			@endempty	    


	    });
	</script>


    @yield('scripts')
</html>
		

</html>
