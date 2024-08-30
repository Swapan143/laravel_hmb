@extends('admin.layouts.master.master')


@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><b>Challan - Date : {{ date("d-M-Y", strtotime($challan->challan_date)) }} - No : {{ $challan->sl_no}}</b></h2>
    </div>
    {{-- <div class="col-lg-2 d-flex justify-content-end">
        <a href="{{ url('admin/user/add') }}" class="btn btn-primary add-btn"><strong>Add</strong></a>
    </div> --}}
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <form method="post" id="meta_form" action="{{ url('admin/challan/update') }}" enctype="multipart/form-data">
                        
        @csrf
        <input type="hidden" name="edit_id" value="{{ $challan->id }}">
        <input type="hidden" id="net_weight" name="net_weight" value="{{ $challan->net_weight }}">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Challan Date:</b></p>
                                    <input type="date" class="form-control disel-input" id="challan_date" placeholder="Enter gross weight" name="challan_date" value="{{ date("Y-m-d", strtotime($challan->challan_date)) }}" data-validation-engine="validate[required]" data-errormessage-value-missing="Required" data-prompt-position="topLeft"  maxlength="50">
                                    @if($errors->has('challan_date'))
                                    <div class="error">{!! $errors->first('challan_date') !!}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Serial No : </b></p>
                                    <p>{{ $challan->sl_no}}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Vehicle No : </b></p>
                                    <p>{{ get_vehicle_name($challan->vehicle_id) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Challan No :  </b></p>
                                    <input type="text" class="form-control disel-input number_character_hipen" id="challan_number" placeholder="Enter challan number" name="challan_number" value="{{ $challan->challan_no }}" data-validation-engine="validate[required]" data-errormessage-value-missing="Required" data-prompt-position="topLeft" maxlength="20">
                                @if($errors->has('challan_number'))
                                <div class="error">{!! $errors->first('challan_number') !!}</div>
                                @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Transporter Name :  </b></p>
                                    @php
                                        $vehicle_data=App\Models\Vehicle::where('id',$challan->vehicle_id)->first();
                                    @endphp
                                    <p>{{ get_transporter_name($vehicle_data->transporter_id) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Company Name :  </b></p>
                                    <select name="client_name" id="client_name" class="form-control banner-input" data-validation-engine="validate[required]" data-errormessage-value-missing="Required" data-prompt-position="bottomLeft" onchange="sidingLocation()">
                                  
                                        @foreach($client_data as $key => $client_row)
                                        <option value="<?=$client_row->id?>"  <?= ($client_row->id==$challan->client_id)?'selected':'' ?>><?=$client_row->company_name?></option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('client_name'))
                                    <div class="error">{!! $errors->first('client_name') !!}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Loading Location :  </b></p>
                                    <p>{{ get_location_name($challan->loading_location_id) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Siding Location :  </b></p>
                                    <select name="vehicle_siding_location" id="vehicle_siding_location" class="form-control banner-input" data-validation-engine="validate[required]" data-errormessage-value-missing="Required" data-prompt-position="bottomLeft">
                                        <option value="">Select vehicle siding location</option>
                                        @foreach($location as $key => $location_row)
                                        <option value="<?=$location_row->id?>"  <?= ($location_row->id==$challan->siding_location_id)?'selected':'' ?>><?=$location_row->location_name?></option>
                                        @endforeach
                                        
                                    </select>
                                    @if($errors->has('vehicle_siding_location'))
                                    <div class="error">{!! $errors->first('vehicle_siding_location') !!}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Gross Weight (MT): </b></p>
                                   
                                    <input type="text" class="form-control disel-input decimal" id="gross_weight" placeholder="Enter gross weight" name="gross_weight" value="{{ $challan->gross_weight }}" data-validation-engine="validate[required]" data-errormessage-value-missing="Gross weight is required" data-prompt-position="topLeft"  maxlength="50">
                                    @if($errors->has('gross_weight'))
                                    <div class="error">{!! $errors->first('gross_weight') !!}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Tare Weight (MT): </b></p>
                                   
                                    <input type="text" class="form-control disel-input decimal" id="tare_weight" placeholder="Enter tare weight" name="tare_weight" value="{{ $challan->tare_weight }}" data-validation-engine="validate[required]" data-errormessage-value-missing="Required" data-prompt-position="topLeft" maxlength="50">
                                    @if($errors->has('tare_weight'))
                                    <div class="error">{!! $errors->first('tare_weight') !!}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Net Weight : </b></p>
                                    <p id="show_net_weight">{{ $challan->net_weight ." MT" }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="emp_para_beside">
                                    <p><b>Remarks : </b></p>
                                    <textarea rows="2" cols="30" style="resize: none;"  name="remarks" id="remarks" class="form-control disel-input" data-errormessage-value-missing="Remarks is required" data-prompt-position="bottomLeft" placeholder="Enter remarks" >{{ $challan->remarks}}</textarea>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-12" style="display: flex; justify-content: center">
                                <a class="btn btn-info-back float-right mr-1" href="{{url('admin/challan/list')}}">Back to List</a>
                                <button class="btn btn-info btn-banner float-right" type="submit"></i>Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() 
    {
        $("#meta_form").validationEngine();
 

        $('#tare_weight').on('keyup', function() 
        {
            var gross_weight = $('#gross_weight').val();
            var tare_weight = $('#tare_weight').val();
            if(tare_weight != '')
            {
                if(Number(gross_weight)>Number(tare_weight))
                {
                    var net_weight = Number(gross_weight)-Number(tare_weight);
                    $('#net_weight').val(parseFloat(net_weight).toFixed(2));
                    $('#show_net_weight').html('');
                    $('#show_net_weight').html(parseFloat(net_weight).toFixed(2)+" MT");
                    
                }
                else
                {
                    $('#tare_weight').val('');
                    $('#net_weight').val('');
                    toastr.options.positionClass = 'toast-top-full-width';
                    toastr.error("Tare weight can tot getter than gross weight");
                }
            }
            else
            {
                $('#net_weight').val('');
            }
            

            

        });

    })

    function sidingLocation()
    {
        var client_name = $('#client_name').val();
    
        var url = $('#url').val();
        $.ajax({
            url:url+'/admin/vehicle/siding-location',
            type:'GET',
            data:{
            "_token": $('#csrf_token').val(),
            client_name:client_name
            },
            success: function(data)
            {
                $('#vehicle_siding_location').html("");
                $('#vehicle_siding_location').html(data);
                
            }
        });
        
    }


</script>
@endsection
