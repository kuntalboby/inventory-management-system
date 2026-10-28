<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7 "> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie10 lt-ie9"> <![endif]-->
<!--[if IE 9]><html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <!-- Basic Page Needs -->

    <meta charset="utf-8">
    <title>{{$basicData->site_name}} - @yield('title','')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Favicons -->
    <link rel="shortcut icon" href="{{asset('frontendassets/assets/images/favicon.ico') }}">

    <!-- FONTS -->
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:100,300,400,400italic,700'>
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Patua+One:100,300,400,400italic,700'>
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Lato:400,400italic,700,700italic,900'>
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Muli:100,300,400,400italic,500,700,700italic'>
    <link href="http://fonts.googleapis.com/css?family=Muli:800,700%7CMerienda:700" rel="stylesheet" property="stylesheet" type="text/css" media="all">

    <!-- CSS -->
    <link rel='stylesheet' href="{{asset('frontendassets/css/global.css') }}">
    <link rel='stylesheet' href="{{asset('frontendassets/assets/css/structure.css') }}">
    <link rel='stylesheet' href="{{asset('frontendassets/assets/css/style.css') }}">
    <link rel='stylesheet' href="{{asset('frontendassets/assets/css/custom.css') }}">

    <!-- Revolution Slider -->
    <link rel="stylesheet" href="{{asset('frontendassets/plugins/rs-plugin/css/settings.css') }}">

</head>

<body class="color-custom style-simple button-stroke layout-full-width no-content-padding header-fixed minimalist-header-no sticky-header 
sticky-tb-color ab-hide subheader-both-center menu-line-below-80
menuo-no-borders menuo-right mobile-tb-center mobile-side-slide mobile-mini-mr-ll be-reg-206" id=""
data-url="{{url('/')}}"
>

    <div id="Wrapper">
        <div id="Inner_Header_wrapper"
            class="bg-parallax"
            data-enllax-ratio="0.3"
                style="background-image:url(@yield('bg_image'))"
            >
            <header id="Header">
                <div class="header_placeholder"></div>
                <x-frontend.header
                    :smallLogo="$basicData->site_logo"
                    :retinaLogo="$basicData->site_thum_logo"
                    :menu="$menu"
                    ></x-frontend.header>
                
            </header>
            <div id="Subheader" style="padding:160px 0 90px">
                <div class="container">
                    <div class="column one">
                        <h1 class="title">@yield('title')</h1>
                    </div>
                </div>
            </div>
              
        </div>
        <div id="Content">
            <div class="content_wrapper clearfix">
                <div class="sections_group">

                    <div class="entry-content">
                    {{-- separator --}}
                        <div class="section mcb-section" style="padding-top:0px; padding-bottom:0px;">
                            <div class="section_wrapper mcb-section-inner">
                                <div class="wrap mcb-wrap one valign-top clearfix" style="margin-top:-50px">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_column">
                                            <div class="column_attr clearfix">
                                                <div style="width: 3px; height: 100px; background: #b0cc80; margin: 0 auto;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- end of separator --}}
                        
                        <div class="section mcb-section shopping-cart order-history" style="padding-top:0px; padding-bottom:20px;">
                            <div class="section_wrapper mcb-section-inner">
                                <div class="wrap mcb-wrap one  valign-top clearfix">
                                    <div class="mcb-wrap-inner">
										<div class="column mcb-column one-fourth column_column">
											<div class="column_attr clearfix">
                                                {{-- Left Menu --}}
												<div class="sidebar">
													
													<aside class="widget">
														<h3>User Navigation</h3>
														<ul>
                                                            <li class="">
																<a href="{{route('users.dashboard')}}"
                                                                 @if (
                                                                request()->routeIs('users.dashboard')
                                                                || request()->routeIs('users.view')
                                                                )
                                                                  class="active_category"
                                                                @endif
                                                                >Order History</a>
															</li>

															<li class="">
																<a
                                                                 @if (
                                                                    request()->routeIs('users.profile'))
                                                                    class="active_category"
                                                                    @endif
                                                                href="{{ route('users.profile') }}">My Account</a>
															</li>
                                                            <li class="">
																<a
                                                                 @if (
                                                                    request()->routeIs('users.password'))
                                                                    class="active_category"
                                                                    @endif
                                                                href="{{ route('users.password') }}">Change Password</a>
															</li>
                                                            <li class="">
																<a
                                                                 @if (
                                                                    request()->routeIs('users.wishlist'))
                                                                    class="active_category"
                                                                    @endif
                                                                href="{{ route('users.wishlist') }}">Wishlist</a>
															</li>

															
															<li class="">
																<a href="{{ route('customer-logout') }}">Logout</a>
															</li>
															
															
														</ul>
													</aside>
													
												</div>
                                                {{-- menu end --}}
											</div>
										</div>
                                        
                                        {{-- Dynamic content --}}
                                        {{$slot}}
                                        {{-- end --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
        <footer id="Footer" class="clearfix">
            <div class="footer_copy">
                <div class="container">
                    <div class="column one">
                        <a id="back_to_top" class="button button_js" href="#"><i class="icon-up-open-big"></i></a>
                        <div class="copyright">
                            {{$basicData->site_copyright}}.&nbsp; &nbsp;
                            
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- mobile menu -->
    <div id="Side_slide" class="right dark" data-width="250">
        <div class="close-wrapper">
            <a href="#" class="close"><i class="icon-cancel-fine"></i></a>
        </div>
        <div class="menu_wrapper"></div>
    </div>
    <div id="body_overlay"></div>

    <!-- JS -->
    <script src="{{asset('frontendassets/js/jquery-2.1.4.min.js') }}"></script>

    <script src="{{asset('frontendassets/js/mfn.menu.js') }}"></script>
    <script src="{{asset('frontendassets/js/jquery.plugins.js') }}"></script>
    <script src="{{asset('frontendassets/js/jquery.jplayer.min.js') }}"></script>
    <script src="{{asset('frontendassets/js/animations/animations.js') }}"></script>
    <script src="{{asset('frontendassets/js/translate3d.js') }}"></script>
    <script src="{{asset('frontendassets/js/scripts.js') }}"></script>

    <script src="{{asset('frontendassets/plugins/rs-plugin/js/jquery.themepunch.tools.min.js') }}"></script>
   
    <script src="{{asset('frontendassets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{asset('frontendassets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{asset('frontendassets/js/custom.js') }}"></script>
</body>

</html>