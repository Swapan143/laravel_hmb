@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Vehicle Management</h2>
        
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Add Vehicle</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" id="meta_form" action="{{ url('admin/vehicle/store') }}" enctype="multipart/form-data">
                        
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Vehicle No <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input number_character" id="vehicle_no" placeholder="Enter vehicle no" name="vehicle_no" value="{{ old('vehicle_no') }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="Vehicle no is required" data-prompt-position="topLeft" maxlength="10">
                                @if($errors->has('vehicle_no'))
                                <div class="error">{!! $errors->first('vehicle_no') !!}</div>
                                @endif
                               
                            </div>
                            <div class="form-group col-md-6">
                                <label>Upload Vehicle Image</label>
                                
                                <img src="{{ URL::asset('assets/admin/img/Add-Photo-Button.png') }}" id="upload_photo_vehicle" onclick="get_vehicle()" style="cursor: pointer; height:100px;width:100px" class="add_img_button">
                                <input type="file" name="item_image_vehicle" class="image-upload selected_img" id="item_image_vehicle" style="display: none" accept=".jpg,.jpeg,.png" onchange="show_photo_vehicle(this)">
                            
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        

                        <div class="row" >
                            <div class="form-group col-md-6">
                                <label>Vehicle Name</label>
                                <input type="text" class="form-control banner-input only_character" id="vehicle_name" placeholder="Enter vehicle name" name="vehicle_name" value="{{ old('vehicle_name') }}" data-validation-engine="" data-errormessage-value-missing="Vehicle name is required" data-prompt-position="topLeft" maxlength="50">
                                @if($errors->has('vehicle_name'))
                                <div class="error">{!! $errors->first('vehicle_name') !!}</div>
                                @endif
                               
                            </div>
                            <div class="form-group col-md-6">
                                <label>Vehicle Chassis No. <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input number_character" id="vehicle_chassis_no" placeholder="Enter vehicle chassis no." name="vehicle_chassis_no" value="{{ old('vehicle_chassis_no') }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="Vehicle chassis no is required" data-prompt-position="topLeft" maxlength="20">
                                @if($errors->has('vehicle_chassis_no'))
                                <div class="error">{!! $errors->first('vehicle_chassis_no') !!}</div>
                                @endif
                               
                            </div>

                            <div class="form-group col-md-6">
                                <label>Engine Number</label>
                                <input type="text" class="form-control banner-input number_character" id="engine_number" placeholder="Enter engine number" name="engine_number" value="{{ old('engine_number') }}" data-validation-engine="" data-errormessage-value-missing="Engine number is required" data-prompt-position="topLeft"  maxlength="50">
                                @if($errors->has('engine_number'))
                                <div class="error">{!! $errors->first('engine_number') !!}</div>
                                @endif
                                
                            </div>

                            <div class="form-group col-md-6 custom-option">
                                <label>Transporter Name <span style="color:red">*</span></label>
                                <select name="transporter_name" id="transporter_name" class="form-control banner-input" data-validation-engine="validate[required]" data-errormessage-value-missing="Transporter name is required" data-prompt-position="bottomLeft">
                                    <option selected disabled>Select transporter name</option>
                                    @foreach($transporter_data as $key => $transporter_row)
                                    <option value="<?=$transporter_row->id?>"><?=$transporter_row->transporter_name?></option>
                                    @endforeach
                                </select>
                                @if($errors->has('transporter_name'))
                                <div class="error">{!! $errors->first('transporter_name') !!}</div>
                                @endif
                                
                            </div>

                            <div class="form-group col-md-6 custom-option">
                                <label>This vehicle serve for the company <span style="color:red">*</span></label>
                                <select name="client_name" id="client_name" class="form-control banner-input" data-validation-engine="validate[required]" data-errormessage-value-missing="Client name is required" data-prompt-position="bottomLeft" onchange="sidingLocation()">
                                    <option selected disabled>Select company name</option>
                                    @foreach($client_data as $key => $client_row)
                                    <option value="<?=$client_row->id?>"><?=$client_row->company_name?></option>
                                    @endforeach
                                </select>
                                @if($errors->has('client_name'))
                                <div class="error">{!! $errors->first('client_name') !!}</div>
                                @endif
                                
                            </div>

                            <div class="form-group col-md-6 custom-option">
                                <label>Vehicle Siding Location <span style="color:red">*</span></label>
                                <select name="vehicle_siding_location" id="vehicle_siding_location" class="form-control banner-input" data-validation-engine="validate[required]" data-errormessage-value-missing="Vehicle siding location is required" data-prompt-position="bottomLeft">
                                    
                                    
                                </select>
                                @if($errors->has('vehicle_siding_location'))
                                <div class="error">{!! $errors->first('vehicle_siding_location') !!}</div>
                                @endif
                                
                            </div>

                            <div class="form-group col-md-6">
                                <label>Vehicle Owner Name</label>
                                <input type="text" class="form-control banner-input only_character" id="owner_name" placeholder="Enter owner name" name="owner_name" value="{{ old('owner_name') }}" data-validation-engine="" data-errormessage-value-missing="Owner name is required" data-prompt-position="topLeft" maxlength="50">
                                @if($errors->has('owner_name'))
                                <div class="error">{!! $errors->first('owner_name') !!}</div>
                                @endif
                                
                            </div>

                            <div class="form-group col-md-6">
                                <label>Contact Number</label>
                                <input type="text" class="form-control banner-input only_integer" id="contact_number" placeholder="Enter contact number" name="contact_number" value="{{ old('contact_number') }}" data-validation-engine="" data-errormessage-value-missing="Contact number is required" data-prompt-position="topLeft"  maxlength="50">
                                @if($errors->has('contact_number'))
                                <div class="error">{!! $errors->first('contact_number') !!}</div>
                                @endif
                                
                            </div>

                            <div class="form-group col-md-6">
                                <label>Vehicle allotted on</label>
                                <input type="date" class="form-control banner-input" id="vehicle_added_on" placeholder="Enter vehicle allotted on date" name="vehicle_added_on" value="{{ old('vehicle_added_on') }}" data-validation-engine="" data-errormessage-value-missing="Vehicle allotted on date is required" data-prompt-position="topLeft">
                                @if($errors->has('vehicle_added_on'))
                                <div class="error">{!! $errors->first('vehicle_added_on') !!}</div>
                                @endif
                                
                            </div>

                        </div>

                        <div class="clearfix"></div>
                        <div class="row" style="display: flex; justify-content: center">
                            <a class="btn btn-info-back float-right mr-1" href="{{url('admin/vehicle/list')}}">Back to List</a>
                            <button class="btn btn-info btn-banner float-right" type="submit"></i>Save</button>
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
    });

    function sidingLocation()
    {
        var client_name = $('#client_name').val();
    
        var url = $('#url').val();
        $.ajax({
            url:url+'/admin/vehicle/siding-location',
            type:'GET',
            data:{
            "_token": $('#csrf_token').val(),
            client_name:client_name
            },
            success: function(data)
            {
                $('#vehicle_siding_location').html("");
                $('#vehicle_siding_location').html(data);
                
            }
        });
        
    }
    
</script>

@endsection
