@php 

@endphp

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
                <div class="row page-titles mb-0"> 
                    <div class="col-md-5 align-self-center">
                        <div class="pageheader">
                            <span class="bx bx-right-arrow-circle bx-sm align-middle m-b-3"></span> 
                            <span>Leave Report</span>  
                        </div>
                    </div><!-- /.col-md-5 -->

                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-dark" href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Leave Report</li>
                        </ol>
                    </div><!-- /.col-md-7 -->
                </div><!-- /.row --> 
                
                <div class="container-fluid">  
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Leave Report</h4>

                                    <form id="employee_report_form"  target="_blank" action="{{ url('leavereport_pdf/pdf/$value->id') }}" enctype="multipart/form-data">
                                        <div class="result"></div>

                                        <div class="form-group row">

                                            <div class="col-md-6">
                                                <label for="inputEmail3" class="col-form-label">Select Employee<span class="text-danger">*</span></label>
                                                <select class="form-control" name="employee_id" id="employee_id" required data-parsley-errors-container=".empError" title="-- Select Employee --" data-live-search="true" data-size="5">';
                                                    @foreach($allEmployee as $value)
                                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="empError"></div>
                                            </div> 

                                            <div class="col-md-6">
                                                <label for="inputEmail3" class="col-form-label">Select Leave Type<span class="text-danger">*</span></label>
                                                <select class="form-control" name="leave_type" id="leave_type" required data-parsley-errors-container=".leaveTypeError" title="-- Select Leave Type --" data-live-search="true" data-size="5">';
                                                    <option value="annual">Annual Leave</option>
                                                    <option value="sick">Sick Leave</option>
                                                </select>
                                                <div class="leaveTypeError"></div>
                                            </div> 
                                        </div>
                                                
                                        <div class="form-group row text-right mb-0">
                                            <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-info btn-md" id="get_report">Submit</button>
                                            </div> 
                                        </div>
                                    </form>
                                    
                                </div><!-- /.card-body -->
                            </div><!-- /.card -->
                        </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
                
            </div><!-- /.page-wrapper -->
        </div><!-- /.main-wrapper -->
@endsection


@section('scriptcontent')
    <script type="text/javascript">
        $(document).ready(function() {
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#employee_id, #leave_type").selectpicker();

            $("#employee_report_form").parsley();

            

            // Save New Group
            $("body").delegate("#employee_report_form", "submit", function(e) {
                e.preventDefault();

                var btn=$('#get_report');
                var emp_id = $("#employee_id").val();
                var leavetype = $("#leave_type").val();

                window.location.replace('leavereport_pdf/pdf/'+emp_id+'/'+leavetype); 

                // $.ajax({
                //     url:"{{ url('leavereport_pdf/pdf/".emp_id."') }}",  
                //     type:"post", 
                //     dataType:"json",
                //     data: new FormData(this), 
                //     contentType: false,
                //     processData:false, 
                //     beforeSend:function() { 
                //         btn.html('Submitting...'); 
                //         btn.attr('disabled',true);
                //     },   
                //     success:function(result) { 
                //         //alert(result);
                //         //console.log(result);
                //         btn.html('Submit'); 
                //         btn.attr('disabled',false);
                        
                //     }  
                // });
            });
        }); 
    </script>
@endsection

