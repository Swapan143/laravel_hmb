@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>User Edit</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Edit User</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" id="meta_form" action="{{ url('admin/user/update') }}" enctype="multipart/form-data">
                        
                        @csrf
                        <input type="hidden" name="edit_id" value="{{ $user->id }}">

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>First Name <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input only_character" id="first_name" placeholder="Enter first name" name="first_name" value="{{ $user->first_name }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="First name is required" data-prompt-position="topLeft" maxlength="30">
                                @if($errors->has('first_name'))
                                <div class="error">{!! $errors->first('first_name') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Last Name <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input only_character" id="last_name" placeholder="Enter last name" name="last_name" value="{{ $user->last_name }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="Last name is required" data-prompt-position="topLeft" maxlength="30">
                                @if($errors->has('last_name'))
                                <div class="error">{!! $errors->first('last_name') !!}</div>
                                @endif
                               
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Email <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input" id="email" placeholder="Enter email" name="email" value="{{ $user->email }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="Email is required" data-prompt-position="topLeft" maxlength="100">
                                @if($errors->has('email'))
                                <div class="error">{!! $errors->first('email') !!}</div>
                                @endif
                               
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Mobile Number <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input only_integer" id="mobile" placeholder="Enter mobile number" name="mobile" value="{{ $user->mobile }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="Mobile number is required" data-prompt-position="topLeft" maxlength="10">
                                @if($errors->has('mobile'))
                                <div class="error">{!! $errors->first('mobile') !!}</div>
                                @endif
                               
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6 custom-option">
                                <label>Admin Role <span style="color:red">*</span></label>
                                <select name="role_id" id="role_id" class="form-control banner-input validate[required]" data-errormessage-value-missing="User role is required" data-prompt-position="bottomLeft">
                                    <option value="">Select role</option>
                                    @foreach($role_data as $key => $role)
                                    <option value="<?=$role->id?>" <?= ($role->id==$user->role_id)?'selected':'' ?>><?=$role->role_name?></option>
                                    @endforeach
                                </select>
                                @if($errors->has('role_id'))
                                <div class="error">{!! $errors->first('role_id') !!}</div>
                                @endif
                               
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6 custom-option">
                                <label>User Role <span style="color:red">*</span></label>
                                <select name="type" id="type" class="form-control banner-input" data-validation-engine="validate[required]" data-errormessage-value-missing="User permission type is required" data-prompt-position="bottomLeft">
                                    <option disabled>Select User permission type</option>
                                    
                                    <option value="1" <?= ($user->type=='1')?'selected':'' ?>>Admin</option>
                                    <option value="2" <?= ($user->type=='2')?'selected':'' ?> >Challan</option>
                                    <option value="3" <?= ($user->type=='3')?'selected':'' ?> >Diesel</option>
                                    <option value="4" <?= ($user->type=='4')?'selected':'' ?> >ChallanAndDiesel</option>
                                    
                                </select>
                                @if($errors->has('type'))
                                <div class="error">{!! $errors->first('type') !!}</div>
                                @endif
                               
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-12" style="display: flex; justify-content: center">
                                <a class="btn btn-info-back float-right mr-1" href="{{url('admin/user/list')}}">Back to List</a>
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
