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
                            <span>Role Permission</span>  
                        </div>
                    </div><!-- /.col-md-5 -->

                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-dark" href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Role Permission</li>
                        </ol>
                    </div><!-- /.col-md-7 -->
                </div><!-- /.row --> 
                
                <div class="container-fluid">  
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Role Permission</h4>

                                    <form id="permission_form" enctype="multipart/form-data">
                                        <div class="result"></div>

                                        <div class="form-group row">

                                            <div class="col-md-6">
                                                <label for="inputEmail3" class="col-form-label">Select Role<span class="text-danger">*</span></label>
                                                <select class="form-control" name="role_id" id="role_id" required data-parsley-errors-container=".roleError" title="-- Select Role --" data-live-search="true" data-size="5">';
                                                    @foreach($allRoles as $value)
                                                        <option value="{{$value->id}}">{{$value->role}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="roleError"></div>
                                            </div> 
                                        </div>

                                        <div class="form-group row">
                                            <input type="hidden" id="delete_permission_id" name="delete_permission_id[]" value=""/>
                                            <div class="col-md-12" id="getRolePermissionData">

                                            </div>
                                        </div>
                                                
                                        <div class="form-group row text-right mb-0">
                                            <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-info btn-md" id="save_menu">Submit</button>
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

            $("#role_id").selectpicker();

            $("#permission_form").parsley();

            // GET ROLE PERMISSION
            $("#role_id").on("change", function(e) {
                e.preventDefault();

                var role_id = $(this).val(); //alert(role_id);
                $.ajax({
                    url:"{{ url('fetch-role-permiision') }}",  
                    type:"post", 
                    dataType:"json",
                    data: {role_id:role_id},
                    success:function(result) {
                        $("#getRolePermissionData").html(result);
                    }
                });
            });  

            //RECORD UNCHECKED CHECKBOX
            var permission_ids = [];
            $("body").delegate(".menu_checkbox", "change", function(e){
                e.preventDefault();

                var id = $(this).val();//alert(id);
                var idvalue = '';

                if ($(this).is(':checked')) {
                    permission_ids.splice( $.inArray(id, permission_ids), 1 );
                }else{
                    permission_ids.push(id);
                }
                //console.log(permission_ids);
                $("#delete_permission_id").val(permission_ids);
            });

            // Save New Group
            $("body").delegate("#permission_form", "submit", function(e) {
                e.preventDefault();

                var btn=$('#save_menu');

                $.ajax({
                    url:"{{ url('insert-role-permission') }}",  
                    type:"post", 
                    dataType:"json",
                    data: new FormData(this), 
                    contentType: false,
                    processData:false, 
                    beforeSend:function() { 
                        btn.html('Submitting...'); 
                        btn.attr('disabled',true);
                    },   
                    success:function(result) { 
                        //alert(result);
                        //console.log(result);
                        btn.html('Submit'); 
                        btn.attr('disabled',false);
                        if(result == 'inserted') { 
                            $(".result").html(' <div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data inserted</div>');

                        }else if(result == 'not_inserted') {
                            $(".result").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data not inserted</div>');
                        }
                    }  
                });
            });
            
        }); 
    </script>
@endsection

