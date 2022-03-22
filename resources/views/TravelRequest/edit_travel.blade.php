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
                            <span>Edit Travel Detail</span>  
                        </div>
                    </div><!-- /.col-md-5 -->

                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-dark" href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a class="text-dark" href="index.php">Travel</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div><!-- /.col-md-7 -->
                </div><!-- /.row --> 
                
                <div class="container-fluid">  
                    <div class="row">
                        <div class="col-md-12 mb-4 mt-2 text-right">
                            <a href="{{ url('travel') }}" class="btn btn-default btn-icon mr-1" tooltip="Back"><i class="bx bx-undo f-s-14 align-middle m-b-2"></i></a> 
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Edit Travel Detail</h4>
                                    <form id="travel_form" enctype="multipart/form-data">
                                        <div class="result"></div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="inputPassword3" class="col-form-label">Employee <span class="text-danger">*</span></label>

                                                <input type="text" class="form-control" value="{{ $employee->name }}" placeholder="For the month of" autocomplete="off" readonly>

                                                <input type="hidden" class="form-control" name="travel_id" value="{{ $travelData->id }}" id="travel_id" placeholder="For the month of" autocomplete="off" required>
                                                
                                            </div>

                                            <div class="col-md-3">
                                                <label for="inputEmail3" class="col-form-label">Month</label>
                                                <input type="text" class="form-control" name="travel_month" value="{{ $travelData->travel_month }}" id="travel_month" placeholder="For the month of" autocomplete="off" required>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="inputEmail3" class="col-form-label">PO</label>
                                                <input type="text" class="form-control" name="po" id="po" placeholder="PO" value="{{ $travelData->PO }}" autocomplete="off" required>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="inputEmail3" class="col-form-label">Misc. Expenses</label>
                                                <input type="text" class="form-control" name="misc_expense" id="misc_expense" value="{{ $travelData->misc_expense }}" placeholder="Misc. Expenses" autocomplete="off" required>
                                            </div>
                                        </div>

                                        <div class="travel_detail_section">
                                            @foreach($travelDetail as $key => $travel)

                                            <div class="section" id="{{$key+1}}">
                                                <div class="form-group row">

                                                    <div class="form-group col-md-3">
                                                        <label for="inputEmail3" class="col-form-label">Place of night halt <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="night_halt" id="night_halt" value="{{ $travel->night_halt }}" placeholder="Place of night halt" autocomplete="off" required>
                                                    </div>
                                                    
                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword3" class="col-form-label">Deduction</label>
                                                        <select class="form-control" name="deduction" id="deduction" required data-parsley-errors-container=".leavetypeError" title="-- Select Deduction Type --">
                                                            @foreach($allDeductions as $value)
                                                                <option value="{{$value->id}}" @if($value->id == $travel->deduction_id) {{"selected"}} @endif>{{$value->deduction_item}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="leavetypeError"></div>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword3" class="col-form-label">From Date <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="from_date" id="from_date" value="{{ $travel->date_from }}"  placeholder="YYYY-MM-DD" required>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword3" class="col-form-label">To Date <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="to_date" id="to_date" value="{{ $travel->date_to }}"  placeholder="YYYY-MM-DD" required> 
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword3" class="col-form-label">No of days <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="no_of_days" id="no_of_days" value="{{ $travel->no_of_days }}"  placeholder="No of days" readonly> 
                                                    </div>
                                                    
                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword3" class="col-form-label">Per Diem Rate <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="per_diem_rate" id="per_diem_rate" value="{{ $travel->per_deim_rate }}"  placeholder="Per Diem Rate" required>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword3" class="col-form-label">Deduction Percentage</label>
                                                        <input type="text" class="form-control" name="deduction_per" value="{{$selectedDeduction->deduction}}" placeholder="Deduction Percentage" readonly> 
                                                        <input type="hidden" class="form-control" name="deduction_amount" id="deduction_amount" value="{{ $travel->deduction_amount }}" > 
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword3" class="col-form-label">Total Amount <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="total_amount" id="total_amount" placeholder="Total Amount" value="{{ $travel->total_amount }}"  readonly> 
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
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

                $("#employee, #deduction").selectpicker();

                $("#from_date, #to_date").datetimepicker({
                    format: 'YYYY-MM-DD',
                    widgetPositioning:{
                        horizontal: 'auto',
                        vertical: 'bottom'
                    }
                });

                $("#to_date").on('dp.change', function(event){
                    //event.preventdefault();

                    var fromdate = $("#from_date").val();
                    var todate = $("#to_date").val();
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
                
                    $("#no_of_days").val(result);
                });

                //DEDUCTION
                var ded_id = $("#deduction").val();
                getDeduction(ded_id);
                $("#deduction").on('change', function(event){
                    event.preventDefault();

                    var deduction_id = $(this).val();
                    getDeduction(deduction_id);
                    
                });

                //function deduction
                function getDeduction(id){
                    $.ajax({
                        url:"{{ url('travel/get-deduction') }}",  
                        type:"post", 
                        dataType:"json",
                        data: {deduction_id:id}, 
                        success:function(response){
                            $("#deduction_per").val(response);
                        }
                    });
                }

                //TOTAL AMOUNT
                $("#per_diem_rate").on('keyup', function(e){
                    e.preventDefault();

                    var amount = 0;
                    var totalAmount = 0;
                    var per_diem_rate = $(this).val();
                    var no_of_days = $("#no_of_days").val();
                    var deduction_per = $("#deduction_per").val();

                    amount = (per_diem_rate * no_of_days);
                    totalAmount = parseInt(amount) - parseInt(((amount * deduction_per)/100));
                    $("#deduction_amount").val(parseInt(((amount * deduction_per)/100)));
                    $("#total_amount").val(totalAmount);

                });


                $("#travel_form").parsley();


                // Save subcategory
                $('body').delegate('#travel_form', 'submit', function(e) { 
                    e.preventDefault();
                    //alert(0);
                    if($('#travel_form').parsley().isValid()) {  

                        var btn = $('#save_travel');

                        $.ajax({
                            url:"{{ url('travel/update-travel') }}",  
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
                                    $(".result").html(' <div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Data Updated</div>');

                                    $("#deduction").selectpicker('refresh');
                                    
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

