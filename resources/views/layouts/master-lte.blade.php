<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Partner Performance | Dashboard 3</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to to the body tag
to get the desired effect
|---------------------------------------------------------|
|LAYOUT OPTIONS | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition sidebar-mini">
<div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->

            <ul class="nav navbar-nav">
                @if (Request::is('/'))
                    <li><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                @elseif (Request::is('testing'))
                    <li><a class="nav-link" href="{{ url('testing') }}">Testing</a></li>
                @elseif (Request::is('pmtct'))
                    <li><a class="nav-link" href="{{ url('pmtct') }}">PMTCT</a></li>
                @elseif (Request::is('art'))
                    <li><a class="nav-link" href="{{ url('art') }}">ART</a></li>
                @elseif (Request::is('vmmc'))
                    <li><a class="nav-link" href="{{ url('vmmc') }}">VMMC</a></li>
                @elseif (Request::is('tb'))
                    <li><a class="nav-link" href="{{ url('tb') }}">TB</a></li>
                @elseif (Request::is('keypop'))
                    <li><a class="nav-link" href="{{ url('keypop') }}">KeyPOP</a></li>
                @elseif (Request::is('indicators'))
                    <li><a class="nav-link" href="{{ url('indicators') }}">Indicators</a></li>
                @elseif (Request::is('non_mer'))
                    <li><a class="nav-link" href="{{ url('non_mer') }}">Non Mer</a></li>
                @elseif (Request::is('pns'))
                    <li><a class="nav-link" href="{{ url('pns') }}">PNS</a></li>
                @elseif (Request::is('surge'))
                    <li><a class="nav-link" href="{{ url('surge') }}">Surge</a></li>
                @elseif (Request::is('violence'))
                    <li><a class="nav-link" href="{{ url('violence') }}">GBV Dashboard</a></li>
                @elseif (Request::is('gbv'))
                    <li><a class="nav-link" href="{{ url('gbv') }}">GBV Deep Dive</a></li>
                @elseif(Request::is('hfr'))
                    <li id="hfr"><a class="nav-link" href="{{ url('hfr') }}">HFR</a></li>
                @elseif(Request::is('cervical_cancer'))
                    <li id="cancer"><a class="nav-link" href="{{ url('cervical_cancer') }}">Cervical Cancer</a></li>
                @elseif(Request::is('regimen'))
                    <li><a class="nav-link" href="{{ url('regimen') }}">MOH 729</a></li>
                @endif

                {{-- <li><a href="{{ url('dispensing') }}">MMD</a></li>
                <li><a href="{{ url('tx_curr') }}">MMD</a></li>
                <li><a href="{{ url('weekly') }}">MMD</a></li> --}}


            </ul>

            @auth
                <ul class="navbar-nav">
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <li class="nav-item dropdown d-none d-sm-inline-block">
                        <a href="#" data-target="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            Indicators Template <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="{{ url('download/indicators/2017') }}">2017</a></li>
                            <li><a class="nav-link" href="{{ url('download/indicators/2018') }}">2018</a></li>
                            <li><a class="nav-link" href="{{ url('download/indicators/2019') }}">2019</a></li>
                            <li><a class="nav-link" href="{{ url('download/indicators/2020') }}">2020</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown d-none d-sm-inline-block">
                        <a href="#" data-target="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            Non-mer Template <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="{{ url('download/non_mer/2017') }}">2017</a></li>
                            <li><a class="nav-link" href="{{ url('download/non_mer/2018') }}">2018</a></li>
                            <li><a class="nav-link" href="{{ url('download/non_mer/2019') }}">2019</a></li>
                            <li><a class="nav-link" href="{{ url('download/non_mer/2020') }}">2020</a></li>
                        </ul>
                    </li>
                    <li class=" dropdown nav-item d-none d-sm-inline-block">
                        <a href="#" data-target="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Download
                            Other Templates</a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="{{ url('/weekly/download/vmmc_circ') }}">Download VMMC Circ
                                    Template</a></li>
                            <li><a class="nav-link" href="{{ url('/weekly/download/prep_new') }}">Download PREP New
                                    Template</a></li>
                            <li><a class="nav-link" href="{{ url('/tx_curr/download') }}">Download TX Current
                                    Template</a>
                            </li>
                            <li><a class="nav-link" href="{{ url('/dispensing/download') }}">Download Multi-Month
                                    Dispensing
                                    Template</a></li>
                            <li><a class="nav-link" href="{{ url('/pns/download') }}">Download PNS Template</a></li>
                            <li><a class="nav-link" href="{{ url('/surge/download') }}">Download Surge Template</a></li>
                            <li><a class="nav-link" href="{{ url('/gbv/download') }}">Download GBV Template</a></li>
                            <li><a class="nav-link" href="{{ url('/hfr/download') }}">Download HFR Template</a></li>
                            <li><a class="nav-link" href="{{ url('/cervical_cancer/download') }}">Download Cervical
                                    Cancer
                                    Template</a>
                            </li>
                            @if (auth()->user()->user_type_id < 3)
                                <li><a class="nav-link" href="{{ url('/gbv/download-report') }}">Download Quarterly GBV
                                        Report</a></li>
                                <li><a class="nav-link" href="{{ url('/hfr/download-report') }}">Download Quarterly HFR
                                        Report</a></li>
                            @endif
                            <li><a class="nav-link" href="{{ url('/surge/set_surge_facilities') }}">Set Surge
                                    Facilities</a></li>
                        </ul>
                    </li>
                    <li class="dropdown nav-item d-none d-sm-inline-block">
                        <a href="#" data-target="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            Upload Templates <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="{{ url('/upload/weekly/vmmc_circ') }}">Upload VMMC Circ</a>
                            </li>
                            <li><a class="nav-link" href="{{ url('/upload/weekly/prep_new') }}">Upload PREP New</a></li>
                            <li><a class="nav-link" href="{{ url('/upload/tx_curr') }}">Upload TX Current</a></li>
                            <li><a class="nav-link" href="{{ url('/upload/dispensing') }}">Upload Multi-Month
                                    Dispensing</a></li>
                            <li><a class="nav-link" href="{{ url('/upload/gbv') }}">Upload GBV</a></li>
                            <li><a class="nav-link" href="{{ url('/upload/hfr') }}">Upload HFR</a></li>
                            <li><a class="nav-link" href="{{ url('/upload/cervical-cancer') }}">Upload Cervical
                                    Cancer</a></li>
                            <li><a class="nav-link" href="{{ url('/upload/surge') }}">Upload Surge</a></li>
                            <li><a class="nav-link" href="{{ url('/upload/pns') }}">Upload PNS</a></li>
                            <li><a class="nav-link" href="{{ url('/upload/indicators') }}">Upload Early Warning
                                    Indicators</a></li>
                            <li><a class="nav-link" href="{{ url('/upload/non_mer') }}">Upload Non-Mer Indicators</a>
                            </li>
                        </ul>
                    </li>

                </ul>
            @endauth

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Messages Dropdown Menu -->
                &nbsp;
                &nbsp;
                &nbsp;
                <li class="nav-item d-none d-sm-inline-block">
                    <a class="nav-link" href="{{ url('/guide') }}">User Guide</a>
                </li>
                &nbsp;
                &nbsp;
                &nbsp;
                @guest
                    <li class="nav-item d-none d-sm-inline-block"><a class="nav-link"
                            href="{{ url('/login') }}">Login</a></li>
                @endguest
                @auth
                    <li class="nav-item dropdown d-none d-sm-inline-block">
                        <a href="#" data-target="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            Account <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            @if (auth()->user()->user_type_id == 1)
                                <li>
                                    <a class="nav-link" href="{{ url('/user/create') }}">
                                        Create User
                                    </a>
                                </li>
                                <li><a class="nav-link" href="{{ url('/user/change_password') }}">Change Password</a>
                                </li>
                                <li><a class="nav-link" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                </li>
                                <form id="logout-form" action="{{ url('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            @endif
                        </ul>
                    </li>
                @endauth
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-2">
            <!-- Brand Logo -->
            <a href="https://partnermanagementsystem.uonbi.ac.ke/api/apps/Partner-Reporting-Dashboards/html/index.html"
                class="brand-link">
                <img src="dist/img/kenya.png" alt="Kenya Logo" class="brand-image img elevation-2">
                <span class="brand-text">Partner Performance</span>
            </a>

            <!-- Sidebar -->
            @if (@isset($counties))
                <div class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="dist/img/user.png" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block">Admin</a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                            data-accordion="false">
                            <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                            <li  class="nav-item has-treeview menu-open">
                                <a href="#" class="">
                                    {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
                                    <div>
                                        <select style="background-color: #0d6efd;" class="btn-primary btn filters form-control" id="filter_county">
                                            <option disabled='true'>Select County</option>
                                            <option value='null' selected='true'>All Counties</option>
                            
                                            @foreach($counties as $county)
                                                <option value="{{ $county->id }}"> {{ $county->name }} </option>
                                            @endforeach
                                        </select>		
                                    </div>
                                </a>
                            </li>
                            <li  style="margin-top: padding-top:10%" class="nav-item has-treeview menu-open">
                                <a href="#" class="">
                                    {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
                                    <div>
                                        <select style="background-color: #0d6efd;" class="btn-primary btn filters form-control" id="filter_county">
                                            <option disabled='true'>Select Subcounty</option>
                                            <option value='null' selected='true'>All Subcounties</option>
                                            @foreach($subcounties as $subcounty)
                                                <option value="{{ $subcounty->id }}"> {{ $subcounty->name }} </option>
                                            @endforeach
                                        </select>		
                                    </div>	
                                </a>
                            </li>
                            <li  class="nav-item has-treeview menu-open">
                                <a href="#" class="">
                                    {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
                                    <div>
                                        <select style="background-color: #0d6efd;" class="btn-primary btn filters form-control" id="filter_county">
                                            <option disabled='true'>Select Ward</option>
                                            <option value='null' selected='true'>All Wards</option>
                            
                                            @foreach($wards as $ward)
                                                <option value="{{ $ward->id }}"> {{ $ward->name }} </option>
                                            @endforeach
                                        </select>		
                                    </div>         
                                    
                                </a>
                            </li>
                            <li  class="nav-item has-treeview menu-open">
                                <a href="#" class="">
                                    {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
                                    <div>
                                        <select style="background-color: #0d6efd;" class="btn-primary btn filters form-control" id="filter_county">
                                            <option disabled='true'>Select Partner</option>
                                            <option value='null' selected='true'>All Partners</option>
                            
                                            @foreach($partners as $partner)
                                                <option value="{{ $partner->id }}"> {{ $partner->name }} </option>
                                            @endforeach
                                        </select>	
                                    </div>         
                                    
                                </a>
                            </li>
                            <li  class="nav-item has-treeview menu-open">
                                <a href="#" class="">
                                    {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
                                    <div>
                                        <select style="background-color: #0d6efd;" class="btn-primary btn filters form-control" id="filter_county">
                                            <option disabled='true'>Select Facility</option>
                                            <option value='null' selected='true'>All Facilities</option>
                    
                                        </select>
                                    </div>         
                                    
                                </a>
                            </li>

                            
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            @endif

        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="navbar-header">
                            <a class="navbar-brand" href="javascript:void(0)"
                                style="padding:0px;padding-top:4px;padding-left:4px;">
                                <img src="{{ url('img/nascop_pepfar_logo.jpg') }}"
                                    style="width:280px;height:52px;" />
                            </a>
                        </div>
                        <div class="col-sm-8">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->


                    <div class="container-fluid">
                        @empty($no_header)

                            @if (session('financial'))
                                @if (isset($no_fac))
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

                    {{-- <div class="row">
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>150</h3>

                                    <p>New Orders</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>53<sup style="font-size: 20px">%</sup></h3>

                                    <p>Bounce Rate</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>44</h3>

                                    <p>User Registrations</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>65</h3>

                                    <p>Unique Visitors</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>
                    <!-- /.row -->
                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-lg-7 connectedSortable">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        Sales
                                    </h3>
                                    <div class="card-tools">
                                        <ul class="nav nav-pills ml-auto">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#revenue-chart"
                                                    data-toggle="tab">Area</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content p-0">
                                        <!-- Morris chart - Sales -->
                                        <div class="chart tab-pane active" id="revenue-chart"
                                            style="position: relative; height: 300px;">
                                            <canvas id="revenue-chart-canvas" height="300"
                                                style="height: 300px;"></canvas>
                                        </div>
                                        <div class="chart tab-pane" id="sales-chart"
                                            style="position: relative; height: 300px;">
                                            <canvas id="sales-chart-canvas" height="300"
                                                style="height: 300px;"></canvas>
                                        </div>
                                    </div>
                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                            <!-- DIRECT CHAT -->
                            <div class="card direct-chat direct-chat-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Direct Chat</h3>

                                    <div class="card-tools">
                                        <span data-toggle="tooltip" title="3 New Messages"
                                            class="badge badge-primary">3</span>
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-toggle="tooltip"
                                            title="Contacts" data-widget="chat-pane-toggle">
                                            <i class="fas fa-comments"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                                class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <!-- Conversations are loaded here -->
                                    <div class="direct-chat-messages">
                                        <!-- Message. Default to the left -->
                                        <div class="direct-chat-msg">
                                            <div class="direct-chat-infos clearfix">
                                                <span class="direct-chat-name float-left">Alexander Pierce</span>
                                                <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                                            </div>
                                            <!-- /.direct-chat-infos -->
                                            <img class="direct-chat-img" src="dist/img/user1-128x128.jpg"
                                                alt="message user image">
                                            <!-- /.direct-chat-img -->
                                            <div class="direct-chat-text">
                                                Is this template really for free? That's unbelievable!
                                            </div>
                                            <!-- /.direct-chat-text -->
                                        </div>
                                        <!-- /.direct-chat-msg -->

                                        <!-- Message to the right -->
                                        <div class="direct-chat-msg right">
                                            <div class="direct-chat-infos clearfix">
                                                <span class="direct-chat-name float-right">Sarah Bullock</span>
                                                <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                                            </div>
                                            <!-- /.direct-chat-infos -->
                                            <img class="direct-chat-img" src="dist/img/user3-128x128.jpg"
                                                alt="message user image">
                                            <!-- /.direct-chat-img -->
                                            <div class="direct-chat-text">
                                                You better believe it!
                                            </div>
                                            <!-- /.direct-chat-text -->
                                        </div>
                                        <!-- /.direct-chat-msg -->

                                        <!-- Message. Default to the left -->
                                        <div class="direct-chat-msg">
                                            <div class="direct-chat-infos clearfix">
                                                <span class="direct-chat-name float-left">Alexander Pierce</span>
                                                <span class="direct-chat-timestamp float-right">23 Jan 5:37 pm</span>
                                            </div>
                                            <!-- /.direct-chat-infos -->
                                            <img class="direct-chat-img" src="dist/img/user1-128x128.jpg"
                                                alt="message user image">
                                            <!-- /.direct-chat-img -->
                                            <div class="direct-chat-text">
                                                Working with AdminLTE on a great new app! Wanna join?
                                            </div>
                                            <!-- /.direct-chat-text -->
                                        </div>
                                        <!-- /.direct-chat-msg -->

                                        <!-- Message to the right -->
                                        <div class="direct-chat-msg right">
                                            <div class="direct-chat-infos clearfix">
                                                <span class="direct-chat-name float-right">Sarah Bullock</span>
                                                <span class="direct-chat-timestamp float-left">23 Jan 6:10 pm</span>
                                            </div>
                                            <!-- /.direct-chat-infos -->
                                            <img class="direct-chat-img" src="dist/img/user3-128x128.jpg"
                                                alt="message user image">
                                            <!-- /.direct-chat-img -->
                                            <div class="direct-chat-text">
                                                I would love to.
                                            </div>
                                            <!-- /.direct-chat-text -->
                                        </div>
                                        <!-- /.direct-chat-msg -->

                                    </div>
                                    <!--/.direct-chat-messages-->

                                    <!-- Contacts are loaded here -->
                                    <div class="direct-chat-contacts">
                                        <ul class="contacts-list">
                                            <li>
                                                <a href="#">
                                                    <img class="contacts-list-img" src="dist/img/user1-128x128.jpg">

                                                    <div class="contacts-list-info">
                                                        <span class="contacts-list-name">
                                                            Count Dracula
                                                            <small
                                                                class="contacts-list-date float-right">2/28/2015</small>
                                                        </span>
                                                        <span class="contacts-list-msg">How have you been? I
                                                            was...</span>
                                                    </div>
                                                    <!-- /.contacts-list-info -->
                                                </a>
                                            </li>
                                            <!-- End Contact Item -->
                                            <li>
                                                <a href="#">
                                                    <img class="contacts-list-img" src="dist/img/user7-128x128.jpg">

                                                    <div class="contacts-list-info">
                                                        <span class="contacts-list-name">
                                                            Sarah Doe
                                                            <small
                                                                class="contacts-list-date float-right">2/23/2015</small>
                                                        </span>
                                                        <span class="contacts-list-msg">I will be waiting for...</span>
                                                    </div>
                                                    <!-- /.contacts-list-info -->
                                                </a>
                                            </li>
                                            <!-- End Contact Item -->
                                            <li>
                                                <a href="#">
                                                    <img class="contacts-list-img" src="dist/img/user3-128x128.jpg">

                                                    <div class="contacts-list-info">
                                                        <span class="contacts-list-name">
                                                            Nadia Jolie
                                                            <small
                                                                class="contacts-list-date float-right">2/20/2015</small>
                                                        </span>
                                                        <span class="contacts-list-msg">I'll call you back at...</span>
                                                    </div>
                                                    <!-- /.contacts-list-info -->
                                                </a>
                                            </li>
                                            <!-- End Contact Item -->
                                            <li>
                                                <a href="#">
                                                    <img class="contacts-list-img" src="dist/img/user5-128x128.jpg">

                                                    <div class="contacts-list-info">
                                                        <span class="contacts-list-name">
                                                            Nora S. Vans
                                                            <small
                                                                class="contacts-list-date float-right">2/10/2015</small>
                                                        </span>
                                                        <span class="contacts-list-msg">Where is your new...</span>
                                                    </div>
                                                    <!-- /.contacts-list-info -->
                                                </a>
                                            </li>
                                            <!-- End Contact Item -->
                                            <li>
                                                <a href="#">
                                                    <img class="contacts-list-img" src="dist/img/user6-128x128.jpg">

                                                    <div class="contacts-list-info">
                                                        <span class="contacts-list-name">
                                                            John K.
                                                            <small
                                                                class="contacts-list-date float-right">1/27/2015</small>
                                                        </span>
                                                        <span class="contacts-list-msg">Can I take a look at...</span>
                                                    </div>
                                                    <!-- /.contacts-list-info -->
                                                </a>
                                            </li>
                                            <!-- End Contact Item -->
                                            <li>
                                                <a href="#">
                                                    <img class="contacts-list-img" src="dist/img/user8-128x128.jpg">

                                                    <div class="contacts-list-info">
                                                        <span class="contacts-list-name">
                                                            Kenneth M.
                                                            <small
                                                                class="contacts-list-date float-right">1/4/2015</small>
                                                        </span>
                                                        <span class="contacts-list-msg">Never mind I found...</span>
                                                    </div>
                                                    <!-- /.contacts-list-info -->
                                                </a>
                                            </li>
                                            <!-- End Contact Item -->
                                        </ul>
                                        <!-- /.contacts-list -->
                                    </div>
                                    <!-- /.direct-chat-pane -->
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <form action="#" method="post">
                                        <div class="input-group">
                                            <input type="text" name="message" placeholder="Type Message ..."
                                                class="form-control">
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-primary">Send</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card-footer-->
                            </div>
                            <!--/.direct-chat -->

                            <!-- TO DO List -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="ion ion-clipboard mr-1"></i>
                                        To Do List
                                    </h3>

                                    <div class="card-tools">
                                        <ul class="pagination pagination-sm">
                                            <li class="page-item"><a href="#" class="page-link">&laquo;</a>
                                            </li>
                                            <li class="page-item"><a href="#" class="page-link">1</a></li>
                                            <li class="page-item"><a href="#" class="page-link">2</a></li>
                                            <li class="page-item"><a href="#" class="page-link">3</a></li>
                                            <li class="page-item"><a href="#" class="page-link">&raquo;</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <ul class="todo-list" data-widget="todo-list">
                                        <li>
                                            <!-- drag handle -->
                                            <span class="handle">
                                                <i class="fas fa-ellipsis-v"></i>
                                                <i class="fas fa-ellipsis-v"></i>
                                            </span>
                                            <!-- checkbox -->
                                            <div class="icheck-primary d-inline ml-2">
                                                <input type="checkbox" value="" name="todo1"
                                                    id="todoCheck1">
                                                <label for="todoCheck1"></label>
                                            </div>
                                            <!-- todo text -->
                                            <span class="text">Design a nice theme</span>
                                            <!-- Emphasis label -->
                                            <small class="badge badge-danger"><i class="far fa-clock"></i> 2
                                                mins</small>
                                            <!-- General tools such as edit or delete-->
                                            <div class="tools">
                                                <i class="fas fa-edit"></i>
                                                <i class="fas fa-trash-o"></i>
                                            </div>
                                        </li>
                                        <li>
                                            <span class="handle">
                                                <i class="fas fa-ellipsis-v"></i>
                                                <i class="fas fa-ellipsis-v"></i>
                                            </span>
                                            <div class="icheck-primary d-inline ml-2">
                                                <input type="checkbox" value="" name="todo2" id="todoCheck2"
                                                    checked>
                                                <label for="todoCheck2"></label>
                                            </div>
                                            <span class="text">Make the theme responsive</span>
                                            <small class="badge badge-info"><i class="far fa-clock"></i> 4
                                                hours</small>
                                            <div class="tools">
                                                <i class="fas fa-edit"></i>
                                                <i class="fas fa-trash-o"></i>
                                            </div>
                                        </li>
                                        <li>
                                            <span class="handle">
                                                <i class="fas fa-ellipsis-v"></i>
                                                <i class="fas fa-ellipsis-v"></i>
                                            </span>
                                            <div class="icheck-primary d-inline ml-2">
                                                <input type="checkbox" value="" name="todo3"
                                                    id="todoCheck3">
                                                <label for="todoCheck3"></label>
                                            </div>
                                            <span class="text">Let theme shine like a star</span>
                                            <small class="badge badge-warning"><i class="far fa-clock"></i> 1
                                                day</small>
                                            <div class="tools">
                                                <i class="fas fa-edit"></i>
                                                <i class="fas fa-trash-o"></i>
                                            </div>
                                        </li>
                                        <li>
                                            <span class="handle">
                                                <i class="fas fa-ellipsis-v"></i>
                                                <i class="fas fa-ellipsis-v"></i>
                                            </span>
                                            <div class="icheck-primary d-inline ml-2">
                                                <input type="checkbox" value="" name="todo4"
                                                    id="todoCheck4">
                                                <label for="todoCheck4"></label>
                                            </div>
                                            <span class="text">Let theme shine like a star</span>
                                            <small class="badge badge-success"><i class="far fa-clock"></i> 3
                                                days</small>
                                            <div class="tools">
                                                <i class="fas fa-edit"></i>
                                                <i class="fas fa-trash-o"></i>
                                            </div>
                                        </li>
                                        <li>
                                            <span class="handle">
                                                <i class="fas fa-ellipsis-v"></i>
                                                <i class="fas fa-ellipsis-v"></i>
                                            </span>
                                            <div class="icheck-primary d-inline ml-2">
                                                <input type="checkbox" value="" name="todo5"
                                                    id="todoCheck5">
                                                <label for="todoCheck5"></label>
                                            </div>
                                            <span class="text">Check your messages and notifications</span>
                                            <small class="badge badge-primary"><i class="far fa-clock"></i> 1
                                                week</small>
                                            <div class="tools">
                                                <i class="fas fa-edit"></i>
                                                <i class="fas fa-trash-o"></i>
                                            </div>
                                        </li>
                                        <li>
                                            <span class="handle">
                                                <i class="fas fa-ellipsis-v"></i>
                                                <i class="fas fa-ellipsis-v"></i>
                                            </span>
                                            <div class="icheck-primary d-inline ml-2">
                                                <input type="checkbox" value="" name="todo6"
                                                    id="todoCheck6">
                                                <label for="todoCheck6"></label>
                                            </div>
                                            <span class="text">Let theme shine like a star</span>
                                            <small class="badge badge-secondary"><i class="far fa-clock"></i> 1
                                                month</small>
                                            <div class="tools">
                                                <i class="fas fa-edit"></i>
                                                <i class="fas fa-trash-o"></i>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                                    <button type="button" class="btn btn-info float-right"><i
                                            class="fas fa-plus"></i> Add item</button>
                                </div>
                            </div>
                            <!-- /.card -->
                        </section>
                        <!-- /.Left col -->
                        <!-- right col (We are only adding the ID to make the widgets sortable)-->
                        <section class="col-lg-5 connectedSortable">

                            <!-- Map card -->
                            <div class="card bg-gradient-primary">
                                <div class="card-header border-0">
                                    <h3 class="card-title">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        Visitors
                                    </h3>
                                    <!-- card tools -->
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-primary btn-sm daterange"
                                            data-toggle="tooltip" title="Date range">
                                            <i class="far fa-calendar-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm"
                                            data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <div class="card-body">
                                    <div id="world-map" style="height: 250px; width: 100%;"></div>
                                </div>
                                <!-- /.card-body-->
                                <div class="card-footer bg-transparent">
                                    <div class="row">
                                        <div class="col-4 text-center">
                                            <div id="sparkline-1"></div>
                                            <div class="text-white">Visitors</div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-4 text-center">
                                            <div id="sparkline-2"></div>
                                            <div class="text-white">Online</div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-4 text-center">
                                            <div id="sparkline-3"></div>
                                            <div class="text-white">Sales</div>
                                        </div>
                                        <!-- ./col -->
                                    </div>
                                    <!-- /.row -->
                                </div>
                            </div>
                            <!-- /.card -->

                            <!-- solid sales graph -->
                            <div class="card bg-gradient-info">
                                <div class="card-header border-0">
                                    <h3 class="card-title">
                                        <i class="fas fa-th mr-1"></i>
                                        Sales Graph
                                    </h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn bg-info btn-sm"
                                            data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas class="chart" id="line-chart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer bg-transparent">
                                    <div class="row">
                                        <div class="col-4 text-center">
                                            <input type="text" class="knob" data-readonly="true" value="20"
                                                data-width="60" data-height="60" data-fgColor="#39CCCC">

                                            <div class="text-white">Mail-Orders</div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-4 text-center">
                                            <input type="text" class="knob" data-readonly="true" value="50"
                                                data-width="60" data-height="60" data-fgColor="#39CCCC">

                                            <div class="text-white">Online</div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-4 text-center">
                                            <input type="text" class="knob" data-readonly="true" value="30"
                                                data-width="60" data-height="60" data-fgColor="#39CCCC">

                                            <div class="text-white">In-Store</div>
                                        </div>
                                        <!-- ./col -->
                                    </div>
                                    <!-- /.row -->
                                </div>
                                <!-- /.card-footer -->
                            </div>
                            <!-- /.card -->

                            <!-- Calendar -->
                            <div class="card bg-gradient-success">
                                <div class="card-header border-0">

                                    <h3 class="card-title">
                                        <i class="far fa-calendar-alt"></i>
                                        Calendar
                                    </h3>
                                    <!-- tools card -->
                                    <div class="card-tools">
                                        <!-- button with a dropdown -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success btn-sm dropdown-toggle"
                                                data-toggle="dropdown">
                                                <i class="fas fa-bars"></i></button>
                                            <div class="dropdown-menu float-right" role="menu">
                                                <a href="#" class="dropdown-item">Add new event</a>
                                                <a href="#" class="dropdown-item">Clear events</a>
                                                <div class="dropdown-divider"></div>
                                                <a href="#" class="dropdown-item">View calendar</a>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success btn-sm"
                                            data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm"
                                            data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <!-- /. tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body pt-0">
                                    <!--The calendar -->
                                    <div id="calendar" style="width: 100%"></div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </section>
                        <!-- right col -->
                    </div>
                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
            </section> --}}
                    <!-- /.content -->
                </div>
                <!-- /.content-wrapper -->
                

                <!-- Control Sidebar -->
                <aside class="control-sidebar control-sidebar-dark">
                    <!-- Control sidebar content goes here -->
                </aside>
                <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- ChartJS -->
        <script src="plugins/chart.js/Chart.min.js"></script>
        <!-- Sparkline -->
        <script src="plugins/sparklines/sparkline.js"></script>
        <!-- JQVMap -->
        <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
        <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
        <!-- jQuery Knob Chart -->
        <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
        <!-- daterangepicker -->
        <script src="plugins/moment/moment.min.js"></script>
        <script src="plugins/daterangepicker/daterangepicker.js"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
        <!-- Summernote -->
        <script src="plugins/summernote/summernote-bs4.min.js"></script>
        <!-- overlayScrollbars -->
        <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.js"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="dist/js/pages/dashboard.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="dist/js/demo.js"></script>
</body>

</html>
