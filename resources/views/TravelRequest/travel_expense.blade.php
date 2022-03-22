
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
                                    <input type="hidden" name="travel_request_id" value="{{$requestData->id}}">
                                    <div class="form-group row">
                                        <div class="form-group col-md-3">
                                            <label for="inputPassword3" class="col-form-label">Employee <span class="text-danger">*</span></label>
                                            
                                            <input type="text" class="form-control" name="employee_name" id="employee_name" placeholder="Employee" value="{{$empData->name}}" autocomplete="off" readonly>
                                            <input type="hidden" class="form-control" name="employee" id="employee" value="{{$empData->id}}">

                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="inputEmail3" class="col-form-label">Month</label>
                                            <input type="text" class="form-control" name="travel_month" id="travel_month" placeholder="For the month of" autocomplete="off" required>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="inputEmail3" class="col-form-label">PO</label>
                                            <input type="text" class="form-control" name="po" id="po" placeholder="PO" value="{{$requestData->po}}" autocomplete="off" readonly>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="inputEmail3" class="col-form-label">Misc. Expenses</label>
                                            <input type="text" class="form-control" name="misc_expense" id="misc_expense" placeholder="Misc. Expenses" autocomplete="off">
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
                                        <input type="hidden" name="requestCount" id="requestCount" value="{{count($travelRequestDetail)}}">
                                        @foreach($travelRequestDetail as $key => $detail)
                                        <div class="section" id="{{$key + 1}}">
                                            <div class="form-group row">
                                                <div class="form-group col-md-3">
                                                    <label for="inputEmail3" class="col-form-label">Place of night halt <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="night_halt[]" placeholder="Place of night halt" value="{{$detail->place_of_halt}}" autocomplete="off" readonly>
                                                </div>
                                                
                                                <div class="form-group col-md-3">
                                                    <label for="inputPassword3" class="col-form-label">Deduction <span class="text-danger">*</span></label>
                                                    <select class="form-control deduction_id" name="deduction[]" data-parsley-errors-container=".deductionError" title="-- Select Deduction Type --">
                                                        @foreach($allDeductions as $value)
                                                            <option value="{{$value->id}}">{{$value->deduction_item}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="deductionError"></div>
                                                </div>
                                            
                                                <div class="form-group col-md-3">
                                                    <label for="inputPassword3" class="col-form-label">From Date <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control from_date" name="from_date[]" placeholder="YYYY-MM-DD"  value="{{$detail->from_date}}" required>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label for="inputPassword3" class="col-form-label">To Date <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control to_date" name="to_date[]" placeholder="YYYY-MM-DD" value="{{$detail->to_date}}" required> 
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label for="inputPassword3" class="col-form-label">Per Diem Rate <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control per_diem_rate" name="per_diem_rate[]" placeholder="Per Diem Rate" autocomplete="off" disabled required>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label for="inputPassword3" class="col-form-label">No of days <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control no_of_days" name="no_of_days[]" placeholder="No of days" readonly> 
                                                </div>
                                                
                                                <div class="form-group col-md-3">
                                                    <label for="inputPassword3" class="col-form-label">Deduction Percentage</label>
                                                    <input type="text" class="form-control deduction_per" name="deduction_per[]" placeholder="Deduction Percentage" readonly> 
                                                    <input type="hidden" class="form-control deduction_amount" name="deduction_amount[]" value=""> 
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label for="inputPassword3" class="col-form-label">Total Amount <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control total_amount" name="total_amount[]" placeholder="Total Amount" readonly> 
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-12 text-right">
                                                    <button class="btn btn-danger btn-sm remove_detail" data-id="{{$key + 1}}" >Delete</button>
                                                </div>
                                            </div>

                                        </div>
                                        @endforeach
                                    </div>
                                            
                                    <div class="form-group row text-right mb-0">
                                        <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                                            
                                            <button type="submit" class="btn btn-info btn-md" id="save_travel">Make Payment</button>
                                            
                                            <a type="button" id="invoice_btn" class="btn btn-info btn-md d-none" target="_blank" href="{{url('travel/dynamic_pdf/pdf/$requestData->id')}}" tooltip="Pdf Download" data-id="'.$travelData->id.'"><i class="bx bxs-download bx-sm"></i> View Invoice</a>
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
            var count = $("#requestCount").val();

            $("body").delegate("#add_more", "click", function(event){
                event.preventDefault();

                count++;
                var html = '';
                html +='<div class="section" id="'+count+'">';
                html += '<div class="form-group row">';
                html += '<div class="form-group col-md-3">';
                html += '<label for="inputEmail3" class="col-form-label">Place of night halt <span class="text-danger">*</span></label>';
                html += '<input type="text" class="form-control" name="night_halt[]" placeholder="Place of night halt" autocomplete="off" required>';
                html += '</div>';
                html += '<div class="form-group col-md-3">';
                html += '<label for="inputPassword3" class="col-form-label">Deduction</label>';
                html += '<select class="form-control deduction_id" name="deduction[]" data-parsley-errors-container=".deductionError" title="-- Select Deduction Type --">';
                @foreach($allDeductions as $value)
                html += '<option value="{{$value->id}}">{{$value->deduction_item}}</option>';
                @endforeach
                html += '</select>';
                html += '<div class="deductionError"></div>';
                html += '</div>';
                html += '<div class="form-group col-md-3">';
                html += '<label for="inputPassword3" class="col-form-label">From Date <span class="text-danger">*</span></label>';
                html += '<input type="text" class="form-control from_date" name="from_date[]" placeholder="YYYY-MM-DD" required>';
                html += '</div>';
                html += '<div class="form-group col-md-3">';
                html += '<label for="inputPassword3" class="col-form-label">To Date <span class="text-danger">*</span></label>';
                html += '<input type="text" class="form-control to_date" name="to_date[]" placeholder="YYYY-MM-DD" required>'; 
                html += '</div>';
                html += '<div class="form-group col-md-3">';
                html += '<label for="inputPassword3" class="col-form-label">Per Diem Rate <span class="text-danger">*</span></label>';
                html += '<input type="text" class="form-control per_diem_rate" name="per_diem_rate[]" placeholder="Per Diem Rate" autocomplete="off" disabled required>';
                html += '</div>';
                html += '<div class="form-group col-md-3">';
                html += '<label for="inputPassword3" class="col-form-label">No of days <span class="text-danger">*</span></label>';
                html += '<input type="text" class="form-control no_of_days" name="no_of_days[]" placeholder="No of days" readonly>'; 
                html += '</div>';
                html += '<div class="form-group col-md-3">';
                html += '<label for="inputPassword3" class="col-form-label">Deduction Percentage</label>';
                html += '<input type="text" class="form-control deduction_per" name="deduction_per[]" placeholder="Deduction Percentage" readonly>'; 
                html += '<input type="hidden" class="form-control deduction_amount" name="deduction_amount[]" value="">'; 
                html += '</div>';
                html += '<div class="form-group col-md-3">';
                html += '<label for="inputPassword3" class="col-form-label">Total Amount <span class="text-danger">*</span></label>';
                html += '<input type="text" class="form-control total_amount" name="total_amount[]" placeholder="Total Amount" readonly>'; 
                html += '</div>';
                html += '</div>';
                html += '<div class="form-group row">';
                html += '<div class="col-md-12 text-right">';
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
                $(".deduction_id").selectpicker();
            });

            //REMOVE ADDED Details
            $("body").delegate(".remove_detail", "click", function(event){
                event.preventDefault();

                var id = $(this).attr('data-id');//alert(id);
                $(this).parents('div #'+id).remove();
            });

            $("#employee, .deduction_id").selectpicker();

            $(".from_date, .to_date").datetimepicker({
                format: 'YYYY-MM-DD',
                widgetPositioning:{
                    horizontal: 'auto',
                    vertical: 'bottom'
                }
            }); 

            //function to get no_of_days
            function getDaysCount(fromdate='', todate=''){

                var dt1 = new Date(fromdate);
                var dt2 = new Date(todate);
            
                var time_difference = dt2.getTime() - dt1.getTime();
                var daysCount = (time_difference / (1000 * 60 * 60 * 24));

                return daysCount;
            }

            $('.to_date').each(function(){
                
                var parent_div = $(this).parents('.section');
                var fromdate = parent_div.find(".from_date").val();
                var todate = $(this).val();

                var result = getDaysCount(fromdate, todate);

                parent_div.find(".no_of_days").val(result);
                getTotalAmount(parent_div);

            });

            //TOTAL AMOUNT
            function getTotalAmount(parent_div){

                var amount = 0;
                var totalAmount = 0;
                var deductionAmount = 0;
                var deduction_per = 0;
                var per_diem_rate = parent_div.find(".per_diem_rate").val();
                var no_of_days = parent_div.find(".no_of_days").val();
                deduction_per = parent_div.find(".deduction_per").val();//alert(deduction_per);

                amount = (per_diem_rate * no_of_days);

                if(deduction_per === ''){
                    totalAmount = parseInt(amount);
                    
                }else{
                    totalAmount = parseInt(amount) - parseInt(((amount * deduction_per)/100));
                    deductionAmount = parseInt(((amount * deduction_per)/100));
                }
                //alert(totalAmount);
                parent_div.find(".deduction_amount").val(deductionAmount);
                parent_div.find(".total_amount").val(totalAmount);

            }

            //deduction percentage function
            function getDeductionPercentage(deduction_id, parent_div){

                $.ajax({
                    url:"{{ url('travel/get-deduction') }}",   
                    type:"post", 
                    dataType:"json",
                    data: {deduction_id}, 
                    success:function(response){
                        //alert(response);
                        //console.log(response);
                        parent_div.find(".deduction_per").val(response);  
                        getTotalAmount(parent_div);                      
                    }
                });
            }
            
            //DEDUCTION
            $('body').delegate('.deduction_id',"change", function(event){
                event.preventDefault();

                var deduction_id = $(this).val(); //alert(deduction_id);
                var parent_div = $(this).parents('.section');//alert(parent_div);

                if(deduction_id !=''){
                    getDeductionPercentage(deduction_id, parent_div);                    
                    parent_div.find(".per_diem_rate").prop('disabled', false);
                }
            });

            $('body').delegate('.per_diem_rate', 'keyup', function(e){
                e.preventDefault();
                //alert($(this).val());
                var parent_div = $(this).parents('.section');
                getTotalAmount(parent_div);
            });

            

            $("body").delegate('.to_date', 'dp.change', function(event){
                //event.preventdefault();

                var parent_div = $(this).parents('.section');
                var fromdate = parent_div.find(".from_date").val();
                var todate = $(this).val();

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
                var result = (time_difference / (1000 * 60 * 60 * 24));
            
                parent_div.find(".no_of_days").val(result);
                getTotalAmount(parent_div);
            });

            $("body").delegate('.from_date', 'dp.change', function(event){
                //event.preventdefault();

                var parent_div = $(this).parents('.section');
                var fromdate = $(this).val();
                var todate = parent_div.find(".to_date").val();

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
                var result = (time_difference / (1000 * 60 * 60 * 24));
            
                parent_div.find(".no_of_days").val(result);
                getTotalAmount(parent_div);
            });


            $("#travel_form").parsley();


            // Save subcategory
            $('body').delegate('#travel_form', 'submit', function(e) { 
                e.preventDefault();
                //alert(0);
                if($('#travel_form').parsley().isValid()) {  

                    var btn = $('#save_travel');
                    
                        $.ajax({
                            url:"{{ url('travel/save-travel') }}",  
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

                                    $(".result").html(' <div class="alert alert-warning alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data exists</div>');

                                }else if(result == 'inserted') { 

                                    $(".result").html(' <div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data inserted</div>');
                                    //$("#employee, .deduction_id").selectpicker('refresh');
                                    
                                    //$('#travel_form')[0].reset();
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

