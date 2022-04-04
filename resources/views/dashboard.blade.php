@extends('layouts.master')
    
@section('content')

        <!-- Preloader -->
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>  
            </svg>
        </div>

        <!-- Main wrapper  -->
        <div id="main-wrapper">

            @include('navigation') 
            
            <!-- Page wrapper  -->
            <div class="page-wrapper">
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center"> 
                        <div class="pageheader">
                            <span class="bx bx-right-arrow-circle bx-sm align-middle m-b-3"></span> 
                            <span>Manage Dashboard</span>  
                        </div>
                    </div><!-- /.col-md-5 -->

                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-dark" href="">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li> 
                        </ol>
                    </div><!-- /.col-md-7 -->
                </div><!-- /.row -->
                         
                <div class="container-fluid"> 
                    <!-- First dashboard widget -->
                    <div class="row"> 

                        <div class="col-xl-12 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Employee List</h4>
                                    <p class="card-title-desc"></p>
                                    <table class="table table-bordered table-striped dt-responsive" id="employeeTable" style="width:100%">
                                        <thead>   
                                            <tr>
                                                <th>Employee</th>
                                                <th>Leave Type</th>
                                                <th>Total Leaves</th>
                                                <th>Taken Leaves</th>
                                                <th>Remaining Leaves</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>

                                    
                                </div><!-- /.card-body -->
                            </div><!-- /.card -->
                         
                        </div>

                    </div><!-- /.row -->

                    <router-view name="backend"></router-view>
                    <vue-progress-bar></vue-progress-bar>

                </div><!-- /.container-fluid --> 

            </div><!-- /.page-wrapper -->
        </div><!-- /.main-wrapper --> 
@endsection


{{-- @section('scriptcontent')
        <script type="text/javascript">
            $(document).ready(function() {  

                $.ajaxSetup({  
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Fetch Groups
                viewEmployeesReport();
                function viewEmployeesReport() {
                    $.ajax({
                        type:'get', 
                        url:'fetch-employee-report',
                        success:function(result) {
                            //console.log(result);
                            $('tbody').html(result);
                            $("#employeeTable").DataTable({
                                "responsive": true,
                                "autoWidth": false, 
                                "order" : [],
                                "columnDefs": [{
                                "targets": [0,4],
                                "orderable": false,
                                }],
                                dom: 'Bfrtip',
                                buttons: [
                                    'pageLength','csv', 'excel', 'pdf'
                                ]
                            });
                        }
                    });
                }

            });

        </script>
    @endsection --}}

    
        

        

    
