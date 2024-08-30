@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Role Management</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Edit Role</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" id="meta_form" action="{{ url('admin/role/update') }}" enctype="multipart/form-data">
                        
                        @csrf
                        <input type="hidden" name="edit_id" value="{{ $role->id }}">

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Role Name <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input" id="name"  name="name"  data-validation-engine=" validate[required]" data-errormessage-value-missing="Role name is required" data-prompt-position="topLeft" value="{{ $role->role_name }}" maxlength="50">
                                @if($errors->has('name'))
                                <div class="error">{!! $errors->first('name') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Role Description <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input" id="description"  name="description" data-validation-engine=" validate[required]" data-errormessage-value-missing="Role description is required" data-prompt-position="topLeft" value="{{ $role->role_description }}">
                                @if($errors->has('description'))
                                <div class="error">{!! $errors->first('description') !!}</div>
                                @endif
                               
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-12" style="display: flex; justify-content: center">
                                <a class="btn btn-info-back float-right mr-1" href="{{url('admin/role/list')}}">Back to List</a>
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
