@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Transporter SMS</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Transporter SMS Management</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    

                        <div class="row" >
                            <div class="form-group col-md-6">
                                <b>Transporter SMS </b>
                                <label class="switch">
                                    @php
                                        $status_check='';
                                        if($master_data->transporter_sms=="1")
                                        {
                                            $status_check='checked';
                                        }
                                    @endphp
                                    <input type="checkbox" value="" onchange="sms_change({{ $master_data->id }})" {{ $status_check }}>
                                    <span class="slider round"></span>
                                </label>
                                
                            </div>
                        </div>
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
    function sms_change(id)
    {
        if(id==null)
        {
            toastr.options.positionClass = 'toast-top-full-width';
            toastr.error("Something went wrong !");
           
        }
        else
        {
            var url = $('#url').val();

            $.ajax({
                url:url+'/admin/setting/sms/update',
                type:'GET',
                data:{
                "_token": $('#csrf_token').val(),
                id:id
                },
                success: function(data)
                {
                    if (data) 
                    {
                    toastr.options.positionClass = 'toast-top-full-width';
                    toastr.success("Status updated sucessfully.");
                    }
                    else
                    {
                    toastr.options.positionClass = 'toast-top-full-width';
                    toastr.success("Something went wrong!");
                    }
                    
                }
            });
        }
    }
</script>
@endsection
