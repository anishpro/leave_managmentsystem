<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content=""> 
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>

    <!-- Core CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/plugins/bootstrap-4.3.1/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/plugins/bootstrap-select/dist/css/bootstrap-select.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/plugins/DataTables/datatables.css')}}">  
      
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/plugins/DataTables/Buttons-1.6.2/css/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/plugins/DataTables/Responsive/css/responsive.bootstrap4.min.css')}}">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/helper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/plugins/bootstrap_datetime_picker/bootstrap-datetimepicker.min.css')}}">

    <!-- Jquery -->
    <script type="text/javascript" src="{{asset('admin_assets/plugins/jquery/jquery-3.2.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/js/popper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/bootstrap-4.3.1/js/bootstrap.min.js')}}"></script>

</head>
<body class="fix-header fix-sidebar">
    @yield('content')
    
    <!-- Jquery -->  
    <script type="text/javascript" src="{{asset('admin_assets/js/sweetalert.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/js/jquery.slimscroll.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/js/sidebarmenu.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/bootstrap-select/dist/js/bootstrap-select.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/DataTables/datatables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/DataTables/Responsive/js/dataTables.responsive.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/bootbox-master/dist/bootbox.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/parsley/parsley.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/js/scripts.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/tinymce/tinymce.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/bootstrap_datetime_picker/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/bootstrap_datetime_picker/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/DataTables/Buttons-1.6.2/js/dataTables.buttons.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/DataTables/Buttons-1.6.2/js/jszip.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/DataTables/Buttons-1.6.2/js/pdfmake.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/DataTables/Buttons-1.6.2/js/buttons.html5.min.js')}}"></script>

    @yield('scriptcontent')
</body>
</html>