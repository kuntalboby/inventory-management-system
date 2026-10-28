@section('currentPage', 'Short Codes')
@php
    $bgColor = "dark";
@endphp

<x-admininnerlayout :$bgColor>
    @section('title', 'Short Codes')

    <section class="content" id="ajaxUpdate" data-url="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12" id="ajaxResponse"></div>
                <div class="col-md-12">
                    @if (session('message'))
                        <div class="alertBox">
                            <p class="text-center text text-{{session('response')}}">{{session('message')}}</p>
                        </div>
                        
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                </div>
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Short Codes</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 sgmts" id="segement_P">
                                    <div class="card card-warning">
                                        <div class="card-body">
                                          <div class="table-responsive">

                                            <table id="basic_table" class="scrollable table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Shortcode</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    $codes = [
                                                        '[BLOCK-HOME_SLIDER]' => 'Home Page Slider',
                                                        '[BLOCK-CONTACT_FORM]' => 'Contact Form',
                                                        '[BLOCK-TESTIMONIALS]' => 'Testimonial',
                                                        '[BLOCK-NEWS_LETTER]' => 'NEWS Letter',
                                                        '[BLOCK-LATEST_PROD]' => 'Latest Products',
                                                        '[BLOCK-PROD_DETAILS]' => 'Product Details',
                                                        '[BLOCK-RELATED_PROD]' => 'Related Products',
                                                        '[BLOCK-ALL_PROD]' => 'All Products',
                                                        '[BLOCK-LATEST_CATEGORY]' => 'Main Categories',
                                                        '[BLOCK-SORT_FILTER]'=> 'Product Sort-name,price',
                                                        '[BLOCK-PRICE_FILTER]'=>'Product PRICE range filter',
                                                        '[BLOCK-ALL_CATEGORY]'=> 'Category Filter',
                                                        '[BLOCK-ALL_CATEGORY_PAGE]'=> 'Categories Listing',
                                                        '[BLOCK-MAP]'=> 'Contact MAP',
                                                        '[BLOCK-THANKS]'=>'Thank You message box',
                                                        '[BLOCK-CHECKOUT]' => 'Checkout Container',
                                                        '[BLOCK-LOGIN_FORM]'=> 'USER login and Registration',
                                                        '[BLOCK-UPDATE_PASSWORD]' => 'Update Password',
                                                        '[BLOCK-CATEGORY_NAME]' =>'Banner Category Name in category page',
                                                        '[BLOCK-ALL_CAT_PROD]' => 'Respective category products'

                                                    ];
                                                @endphp
                                                <tbody>
                                                    @foreach ( $codes as $sc=>$code )
                                                        <tr>
                                                            <td>{{$code}}</td>
                                                            <td>{{$sc}}</td>
                                                            <td>
                                                                <button class="btn btn-warning copyBtn" text-to-copy="{{$sc}}">Copy</button> 
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    
                                                </tbody>
                                            </table>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 d-none" id="json-output"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-admininnerlayout>