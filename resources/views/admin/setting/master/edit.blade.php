@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Master Management</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Update Master Management</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" id="meta_form" action="{{ url('admin/setting/master/update') }}" enctype="multipart/form-data">
                        
                        @csrf
                        <input type="hidden" name="edit_id" value="{{ @$master_data->id }}">

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Tanker Fare</label>
                                <input type="text" class="form-control banner-input" id="tanker_fare"  name="tanker_fare"  data-validation-engine=" validate[required]" data-errormessage-value-missing="Tanker fare is required" data-prompt-position="topLeft" value="{{ @$master_data->tanker_fare }}" maxlength="50">
                                @if($errors->has('tanker_fare'))
                                <div class="error">{!! $errors->first('tanker_fare') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Freight Charge per MT</label>
                                <input type="text" class="form-control banner-input" id="freight_charge"  name="freight_charge"  data-validation-engine=" validate[required]" data-errormessage-value-missing="Freight charge is required" data-prompt-position="topLeft" value="{{ @$master_data->freight_charge }}" maxlength="50">
                                @if($errors->has('freight_charge'))
                                <div class="error">{!! $errors->first('freight_charge') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Diesel(HSD) Price per Litre</label>
                                <input type="text" class="form-control banner-input" id="diesel_price"  name="diesel_price"  data-validation-engine=" validate[required]" data-errormessage-value-missing="Diesel price is required" data-prompt-position="topLeft" value="{{ @$master_data->diesel_price }}" maxlength="50">
                                @if($errors->has('diesel_price'))
                                <div class="error">{!! $errors->first('diesel_price') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Accidental Rate</label>
                                <input type="text" class="form-control banner-input" id="accidental_rate"  name="accidental_rate"  data-validation-engine=" validate[required]" data-errormessage-value-missing="Accidental rate is required" data-prompt-position="topLeft" value="{{ @$master_data->accidental_rate }}" maxlength="50">
                                @if($errors->has('accidental_rate'))
                                <div class="error">{!! $errors->first('accidental_rate') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-12" style="display: flex; justify-content: center">
                                <a class="btn btn-info-back float-right mr-1" href="{{url('admin/setting/master')}}">Back to List</a>
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
