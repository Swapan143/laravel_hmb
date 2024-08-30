@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Charge Management</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Edit Charge</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" id="meta_form" action="{{ url('admin/charges/update') }}" enctype="multipart/form-data">
                        
                        @csrf
                        <input type="hidden" name="edit_id" value="{{ $charge->id }}">
                        <input type="hidden" name="client_id" value="{{ $charge->client_id }}">
                        <input type="hidden" name="location_id" value="{{ $charge->location_id }}">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Company Name <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input only_character" id="client_name" name="client_name" value="{{ $charge->client_name }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="Client name name is required" data-prompt-position="topLeft" maxlength="50" readonly>
                                @if($errors->has('client_name'))
                                <div class="error">{!! $errors->first('client_name') !!}</div>
                                @endif
                                
                            </div>
                       
                            <div class="form-group col-md-6">
                                <label>Location Name <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input only_character" id="location_name"  name="location_name" value="{{ $charge->location_name }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="Location name is required" data-prompt-position="topLeft" maxlength="50" readonly>
                                @if($errors->has('location_name'))
                                <div class="error">{!! $errors->first('location_name') !!}</div>
                                @endif
                                
                            </div>

                            <div class="form-group col-md-6">
                                <label>Month <span style="color:red">*</span></label>
                                <select name="month" id="month" class="form-control banner-input" data-validation-engine="validate[required]" data-errormessage-value-missing="Month is required" data-prompt-position="bottomLeft">
                                    
                                    <option value="1" {{($charge->month=="01")?'selected':'' }}>January</option>
                                    <option value="2" {{($charge->month=="02")?'selected':'' }}>February</option>
                                    <option value="3" {{($charge->month=="03")?'selected':'' }}>March</option>
                                    <option value="4" {{($charge->month=="04")?'selected':'' }}>April</option>
                                    <option value="5" {{($charge->month=="05")?'selected':'' }}>May</option>
                                    <option value="6" {{($charge->month=="06")?'selected':'' }}>June</option>
                                    <option value="7" {{($charge->month=="07")?'selected':'' }}>July</option>
                                    <option value="8" {{($charge->month=="08")?'selected':'' }}>August</option>
                                    <option value="9" {{($charge->month=="09")?'selected':'' }}>September</option>
                                    <option value="10" {{($charge->month=="10")?'selected':'' }}>October</option>
                                    <option value="11" {{($charge->month=="11")?'selected':'' }}>November</option>
                                    <option value="12" {{($charge->month=="12")?'selected':'' }}>December</option>
                                </select>
                                @if($errors->has('month'))
                                <div class="error">{!! $errors->first('month') !!}</div>
                                @endif
                                
                            </div>

                            <div class="form-group col-md-6">
                                <label>Year <span style="color:red">*</span></label>
                                <select name="year" id="year" class="form-control banner-input" data-validation-engine="validate[required]" data-errormessage-value-missing="Year is required" data-prompt-position="bottomLeft">
                                    
                                    @for($i=2020; $i <= 2040; $i++)
                                    <option value="{{ $i }}" {{($charge->year==$i)?'selected':'' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @if($errors->has('year'))
                                <div class="error">{!! $errors->first('year') !!}</div>
                                @endif
                                
                            </div>
                        

                            <div class="form-group col-md-6">
                                <label>Tanker Fare</label>
                                <input type="text" class="form-control banner-input decimal" id="tanker_fare"  name="tanker_fare"  data-validation-engine=" validate[required]" data-errormessage-value-missing="Tanker fare is required" data-prompt-position="topLeft" value="{{ $charge->tanker_fare }}" maxlength="50">
                                @if($errors->has('tanker_fare'))
                                <div class="error">{!! $errors->first('tanker_fare') !!}</div>
                                @endif
                                
                            </div>
                    
                            <div class="form-group col-md-6">
                                <label>Freight Charge per MT</label>
                                <input type="text" class="form-control banner-input decimal" id="freight_charge"  name="freight_charge" placeholder="Enter Freight charge"  data-validation-engine=" validate[required]" data-errormessage-value-missing="Freight charge is required" data-prompt-position="topLeft" value="{{ $charge->freight_charge }}"  maxlength="50">
                                @if($errors->has('freight_charge'))
                                <div class="error">{!! $errors->first('freight_charge') !!}</div>
                                @endif
                                
                            </div>
             
                            <div class="form-group col-md-6">
                                <label>Diesel(HSD) Price per Litre</label>
                                <input type="text" class="form-control banner-input decimal" id="diesel_price"  name="diesel_price"  data-validation-engine=" validate[required]" data-errormessage-value-missing="Diesel price is required" data-prompt-position="topLeft" value="{{ $charge->diesel_price }}" maxlength="50">
                                @if($errors->has('diesel_price'))
                                <div class="error">{!! $errors->first('diesel_price') !!}</div>
                                @endif
                                
                            </div>
      
                            <div class="form-group col-md-6">
                                <label>Accidental Rate</label>
                                <input type="text" class="form-control banner-input decimal" id="accidental_rate"  name="accidental_rate"  data-validation-engine=" validate[required]" data-errormessage-value-missing="Accidental rate is required" data-prompt-position="topLeft" value="{{ $charge->accidental_rate }}" maxlength="50">
                                @if($errors->has('accidental_rate'))
                                <div class="error">{!! $errors->first('accidental_rate') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        
                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-12" style="display: flex; justify-content: center">
                                <a class="btn btn-info-back float-right mr-1" href="{{url('admin/location/list')}}">Back to List</a> 
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
        flight();
        $("#meta_form").validationEngine();
        $("#type").change(function()
        {
            flight();
        });

        function flight()
        {
           
            var type=$("#type").val();
            if(type=='2')
            {
                $("#show_flight").show();
            }
            else
            {
                $("#show_flight").hide();
            }
        }
    })
   
</script>
@endsection
