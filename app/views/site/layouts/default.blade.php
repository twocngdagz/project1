<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- first
        ================================================== -->
        <meta charset="utf-8" />
        <title>
            @section('title')
            ClinicRise
            @show
        </title>
        <meta name="keywords" content="keywords" />
        <meta name="author" content="Jon Doe" />
        <meta name="description" content="Lorem ipsum dolor sit amet, nihil fabulas et sea, nam posse menandri scripserit no, mei." />

        <!-- Mobile Specific Metas
        ================================================== -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- CSS
        ================================================== -->
        <link rel="stylesheet" type="text/css" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" type="text/css"  href="{{asset('bootstrap/css/bootstrap-theme.min.css')}}">
        <link rel="stylesheet" type="text/css"  href="{{asset('bootstrap/css/bootstrap-dropdown-checkbox.css')}}">
        <link rel="stylesheet" type="text/css"  href="{{asset('bootstrap/css/bootstrap-accessibility.css')}}">
		<link rel="stylesheet" type="text/css"  href="{{asset('bootstrap/css/bootstrap-multiselect.css')}}">
        
        <link rel="stylesheet" type="text/css"  href="{{asset('/css/clinicrise.css')}}">
        <link rel="stylesheet" type="text/css"  href="{{asset('css/daterangepicker-bs3.css')}}" />
        <link rel="stylesheet" type="text/css"  href="{{asset('css/animate.css')}}" />
        <link rel="stylesheet" type="text/css"  href="{{asset('css/typeaheadjs.css')}}" />
        <link rel="stylesheet" type="text/css"  href="{{asset('css/datepicker.css')}}" />
        <link rel="stylesheet" type="text/css"  href="{{asset('css/daterangepicker.css')}}" />
        <link rel="stylesheet" href="{{URL::asset('css/dataTables.bootstrap.css');}}">
        <link rel="stylesheet" href="{{URL::asset('css/dropzone.css');}}">


        
         <!-- Javascripts
        ================================================== -->

        <script type="text/javascript" src="{{asset('/js/jquery-1.11.1.js')}}"></script>
        <script src="{{URL::asset('js/jquery.dataTables.js');}}"></script>
        <script src="{{URL::asset('js/dataTables.bootstrap.js');}}"></script>
        
        <script type="text/javascript" src="{{asset('/bootstrap/js/bootstrap.js')}}"></script>
        <script type="text/javascript" src="{{asset('/bootstrap/js/bootstrap-dropdown-checkbox.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/bootstrap/js/bootstrap-growl.js')}}"></script>
		<script type="text/javascript" src="{{asset('/bootstrap/js/bootstrap-multiselect.js')}}"></script>
        
        <script type="text/javascript" src="{{asset('/js/date.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/moment.js')}}"></script>

        <script type="text/javascript" src="{{asset('/js/daterangepicker.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/keynavigator.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/jquery.flot.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/jquery.flot.pie.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/jquery.flot.time.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/jquery.flot.symbol.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/jquery.flot.axislabels.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/jquery.mask.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/typeahead.jquery.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/bloodhound.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/dropzone.js')}}"></script>
<!--
        <script type="text/javascript" src="{{asset('/js/bootstrap-datepicker.js')}}"></script>
-->
<!--
        <script type="text/javascript" src="{{asset('/js/var_dump.js')}}"></script>
-->

        

        <style>
        body {
            padding: 60px 0;
        }
        @yield('styles')
        </style>

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Favicons
        ================================================== -->
    </head>

    <body>
        <!-- To make sticky footer need to wrap in a div -->
        <div id="wrap">
        <!-- Navbar -->
        <div class="navbar navbar-default navbar-inverse navbar-fixed-top" >
             <div class="container">
             <div class="navbar-inner pull-left"></div>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{{ URL::to('/') }}}"><b>ClinicRise</b></a>
                </div>
                
                <div class="collapse navbar-collapse navbar-ex1-collapse ">
                    <ul class="nav navbar-nav">
                        @if (Auth::check())
                        <li {{ (Request::is('user/patient') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/patient') }}}">Patients</a></li>          
                        {{--<li {{ (Request::is('user/referralgrid') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/referralgrid') }}}">Referral Grid</a></li>--}}
                        <li {{ (Request::is('user/clinic') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/clinic') }}}">Referral Source</a></li>
                        <li {{ (Request::is('user/activity') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/activity') }}}">Activities</a></li>
                       <li {{ (Request::is('user/report') || Request::is('user/dashboard') ? ' class="active"' : '') }} class="dropdown">
                         <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <span class="caret"></span></a>
                         <ul class="dropdown-menu" role="menu">
                           <li {{ (Request::is('user/report') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/report') }}}">Referrals</a></li>
                           <li {{ (Request::is('user/dashboard') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/dashboard') }}}">Conversions</a></li>
                         </ul>
                       </li>
                        @else
                        <li {{ (Request::is('/') ? ' class="active"' : '') }}><a href="{{{ URL::to('') }}}">Home</a></li>
                        @endif
                    </ul>

                    <ul class="nav navbar-nav pull-right">
                        @if (Auth::check())
                            @if(Auth::user()->role == 'admin') 
                                <li><a href="{{{ URL::to('admin/practice') }}}">Admin Panel</a></li>
                            @endif 
                        <li><a href="{{{ URL::to('user') }}}">Logged in as {{{ Auth::user()->name }}}</a></li>
                        <li><a href="{{{ URL::to('user/logout') }}}">Logout</a></li>
                        @else
                        <li {{ (Request::is('user/login') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/login') }}}">Login</a></li>
                        <li {{ (Request::is('user/register') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/register') }}}">Register</a></li>
                        @endif
                    </ul>
                    <!-- ./ nav-collapse -->
                </div>
            </div>
        </div>
        <!-- ./ navbar -->

        <!-- Container -->
        <div class="container">
            <!-- Notifications -->
            @include('notifications')
            <!-- ./ notifications -->

            <!-- Content -->
            @yield('content')
            <!-- ./ content -->
        </div>
        <!-- ./ container -->

        <!-- the following div is needed to make a sticky footer -->
        <div id="push"></div>
       </div>
        <!-- ./wrap -->


        <div id="footer">
          <div class="container">

          </div>
        </div>


        <!--- default script for all, like fading alerts and so on --->
        <script src="{{asset('/js/clinicrise.js')}}"></script>


        @yield('scripts')
        <script type="text/javascript">
            (function() {
                var s = document.createElement("script");
                s.type = "text/javascript";
                s.async = true;
                s.src = '//api.usersnap.com/load/'+
                    '7ab0856a-c447-4ec2-91fd-8af5700806c4.js';
                var x = document.getElementsByTagName('script')[0];
                x.parentNode.insertBefore(s, x);
            })();
        </script>
    </body>
</html>
