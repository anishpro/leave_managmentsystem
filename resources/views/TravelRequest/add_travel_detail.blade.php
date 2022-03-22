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
                            <span>Add Travel Detail</span>  
                        </div>
                    </div><!-- /.col-md-5 -->

                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-dark" href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a class="text-dark" href="index.php">Travel</a></li>
                            <li class="breadcrumb-item active">Add</li>
                        </ol>
                    </div><!-- /.col-md-7 -->
                </div><!-- /.row --> 
                
                <div class="container-fluid">  
                    <div class="row">
                        <div class="col-md-12 mb-4 mt-2 text-right">
                            <a href="{{ url('travel')}}" class="btn btn-default btn-icon mr-1" tooltip="Back"><i class="bx bx-undo f-s-14 align-middle m-b-2"></i></a> 
                        </div>

                        <div class="col-md-12"> 
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Travel Info</h4>
                                    <form id="travel_form" enctype="multipart/form-data">
                                        <div class="result"></div>

                                        <div class="form-group row">
                                            <div class="form-group col-md-3">
                                                <label for="inputPassword3" class="col-form-label">Employee <span class="text-danger">*</span></label>
                                                <select class="form-control" name="employee" id="employee" data-live-search="true" data-size="5" required data-parsley-errors-container=".employeeError" title="-- Select Employee --">
                                                    @foreach($allEmployee as $value)
														<option value="{{$value->id}}">{{$value->name}}</option>
													@endforeach
                                                </select>
                                                <div class="employeeError"></div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="inputEmail3" class="col-form-label">Position</label>
                                                <input type="text" class="form-control" placeholder="Position" autocomplete="off" name="position_id" id="position_id" value="" readonly>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="inputEmail3" class="col-form-label">Duty Station</label>
                                                <input type="text" class="form-control" placeholder="Duty Station" name="duty_station" id="duty_station" value="" autocomplete="off" readonly>
                                            </div> 

                                            <div class="form-group col-md-3">
                                                <label for="inputPassword3" class="col-form-label">Recommended By <span class="text-danger">*</span></label>
                                                <select class="form-control" name="recommeded_by" id="recommeded_by" data-live-search="true" data-size="5" required data-parsley-errors-container=".recommendationError" title="-- Select Employee --">
                                                    
                                                </select>
                                                <div class="recommendationError"></div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="inputEmail3" class="col-form-label">Request Type <span class="text-danger">*</span></label>
                                                <select class="form-control" name="request_type" id="request_type" data-live-search="true" data-size="5" required data-parsley-errors-container=".requestError" title="-- Select Reuest Type --">
                                                @foreach($allRequestTypes as $value)
													<option value="{{$value}}">{{$value}}</option>
												@endforeach
                                                </select>
                                                <div class="requestError"></div> 
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="inputEmail3" class="col-form-label">Travel Type <span class="text-danger">*</span></label>
                                                <select class="form-control" name="travel_type" id="travel_type" data-live-search="true" data-size="5" required data-parsley-errors-container=".travelError" title="-- Select Travel type --">
                                                @foreach($allTravelTypes as $value)
													<option value="{{$value}}">{{$value}}</option>
												@endforeach
                                                </select>
                                                <div class="travelError"></div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="inputEmail3" class="col-form-label">PO <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="po" id="po" placeholder="PO" autocomplete="off" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="inputEmail3" class="col-form-label">Purpose/Objective <span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="purpose" id="purpose" placeholder="Purpose of travel" rows="2" required></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <h4 class="card-title mb-4">Travel Detail</h4>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <button class="btn btn-primary btn-sm" id="add_more">Add More</button>
                                            </div>
                                        </div>

                                        <div class="travel_detail_section">
                                            <div class="section" id="1">
                                                <div class="form-group row">
                                                    <div class="form-group col-md-4">
                                                        <label for="inputEmail3" class="col-form-label">Place of night halt <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="night_halt[]" placeholder="Place of night halt" autocomplete="off" required>
                                                    </div>
                                                
                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword3" class="col-form-label">From Date <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control from_date" name="from_date[]" placeholder="YYYY-MM-DD" required>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword3" class="col-form-label">To Date <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control to_date" name="to_date[]" placeholder="YYYY-MM-DD" required> 
                                                    </div>

                                                    <div class="col-md-2 text-right mt-4">
                                                        <button class="btn btn-danger btn-sm remove_detail" data-id="1" >Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="inputEmail3" class="col-form-label">Contact Address <span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="contact_address" id="contact_address" placeholder="Contact Address" rows="2" required></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <div class="form-group col-md-4">
                                                <div class="row">
                                                    <label for="inputPassword3" class="col-form-label col-md-12">Invitation from the Government <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="government_invitaion1" name="government_invitaion" value="Yes" checked>
                                                            <label class="custom-control-label" for="government_invitaion1">Yes</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="government_invitaion2" name="government_invitaion" value="No">
                                                            <label class="custom-control-label" for="government_invitaion2">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <div class="row">
                                                    <label for="inputPassword3" class="col-form-label col-md-12">Previous Duty Travel Report(s) <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="prev_duty_report1" name="prev_duty_report" value="Yes">
                                                            <label class="custom-control-label" for="prev_duty_report1">Yes</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="prev_duty_report2" name="prev_duty_report" value="No" checked>
                                                            <label class="custom-control-label" for="prev_duty_report2">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <div class="row">
                                                    <label for="inputPassword3" class="col-form-label col-md-12">Security Clearance Obtained <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="security_clearance1" name="security_clearance" value="Yes" checked>
                                                            <label class="custom-control-label" for="security_clearance1">Yes</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="security_clearance2" name="security_clearance" value="No">
                                                            <label class="custom-control-label" for="security_clearance2">No</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="security_clearance3" name="security_clearance" value="in_process">
                                                            <label class="custom-control-label" for="security_clearance3">In Process</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row" id="file_section">
                                            <div class="col-md-6">
                                                <label for="inputEmail3" class="col-form-label">Attach the copy</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="attachment" id="customFile" accept=".jpg,.jpeg,.png, .pdf, .doc, .docx">
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                </div> 
                                                <div class="text-muted small mt-2">valid Ext: jpg, jpeg, png, pdf, doc, docx</div>
                                                <div class="get_image_preview mt-3"> 
                                                    <img src="../uploads/sample_placeholder.png" class="rounded" style="height:80px;">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="form-group col-md-12">
                                                <label for="inputEmail3" class="col-form-label">Follow-up  action(s) taken/recommended implemented</label>
                                                <textarea class="form-control" name="follow_up" id="follow_up" placeholder="" rows="2"></textarea>
                                            </div>
                                        </div> 

                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <h6>Counterpart in/or other person participating in the filed:</h6>
                                            </div>                                            
                                        </div>

                                        <div class="form-group row">

                                            <div class="form-group col-md-4">
                                                <div class="row">
                                                    <label for="inputPassword3" class="col-form-label col-md-12">Travel Advance Requested <span class="text-danger">*</span></label>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="travel_advance_request1" name="travel_advance_request" value="Yes" checked>
                                                            <label class="custom-control-label" for="travel_advance_request1">Yes</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="travel_advance_request2" name="travel_advance_request" value="No">
                                                            <label class="custom-control-label" for="travel_advance_request2">No</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="advance_amount" id="advance_amount" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Amount" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <div class="row">
                                                    <label for="inputPassword3" class="col-form-label col-md-12">Mode of Travel <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="customRadio" name="travel_mode" value="by_road">
                                                            <label class="custom-control-label" for="customRadio">By Road</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="customRadio2" name="travel_mode" value="by_air" checked>
                                                            <label class="custom-control-label" for="customRadio2">By Air</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-5">
                                                <div class="row">
                                                    <label for="inputPassword3" class="col-form-label col-md-12">If By Air, Official Vehicle for airport drop & pick-up required</label>
                                                    <div class="col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="vehicle_required" name="vehicle_required" value="Yes" checked>
                                                            <label class="custom-control-label" for="vehicle_required">Yes</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="vehicle_required1" name="vehicle_required" value="No">
                                                            <label class="custom-control-label" for="vehicle_required1">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="form-group col-md-12">
                                                <label for="inputEmail3" class="col-form-label">Request for Hired Vehicle:</label>
                                                <textarea class="form-control" name="hired_vehicle" id="hired_vehicle" placeholder="" rows="2"></textarea>
                                            </div>
                                        </div> 

                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="inputEmail3" class="col-form-label">Comment:</label>
                                                <textarea class="form-control" name="comment" id="comment" placeholder="Write Comment" rows="2"></textarea>
                                            </div>
                                        </div>
                                                
                                        <div class="form-group row text-right mb-0">
                                            <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-info btn-md" id="save_travel">Submit</button>
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

                //ADD MORE TRAVEL SECTION
                var count = 1;

                $("body").delegate("#add_more", "click", function(event){
                    event.preventDefault();

                    count++;
                    var html = '';
                    html +='<div class="section" id="'+count+'">';
                    html += '<div class="form-group row">';
                    html += '<div class="form-group col-md-4">';
                    html += '<label for="inputEmail3" class="col-form-label">Place of night halt <span class="text-danger">*</span></label>';
                    html += '<input type="text" class="form-control" name="night_halt[]" placeholder="Place of night halt" autocomplete="off" required>';
                    html += '</div>';
                    
                    html += '<div class="form-group col-md-3">';
                    html += '<label for="inputPassword3" class="col-form-label">From Date <span class="text-danger">*</span></label>';
                    html += '<input type="text" class="form-control from_date" name="from_date[]" placeholder="YYYY-MM-DD" required>';
                    html += '</div>';
                    html += '<div class="form-group col-md-3">';
                    html += '<label for="inputPassword3" class="col-form-label">To Date <span class="text-danger">*</span></label>';
                    html += '<input type="text" class="form-control to_date" name="to_date[]" placeholder="YYYY-MM-DD" required>'; 
                    html += '</div>';
                    html += '<div class="col-md-2 text-right mt-4">';
                    html += '<button class="btn btn-danger btn-sm remove_detail" data-id="'+count+'" >Delete</button>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    $(".travel_detail_section").append(html);
                    $(".from_date, .to_date").datetimepicker({
                        format: 'YYYY-MM-DD',
                        widgetPositioning:{
                            horizontal: 'auto',
                            vertical: 'bottom'
                        }
                    });
                    $(".deduction").selectpicker();
                });

                //REMOVE ADDED Details
                $("body").delegate(".remove_detail", "click", function(event){
                    event.preventDefault();

                    var id = $(this).attr('data-id');//alert(id);
                    $(this).parents('div #'+id).remove();
                });

                $("#employee, .deduction, #recommeded_by, #request_type, #travel_type").selectpicker();

                //date_from and date_to
                $(".from_date, .to_date").datetimepicker({
                    format: 'YYYY-MM-DD',
                    widgetPositioning:{
                        horizontal: 'auto',
                        vertical: 'bottom'
                    }
                }); 

                //Change of radio
                $('input[type=radio][name = government_invitaion]').on('change', function() {
                    
                    var radioValue = $(this).val();
                    if(radioValue == 'Yes'){
                        $("#file_section").removeClass('d-none');
                    }else{
                        $("#file_section").addClass('d-none');
                    }
                });

                //Change of radio travel_advance_request
                $('input[type=radio][name = travel_advance_request]').on('change', function() {
                    
                    var radioValue = $(this).val();
                    if(radioValue == 'Yes'){
                        $("#advance_amount").attr('readonly', false);
                    }else{
                        $("#advance_amount").attr('readonly', true);
                    }
                });

                //get recommended employee list
                $("#employee").on('change', function(event){
                    event.preventDefault();

                    var emp_id = $(this).val();//alert(emp_id);

                    $.ajax({
                        url : "{{ url('travel_request/get-remmendedby-employee-list') }}",  
                        type : "post", 
                        dataType : "json",
                        data : {emp_id:emp_id},
                        success : function(response){
                            $("#recommeded_by").html(response['empList']);
                            $("#recommeded_by").selectpicker('refresh');
                            $("#position_id").val(response['postion']);
                            $("#duty_station").val(response['duty_station']);
                        }
                    });
                })

                //travel form parsley
                $("#travel_form").parsley();

                // Save subcategory
                $('body').delegate('#travel_form', 'submit', function(e) { 
                    e.preventDefault();
                    //alert(0);
                    if($('#travel_form').parsley().isValid()) {  

                        var btn = $('#save_travel');

                        $.ajax({
                            url:"{{ url('travel_request/insert-request-travel') }}",  
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
                                console.log(result);
                                btn.html('Submit'); 
                                btn.attr('disabled',false);
                                if(result == 'exist') { 
                                    $(".result").html(' <div class="alert alert-warning alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data exists</div>');
                                }else if(result == 'inserted') { 
                                    $(".result").html(' <div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data inserted</div>');

                                    $("#employee, #deduction").selectpicker('refresh');
                                    
                                    $('#travel_form')[0].reset();
                                }else if(result == 'not_inserted') {

                                    $(".result").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data not inserted</div>');

                                }
                            }  
                        });
                    }
                });
                
            }); 
        </script> 
        @endsection

