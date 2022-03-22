
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
                            <span>Edit Application</span>  
                        </div>
                    </div><!-- /.col-md-5 -->

                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-dark" href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a class="text-dark" href="index.php">Application</a></li>
                            <li class="breadcrumb-item active">Edit Application</li>
                        </ol>
                    </div><!-- /.col-md-7 -->
                </div><!-- /.row --> 
                
                <div class="container-fluid">  
                    <div class="row">
                        <div class="col-md-12 mb-4 mt-2 text-right">
                            <a href="{{ url('application')}}" class="btn btn-default btn-icon mr-1" tooltip="Back"><i class="bx bx-undo f-s-14 align-middle m-b-2"></i></a> 
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Edit Application</h4>
                                    <form id="application_form" enctype="multipart/form-data">
                                        <div class="result"></div>

                                        <input type="hidden" class="form-control" name="application_id" value="{{$applicationData->id}}">

                                        <div class="form-group row">
                                            <div class="col-md-5">
                                                <label for="inputPassword3" class="col-form-label">From Date <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control application_from_date" name="application_from_date" placeholder="YYYY-MM-DD" value="{{$applicationData->from_date}}" required>
                                            </div>

                                            <div class="col-md-5">
                                                <label for="inputPassword3" class="col-form-label">To Date <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control application_to_date" name="application_to_date" placeholder="YYYY-MM-DD" value="{{$applicationData->to_date}}" required> 
                                            </div>

                                            <div class="col-md-2">
                                                <div class="d-none" id="total_days_section">
                                                    <label for="inputPassword3" class="col-form-label">Total Days <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="total_days_count" id="total_days_count" value="{{$applicationData->day_count}}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label for="inputPassword3" class="col-form-label">Employee <span class="text-danger">*</span></label>
                                                <select class="form-control" name="employee" id="employee" data-live-search="true" data-size="5" required data-parsley-errors-container=".employeeError" title="-- Select Employee --">
                                                    @foreach($allEmployee as $value)
                                                        <option value="{{$value->id}}" @if($value->id == $applicationData->emp_id) {{"selected"}} @endif>{{$value->name}} ({{$value->emp_code}})</option>
                                                    @endforeach
                                                </select>
                                                <div class="employeeError"></div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="inputPassword3" class="col-form-label">Leave Type <span class="text-danger">*</span></label>
                                                <select class="form-control" name="leave_type" id="leave_type" data-live-search="true" data-size="5" required data-parsley-errors-container=".leavetypeError" title="-- Select Leave Type --">
                                                    @foreach($allLeaveType as $value)
                                                        <option value="{{$value->id}}" @if($value->id == $applicationData->leave_type_id) {{"selected"}} @endif>{{$value->leave_type}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="leavetypeError"></div>
                                            </div>
                                        </div> 

                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label for="inputPassword3" class="col-form-label">Project</label>
                                                <input type="text" class="form-control" name="project" id="project" placeholder="Enter Project Name" value="{{$applicationData->project}}" autocomplete="off"> 
                                            </div>

                                            <div class="col-md-6">
                                                <label for="inputPassword3" class="col-form-label">Contact <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="contact" id="contact" placeholder="Enter Contact" onkeypress="return event.charCode >= 48 && event.charCode <= 57" minLength="10" maxLength="10" autocomplete="off" value="{{$applicationData->contact}}" required> 
                                            </div>
                                        </div> 
                                            
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label for="inputPassword3" class="col-form-label">Address During Leave <span class="text-danger">*</span></label>
                                                <textarea class="form-control" rows="2" name="address" id="address" placeholder="Write Address" required>{{$applicationData->address}}</textarea>
                                            </div>
                                        </div> 

                                        <div class="form-group row">
                                            <div class="col-md-12 text-right">
                                                <button type="button" class="btn btn-info btn-md" id="apply_detail" style="margin-top:25px;">Apply</button>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="application_section d-none">

                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <h4 class="card-title mb-4">Leave Detail</h4>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button class="btn btn-primary btn-sm" id="add_more">Add More</button>
                                                </div>
                                            </div>   

                                            <div class="application_date_section">
                                                <input type="hidden" name="removeIds" id="removeIds" value="">
                                                @foreach($applicationDetail as $index => $detail)
                                                    <!-- {{$detail->no_of_days}} -->
                                                    <div class="section" id="{{$index + 1}}">
                                                        <input type="hidden" name="detailIds[]" id="detailIds" value="{{$detail->id}}">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <label for="inputPassword3" class="col-form-label">From Date <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control from_date" name="from_date[]" placeholder="YYYY-MM-DD" value="{{$detail->from_date}}"  autocomplete="off" required>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="inputPassword3" class="col-form-label">To Date <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control to_date" name="to_date[]" placeholder="YYYY-MM-DD" value="{{$detail->to_date}}"  autocomplete="off" required> 
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="inputPassword3" class="col-form-label">Shift <span class="text-danger">*</span></label>
                                                                <select class="form-control shift_type" name="shift_type[]" required data-parsley-errors-container=".shiftError" title="-- Select Shift Type --">
                                                                    @foreach($allShiftType as $key => $type)
                                                                        <option value="{{$type}}" @if($type == $detail->leave_shift) {{"selected"}} @endif>{{$key}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="shiftError"></div>
                                                            </div>

                                                            <div class="col-md-1">
                                                                <label for="inputPassword3" class="col-form-label">Days </label>
                                                                <div class="days_label text-center">{{$detail->no_of_days}}</div>
                                                                <input type="hidden" class="form-control days" name="days[]" value="{{$detail->no_of_days}}" placeholder="Days" readonly>
                                                            </div>
                                                        </div>  

                                                        <div class="form-group row">
                                                            <div class="col-md-12 text-right">
                                                                <button class="btn btn-danger btn-sm delete_detail" data-id="{{$detail->id}}" >Delete</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endforeach
                                            </div>                              
                                            
                                            <div class="form-group row">

                                                <div class="col-md-6">
                                                    <label for="inputEmail3" class="col-form-label">Related Documents</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="attachment" id="customFile" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                                    </div> 
                                                    <div class="text-muted small mt-2">valid Ext: jpg, jpeg, png, pdf, doc, docx</div>
                                                    <div class="get_image_preview mt-3"> 
                                                        @if($applicationData->attachment != 'NULL')
                                                            @if(in_array(pathinfo($applicationData->attachment, PATHINFO_EXTENSION), $imageArray) )
                                                                <img src="{{url('uploads/profile')}}/{{$applicationData->attachment}}" class="rounded" style="height:80px;">
                                                            @else
                                                                <label>{{$applicationData->attachment}}</label>
                                                            @endif
                                                            <input type="hidden" name="old_attachment" value="{{$applicationData->attachment}}">
                                                        @else
                                                            <img src="{{url('uploads/sample_placeholder.png')}}" class="rounded" style="height:80px;">
                                                        @endif
                                                    </div>
                                                </div> 
                                            </div>
                                                    
                                            <div class="form-group row text-right mb-0">
                                                <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-info btn-md" id="save_application">Submit</button>
                                                </div> 
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

                $("#employee, #leave_type, .shift_type").selectpicker();

                $(".application_from_date, .application_to_date").datetimepicker({
                    format: 'YYYY-MM-DD',
                    widgetPositioning:{
                        horizontal: 'auto',
                        vertical: 'bottom'
                    }
                });
                
                $("#application_form").parsley();

                //click on apply
                $("#apply_detail").click(function(event){
                    event.preventDefault();

                    var application_from_date = $(".application_from_date").val();
                    var application_to_date = $(".application_to_date").val();

                    if(application_from_date !='' && application_to_date !=''){
                        
                        //console.log(min_date+' '+max_date);
                        $(".from_date, .to_date").datetimepicker({
                            format: 'YYYY-MM-DD',                    
                            //minDate: new Date(application_from_date),
                            // maxDate: new Date(application_to_date),
                            widgetPositioning:{
                                horizontal: 'auto',
                                vertical: 'bottom'
                            }
                        });

                        var dt1 = new Date(application_from_date);
                        var dt2 = new Date(application_to_date);
                    
                        var time_difference = dt2.getTime() - dt1.getTime();
                        var result = (time_difference / (1000 * 60 * 60 * 24)) + 1;

                        $(".application_section").removeClass('d-none');
                        $(this).addClass('d-none');
                        $(".application_from_date, .application_to_date").attr('readonly', true);

                        $("#total_days_count").val(result);
                        //$(".days_label").html(result);
                        $("#total_days_section").removeClass('d-none');                        

                    }else{
                        $(".result").html('<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Please, make sure both dates are selected</div>')
                    }
                    
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
                });

                //ADD MORE TRAVEL SECTION
                var count = 1;

                $("body").delegate("#add_more", "click", function(event){
                    event.preventDefault();

                    count++;
                    var application_from_date = $(".application_from_date").val();
                    var application_to_date = $(".application_to_date").val();

                    var html = '';
                    html +='<div class="section" id="'+count+'">';
                    html +='<input type="hidden" name="detailIds[]" id="detailIds" value="">';
                    html += '<div class="form-group row" id="'+count+'">';
                    html += '<div class="col-md-4">';
                    html += '<label for="inputPassword3" class="col-form-label">From Date <span class="text-danger">*</span></label>';
                    html += '<input type="text" class="form-control from_date" name="from_date[]" id="from_date'+count+'" autocomplete="off" placeholder="YYYY-MM-DD" value="" required>';
                    html += '</div>';
                    html += '<div class="col-md-4">';
                    html += '<label for="inputPassword3" class="col-form-label">To Date <span class="text-danger">*</span></label>';
                    html += '<input type="text" class="form-control to_date" name="to_date[]" id="to_date'+count+'" placeholder="YYYY-MM-DD" value="" autocomplete="off" required>'; 
                    html += '</div>';
                    html += '<div class="col-md-3">';
                    html += '<label for="inputPassword3" class="col-form-label">Shift <span class="text-danger">*</span></label>';
                    html += '<select class="form-control shift_type" name="shift_type[]" id="shift_type'+count+'" required data-parsley-errors-container=".shiftError" title="-- Select Shift Type --">';
                        @foreach($allShiftType as $key => $type)
                            html += '<option value="{{$type}}" @if($type === "full_day") {{"selected"}} @endif>{{$key}}</option>';
                        @endforeach
                    html += '</select>';
                    html += '<div class="shiftError"></div>';
                    html += '</div>';
                    html += '<div class="col-md-1">';
                    html += '<label for="inputPassword3" class="col-form-label">Days</label>';
                    html += '<div class="days_label text-center" id="days_label'+count+'"></div>';
                    html += '<input type="hidden" class="form-control days" name="days[]" id="days'+count+'" placeholder="Days" readonly>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="form-group row">';
                    html += '<div class="col-md-12 text-right">';
                    html += '<button class="btn btn-danger btn-sm remove_detail" data-id="'+count+'" >Remove</button>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    var parentDiv = $(".section #"+count).attr('data-id');

                    $(".application_date_section").append(html);

                    $("#from_date"+count+", #to_date"+count+"").datetimepicker({
                        format: 'YYYY-MM-DD',                    
                        minDate: new Date(application_from_date),
                        maxDate: new Date(application_to_date),
                        widgetPositioning:{
                            horizontal: 'auto',
                            vertical: 'bottom'
                        }
                    });
                    
                    $("#shift_type"+count+"").selectpicker();

                    $("#from_date"+count+"").val(application_from_date);
                    $("#to_date"+count+"").val(application_to_date);

                    var dt1 = new Date(application_from_date);
                    var dt2 = new Date(application_to_date);
                
                    var time_difference = dt2.getTime() - dt1.getTime();
                    var result = (time_difference / (1000 * 60 * 60 * 24)) + 1;

                    $("#days_label"+count+"").html(result);
                    $("#days"+count+"").val(result);
                });

                //REMOVE ADDED Details
                $("body").delegate(".remove_detail", "click", function(event){
                    event.preventDefault();

                    var id = $(this).attr('data-id');//alert(id);
                    $(this).parents('div #'+id).remove();
                });

                $("body").delegate('.from_date, .to_date', 'dp.change', function(event){
                    //event.preventdefault();

                    var parent_div = $(this).parents('.section');
                    var fromdate = parent_div.find(".from_date").val(); 
                    //console.log('fromdate'+fromdate);
                    var todate = parent_div.find(".to_date").val();
                    //console.log('todate'+todate);

                    if(todate < fromdate){
                        $(".result").html('<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>To date should be either equal or greater than From date</div>');
                        return false;
                    }else{
                        $(".result").html('');
                    }
                    
                    if ((fromdate == "") || (todate == "")) {
                        $(".result").html('<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Please enter two dates</div>');
                        return false
                    }
                
                    var dt1 = new Date(fromdate);
                    var dt2 = new Date(todate);
                
                    var time_difference = dt2.getTime() - dt1.getTime();
                    var result = (time_difference / (1000 * 60 * 60 * 24)) + 1;
                
                    parent_div.find(".days").val(result);
                    parent_div.find(".days_label").html(result);
                   
                });

                //Shift Change
                $("body").delegate('.shift_type', 'change', function(event){
                    //event.preventdefault();

                    var leaveTime = 0;
                    var parent_div = $(this).parents('.section');
                    var shift_type = $(this).val();
                    var fromdate = parent_div.find(".from_date").val(); 
                    //console.log('fromdate'+fromdate);
                    var todate = parent_div.find(".to_date").val();
                    //console.log('todate'+todate);

                    if(todate < fromdate){
                        $(".result").html('<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>To date should be either equal or greater than From date</div>');
                        return false;
                    }else{
                        $(".result").html('');
                    }
                    if ((fromdate == "") || (todate == "")) {
                        $(".result").html('<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Please enter two dates</div>');
                        return false
                    }

                    if(shift_type == 'full_day'){
                        leaveTime = 1;
                    }else{
                        leaveTime = 0.5;
                    }
                
                    var dt1 = new Date(fromdate);
                    var dt2 = new Date(todate);
                
                    var time_difference = dt2.getTime() - dt1.getTime();
                    var result = ((time_difference / (1000 * 60 * 60 * 24)) + 1) * leaveTime;
                
                    parent_div.find(".days").val(result);
                    parent_div.find(".days_label").html(result);
                   
                });

                //click file input to upload multiple files
                $('#customFile').on("change", previewImages);
                
                /* Post Image Preview */
                function previewImages() {

                    var preview = $('.get_image_preview').empty();
                    var numFiles = this.files.length; //alert(numFiles); 

                    if(numFiles > 0) {

                        if(numFiles <= 5) {

                            if (this.files) $.each(this.files, readAndPreview);

                            if(numFiles == 0) {

                                $(this).siblings('.custom-file-label').text('Choose file');

                            }else if(numFiles == 1) {

                                var fileName = this.files[0].name;
                                $(this).siblings('.custom-file-label').text(fileName);

                            }else {

                                $(this).siblings('.custom-file-label').text(numFiles +' files'); 
                            }

                        }else {

                            $(this).siblings('.custom-file-label').text('Choose file');
                            alert('Maximum 5 files are allowed to upload');
                        }

                        function readAndPreview(i, file) {

                            if (!/\.(jpe?g|png|pdf|doc|docx)$/i.test(file.name)){
                                return alert(file.name +" is not an image");
                            } // else...

                            var reader = new FileReader();

                            $(reader).on("load", function() {
                                //$preview.append($("<img/>", {src:this.result, height:100}));
                                preview.append("<li><img src='"+this.result+"' class='rounded'></li>"); 
                            });
                            reader.readAsDataURL(file);            
                        }

                    }else {
                        $(this).siblings('.custom-file-label').text('Choose file');
                    }               
                }

                // Save subcategory
                $('body').delegate('#application_form', 'submit', function(e) { 
                    e.preventDefault();
                    //alert(0);
                    if($('#application_form').parsley().isValid()) {  

                        var btn = $('#save_application');

                        $.ajax({
                            url:"{{ url('application/update-application') }}",  
                            type:"post", 
                            dataType:"json",
                            data: new FormData(this), 
                            contentType: false,
                            processData:false,
                            beforeSend:function() {
                                btn.html('Submitting...'); 
                                //btn.attr('disabled',true);
                            },   
                            success:function(result) {
                                //alert(result);
                                //console.log(result);
                                btn.html('Submit'); 
                                //btn.attr('disabled',false);
                                if(result['signal'] == 'exist') { 

                                    $(".result").html(' <div class="alert alert-warning alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data exists</div>');
                                    
                                }else if(result['signal'] == 'inserted') { 

                                    $(".result").html(' <div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data inserted</div>');

                                    $("#employee, #leave_type").selectpicker('refresh');

                                    $('.get_image_preview').html('<img src="../uploads/sample_placeholder.png" class="rounded" style="height:80px;">');
                                    $("#customFile").siblings(".custom-file-label").text('Choose file'); 

                                    $('#application_form')[0].reset();

                                }else if(result['signal'] == 'issue_in_date'){

                                    $(".result").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>There is issue in dates</div>');

                                }else if(result['signal'] == 'invalid'){

                                    $(".result").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Invalid Data</div>');

                                }else if(result['signal'] == 'not_inserted') {

                                    $(".result").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data not inserted</div>');

                                }else if(result['signal'] == 'not_valid'){

                                    $(".result").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>'+result['data']+'</div>');

                                }else if(result['signal'] == 'no_contract') {

                                    $(".result").html(' <div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>'+result['data']+'</div>');

                                }
                            }  
                        });
                    }
                });
                
            }); 
        </script>
        @endsection

