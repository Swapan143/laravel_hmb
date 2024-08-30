@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Challan Management </h2>
        
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Add Challan</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" id="meta_form" action="{{ url('admin/challan/store') }}" enctype="multipart/form-data">
                        
                        @csrf
                        <input type="hidden" name="transporter_id" id="transporter_id">
                        <input type="hidden" name="client_id" id="client_id">
                        <div class="row" >
                            <div class="form-group col-md-6">
                                <label>Challan Date<span style="color:red">*</span></label>
                                <input type="date" class="form-control banner-input" id="challan_date" placeholder="Enter gross weight" name="challan_date" value="{{ old('challan_date') }}" data-validation-engine="validate[required]" data-errormessage-value-missing="Challan date is required" data-prompt-position="topLeft"  maxlength="50">
                                @if($errors->has('challan_date'))
                                <div class="error">{!! $errors->first('challan_date') !!}</div>
                                @endif
                                
                            </div>
                            <div class="form-group col-md-6 custom-option">
                                <label>Vehicle Number <span style="color:red">*</span></label>
                                <select name="vehicle_id" id="vehicle_id" class="form-control banner-input js-example-basic-search" data-validation-engine="validate[required]" data-errormessage-value-missing="Vehicle number is required" data-prompt-position="bottomLeft">
                                    <option selected disabled>Select vehicle number</option>
                                    @foreach($vehicle_data as $key => $vehicle_row)
                                    <option value="<?=$vehicle_row->id?>"><?=$vehicle_row->vehicle_no?></option>
                                    @endforeach
                                </select>
                                @if($errors->has('vehicle_id'))
                                <div class="error">{!! $errors->first('vehicle_id') !!}</div>
                                @endif
                               
                            </div>

                            <div class="form-group col-md-6">
                                <label>Transporter Name <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input number_character" id="transporter_name" placeholder="Transporter name" name="transporter_name"  data-validation-engine="validate[required]" data-errormessage-value-missing="Transporter name is required" data-prompt-position="topLeft"  maxlength="50" readonly>
                                @if($errors->has('transporter_name'))
                                <div class="error">{!! $errors->first('transporter_name') !!}</div>
                                @endif
                                
                            </div>

                            <div class="form-group col-md-6">
                                <label>Company Name<span style="color:red">*</span> </label>
                                <span class="btn-info-back float-right mr-1" href="#"  onclick="changeCompany()" style="padding: 0 10px;cursor: pointer;">Change</span>
                                <input type="text" class="form-control banner-input number_character " id="client_name" placeholder="Company name" name="client_name"  data-validation-engine="validate[required]" data-errormessage-value-missing="Company is required" data-prompt-position="topLeft"  maxlength="50" readonly>
                                
                                @if($errors->has('client_name'))
                                <div class="error">{!! $errors->first('client_name') !!}</div>
                                @endif
                                
                            </div>

                            <div class="form-group col-md-6">
                                <label>Challan Number <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input number_character_hipen" id="challan_number" placeholder="Enter challan number" name="challan_number" value="{{ old('challan_number') }}" data-validation-engine="validate[required]" data-errormessage-value-missing="Challan number is required" data-prompt-position="topLeft" maxlength="20">
                                @if($errors->has('challan_number'))
                                <div class="error">{!! $errors->first('challan_number') !!}</div>
                                @endif
                               
                            </div>

                            <div class="form-group col-md-6 custom-option">
                                <label>Loading Location <span style="color:red">*</span></label>
                                <select name="loading_location" id="loading_location" class="form-control banner-input js-example-basic-search" data-validation-engine="validate[required]"data-errormessage-value-missing="Loading location is required" data-prompt-position="bottomLeft">
                                    <option value="">Select loading location</option>
                                    @foreach($loading_location_data as $key => $loding_row)
                                    <option value="<?=$loding_row->id?>"><?=$loding_row->location_name?></option>
                                    @endforeach
                                </select>
                                @if($errors->has('loading_location'))
                                <div class="error">{!! $errors->first('loading_location') !!}</div>
                                @endif
                               
                            </div>

                            <div class="form-group col-md-6 ">
                                <label>Siding Location <span style="color:red">*</span></label>
                                <select name="siding_location" id="siding_location" class="form-control banner-input js-example-basic-search" data-validation-engine="validate[required]" data-errormessage-value-missing="Siding location is required" data-prompt-position="bottomLeft">
                                    <option value="">Select siding location</option>
                                    @foreach($siding_location_data as $key => $siding_row)
                                    <option value="<?=$siding_row->id?>"><?=$siding_row->location_name?></option>
                                    @endforeach
                                </select>
                                @if($errors->has('siding_location'))
                                <div class="error">{!! $errors->first('siding_location') !!}</div>
                                @endif
                               
                            </div>

                            <div class="form-group col-md-6">
                                <label>Gross Weight (in MT)<span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input decimal" id="gross_weight" placeholder="Enter gross weight" name="gross_weight" value="{{ old('gross_weight') }}" data-validation-engine="validate[required]" data-errormessage-value-missing="Gross weight is required" data-prompt-position="topLeft"  maxlength="50">
                                @if($errors->has('gross_weight'))
                                <div class="error">{!! $errors->first('gross_weight') !!}</div>
                                @endif
                                
                            </div>

                            

                            <div class="form-group col-md-6">
                                <label>Tare Weight (in MT)<span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input decimal" id="tare_weight" placeholder="Enter tare weight" name="tare_weight" value="{{ old('tare_weight') }}" data-validation-engine="validate[required]" data-errormessage-value-missing="Tare weight is required" data-prompt-position="topLeft" maxlength="50">
                                @if($errors->has('tare_weight'))
                                <div class="error">{!! $errors->first('tare_weight') !!}</div>
                                @endif
                                
                            </div>

                            <div class="form-group col-md-6">
                                <label>Net Weight (in MT)<span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input decimal" id="net_weight" placeholder="Net weight " name="net_weight" value="{{ old('net_weight') }}" data-validation-engine="validate[required]" data-errormessage-value-missing="Net weight is required" data-prompt-position="topLeft"  maxlength="50" readonly>
                                @if($errors->has('net_weight'))
                                <div class="error">{!! $errors->first('net_weight') !!}</div>
                                @endif
                                
                            </div>

                            
                            <div class="form-group col-md-6">
                                <label>Remarks</label>
                                <textarea rows="2" cols="30" style="resize: none;"  name="remarks" id="remarks" class="form-control" data-errormessage-value-missing="Remarks is required" data-prompt-position="bottomLeft" placeholder="Enter remarks" ></textarea>
                                   
                            </div>
                            

                            <div class="form-group col-md-6">
                                <label>Upload Challan Image</label>
                                
                                <img src="{{ URL::asset('assets/admin/img/Add-Photo-Button.png') }}" id="upload_photo_vehicle" onclick="get_vehicle()" style="cursor: pointer; height:100px;width:100px" class="add_img_button">
                                <input type="file" name="item_image_vehicle" class="image-upload selected_img" id="item_image_vehicle" style="display: none" accept=".jpg,.jpeg,.png" onchange="show_photo_vehicle(this)">
                            
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <a class="btn btn-info-back float-right mr-1" href="{{url('admin/challan/list')}}">Back to List</a>
                            <button class="btn btn-info btn-banner float-right" type="submit"></i>Save</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="company_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Change Company</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="challan_image_show">
            <form method="post" id="meta_form" action="#" enctype="multipart/form-data">
                        
                @csrf
               
                <div class="row" style="display: flex; justify-content: center">
                    <div class="form-group col-md custom-option">
                        <label>Select Company Name<span style="color:red">*</span></label>
                        <select name="select_company_name" id="select_company_name" class="form-control banner-input" data-validation-engine="validate[required]" data-errormessage-value-missing="Vehicle number is required" data-prompt-position="bottomLeft">
                           
                            
                        </select>
                
                       
                    </div>
                </div>

                <div class="row" style="display: flex; justify-content: center">
                    <button class="btn btn-info btn-banner float-right" type="button" onclick="saveCompany()"></i>Change Company</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() 
    {
        $("#meta_form").validationEngine();

        $('#vehicle_id').on('change', function() 
        {
            var id = $('#vehicle_id').val();

            var current_url = $('#current_url').val();
    
            $.ajax({
                url:current_url+'/vehicle',
                type:'GET',
                data:{
                "_token": $('#csrf_token').val(),
                id:id
                },
                success: function(data)
                {
                    var result = JSON.parse(data);
                    $('#transporter_name').val(result.transporter_name);
                    $('#transporter_id').val(result.transporter_id);
                    $('#client_name').val(result.client_name);
                    $('#client_id').val(result.client_id);
                }
            });
        });

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

    function changeCompany()
    {
        
        var vehicle_id = $('#vehicle_id').val();

        if(vehicle_id==null)
        {
            toastr.options.positionClass = 'toast-top-full-width';
            toastr.error("please select vehicle number");
           
        }
        else
        {
            var client_name =$('#client_name').val();
            var current_url = $('#current_url').val();
            $('#company_modal').modal('show');

            $.ajax({
                url:current_url+'/company',
                type:'GET',
                data:{
                "_token": $('#csrf_token').val(),
                client_name:client_name
                },
                success: function(data)
                {
                    $('#select_company_name').html("");
                    $('#select_company_name').html(data);
                    
                }
            });
        }
    
    }

    function saveCompany()
    {
        var company_name =$("#select_company_name").find(':selected').attr('data-company_name');
        var company_id =$("#select_company_name").val();
        

        if(company_name==null)
        {
            toastr.options.positionClass = 'toast-top-full-width';
            toastr.error("please select company name");
           
        }
        else
        {
            var vehicle_id = $('#vehicle_id').val();
            $('#client_name').val(company_name);
            var current_url = $('#current_url').val();

            $.ajax({
                url:current_url+'/company-save',
                type:'GET',
                data:{
                "_token": $('#csrf_token').val(),
                company_id:company_id,vehicle_id:vehicle_id
                },
                success: function(data)
                {
                    
                    $('#company_modal').modal('toggle'); 
                    toastr.options.positionClass = 'toast-top-full-width';
                    toastr.success("Company name updated");
                    
                }
            });
        }
    }

</script>
@endsection
