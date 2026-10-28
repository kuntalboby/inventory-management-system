<div id="Top_bar">
                    <div class="container">
                        <div class="column one">
                            <div class="top_bar_left clearfix">
                                <div class="logo">
                                    <a id="logo" href="{{url('/')}}" title="" data-height="70" data-padding="10">
                                        <img class="logo-main scale-with-grid" 
                                        src="{{asset('/storage/website-image/'.$smallLogo)}}"
                                        data-retina="{{asset('/storage/website-image/'.$retinaLogo)}}"
                                        data-height="100" alt="RBGM3">
                                        <img class="logo-sticky scale-with-grid"
                                        src="{{asset('/storage/website-image/'.$smallLogo)}}"
                                        data-retina="{{asset('/storage/website-image/'.$retinaLogo)}}"
                                        data-height="100" alt="RBGM3">
                                        <img class="logo-mobile scale-with-grid"
                                        src="{{asset('/storage/website-image/'.$smallLogo)}}"
                                        data-retina="{{asset('/storage/website-image/'.$retinaLogo)}}"
                                        data-height="98" alt="RBGM3">
                                        <img class="logo-mobile-sticky scale-with-grid"
                                        src="{{asset('/storage/website-image/'.$smallLogo)}}"
                                        data-retina="{{asset('/storage/website-image/'.$retinaLogo)}}"
                                        data-height="98" alt="RBGM3">
                                    </a>
                                </div>
                                <div class="menu_wrapper">
                                    <nav id="menu" class="menu-main-menu-container">
                                        <ul id="menu-main-menu" class="menu">
                                            @if ($menu)
                                                @foreach ($menu as $key=>$item)
                                                        @if (in_array( $item['position'],['1','3']) )
                                                            <li class="{{ $item['active'] }}">
                                                                @if ($item['slug'] === 'home')
                                                                    <a href="{{ route('page','') }}">
                                                                        <span>{{$item['title']}}</span>
                                                                     </a>
                                                                @else
                                                                    <a href="{{ route('page',$item['slug']) }}">
                                                                        <span>{{$item['title']}}</span>
                                                                    </a>
                                                                @endif
                                                                
                                                                
                                                            </li>
                                                        @endif
                                                @endforeach
                                            @endif
                                        </ul>
                                    </nav>
                                    <a class="responsive-menu-toggle" href="#"><i class="icon-menu-fine"></i></a>
                                </div>
                            </div>
							<div class="top_bar_right">
                                <div id="header_left_items" class="top_bar_right_wrapper
                                    @if (auth()->check() && auth()->user()->role === 'customer' )
                                       user_loggedin_active
                                    @endif
                                 ">
                                    <a id="header_cart" href="{{ route('page','user-login') }}"><i class="icon-user-line"></i></a>
									<a id="header_cart" href="{{ route('users.wishlist') }}"><i class="icon-heart-line"></i><span id="wishlistQnt">0</span></a>
									<a id="header_cart" href="{{ route('page','cart') }}"><i class="icon-bag-fine"></i><span id="catProdQnt">0</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
</div>