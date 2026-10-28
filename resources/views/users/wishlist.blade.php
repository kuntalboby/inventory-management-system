<x-frontend.customerlayout
                       :basicData="$sitedata"
                       :menu="$menu"
                       >
    @section('bg_image',$bg_image)
    @section('title',$title)

    <div class="column mcb-column three-fourth column_column">
                    <div class="column_attr clearfix">
							<div class="products_wrapper isotope_wrapper">
                                <ul class="products" id="productsContainer">
                                   
                                @forelse ($wishListProd as $prod)
                                    @php
                                         $imagePath = (!empty($prod->product->image) && file_exists(public_path("storage/products/" . $prod->product->image)))
                                        ? asset('/storage/products/' . $prod->product->image)
                                        : asset('/storage/website-image/no-image.png');
                                    @endphp     
                                    <li id="product_{{$prod->product->product_code}}" class="prodList">
                                        <div class="image_frame scale-with-grid product-loop-thumb">
                                            <div class="image_wrapper">
                                                <div class="mask"></div>
                                            <img width="600" height="500" src="{{$imagePath}}" class="scale-with-grid wp-post-image" alt="{{$prod->product->name}}">
                                                <div class="image_links double">
                                                    @if ( $prod->product->quantity > 0 )
                                                        <a href="javascript:void(0);" class="link"
                                                        onclick="addTocart('{{$prod->product->product_code}}','1','{{$prod->product->selling_price}}');">
                                                            <i class="icon-basket"></i>
                                                        </a>
                                                    @endif
                                                    <a class="link" href="{{route('product-details', $prod->product->slug)}}">
                                                        <i class="icon-link"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="desc">
                                            <h4><a href="{{route('product-details', $prod->product->slug)}}">{{$prod->product->name}}</a></h4>
                                            <span class="price">₹{{$prod->product->selling_price}}</span>
                                        </div>
                                    </li>
                                @empty
                                        <li>
                                            <div class="empty">Sorry no product found!</div>
                                        </li>
                                @endforelse
                                    



                                </ul>
                            </div>					
                    </div>
    </diV>

</x-frontend.customerlayout>