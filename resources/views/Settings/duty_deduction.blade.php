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
                            <span>Manage Deduction Item</span>  
                        </div>
                    </div><!-- /.col-md-5 -->

                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-dark" href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Deduction Item</li>
                        </ol>
                    </div><!-- /.col-md-7 -->
                </div><!-- /.row -->
                
                <div class="container-fluid"> 
                    <div class="row">
                        <div class="col-md-12 mb-4 mt-2 text-right">
                            <a href="javascript:void(0);" class="btn btn-default btn-icon mr-1" tooltip="Add New" id="add_deduction"><i class="bx bx-plus f-s-14 align-middle m-b-2"></i></a> 

                            <button type="submit" class="btn btn-default btn-icon" id="deduction_delete" tooltip="Delete"><i class="bx bx-trash f-s-14 align-middle m-b-2"></i></button> 
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Deduction Item List</h4>
                                    <p class="card-title-desc"></p>
                                    <table class="table table-bordered table-striped dt-responsive" id="groupTable" style="width:100%">
                                        <thead>   
                                            <tr>
                                                <th width="5%">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="deduction_selectall" id="deduction_selectall">
                                                        <label class="form-check-label" for="deduction_selectall"></label>
                                                    </div>
                                                </th>
                                                <th>Deduction Item</th>
                                                <th>Deduction</th>
                                                <th>Added Date</th>
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
        <div class="modal" id="group_modal">
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
                viewDeduction();
                function viewDeduction() {
                    $.ajax({
                        type:'get', 
                        url:'fetch-deduction',
                        success:function(result) {
                            //console.log(result);
                            $('tbody').html(result);
                            $("#groupTable").DataTable({
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

                // Add Group Modal
                $('body').delegate('#add_deduction','click',function(e) {   
                    e.preventDefault();
                    
                    $.ajax({
                        type:"get",
                        url:"add-deduction",
                        dataType:"json",
                        success:function(response) {
                            //console.log(response); 
                            $("#group_modal").modal('show');
                            $(".modal-body").html(response['content']);
                            $(".modal-title").html(response['title']);
                            $(".modal-body").find('#group_form').parsley();
                        }
                    });
                });

                // Save New Group
                $('body').delegate('#deduction_form', 'submit', function(e) { 
                    e.preventDefault();
                    //alert(0);
                    if($('#deduction_form').parsley().isValid()) {  

                        var btn=$('#save_deduction');

                        $.ajax({
                            url:"insert-deduction",  
                            type:"post", 
                            dataType:"json",
                            data: $('#deduction_form').serialize(), 
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
                                    $(".result").html(' <div class="alert alert-warning alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data exists</div>');
                                }else if(result == 'inserted') { 
                                    $(".result").html(' <div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data inserted</div>');
                                    $("#groupTable").dataTable().fnDestroy();
                                    viewDeduction();
                                    $('#deduction_form')[0].reset();
                                }else if(result == 'not_inserted') {
                                    $(".result").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data not inserted</div>');
                                }
                            }  
                        });
                    }
                });

                //EDIT GROUP 
                $('body').delegate('.deduction_edit','click',function(e) {   
                    e.preventDefault();

                    var deduction_id = $(this).attr('data-id');
                    
                    $.ajax({
                        type:"get",
                        url:"edit-deduction",
                        dataType:"json",
                        data : {deduction_id:deduction_id},
                        success:function(response) {
                            //console.log(response); 
                            $("#group_modal").modal('show');
                            $(".modal-body").html(response['content']);
                            $(".modal-title").html(response['title']);
                            $(".modal-body").find('#edit_deduction_form').parsley();
                        }
                    });
                });

                // Update Group
                $("body").delegate("#edit_deduction_form", "submit", function(e) {
                    e.preventDefault();

                    if($('#edit_deduction_form').parsley().isValid()) {  
                        
                        var btn=$('#update_deduction');
        
                        $.ajax({
                            url:"update-deduction", 
                            type:"post", 
                            data: $('#edit_deduction_form').serialize(),    
                            beforeSend:function() {
                                btn.html('Updating...'); 
                                btn.attr('disabled',true);
                            },
                            success:function(result) {
                                console.log(result);
                                btn.html('Update');
                                btn.attr('disabled',false); 

                                if(result == 'updated') {
                                    $(".result_group").html(' <div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-check-circle"></i></strong> Data Updated</div>');
                                    //location.reload();
                                    $("#groupTable").dataTable().fnDestroy();
                                    viewDeduction();
                                }else if (result == 'not_update') {
                                    $(".result_group").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-times-circle"></i></strong> Data Not Updated</div>');
                                }else if (result == 'exist') {
                                    $(".result_group").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-times-circle"></i></strong> Data Already Exists</div>');
                                }
                            }
                        });
                    }
                });

                // Select all checkbox
                $("body").delegate("#deduction_selectall", "change", function () {
                    $(".deduction_checkbox:checkbox").prop('checked', $(this).prop("checked")); 
                });

                // Multiple checkbox delete group
                $('#deduction_delete').click(function() {

                    var id = [];

                    $('.deduction_checkbox:checked').each(function(i) {
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
                                        url:'delete-deduction',
                                        method:'POST',
                                        data:{deduction_id:id},
                                        success:function(res) {
                                            for(var i=0; i<id.length; i++) {
                                                $('tr#'+id[i]+'').css('background-color', '#dc3545');
                                                $('tr#'+id[i]+'').fadeOut('slow');
                                            }
                                            $("#groupTable").dataTable().fnDestroy();
                                            viewDeduction();
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