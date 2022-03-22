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
                                        <input type="hidden" name="request_id" value="{{$requestTravelData->id}}">
                                        <div class="result"></div>

                                        <div class="form-group row">
                                            <div class="form-group col-md-3">
                                                <label for="inputPassword3" class="col-form-label">Employee <span class="text-danger">*</span></label>
                                                <select class="form-control" name="employee" id="employee" data-live-search="true" data-size="5" required data-parsley-errors-container=".employeeError" title="-- Select Employee --">
                                                    @foreach($allEmployee as $value)
														<option value="{{$value->id}}" @if($value->id == $requestTravelData->emp_id) {{"selected"}} @endif>{{$value->name}}</option>
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
                                                @foreach($recommendedEmployee as $value)
														<option value="{{$value->id}}" @if($value->id == $requestTravelData->recommenedBy_id) {{"selected"}} @endif>{{$value->name}}</option>
													@endforeach
                                                </select>
                                                <div class="recommendationError"></div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="inputEmail3" class="col-form-label">Request Type <span class="text-danger">*</span></label>
                                                <select class="form-control" name="request_type" id="request_type" data-live-search="true" data-size="5" required data-parsley-errors-container=".requestError" title="-- Select Reuest Type --">
                                                @foreach($allRequestTypes as $value)
													<option value="{{$value}}" @if($value == $requestTravelData->request_type) {{"selected"}} @endif>{{$value}}</option>
												@endforeach
                                                </select>
                                                <div class="requestError"></div> 
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="inputEmail3" class="col-form-label">Travel Type <span class="text-danger">*</span></label>
                                                <select class="form-control" name="travel_type" id="travel_type" data-live-search="true" data-size="5" required data-parsley-errors-container=".travelError" title="-- Select Travel type --">
                                                @foreach($allTravelTypes as $value)
													<option value="{{$value}}" @if($value == $requestTravelData->travel_type) {{"selected"}} @endif>{{$value}}</option>
												@endforeach
                                                </select>
                                                <div class="travelError"></div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="inputEmail3" class="col-form-label">PO <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="po" id="po" placeholder="PO" autocomplete="off" value="{{$requestTravelData->po}}" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="inputEmail3" class="col-form-label">Purpose/Objective <span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="purpose" id="purpose" placeholder="Purpose of travel" rows="2" required>{{$requestTravelData->travel_purpose}}</textarea>
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
                                            <input type="hidden" name="removeIds" id="removeIds" value="">
                                            @foreach($requestTravelDetail as $index => $detail)
                                            <div class="section" id="{{$index + 1}}">

                                                <input type="hidden" name="detailIds[]" id="detailIds" value="{{$detail->id}}">
                                                <div class="form-group row">
                                                    <div class="form-group col-md-4">
                                                        <label for="inputEmail3" class="col-form-label">Place of night halt <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="night_halt[]" value="{{$detail->place_of_halt}}" placeholder="Place of night halt" autocomplete="off" required>
                                                    </div> 
                                                
                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword3" class="col-form-label">From Date <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control from_date" name="from_date[]" value="{{$detail->from_date}}" placeholder="YYYY-MM-DD" required>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword3" class="col-form-label">To Date <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control to_date" name="to_date[]" value="{{$detail->to_date}}" placeholder="YYYY-MM-DD" required> 
                                                    </div>

                                                    <div class="col-md-2 text-right mt-4">
                                                    <button class="btn btn-danger btn-sm delete_detail" data-id="{{$detail->id}}" >Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="inputEmail3" class="col-form-label">Contact Address <span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="contact_address" id="contact_address" placeholder="Contact Address" rows="2" required>{{$requestTravelData->contact_address}}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <div class="form-group col-md-4">
                                                <div class="row">
                                                    <label for="inputPassword3" class="col-form-label col-md-12">Invitation from the Government <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="government_invitaion1" name="government_invitaion" value="Yes" @if($requestTravelData->government_invitation == 'Yes') {{"checked"}} @endif>
                                                            <label class="custom-control-label" for="government_invitaion1">Yes</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="government_invitaion2" name="government_invitaion" value="No" @if($requestTravelData->government_invitation == 'No') {{"checked"}} @endif>
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
                                                            <input type="radio" class="custom-control-input" id="prev_duty_report1" name="prev_duty_report" value="Yes" @if($requestTravelData->prev_travel_report == 'Yes') {{"checked"}} @endif>
                                                            <label class="custom-control-label" for="prev_duty_report1">Yes</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="prev_duty_report2" name="prev_duty_report" value="No" @if($requestTravelData->prev_travel_report == 'No') {{"checked"}} @endif>
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
                                                            <input type="radio" class="custom-control-input" id="security_clearance1" name="security_clearance" value="Yes" @if($requestTravelData->security_clearance_obtained == 'Yes') {{"checked"}} @endif>
                                                            <label class="custom-control-label" for="security_clearance1">Yes</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="security_clearance2" name="security_clearance" value="No" @if($requestTravelData->security_clearance_obtained == 'No') {{"checked"}} @endif>
                                                            <label class="custom-control-label" for="security_clearance2">No</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="security_clearance3" name="security_clearance" value="in_process" @if($requestTravelData->security_clearance_obtained == 'in_process') {{"checked"}} @endif>
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
                                                    @if($requestTravelData->attachment != 'NULL')
                                                        @if(in_array(pathinfo($requestTravelData->attachment, PATHINFO_EXTENSION), $imageArray) )
                                                            <img src="{{url('uploads/travel_attachment')}}/{{$requestTravelData->attachment}}" class="rounded" style="height:80px;">
                                                        @else
                                                            <label>{{$requestTravelData->attachment}}</label>
                                                        @endif
                                                        <input type="hidden" name="old_attachment" value="{{$requestTravelData->attachment}}">
                                                    @else
                                                        <img src="{{url('uploads/sample_placeholder.png')}}" class="rounded" style="height:80px;">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="form-group col-md-12">
                                                <label for="inputEmail3" class="col-form-label">Follow-up  action(s) taken/recommended implemented</label>
                                                <textarea class="form-control" name="follow_up" id="follow_up" placeholder="" rows="2">{{$requestTravelData->follow_up_action}}</textarea>
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
                                                            <input type="radio" class="custom-control-input" id="travel_advance_request1" name="travel_advance_request" value="Yes" @if($requestTravelData->travel_advance_requested == 'Yes') {{"checked"}} @endif>
                                                            <label class="custom-control-label" for="travel_advance_request1">Yes</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="travel_advance_request2" name="travel_advance_request" value="No" @if($requestTravelData->travel_advance_requested == 'No') {{"checked"}} @endif>
                                                            <label class="custom-control-label" for="travel_advance_request2">No</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="advance_amount" id="advance_amount" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Amount" value="{{$requestTravelData->travel_advance_amount}}"autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <div class="row">
                                                    <label for="inputPassword3" class="col-form-label col-md-12">Mode of Travel <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="customRadio" name="travel_mode" value="by_road" @if($requestTravelData->mode_of_travel == 'by_road') {{"checked"}} @endif>
                                                            <label class="custom-control-label" for="customRadio">By Road</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="customRadio2" name="travel_mode" value="by_air" @if($requestTravelData->mode_of_travel == 'by_air') {{"checked"}} @endif>
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
                                                            <input type="radio" class="custom-control-input" id="vehicle_required" name="vehicle_required" value="Yes" @if($requestTravelData->official_vehicle_requied == 'Yes') {{"checked"}} @endif>
                                                            <label class="custom-control-label" for="vehicle_required">Yes</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" id="vehicle_required1" name="vehicle_required" value="No" @if($requestTravelData->official_vehicle_requied == 'No') {{"checked"}} @endif>
                                                            <label class="custom-control-label" for="vehicle_required1">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="form-group col-md-12">
                                                <label for="inputEmail3" class="col-form-label">Request for Hired Vehicle:</label>
                                                <textarea class="form-control" name="hired_vehicle" id="hired_vehicle" placeholder="" rows="2">{{$requestTravelData->hired_vehicle_request}}</textarea>
                                            </div>
                                        </div> 

                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="inputEmail3" class="col-form-label">Comment:</label>
                                                <textarea class="form-control" name="comment" id="comment" placeholder="Write Comment" rows="2">{{$requestTravelData->comment}}</textarea>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="inputPassword3" class="col-form-label">Request Status <span class="text-danger">*</span></label>
                                                <select class="form-control" name="request_status" id="request_status" data-live-search="true" data-size="5" required data-parsley-errors-container=".statusError" title="-- Select Employee --">
                                                @foreach($requestStatus as $value)
														<option value="{{$value}}" @if($value == $requestTravelData->status) {{"selected"}} @endif>{{$value}}</option>
													@endforeach
                                                </select>
                                                <div class="statusError"></div>
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

                $("#employee, .deduction, #recommeded_by, #request_type, #travel_type, #request_status").selectpicker();

                //date_from and date_to
                $(".from_date, .to_date").datetimepicker({
                    format: 'YYYY-MM-DD',
                    widgetPositioning:{
                        horizontal: 'auto',
                        vertical: 'bottom'
                    }
                }); 

                //ADD MORE TRAVEL SECTION
                var count = 1;

                $("body").delegate("#add_more", "click", function(event){
                    event.preventDefault();

                    count++;
                    var html = '';
                    html +='<div class="section" id="'+count+'">';
                    html +='<input type="hidden" name="detailIds[]" id="detailIds" value="">';
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

                

                //DELETE EXISTING APPLICATION DETAILS
                $("body").delegate(".delete_detail", "click", function(event){
                    event.preventDefault();

                    var removeData = $("#removeIds").val();
                    var id = $(this).attr('data-id');
                    if(removeData == ''){
                        $("#removeIds").val(id);
                    }else{
                        var result = removeData.concat(",", id);
                        $("#removeIds").val(result);
                    }
                    $(this).parents('div #'+id+'').remove();
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
                            url:"{{ url('travel_request/update-request-travel') }}",  
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
                                if(result == 'updated') { 
                                    $(".result").html(' <div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data updated</div>');

                                }else if(result == 'not_updated') {

                                    $(".result").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data not updated</div>');

                                }
                            }  
                        });
                    }
                });
                
            }); 
        </script> 
        @endsection

