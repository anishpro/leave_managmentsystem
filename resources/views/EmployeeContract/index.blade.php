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
                            <span>Manage Employee Contract</span>  
                        </div>
                    </div><!-- /.col-md-5 -->

                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-dark" href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Employee Contract</li>
                        </ol>
                    </div><!-- /.col-md-7 -->
                </div><!-- /.row -->
                
                <div class="container-fluid"> 
                    <div class="row">
                        <div class="col-md-12 mb-4 mt-2 text-right">
                            <a href="javascript:void(0);" class="btn btn-default btn-icon mr-1" tooltip="Add New" id="add_employeeContract"><i class="bx bx-plus f-s-14 align-middle m-b-2"></i></a> 

                            <button type="submit" class="btn btn-default btn-icon" id="employeeContract_delete" tooltip="Delete"><i class="bx bx-trash f-s-14 align-middle m-b-2"></i></button> 
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Employee Contract List</h4>
                                    <p class="card-title-desc"></p>
                                    <table class="table table-bordered table-striped dt-responsive" id="employeeContractTable" style="width:100%">
                                        <thead>   
                                            <tr>
                                                <th width="5%">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="employeeContract_selectall" id="employeeContract_selectall">
                                                        <label class="form-check-label" for="employeeContract_selectall"></label>
                                                    </div>
                                                </th>
                                                <th>Name</th>
                                                <th>Contract Start Date</th>
                                                <th>Contract End Date</th>
                                                <th>No of Month</th>
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
        <div class="modal" id="employeeContract_modal">
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
                viewEmployeeContract();
                function viewEmployeeContract() {
                    $.ajax({
                        type:'get', 
                        url:'fetch-contract',
                        success:function(result) {
                            //console.log(result);
                            $('tbody').html(result);
                            $("#employeeContractTable").DataTable({
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

                // Add Employee Modal
                $('body').delegate('#add_employeeContract','click',function(e) {   
                    e.preventDefault();
                    
                    $.ajax({
                        type:"get",
                        url:"add-contract",
                        dataType:"json",
                        success:function(response) {
                            //console.log(response); 
                            $("#employeeContract_modal").modal('show');
                            $(".modal-body").html(response['content']);
                            $(".modal-title").html(response['title']);
                            $(".modal-body").find("#employee").selectpicker();
                            $(".modal-body").find("#contract_start_date, #contract_end_date").datetimepicker({
                                format: 'YYYY-MM-DD',
                                widgetPositioning:{
                                    horizontal: 'auto',
                                    vertical: 'bottom'
                                }
                            });
                            $(".modal-body").find('#employeeContract_form').parsley();
                        }
                    });
                });

                // Save New Group
                $('body').delegate('#employeeContract_form', 'submit', function(e) { 
                    e.preventDefault();
                    //alert(0);
                    if($('#employeeContract_form').parsley().isValid()) {  

                        var btn=$('#save_employeeContract');

                        $.ajax({
                            url:"insert-contract",  
                            type:"post", 
                            dataType:"json",
                            data: $('#employeeContract_form').serialize(), 
                            beforeSend:function() { 
                                btn.html('Submitting...'); 
                                btn.attr('disabled',true);
                            },   
                            success:function(result) { 
                                //alert(result);
                                //console.log(result);
                                btn.html('Submit'); 
                                btn.attr('disabled',false);
                                if(result == 'exist') { 

                                    $(".result").html(' <div class="alert alert-warning alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Email exists</div>');

                                }else if(result == 'inserted') {

                                    $(".result").html(' <div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data inserted</div>');
                                    $("#employeeContractTable").dataTable().fnDestroy();
                                    viewEmployeeContract();
                                    $('#employeeContract_form')[0].reset();

                                }else if(result == 'not_inserted') {

                                    $(".result").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data not inserted</div>');

                                }
                            }  
                        });
                    }
                });

                //EDIT HOLIDAY 
                $('body').delegate('.contract_edit','click',function(e) {   
                    e.preventDefault();

                    var contract_id = $(this).attr('data-id');
                    
                    $.ajax({
                        type:"get",
                        url:"edit-contract",
                        dataType:"json",
                        data : {contract_id:contract_id},
                        success:function(response) {
                            //console.log(response); 
                            $("#employeeContract_modal").modal('show');
                            $(".modal-body").html(response['content']);
                            $(".modal-title").html(response['title']);                            
                            
                            $(".modal-body").find("#employee").selectpicker();
                            $(".modal-body").find("#contract_start_date, #contract_end_date").datetimepicker({
                                format: 'YYYY-MM-DD',
                                widgetPositioning:{
                                    horizontal: 'auto',
                                    vertical: 'bottom'
                                }
                            });
                            $(".modal-body").find('#edit_employeeContract_form').parsley();
                        }
                    });
                });

                // Update Group
                $("body").delegate("#edit_employeeContract_form", "submit", function(e) {
                    e.preventDefault();

                    if($('#edit_employeeContract_form').parsley().isValid()) {  
                        
                        var btn=$('#update_contract');
        
                        $.ajax({
                            url:"update-contract", 
                            type:"post", 
                            data: $('#edit_employeeContract_form').serialize(),    
                            beforeSend:function() {
                                btn.html('Updating...'); 
                                btn.attr('disabled',true);
                            },
                            success:function(result) {
                                console.log(result);
                                btn.html('Update');
                                btn.attr('disabled',false); 

                                if(result == 'updated') {
                                    $(".result_contract").html(' <div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-check-circle"></i></strong> Data Updated</div>');
                                    //location.reload();
                                    $("#employeeContractTable").dataTable().fnDestroy();
                                    viewEmployeeContract();
                                }else if (result == 'not_update') {
                                    $(".result_contract").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-times-circle"></i></strong> Data Not Updated</div>');
                                }else if (result == 'exist') {
                                    $(".result_contract").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-times-circle"></i></strong> Data Already Exists</div>');
                                }
                            }
                        });
                    }
                });

                // Select all checkbox
                $("body").delegate("#employeeContract_selectall", "change", function () {
                    $(".contract_checkbox:checkbox").prop('checked', $(this).prop("checked")); 
                });

                // Multiple checkbox delete group
                $('#employeeContract_delete').click(function() {

                    var id = [];

                    $('.contract_checkbox:checked').each(function(i) {
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
                                        url:'delete-contract',
                                        method:'POST',
                                        data:{empContract_id:id},
                                        success:function(res) {
                                            for(var i=0; i<id.length; i++) {
                                                $('tr#'+id[i]+'').css('background-color', '#dc3545');
                                                $('tr#'+id[i]+'').fadeOut('slow');
                                            }
                                            $("#employeeContractTable").dataTable().fnDestroy();
                                            viewEmployeeContract();
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