@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Vehicle Notification Management</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Edit Vehicle Notification</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" id="meta_form" action="{{ url('admin/vehicle-notification/update') }}" enctype="multipart/form-data">
                        
                        @csrf
                        <input type="hidden" name="edit_id" value="{{ $vehicle_noti_data->id }}">

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Vehicle No.<span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input validate[required]" id="vehicle_id" placeholder="Enter vehicle no." name="vehicle_id" value="{{ get_vehicle_name($vehicle_noti_data->vehicle_id) }}" data-validation-engine="" data-errormessage-value-missing="Vehicle no. is required" data-prompt-position="topLeft" readonly>
                                
                                @if($errors->has('vehicl_name'))
                                <div class="error">{!! $errors->first('vehicl_name') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                       

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Permit Valid Upto</label>
                                <input type="date" class="form-control banner-input" id="permit_valid_date" placeholder="Enter Permit Valid Upto" name="permit_valid_date" value="{{ $vehicle_noti_data->permit_valid_date }}" data-validation-engine="" data-errormessage-value-missing="Permit valid upto is required" data-prompt-position="topLeft">
                                @if($errors->has('permit_valid_date'))
                                <div class="error">{!! $errors->first('permit_valid_date') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Tax Valid Upto</label>
                                <input type="date" class="form-control banner-input" id="tax_valid_date" placeholder="Enter Tax Valid Upto" name="tax_valid_date" value="{{ $vehicle_noti_data->tax_valid_date }}" data-validation-engine="" data-errormessage-value-missing="Tax valid upto is required" data-prompt-position="topLeft">
                                @if($errors->has('tax_valid_date'))
                                <div class="error">{!! $errors->first('tax_valid_date') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Fitness Valid Upto</label>
                                <input type="date" class="form-control banner-input" id="fitness_valid_date" placeholder="Enter Fitness Valid Upto" name="fitness_valid_date" value="{{ $vehicle_noti_data->fitness_valid_date }}" data-validation-engine="" data-errormessage-value-missing="Fitness valid date is required" data-prompt-position="topLeft">
                                @if($errors->has('fitness_valid_date'))
                                <div class="error">{!! $errors->first('fitness_valid_date') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Insurance Valid Upto</label>
                                <input type="date" class="form-control banner-input" id="insurance_valid_date" placeholder="Enter Insurance Valid Upto" name="insurance_valid_date" value="{{ $vehicle_noti_data->insurance_valid_date }}" data-validation-engine="" data-errormessage-value-missing="Insurance valid upto is required" data-prompt-position="topLeft">
                                @if($errors->has('insurance_valid_date'))
                                <div class="error">{!! $errors->first('insurance_valid_date') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-12" style="display: flex; justify-content: center">
                                <a class="btn btn-info-back float-right mr-1" href="{{url('admin/vehicle-notification/list')}}">Back to List</a>
                                <button class="btn btn-info btn-banner float-right" type="submit"></i>Save</button>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() 
    {
        $("#meta_form").validationEngine();
    })
    //  $(document).ready(function() {
    //     $('#name').attr("disabled", true);
    // })
    function showPreview(event) {
        if (event.target.files.length > 0) {
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("file-ip-1-preview");
            preview.src = src;
            preview.style.display = "block";
        }
    }
</script>
@endsection
