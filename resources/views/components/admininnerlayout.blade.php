
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ env( 'APP_NAME') }} ::Adminpanel - @yield('title','Inner Page')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('adminassets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('adminassets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Theme style -->
    <link href="//cdn.datatables.net/2.1.0/css/dataTables.dataTables.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/css/tempusdominus-bootstrap-4.min.css">

    <link rel="stylesheet" href="{{asset('adminassets/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('adminassets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

  <link rel="stylesheet" href="{{asset('adminassets/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{asset('adminassets/plugins/daterangepicker/daterangepicker.css') }}">
  @if (  request()->routeIs('admin.pages.add*') || request()->routeIs('admin.pages.edit*'))
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  @endif
  <link rel="stylesheet" href="{{asset('adminassets/dist/css/custom.css') }}">

  
</head>
<body class="hold-transition {{$bgColor}} sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-light">
   <x-adminnavbar></x-adminnavbar>
  </nav>
<x-adminleftpanel></x-adminleftpanel>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <x-adminbreadcrumb></x-adminbreadcrumb>
    {{$slot}}
  </div>
  <aside class="control-sidebar control-sidebar-{{$bgColor}}">
  </aside>

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io"></a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{asset('adminassets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<!-- Bootstrap -->
<script src="{{asset('adminassets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('adminassets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{asset('adminassets/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script src="{{asset('adminassets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{asset('adminassets/dist/js/adminlte.js') }}"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="{{asset('adminassets/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
<script src="{{asset('adminassets/plugins/raphael/raphael.min.js') }}"></script>
<script src="{{asset('adminassets/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{asset('adminassets/plugins/select2/js/select2.full.min.js')}}"></script>
<!-- datatable -->
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
<script src="{{asset('adminassets/dist/datatables/simple_numbers_no_ellipses.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/3.2.4/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.datatables.net/buttons/3.2.4/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>


<!-- AdminLTE for demo purposes -->
{{-- <script src="{{asset('adminassets/dist/js/pages/dashboard2.js') }}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/js/tempusdominus-bootstrap-4.min.js"></script>



@if (  request()->routeIs('admin.pages.add*') || request()->routeIs('admin.pages.edit*'))
    <script src="{{ asset('adminassets/dist/ckeditor/ckeditor.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="{{asset('adminassets/dist/js/pages.js') }}"></script>

@endif
<script src="{{asset('adminassets/dist/js/custom.js') }}"></script>
</body>
</html>