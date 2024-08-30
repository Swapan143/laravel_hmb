@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Change Password</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{url('admin/dashboard')}}"><strong>Home</strong></a>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Change Password</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form class="banner-add-form" method="post" id="meta_form" action="{{ url('admin/save-change-password') }}" enctype="multipart/form-data">
                        
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id}}" />
                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Old password</label>
                                <input type="text" class="form-control banner-input" id="old_password" placeholder="Old Password" name="old_password" value="{{ old('old_password') }}" data-validation-engine=" validate[required,minSize[6],maxSize[16]]" data-errormessage-value-missing="Old Password is required" data-prompt-position="topLeft">
                                @if($errors->has('old_password'))
                                <div class="error">{!! $errors->first('old_password') !!}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>New password</label>
                                <input type="text" class="form-control banner-input" id="new_password" placeholder="New Password" name="new_password" value="{{ old('new_password') }}" data-validation-engine=" validate[required,minSize[6],maxSize[16],different:old_password]" data-errormessage-value-missing="New Password is required" data-prompt-position="topLeft">
                                @if($errors->has('new_password'))
                                <div class="error">{!! $errors->first('new_password') !!}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Confirm password</label>
                                <input type="text" class="form-control banner-input" id="confirm_password" placeholder="Confirm Password" name="confirm_password" value="{{ old('confirm_password') }}" data-validation-engine=" validate[required,minSize[6],maxSize[16],equals[new_password]]" data-errormessage-value-missing="Confirm Password is required" data-prompt-position="topLeft">
                                @if($errors->has('confirm_password'))
                                <div class="error">{!! $errors->first('confirm_password') !!}</div>
                                @endif
                                
                            </div>
                            <div class="form-group col-md-12" style="display: flex; justify-content: center">
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
