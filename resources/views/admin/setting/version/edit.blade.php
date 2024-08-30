@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>App Version</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Update App Version</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" id="meta_form" action="{{ url('admin/setting/version/update') }}" enctype="multipart/form-data">
                        
                        @csrf
                        <input type="hidden" name="edit_id" value="{{ @$app_version->id }}">

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>App Version</label>
                                <input type="text" class="form-control banner-input" id="version"  name="version"  data-validation-engine=" validate[required]" data-errormessage-value-missing="Version is required" data-prompt-position="topLeft" value="{{ @$app_version->version }}" maxlength="50">
                                @if($errors->has('version'))
                                <div class="error">{!! $errors->first('version') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Status</label>
                                <select name="status" id="status" class="form-control banner-input validate[required]" data-errormessage-value-missing="Status is required" data-prompt-position="bottomLeft">
                                    <option value="2" <?= ($app_version->status==2)?'selected':'' ?>>Active</option>
                                    <option value="1" <?= ($app_version->status==1)?'selected':'' ?>>Inactive</option>
                                    
                                </select>
                                @if($errors->has('status'))
                                <div class="error">{!! $errors->first('status') !!}</div>
                                @endif
                               
                            </div>
                        </div>

                    
                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-12" style="display: flex; justify-content: center">
                                <a class="btn btn-info-back float-right mr-1" href="{{url('admin/setting/version')}}">Back to List</a>
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
