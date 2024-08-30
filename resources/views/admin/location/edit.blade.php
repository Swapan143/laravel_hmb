@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Location Management</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Edit Location</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" id="meta_form" action="{{ url('admin/location/update') }}" enctype="multipart/form-data">
                        
                        @csrf
                        <input type="hidden" name="edit_id" value="{{ $location->id }}">
                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6 custom-option">
                                <label>Company Name<span style="color:red">*</span></label>
                                <select name="client_id" id="client_id" class="form-control banner-input"  data-validation-engine="validate[required]" data-errormessage-value-missing="Company name is required" data-prompt-position="bottomLeft">
                                    <option value="" disabled>Select company name</option>
                                    @foreach($client_data as $key => $client_row)
                                    <option value="<?=$client_row->id?>"  <?= ($client_row->id==$location->client_id)?'selected':'' ?>><?=$client_row->company_name?></option>
                                    @endforeach
                                </select>
                                @if($errors->has('client_id'))
                                <div class="error">{!! $errors->first('client_id') !!}</div>
                                @endif
                                
                            </div>
                        </div>
                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Location Name <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input" id="name"  name="name"  data-validation-engine=" validate[required]" data-errormessage-value-missing="Location name is required" data-prompt-position="topLeft" value="{{ $location->location_name }}" maxlength="50">
                                @if($errors->has('name'))
                                <div class="error">{!! $errors->first('name') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6 custom-option">
                                <label>Location Type <span style="color:red">*</span></label>
                                <select name="type" id="type" class="form-control banner-input" 
                                data-validation-engine="validate[required]" data-errormessage-value-missing="Type is required" data-prompt-position="bottomLeft">
                                    <option value="1" <?= ($location->location_type==1)?'selected':'' ?>>Loading Location</option>
                                    <option value="2" <?= ($location->location_type==2)?'selected':'' ?>>Siding Location</option>
                                </select>
                              
                                @if($errors->has('type'))
                                <div class="error">{!! $errors->first('type') !!}</div>
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
        
        $("#meta_form").validationEngine();
       
    })
   
</script>
@endsection
