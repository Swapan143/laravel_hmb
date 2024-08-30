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
                    <h5>Import CSV </h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" id="meta_form" action="{{ url('admin/vehicle/upload-csv') }}" enctype="multipart/form-data">
                        
                        @csrf
                   
                        

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Upload CSV</label>
                                <input type="file" class="form-control banner-input" id="file" name="file" value="{{ old('file') }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="File is required" data-prompt-position="topLeft" accept=".csv" >
                                @if($errors->has('file'))
                                <div class="error">{!! $errors->first('file') !!}</div>
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
    })
    
</script>
@endsection
