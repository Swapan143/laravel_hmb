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
                    <h5>Add Vehicle Notification</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" id="meta_form" action="{{ url('admin/vehicle-notification/store') }}" enctype="multipart/form-data">
                        
                        @csrf
        
                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6 custom-option">
                                <label>Vehicle No. <span style="color:red">*</span></label>
                                <select name="vehicle_id" id="vehicle_id" class="form-control banner-input js-example-basic-search" data-validation-engine="validate[required]" data-errormessage-value-missing="vehicl No. is required" data-prompt-position="bottomLeft">
                                    <option selected disabled>Select Vehicle No.</option>
                                    @foreach($vehicle_data as $key => $vehicle_row)
                                    <option value="<?=$vehicle_row->id?>"><?=$vehicle_row->vehicle_no ?><?php if(!empty($vehicle_row->vehicle_name)){ echo " - ".$vehicle_row->vehicle_name; } ?></option>
                                    @endforeach
                                </select>
                                @if($errors->has('vehicl_name'))
                                <div class="error">{!! $errors->first('vehicl_name') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                       

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Permit Valid Upto</label>
                                <input type="date" class="form-control banner-input" id="permit_valid_date" placeholder="Enter Permit Valid Upto" name="permit_valid_date" value="{{ old('permit_valid_date') }}" data-validation-engine="" data-errormessage-value-missing="Permit valid upto is required" data-prompt-position="topLeft">
                                @if($errors->has('permit_valid_date'))
                                <div class="error">{!! $errors->first('permit_valid_date') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Tax Valid Upto</label>
                                <input type="date" class="form-control banner-input" id="tax_valid_date" placeholder="Enter Tax Valid Upto" name="tax_valid_date" value="{{ old('tax_valid_date') }}" data-validation-engine="" data-errormessage-value-missing="Tax valid upto is required" data-prompt-position="topLeft">
                                @if($errors->has('tax_valid_date'))
                                <div class="error">{!! $errors->first('tax_valid_date') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Fitness Valid Upto</label>
                                <input type="date" class="form-control banner-input" id="fitness_valid_date" placeholder="Enter Fitness Valid Upto" name="fitness_valid_date" value="{{ old('fitness_valid_date') }}" data-validation-engine="" data-errormessage-value-missing="Fitness valid date is required" data-prompt-position="topLeft">
                                @if($errors->has('fitness_valid_date'))
                                <div class="error">{!! $errors->first('fitness_valid_date') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Insurance Valid Upto</label>
                                <input type="date" class="form-control banner-input" id="insurance_valid_date" placeholder="Enter Insurance Valid Upto" name="insurance_valid_date" value="{{ old('insurance_valid_date') }}" data-validation-engine="" data-errormessage-value-missing="Insurance valid upto is required" data-prompt-position="topLeft">
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
    
</script>
@endsection
