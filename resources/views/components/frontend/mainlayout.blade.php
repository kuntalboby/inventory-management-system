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
    <meta name="author" content="@yield('author','')">
    <meta name="description" content="@yield('meta_description','')">
    <meta name="keywords" content="@yield('meta_keywords','')">
    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Favicons -->
    <link rel="shortcut icon" href="{{asset('frontendassets/assets/images/favicon.ico') }}">

    <!-- FONTS -->
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,400italic,700'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Patua+One:100,300,400,400italic,700'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Lato:400,400italic,700,700italic,900'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Muli:100,300,400,400italic,500,700,700italic'>
    <link href="https://fonts.googleapis.com/css?family=Muli:800,700%7CMerienda:700" rel="stylesheet" property="stylesheet" type="text/css" media="all">

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
menuo-no-borders menuo-right mobile-tb-center mobile-side-slide mobile-mini-mr-ll be-reg-206
@yield('bodyClass','')" id="@yield('bodyId','')"
data-url="{{url('/')}}"
>

    <div class="popup-overlay">
        <div class="popup-box">
            <h3>Enter Postal Code</h3>
            <input type="number" id="user_pin_check" class="pincodeInput_nav" placeholder="PIN" />
            <button id="navpincheck" type="button" data-page="home">Submit</button>
            <button id="closeme" type="button">Close</button>
        </div>
    </div>

    <div class="pin_location_nav"><a href="javascript:void(0);" id="pin_location">Choose your address location to see product availability and delivery options</a></div>
    <div id="Wrapper">
        <div id="@if ($pageSlug == 'home') Header_wrapper @else 
            Inner_Header_wrapper @endif" 
            class="bg-parallax" 
            data-enllax-ratio="0.3"
            @if ($pageSlug != 'home')
                style="background-image:url(@yield('bg_image'))"
            @endif
            >
            <div class="under_construction">Our website is under construction</div>
            <header id="Header">
                <div class="header_placeholder"></div>
                <x-frontend.header
                    :smallLogo="$basicData->site_logo"
                    :retinaLogo="$basicData->site_thum_logo"
                    :menu="$menu"
                    ></x-frontend.header>
                @if ($pageSlug == 'home')
                    {!! $headerBannerSlider !!}
                @endif
            </header>
                @if ($pageSlug != 'home')
                        {!! $headerBannerSlider !!}
                @endif
        </div>
        <div id="Content">
            <div class="content_wrapper clearfix">
                <div class="sections_group">


                    <div class="entry-content">
                        {{-- content of pages --}}
                        {{$slot}}
                        {{-- end content --}}
                        {{-- Contact Box --}}
                        <div class="section mcb-section no-margin-h no-margin-v equal-height" id="Contact" style="padding-top:130px; padding-bottom:0px; background-color:" data-id="#contact">
                            <div class="section_wrapper mcb-section-inner">
                                <div class="wrap mcb-wrap one valign-top clearfix">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column mcb-item-6aan8hyvn one-second column_column" style="height: 323px;">
                                            <div class="column_attr clearfix align_center" style=" padding:40px 15% 45px; border: 1px solid #ecefe5;">
                                                <div class="image_frame image_item no_link scale-with-grid aligncenter no_border" style="margin-top:-70px;">
                                                    <div class="image_wrapper">
                                                        <img class="scale-with-grid" src="{{asset('frontendassets/assets/images/home_tea3_pic20.png') }}">
                                                    </div>
                                                </div>
                                                <hr class="no_line" style="margin: 0 auto 40px;">
                                                <p>
                                                    Aliquam pellentesque mollis ex id placerat donec pretium sapien ligula
                                                </p>
                                                <h3><strong>{{$basicData->site_contact}}</strong></h3>
                                                <hr class="no_line" style="margin: 0 auto 15px;">

                                                <h6 style="color: #afafaf; letter-spacing: 4px; font-weight: 600;">MONDAY - FRIDAY
                                                    <br>09:00 AM - 06:00 PM</h6>
                                            </div>
                                        </div>
                                        <div class="column mcb-column mcb-item-5e59y51cb one-second column_column" style="height: 323px;">
                                            <div class="column_attr clearfix align_center" style=" padding:40px 15% 45px; border: 1px solid #ecefe5; border-left: 0px;">
                                                <div class="image_frame image_item no_link scale-with-grid aligncenter no_border" style="margin-top:-70px;">
                                                    <div class="image_wrapper"><img class="scale-with-grid" src="{{asset('frontendassets/assets/images/home_tea3_pic21.png') }}">
                                                    </div>
                                                </div>
                                                <hr class="no_line" style="margin: 0 auto 40px;">
                                                <p>
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla mauris dolor, gravida
                                                </p>
                                                <h3><a href="mailto:{{$basicData->site_email}}"><span><strong>{{$basicData->site_email}}</strong></span></a></h3>
                                                <hr class="no_line" style="margin: 0 auto 15px;">
                                                <h6 style="color: #afafaf; letter-spacing: 4px; font-weight: 600;">MONDAY - SUNDAY
                                                    <br> 24/H
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div class="section mcb-section" id="Social" style="padding-top:60px; padding-bottom:30px;">
                            <div class="section_wrapper mcb-section-inner">
                                <div class="wrap mcb-wrap one valign-top clearfix">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column mcb-item-egvw4bn24 one column_image">
                                            <div class="image_frame image_item no_link scale-with-grid aligncenter no_border">
                                                <div class="image_wrapper"><img class="scale-with-grid" src="{{asset('frontendassets/assets/images/home_tea3_pic22-1.png') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column mcb-column mcb-item-bzce1dbdd one column_column">
                                            <div class="column_attr clearfix align_center">
                                                <h3 style="font-weight: 600;">In a healthy body, <span style="color: #8aa932;">healthy mind</span>.</h3>
                                                <p style="font-size: 15px;">
                                                    <i style="color: #395794;" class="icon-facebook-squared"></i><a style="color: #737373;" href="#">Facebook</a>
                                                    <i style="color: #f55240;" class="icon-instagram"></i><a style="color: #737373;" href="#">Instagram</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {{-- end of Contact Box --}}

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
                            <a href="{{ route('page','privacy-policy') }}">Privacy Policy</a>
                                | <a href="{{ route('page','terms-and-condition') }}">Terms and Condition</a> |
                                <a href="{{ route('page','refund-policy') }}">Refund Policy</a>
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
    <script src="{{asset('frontendassets/plugins/rs-plugin/js/jquery.themepunch.revolution.min.js') }}"></script>

    <script src="{{asset('frontendassets/plugins/rs-plugin/js/extensions/revolution.extension.video.min.js') }}"></script>
    <script src="{{asset('frontendassets/plugins/rs-plugin/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
    <script src="{{asset('frontendassets/plugins/rs-plugin/js/extensions/revolution.extension.actions.min.js') }}"></script>
    <script src="{{asset('frontendassets/plugins/rs-plugin/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
    <script src="{{asset('frontendassets/plugins/rs-plugin/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
    <script src="{{asset('frontendassets/plugins/rs-plugin/js/extensions/revolution.extension.navigation.min.js') }}"></script>
    <script src="{{asset('frontendassets/plugins/rs-plugin/js/extensions/revolution.extension.migration.min.js') }}"></script>
    <script src="{{asset('frontendassets/plugins/rs-plugin/js/extensions/revolution.extension.parallax.min.js') }}"></script>

    @if (request()->route('slug') == 'contact-us')
            <script src="https://maps.google.com/maps/api/js?sensor=false&ver=5.9"></script>
        <script>
        function google_maps_5a12adb89bed2() {
            var latlng = new google.maps.LatLng( 22.8240, 87.2407);
            var draggable = true;
            var myOptions = {
                zoom: 13,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                styles: [{
                    featureType: "all",
                    elementType: "labels",
                    stylers: [{
                        visibility: "on"
                    }]
                }, {
                    featureType: "administrative",
                    elementType: "all",
                    stylers: [{
                        visibility: "off"
                    }]
                }, {
                    featureType: "landscape",
                    elementType: "all",
                    stylers: [{
                        color: "#f2f2f2"
                    }, {
                        visibility: "simplified"
                    }]
                }, {
                    featureType: "poi",
                    elementType: "all",
                    stylers: [{
                        visibility: "off"
                    }]
                }, {
                    featureType: "road",
                    elementType: "all",
                    stylers: [{
                        visibility: "on"
                    }]
                }, {
                    featureType: "road",
                    elementType: "geometry",
                    stylers: [{
                        color: "#f2f2f2"
                    }, {
                        lightness: "50"
                    }]
                }, {
                    featureType: "road",
                    elementType: "labels.text",
                    stylers: [{
                        visibility: "on"
                    }]
                }, {
                    featureType: "road",
                    elementType: "labels.text.fill",
                    stylers: [{
                        color: "#f2f2f2"
                    }, {
                        lightness: "-60"
                    }]
                }, {
                    featureType: "road",
                    elementType: "labels.text.stroke",
                    stylers: [{
                        color: "#f2f2f2"
                    }, {
                        lightness: "50"
                    }]
                }, {
                    featureType: "road",
                    elementType: "labels.icon",
                    stylers: [{
                        visibility: "off"
                    }]
                }, {
                    featureType: "transit",
                    elementType: "all",
                    stylers: [{
                        visibility: "simplified"
                    }, {
                        color: "#f2f2f2"
                    }, {
                        lightness: "50"
                    }]
                }, {
                    featureType: "transit.station",
                    elementType: "all",
                    stylers: [{
                        visibility: "off"
                    }]
                }, {
                    featureType: "water",
                    elementType: "all",
                    stylers: [{
                        color: "#f2f2f2"
                    }, {
                        lightness: "-10"
                    }]
                }],
                draggable: draggable,
                zoomControl: true,
                mapTypeControl: false,
                streetViewControl: false,
                scrollwheel: false
            };
            var map = new google.maps.Map(document.getElementById("google-map-area-5a12adb89bed2"), myOptions);
            var marker = new google.maps.Marker({
                position: latlng,
                icon: "{{asset('frontendassets/images/home_vegetables_pin.png') }}",
                map: map
            });
        }
        jQuery(document).ready(function($) {
            google_maps_5a12adb89bed2();
        });
    </script>
    @endif


    
    <script>
        var tpj = jQuery;
        var revapi1;
        tpj(document).ready(function() {
            if (tpj("#rev_slider_1_1").revolution == undefined) {
                revslider_showDoubleJqueryError("#rev_slider_1_1");
            } else {
                revapi1 = tpj("#rev_slider_1_1").show().revolution({
                    sliderType: "standard",
                    sliderLayout: "auto",
                    dottedOverlay: "none",
                    delay: 4000,
                    navigation: {
                        keyboardNavigation: "off",
                        keyboard_direction: "horizontal",
                        mouseScrollNavigation: "off",
                        mouseScrollReverse: "default",
                        onHoverStop: "off",
                    },
                    visibilityLevels: [1240, 1024, 778, 480],
                    gridwidth: 1240,
                    gridheight: 740,
                    lazyType: "none",
                    shadow: 0,
                    spinner: "spinner0",
                    stopLoop: "off",
                    stopAfterLoops: -1,
                    stopAtSlide: -1,
                    shuffle: "off",
                    autoHeight: "off",
                    disableProgressBar: "on",
                    hideThumbsOnMobile: "off",
                    hideSliderAtLimit: 0,
                    hideCaptionAtLimit: 0,
                    hideAllCaptionAtLilmit: 0,
                    debugMode: false,
                    fallbacks: {
                        simplifyAll: "off",
                        nextSlideOnWindowFocus: "off",
                        disableFocusListener: false,
                    }
                });
            }
        });
        /*]]>*/
    </script>
    <script src="{{asset('frontendassets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{asset('frontendassets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
     @if (request()->route('slug') == 'checkout'){
        <script src="{{asset('frontendassets/js/checkout.js') }}"></script>
     }
     @endif
    <script src="{{asset('frontendassets/js/custom.js') }}"></script>
</body>

</html>