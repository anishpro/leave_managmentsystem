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
                            <span>Manage Employee</span>  
                        </div>
                    </div><!-- /.col-md-5 -->

                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-dark" href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Employee</li>
                        </ol>
                    </div><!-- /.col-md-7 -->
                </div><!-- /.row -->
                 
                <div class="container-fluid"> 
                    <div class="row">
                        <div class="col-md-12 mb-4 mt-2 text-right">
                            <a href="{{url('employees/create')}}" class="btn btn-default btn-icon mr-1" tooltip="Add New" id="add_employee"><i class="bx bx-plus f-s-14 align-middle m-b-2"></i></a> 
                            <button type="submit" class="btn btn-default btn-icon" id="employee_delete" tooltip="Delete"><i class="bx bx-trash f-s-14 align-middle m-b-2"></i></button> 
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Employee List</h4>
                                    <p class="card-title-desc"></p>
                                    <table class="table table-bordered table-striped dt-responsive" id="employeeTable" style="width:100%">
                                        <thead>   
                                            <tr>
                                                <th width="5%">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="employee_selectall" id="employee_selectall">
                                                        <label class="form-check-label" for="employee_selectall"></label>
                                                    </div>
                                                </th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Group</th>
                                                <th>Position</th>
                                                <th>Duty Station</th>
                                                <th>Contact</th>
                                                <th>Action</th>  
                                            </tr>
                                        </thead>

                                        <tbody>

                                        </tbody>
                                    </table>
                                </div><!-- /.card-body -->
                            </div><!-- /.card -->
                        </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
                
            </div><!-- /.page-wrapper -->
        </div><!-- /.main-wrapper -->

        <!-- Category Modal Edit/view -->     
        <div class="modal" id="employee_modal">
            <div class="modal-dialog modal-md"> 
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body"> 
                        Modal body..
                    </div>
                </div>
            </div>
        </div> 

@endsection


@section('scriptcontent')        
        <script type="text/javascript">
            $(document).ready(function() {  

                $.ajaxSetup({  
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Fetch Groups
                viewEmployees();
                function viewEmployees() {
                    $.ajax({
                        type:'get', 
                        url:'employees/fetch-employee',
                        success:function(result) {
                            //console.log(result);
                            $('tbody').html(result);
                            $("#employeeTable").DataTable({
                                "responsive": true,
                                "autoWidth": false, 
                                "order" : [],
                                "columnDefs": [{
                                "targets": [0,5],
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

                //EDIT HOLIDAY 
                $('body').delegate('.employee_edit','click',function(e) {   
                    e.preventDefault();

                    var emp_id = $(this).attr('data-id');
                    
                    $.ajax({
                        type:"get",
                        url:"edit-employee",
                        dataType:"json",
                        data : {emp_id:emp_id},
                        success:function(response) {
                            //console.log(response); 
                            $("#employee_modal").modal('show');
                            $(".modal-body").html(response['content']);
                            $(".modal-title").html(response['title']);                            
                            $(".modal-body").find("#group_id, #position, #duty_station, #role_id").selectpicker();
                            $(".modal-body").find('#edit_employee_form').parsley();
                        }
                    });
                });

                // Update Group
                $("body").delegate("#edit_employee_form", "submit", function(e) {
                    e.preventDefault();

                    if($('#edit_employee_form').parsley().isValid()) {  
                        
                        var btn=$('#update_employee');
        
                        $.ajax({
                            url:"update-employee", 
                            type:"post", 
                            data: $('#edit_employee_form').serialize(),    
                            beforeSend:function() {
                                btn.html('Updating...'); 
                                btn.attr('disabled',true);
                            },
                            success:function(result) {
                                console.log(result);
                                btn.html('Update');
                                btn.attr('disabled',false); 

                                if(result == 'updated') {
                                    $(".result_employee").html(' <div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-check-circle"></i></strong> Data Updated</div>');
                                    //location.reload();
                                    $("#employeeTable").dataTable().fnDestroy();
                                    viewEmployees();
                                }else if (result == 'not_update') {
                                    $(".result_employee").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-times-circle"></i></strong> Data Not Updated</div>');
                                }else if (result == 'exist') {
                                    $(".result_employee").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-times-circle"></i></strong> Data Already Exists</div>');
                                }
                            }
                        });
                    }
                });

                // Select all checkbox
                $("body").delegate("#employee_selectall", "change", function () {
                    $(".employee_checkbox:checkbox").prop('checked', $(this).prop("checked")); 
                });

                // Multiple checkbox delete group
                $('#employee_delete').click(function() {

                    var id = [];

                    $('.employee_checkbox:checked').each(function(i) {
                        id[i] = $(this).val();
                    });

                    if(id.length === 0) {

                        bootbox.alert({
                            message: "Please Select atleast one checkbox!",
                            //size: 'small'
                        });  

                    }else{

                        bootbox.confirm({ 
                            message: "Are you sure you want to delete this?",
                            buttons: {
                                confirm: {
                                    label: 'Yes',
                                    className: 'btn-success'
                                },
                                cancel: {
                                    label: 'No',
                                    className: 'btn-danger'
                                }
                            },
                            callback: function (result) {

                                if(result) {
                                    $.ajax({
                                        url:'delete-employee',
                                        method:'POST',
                                        data:{emp_id:id},
                                        success:function(res) {
                                            for(var i=0; i<id.length; i++) {
                                                $('tr#'+id[i]+'').css('background-color', '#dc3545');
                                                $('tr#'+id[i]+'').fadeOut('slow');
                                            }
                                            $("#employeeTable").dataTable().fnDestroy();
                                            viewEmployees();
                                        }
                                    });                             
                                }
                            }
                        });                
                    }            
                });
            });
        </script>
        @endsection