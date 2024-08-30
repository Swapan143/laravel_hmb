@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Transporter Management</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Edit Transporter</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" id="meta_form" action="{{ url('admin/vendor/update') }}" enctype="multipart/form-data">
                        
                        @csrf
                        <input type="hidden" name="edit_id" value="{{ $vendor->id }}">

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Transporter Name <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input" id="transporter_name" placeholder="Enter transporter name" name="transporter_name" value="{{ $vendor->transporter_name }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="Transporter name is required" data-prompt-position="topLeft">
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Company Name</label>
                                <input type="text" class="form-control banner-input" id="company_name" placeholder="Enter company name" name="company_name" value="{{ $vendor->company_name }}" data-validation-engine="" data-errormessage-value-missing="Company name is required" data-prompt-position="topLeft">
                               
                            </div>
                        </div>


                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Mobile Number <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input" id="mobile" placeholder="Enter mobile number" name="mobile" value="{{ $vendor->mobile }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="Mobile number is required" data-prompt-position="topLeft">
                               
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Email <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input" id="email" placeholder="Enter email" name="email" value="{{ $vendor->email }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="Email is required" data-prompt-position="topLeft">
                               
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Address</label>
                                <textarea rows="2" cols="30" style="resize: none;"  name="address" id="address" class="form-control" data-errormessage-value-missing="Address is required" data-prompt-position="bottomLeft" placeholder="Enter address" >{{ $vendor->address }}</textarea>
                               
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
