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
                            <span>Manage Travel Detail</span>  
                        </div>
                    </div><!-- /.col-md-5 -->

                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-dark" href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Travel Detail</li>
                        </ol>
                    </div><!-- /.col-md-7 -->
                </div><!-- /.row -->
                
                <div class="container-fluid"> 
                    <div class="row">
                        <div class="col-md-12 mb-4 mt-2 text-right">
                             
                            <button type="submit" class="btn btn-default btn-icon" id="travel_delete" tooltip="Delete"><i class="bx bx-trash f-s-14 align-middle m-b-2"></i></button> 
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Travel Detail List</h4>
                                    <p class="card-title-desc"></p>
                                    <table class="table table-bordered table-striped dt-responsive" id="travelTable" style="width:100%">
                                        <thead>   
                                            <tr>
                                                <th width="5%">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="travel_selectall" id="travel_selectall">
                                                        <label class="form-check-label" for="travel_selectall"></label>
                                                    </div>
                                                </th>
                                                <th>Employee</th>
                                                <th>For the month of</th>
                                                <th>PO</th>
                                                <th>Total Amount</th>
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
        <div class="modal" id="holiday_modal">
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
                viewTravelDetail();
                function viewTravelDetail() {
                    $.ajax({
                        type:'get', 
                        url:'travel/fetch-travel',
                        success:function(result) {
                            //console.log(result);
                            $('tbody').html(result);
                            $("#travelTable").DataTable({
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



                //VIEW DETAIL
                
                $('body').delegate('.holiday_view','click',function(e) {   
                    e.preventDefault();
                    
                    var holiday_id = $(this).attr('data-id');//alert(holiday_id);
                    
                    $.ajax({
                        type:"get",
                        url:"view-holiday",
                        dataType:"json",
                        data : {holiday_id:holiday_id},
                        success:function(response) {
                            //console.log(response); 
                            $("#holiday_modal").modal('show');
                            $(".modal-body").html(response['content']);
                            $(".modal-title").html(response['title']);
                        }
                    });
                });

                //EDIT HOLIDAY 
                $('body').delegate('.holiday_edit','click',function(e) {   
                    e.preventDefault();

                    var holiday_id = $(this).attr('data-id');
                    
                    $.ajax({
                        type:"get",
                        url:"edit-holiday",
                        dataType:"json",
                        data : {holiday_id:holiday_id},
                        success:function(response) {
                            //console.log(response); 
                            $("#holiday_modal").modal('show');
                            $(".modal-body").html(response['content']);
                            $(".modal-title").html(response['title']);
                            
                            $(".modal-body").find("#group_ids").selectpicker();
                            $(".modal-body").find("#holiday_date").datetimepicker({
                                format: 'YYYY-MM-DD',
                                widgetPositioning:{
                                    horizontal: 'auto',
                                    vertical: 'bottom'
                                }
                            });
                            $(".modal-body").find('#edit_holiday_form').parsley();
                        }
                    });
                });

                // Update Group
                $("body").delegate("#edit_holiday_form", "submit", function(e) {
                    e.preventDefault();

                    if($('#edit_holiday_form').parsley().isValid()) {  
                        
                        var btn=$('#update_holiday');
        
                        $.ajax({
                            url:"update-holiday", 
                            type:"post", 
                            data: $('#edit_holiday_form').serialize(),    
                            beforeSend:function() {
                                btn.html('Updating...'); 
                                btn.attr('disabled',true);
                            },
                            success:function(result) {
                                console.log(result);
                                btn.html('Update');
                                btn.attr('disabled',false); 

                                if(result == 'updated') {
                                    $(".result_holiday").html(' <div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-check-circle"></i></strong> Data Updated</div>');
                                    //location.reload();
                                    $("#holidayTable").dataTable().fnDestroy();
                                    viewHolidays();
                                }else if (result == 'not_update') {
                                    $(".result_holiday").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-times-circle"></i></strong> Data Not Updated</div>');
                                }else if (result == 'exist') {
                                    $(".result_holiday").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong><i class="fa fa-times-circle"></i></strong> Data Already Exists</div>');
                                }
                            }
                        });
                    }
                }); 

                // Select all checkbox
                $("body").delegate("#travel_selectall", "change", function () {
                    $(".travel_checkbox:checkbox").prop('checked', $(this).prop("checked")); 
                });

                // Multiple checkbox delete group
                $('#travel_delete').click(function() {

                    var id = [];

                    $('.travel_checkbox:checked').each(function(i) {
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
                                        url:'travel/delete-travel',
                                        method:'POST',
                                        data:{travel_id:id},
                                        success:function(res) {
                                            for(var i=0; i<id.length; i++) {
                                                $('tr#'+id[i]+'').css('background-color', '#dc3545');
                                                $('tr#'+id[i]+'').fadeOut('slow');
                                            }
                                            $("#travelTable").dataTable().fnDestroy();
                                            viewTravelDetail();
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