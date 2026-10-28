@section('currentPage', 'Testimonials')
@php
    $bgColor = "dark";
@endphp

<x-admininnerlayout :$bgColor>
    @section('title', 'Testimonials')

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
                            <h3 class="card-title">Testimonials ( <button class="btn btn-warning copyBtn" text-to-copy="[BLOCK-TESTIMONIALS]">[BLOCK-TESTIMONIALS]</button>  )</h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.pages.testimonial-add') }}" class="btn btn-primary text-light">
                                    Add
                                </a>
                            </div>
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
                                                        <th>Serial No</th>
                                                        <th>Image</th>
                                                        <th>Name</th>
                                                        <th>Designation</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($testimonials))
                                                        @foreach ($testimonials as $k => $testimonial)
                                                            <tr>
                                                                <td width="10%">{{ $k + 1 }}</td>
                                                                <td>
                                                                    @if ($testimonial->image!='' && file_exists(public_path("storage/frontend/pages/" . $testimonial->image)))
                                                                        <img style="width: 50px; text-align: center; margin: 0 auto; display: block;" class="img-responsive" src="{{ asset('storage/frontend/pages/'.$testimonial->image)}}" alt="{{$testimonial->name}}" />
                                                                    @else
                                                                        <img  style="width: 50px; text-align: center; margin: 0 auto; display: block;" src="{{ asset('/storage/website-image/no-image.png')}}" alt="No Pic" />
                                                                    @endif
                                                                </td>
                                                                <td>{{  ucwords(strtolower($testimonial->author)) }}</td>
                                                                <td>{{$testimonial->designation}}</td>
                                                                <td>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <a href="{{ route('admin.pages.testimonial-edit', [$testimonial->id]) }}"
                                                                                title="Edit" class="text-warning">
                                                                                <i class="fa fa-pen"></i>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <a href="javascript:void(0);"
                                                                                data-name="{{$testimonial->image}}" tbl="testimon" data-id="{{$testimonial->id}}" id="removeImage" class="text-danger">
                                                                                <i class="fa fa-trash"></i>
                                                                            </a>
                                                                            
                                                                        </div>

                                                                    </div>
                                                                </td>   
                                                                
                                                            </tr>
                                                        @endforeach
                                                    @endif
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