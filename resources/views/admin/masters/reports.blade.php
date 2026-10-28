@section('currentPage', ' Reports')
@php
    $bgColor="dark";
@endphp
<x-admininnerlayout :$bgColor>
  @section('title','Reports')
    
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <col-md-12>
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
            </col-md-12>
              <!-- general form elements -->
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">Reports</h3>
                  
                </div>
            
                <form id="reportGenerator" action="{{route('admin.masters.create-report')}}" enctype="multipart/form-data" method="post">
                 @csrf
                  <div class="card-body">
                    <div class="row">
                        
                       
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="report_type">Select Type *</label>
                                <select class="form-control selectPicker"  name="report_type" id="report_type">
                                    <option value="">Report Type</option>
                                    <option value="DRPT">Daily Stock Report</option>
                                    <option value="SRPT">Daily Sales Report</option>
                                   
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="report_of">Select Date Range*</label>
                                <select class="form-control selectPicker"  name="report_of" id="report_of">
                                    <option value="">Select</option>
                                    <option value="PD">Yesterday</option>
                                    {{-- <option value="ALL">ALL</option>
                                    <option value="PW">Previous Week (Mon-Sun)</option>
                                    <option value="PM">Previous Month</option> --}}
                                    <option value="DR">Custom</option>
                                </select>
                            </div>
                        </div>
                       
                         <div class="col-md-6 dt-range  d-none">
                                     <div class="form-group">
                                        <label for="date_from">Date From*</label>
                                                <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                                    <input type="text" id="date_from" name="date_from" class="form-control datetimepicker-input"
                                                           data-target="#datetimepicker1" placeholder="DD-MM-YYYY"
                                                           value="{{ old('date_from', request('date_from')) }}">
                                                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                         </div>
                                           
                                </div>
                                <!-- Purchase Date To -->
                            <div class="col-md-6 dt-range  d-none">
                                     <div class="form-group">
                                        <label for="date_to">Date To*</label>
                                            <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                                <input type="text" id="date_to" name="date_to" class="form-control datetimepicker-input"
                                                       data-target="#datetimepicker2" placeholder="DD-MM-YYYY"
                                                       value="{{ old('date_to', request('date_to')) }}">
                                                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                 </div>
                    
                    </div>
                    

                
                  </div>
                  <!-- /.card-body -->
  
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Download Excel</button>
                  </div>
                </form>
              </div>
              <!-- /.card -->
  
        
            </div>
    
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
</x-admininnerlayout>