@extends('admin.layouts.master.master')
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2> About Us</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{url('admin/dashboard')}}"><strong>Home</strong></a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ url('admin/about-us') }}"><strong>About Us</strong></a>
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
                    <h5>About Us</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form class="banner-add-form" method="post" action="{{url('admin/update-about-us')}}" enctype="multipart/form-data" id="validation_engine_id">
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <div class="row">
                            @csrf
                           
                            <div class="form-group col-md-12">
                                <textarea class="form-control banner-input" id="service_desc" rows="3" placeholder="About Us" name="desc" data-validation-engine=" validate[required]" data-errormessage-value-missing="About us is required" data-prompt-position="topLeft">{{ !empty($data->description) ? $data->description:'' }}</textarea>
                                @if($errors->has('desc'))
                                <div class="error">{!! $errors->first('desc') !!}</div>
                            @endif
                            </div>
                            <div class="form-group col-md-12">
                                <button class="btn btn-info float-right btn-banner" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() 
    {
        $("#validation_engine_id").validationEngine();
        CKEDITOR.disableAutoInline = true;
        CKEDITOR.replace('desc');
    })
</script>
@endsection
