@section('currentPage', 'List of categories')
@php
    $bgColor = "dark";
@endphp

<x-admininnerlayout :$bgColor>
    @section('title', 'Category List')

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
                            <h3 class="card-title">Categories</h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.masters.categories-add') }}" class="btn btn-primary text-light">
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
                                                        <th>Name</th>
                                                        <th>Parent</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($allCategories))
                                                        @foreach ($allCategories as $k => $category)
                                                            <tr>
                                                                <td width="10%">{{ $k + 1 }}</td>
                                                                <td>{{  ucwords(strtolower($category['name'])) }}</td>
                                                                <td>
                                                                    @if (empty($category['parent_tree']))
                                                                        <span>Parent Category</span>
                                                                    @else
                                                                        <ul class="list-group list-group-horizontal cat-list">
                                                                            @foreach ($category['parent_tree'] as $parents)
                                                                                <li class="list-group-item parentCatList">
                                                                                    <a title="Edit" href="{{ route('admin.masters.categories-edit', [$parents['slug']]) }}">
                                                                                        {{ ucwords(strtolower($parents['name'] )) }}
                                                                                    </a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($category['status'] === '0')
                                                                        <div class="text-success">Active</div>
                                                                    @else
                                                                        <div class="text-danger">Inactive</div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <a href="{{ route('admin.masters.categories-edit', [$category['slug']]) }}"
                                                                                title="Edit" class="text-warning">
                                                                                <i class="fa fa-pen"></i>
                                                                            </a>
                                                                        </div>
                                                                        {{-- 
                                                                        <div class="col-md-4">
                                                                            <a data-owner-id="{{ $category['id'] }}"
                                                                                onclick="return confirm('Are you sure you want to delete it?');"
                                                                                href="{{ route('admin.masters.categories-delete', [$category['slug']]) }}"
                                                                                id="page_delete_{{ $category['id'] }}"
                                                                                title="Delete" class="text-danger anchore">
                                                                                <i class="fa fa-trash"></i>
                                                                            </a>
                                                                        </div>
                                                                        --}}
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