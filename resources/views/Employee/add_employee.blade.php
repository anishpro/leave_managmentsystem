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
                            <span>Add Employee</span>  
                        </div>
                    </div><!-- /.col-md-5 -->

                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-dark" href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a class="text-dark" href="index.php">Employee</a></li>
                            <li class="breadcrumb-item active">Add Employee</li>
                        </ol>
                    </div><!-- /.col-md-7 -->
                </div><!-- /.row --> 
                
                <div class="container-fluid">  
                    <div class="row">
                        <div class="col-md-12 mb-4 mt-2 text-right">
                            <a href="{{ url('employees')}}" class="btn btn-default btn-icon mr-1" tooltip="Back"><i class="bx bx-undo f-s-14 align-middle m-b-2"></i></a> 
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Add Employee</h4>

                                    <form id="employee_form" enctype="multipart/form-data">
                                        <div class="result"></div>

                                        <div class="form-group row">

                                            <div class="col-md-4">
                                                <label for="inputEmail3" class="col-form-label">Employee Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="employee" id="employee" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" placeholder="Enter Employee Name" autocomplete="off" required>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="inputEmail3" class="col-form-label">Employee Code <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="employee_code" id="employee_code" placeholder="Enter Employee Code" autocomplete="off" required>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="inputEmail3" class="col-form-label">Username <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="username" id="username" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" placeholder="Enter Username" autocomplete="off" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <div class="col-md-4">
                                                <label for="inputEmail3" class="col-form-label">Position <span class="text-danger">*</span></label>
                                                <select class="form-control" name="position" id="position" required data-parsley-errors-container=".positionError" title="-- Select Position --" data-live-search="true" data-size="5">';
                                                    @foreach($allPositions as $value)
                                                        <option value="{{$value->id}}">{{$value->position}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="positionError"></div>
                                            </div>   

                                            <div class="col-md-4">
                                                <label for="inputEmail3" class="col-form-label">Role <span class="text-danger">*</span></label>
                                                <select class="form-control" name="role_id" id="role_id" required data-parsley-errors-container=".roleError" title="-- Select Role --" data-live-search="true" data-size="5">';
                                                    @foreach($allRoles as $value)
                                                        <option value="{{$value->id}}">{{ucwords($value->role)}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="roleError"></div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label for="inputEmail3" class="col-form-label">Duty Station <span class="text-danger">*</span></label>
                                                <select class="form-control" name="duty_station" id="duty_station" required data-parsley-errors-container=".dutyError" title="-- Select Position --" data-live-search="true" data-size="5">';
                                                    @foreach($allStations as $value)
                                                        <option value="{{$value->id}}">{{$value->work_place}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="dutyError"></div>
                                            </div>
                                        </div>

                                        <div class="form-group row">   

                                            <div class="col-md-4">
                                                <label for="inputPassword3" class="col-form-label">Group <span class="text-danger">*</span></label>
                                                <select class="form-control" name="group_id" id="group_id" required data-parsley-errors-container=".productError" title="-- Select Group --" data-live-search="true" data-size="5">';
                                                    @foreach($allGroups as $value)
                                                        <option value="{{$value->id}}">{{ucfirst($value->group_name)}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="productError"></div>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="inputEmail3" class="col-form-label">Contact <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="contact" id="contact" onkeypress="return event.charCode >= 48 && event.charCode <= 57" minLength="10" maxLength="10" placeholder="Enter Contact Number" autocomplete="off" required>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="inputEmail3" class="col-form-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" autocomplete="off" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label for="inputEmail3" class="col-form-label">Address</label>
                                                <textarea class="form-control" name="address" id="address" placeholder="Address" autocomplete="off" rows="2"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">   
                                            <div class="col-md-6">
                                                <label for="inputEmail3" class="col-form-label">Signature</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="signature" id="customSignatureFile" accept=".jpg,.jpeg,.png">
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                </div> 
                                                <div class="text-muted small mt-2">valid Ext: jpg, jpeg, png</div>
                                                <div class="get_signature_preview mt-3">
                                                    <img src="../uploads/sample_placeholder.png" class="rounded" style="height:80px;">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="inputEmail3" class="col-form-label">Profile Image</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="attachment" id="customFile" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                </div> 
                                                <div class="text-muted small mt-2">valid Ext: jpg, jpeg, png</div>
                                                <div class="get_image_preview mt-3"> 
                                                    <img src="../uploads/sample_placeholder.png" class="rounded" style="height:80px;">
                                                </div>
                                            </div> 
                                        </div>
                                                
                                        <div class="form-group row text-right mb-0">
                                            <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-info btn-md" id="save_employee">Submit</button>
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

                $("#group_id, #position, #duty_station, #role_id").selectpicker();

                $("#employee_form").parsley();

                //click file input to upload multiple files
                $('#customFile').on("change", previewImages);
                $('#customSignatureFile').on("change", previewSignatureImages);

                /* Post Image Preview */
                function previewSignatureImages() {

                    var preview = $('.get_signature_preview').empty();
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

                // Save New Group
                $('body').delegate('#employee_form', 'submit', function(e) { 
                    e.preventDefault();
                    //alert(0);
                    if($('#employee_form').parsley().isValid()) {  

                        var btn=$('#save_employee');

                        $.ajax({
                            url:"{{ url('employees/insert-employee') }}",  
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
                                if(result == 'exist') { 
                                    $(".result").html(' <div class="alert alert-warning alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Email exists</div>');
                                }else if(result == 'inserted') { 
                                    $(".result").html(' <div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data inserted</div>');

                                    $('.get_image_preview').html('<img src="../uploads/sample_placeholder.png" class="rounded" style="height:80px;">');
                                    $("#customFile").siblings(".custom-file-label").text('Choose file');

                                    $("#employeeTable").dataTable().fnDestroy();
                                    
                                    $('#employee_form')[0].reset();

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

