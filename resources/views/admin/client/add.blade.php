@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Company Management</h2>
        
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Add Company</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" id="meta_form" action="{{ url('admin/client/store') }}" enctype="multipart/form-data">
                        
                        @csrf
                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Upload Company Logo</label>
                                <br/>
                                <img src="{{ URL::asset('assets/admin/img/Add-Photo-Button.png') }}" id="upload_photo_company" onclick="get_company()" style="cursor: pointer; height:100px;width:100px" class="add_img_button">
                                <input type="file" name="item_image_company" class="image-upload selected_img" id="input_upload_company" style="display: none" accept=".jpg,.jpeg,.png" onchange="show_photo_company(this)">
                            
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Company Name <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input" id="company_name" placeholder="Enter company name" name="company_name" value="{{ old('company_name') }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="Company name is required" data-prompt-position="topLeft">

                                @if($errors->has('company_name'))
                                <div class="error">{!! $errors->first('company_name') !!}</div>
                                @endif
                                
                            </div>
                        </div>
                        
                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Company Contact No. <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input" id="mobile" placeholder="Enter company contact no." name="mobile" value="{{ old('mobile') }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="Company contact no. is required" data-prompt-position="topLeft">

                                @if($errors->has('mobile'))
                                <div class="error">{!! $errors->first('mobile') !!}</div>
                                @endif
                               
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Company GST No.</label>
                                <input type="text" class="form-control banner-input" id="gst" placeholder="Enter company GST no." name="gst" value="{{ old('gst') }}" data-validation-engine="" data-errormessage-value-missing="Company gst is required" data-prompt-position="topLeft">
                                @if($errors->has('gst'))
                                <div class="error">{!! $errors->first('gst') !!}</div>
                                @endif
                               
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Challan Format</label>
                                <input type="text" class="form-control banner-input" id="challan_format" placeholder="Enter challan format ex: dd-mm-yyyy" name="challan_format" value="{{ old('challan_format') }}" data-validation-engine="" data-errormessage-value-missing="Challan format is required" data-prompt-position="topLeft">
                                @if($errors->has('challan_format'))
                                <div class="error">{!! $errors->first('challan_format') !!}</div>
                                @endif
                               
                            </div>
                        </div>

                      
                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Company Registered Address</label>
                                <textarea rows="2" cols="30" style="resize: none;"  name="address" id="address" class="form-control" data-errormessage-value-missing="Address is required" data-prompt-position="bottomLeft" placeholder="Enter company registered address" ></textarea>
                               
                            </div>
                        </div>


                        

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-12" style="display: flex; justify-content: center">
                                <a class="btn btn-info-back float-right mr-1" href="{{url('admin/vendor/list')}}">Back to List</a>
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
