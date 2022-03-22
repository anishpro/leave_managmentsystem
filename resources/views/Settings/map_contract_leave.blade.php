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
                            <span>Manage Contract Leave Mapping</span>  
                        </div>
                    </div><!-- /.col-md-5 -->

                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-dark" href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Contract Leave Mapping</li>
                        </ol>
                    </div><!-- /.col-md-7 -->
                </div><!-- /.row -->
                
                <div class="container-fluid"> 
                    <div class="row">
                        <div class="col-md-12 mb-4 mt-2 text-right">
                            <a href="javascript:void(0);" class="btn btn-default btn-icon mr-1" tooltip="Add New" id="add_mapcontract"><i class="bx bx-plus f-s-14 align-middle m-b-2"></i></a> 

                            <button type="submit" class="btn btn-default btn-icon" id="mapcontract_delete" tooltip="Delete"><i class="bx bx-trash f-s-14 align-middle m-b-2"></i></button> 
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Mapping List</h4>
                                    <p class="card-title-desc"></p>
                                    <table class="table table-bordered table-striped dt-responsive" id="mapcontractTable" style="width:100%">
                                        <thead>   
                                            <tr>
                                                <th width="5%">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="mapcontract_selectall" id="mapcontract_selectall">
                                                        <label class="form-check-label" for="mapcontract_selectall"></label>
                                                    </div>
                                                </th>
                                                <th>Contract Month</th>
                                                <th>LeaveType</th>
                                                <th>Leave Duration</th>
                                                <th>Added Date</th>
                                                <!-- <th>Action</th>   -->
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
        <div class="modal" id="mapcontract_modal">
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
                viewMapContract();
                function viewMapContract() {
                    $.ajax({
                        type:'get', 
                        url:'fetch-mapcontract',
                        success:function(result) {
                            //console.log(result);
                            $('tbody').html(result);
                            $("#mapcontractTable").DataTable({
                                "responsive": true,
                                "autoWidth": false, 
                                "order" : [],
                                "columnDefs": [{
                                "targets": [0],
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

                function add_dynamic_input_field(count)
                {
                    var button = '';
                    if(count > 1)
                    {
                    button = '<button type="button" name="remove" id="'+count+'" class="btn btn-danger btn-xs remove">x</button>';
                    }
                    else
                    {
                    button = '<button type="button" name="add_more" id="add_more" class="btn btn-success btn-xs">+</button>';
                    }
                    output = '<tr id="row'+count+'">';
                    output += '<td><input type="text" name="programming_languages[]" placeholder="Add Programming Languages" class="form-control name_list" /></td>';
                    output += '<td align="center">'+button+'</td></tr>';
                    $('#dynamic_field').append(output);
                }

                // Add Group Modal
                $('body').delegate('#add_mapcontract','click',function(e) {    
                    e.preventDefault();
                    
                    $.ajax({
                        type:"get",
                        url:"add-mapcontract",
                        dataType:"json",
                        success:function(response) {
                            console.log(response); 
                            $("#mapcontract_modal").modal('show');
                            $(".modal-body").html(response['content']);
                            $(".modal-title").html(response['title']);
                            $(".modal-body").find('#leave_type_id').selectpicker();
                            $(".modal-body").find('#contract_form').parsley();
                        }
                    });
                });

                // Save New Group
                $('body').delegate('#contract_form', 'submit', function(e) { 
                    e.preventDefault();
                    //alert(0);
                    if($('#contract_form').parsley().isValid()) {  

                        var btn = $('#save_contract');

                        $.ajax({
                            url:"insert-mapcontract",  
                            type:"post", 
                            dataType:"json",
                            data: $('#contract_form').serialize(), 
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
                                    $("#mapcontractTable").dataTable().fnDestroy();
                                    viewMapContract();
                                    $('#contract_form')[0].reset();
                                }else if(result == 'not_inserted') {
                                    $(".result").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data not inserted</div>');
                                }
                            }  
                        });
                    }
                });

                //EDIT LEAVETYPE 
                $('body').delegate('.leavetype_edit','click',function(e) {   
                    e.preventDefault();

                    var leavetype_id = $(this).attr('data-id');
                    
                    $.ajax({
                        type:"get",
                        url:"edit-leavetype",
                        dataType:"json",
                        data : {leavetype_id:leavetype_id},
                        success:function(response) {
                            //console.log(response); 
                            $("#leavetype_modal").modal('show');
                            $(".modal-body").html(response['content']);
                            $(".modal-title").html(response['title']);
                            $(".modal-body").find('#edit_mapping_required').selectpicker();
                            $(".modal-body").find('#edit_leavetype_form').parsley();
                        }
                    });
                });

                // Update LeaveType
                $("body").delegate("#edit_leavetype_form", "submit", function(e) {
                    e.preventDefault();

                    if($('#edit_leavetype_form').parsley().isValid()) {  
                        
                        var btn=$('#update_leavetype');
                        var edit_leavetype_id=$('#edit_leavetype_id').val();//alert(edit_leavetype_id);
                        var edit_leavetype_name=$('#edit_leavetype_name').val();//alert(edit_leavetype_name);
                        var edit_mapping_required=$('#edit_mapping_required').val();//alert(edit_mapping_required);
        
                        $.ajax({
                            url:"update-leavetype", 
                            type:"post", 
                            data: {edit_leavetype_id:edit_leavetype_id, edit_leavetype_name:edit_leavetype_name, edit_mapping_required:edit_mapping_required},    
                            beforeSend:function() {
                                btn.html('Updating...'); 
                                btn.attr('disabled',true);
                            },
                            success:function(result) {
                                console.log(result);
                                btn.html('Update');
                                btn.attr('disabled',false); 

                                if(result == 'updated') {
                                    $(".result_leavetype").html(' <div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-check-circle"></i></strong> Data Updated</div>');
                                    //location.reload();
                                    $("#leavetypeTable").dataTable().fnDestroy();
                                    viewLeaveType();
                                }else if (result == 'not_update') {
                                    $(".result_leavetype").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-times-circle"></i></strong> Data Not Updated</div>');
                                }else if (result == 'exist') {
                                    $(".result_leavetype").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-times-circle"></i></strong> Data Already Exists</div>');
                                }
                            }
                        });
                    }
                });

                // Select all checkbox
                $("body").delegate("#mapcontract_selectall", "change", function () {
                    $(".contract_checkbox:checkbox").prop('checked', $(this).prop("checked")); 
                });

                // Multiple checkbox delete leavetype
                $('#mapcontract_delete').click(function() { 

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
                                        url:'delete-mapcontract',
                                        method:'POST',
                                        data:{contract_id:id},
                                        success:function(res) {
                                            for(var i=0; i<id.length; i++) {
                                                $('tr#'+id[i]+'').css('background-color', '#dc3545');
                                                $('tr#'+id[i]+'').fadeOut('slow');
                                            }
                                            $("#mapcontractTable").dataTable().fnDestroy();
                                            viewMapContract();
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