@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Diesel Management </h2>
        
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Add Diesel</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" id="meta_form" action="{{ url('admin/diesel/store') }}" enctype="multipart/form-data">
                        
                        @csrf
                        <input type="hidden" name="transporter_id" id="transporter_id">
                        <input type="hidden" name="client_id" id="client_id">

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6 custom-option">
                                <label>Vehicle Number <span style="color:red">*</span></label>
                                <select name="vehicle_id" id="vehicle_id" class="form-control banner-input js-example-basic-search" 
                                data-validation-engine="validate[required]" data-errormessage-value-missing="Vehicle number is required" data-prompt-position="bottomLeft">
                                    <option selected disabled>Select vehicle number</option>
                                    @foreach($vehicle_data as $key => $vehicle_row)
                                    <option value="<?=$vehicle_row->id?>"><?=$vehicle_row->vehicle_no?></option>
                                    @endforeach
                                </select>
                                @if($errors->has('vehicle_id'))
                                <div class="error">{!! $errors->first('vehicle_id') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Transporter Name <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input number_character" id="transporter_name" placeholder="Transporter name" name="transporter_name"  data-validation-engine="validate[required]" data-errormessage-value-missing="Transporter name is required" data-prompt-position="topLeft"  maxlength="50" readonly>
                                @if($errors->has('transporter_name'))
                                <div class="error">{!! $errors->first('transporter_name') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Company Name<span style="color:red">*</span></label>
                                <span class="btn-info-back float-right mr-1" href="#"  onclick="changeCompany()" style="padding: 0 10px;cursor: pointer;">Change</span>
                                <input type="text" class="form-control banner-input number_character " id="client_name" placeholder="Company name" name="client_name"  data-validation-engine="validate[required]" data-errormessage-value-missing="Company is required" data-prompt-position="topLeft"  maxlength="50" readonly>
                                
                                @if($errors->has('client_name'))
                                <div class="error">{!! $errors->first('client_name') !!}</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Quantity (Litres) <span style="color:red">*</span></label>
                                <input type="text" class="form-control banner-input only_integer" id="quantity" placeholder="Enter quantity" name="quantity" value="{{ old('quantity') }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="Quantityn is required" data-prompt-position="topLeft">
                                @if($errors->has('quantity'))
                                <div class="error">{!! $errors->first('quantity') !!}</div>
                                @endif
                               
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-6">
                                <label>Remarks</label>
                                <textarea rows="2" cols="30" style="resize: none;"  name="remarks" id="remarks" class="form-control" data-errormessage-value-missing="Remarks is required" data-prompt-position="bottomLeft" placeholder="Enter remarks" ></textarea>
                               
                            </div>
                        </div>

                        
                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-12" style="display: flex; justify-content: center">
                                <a class="btn btn-info-back float-right mr-1" href="{{url('admin/diesel/list')}}">Back to List</a> 
                                <button class="btn btn-info btn-banner float-right" type="submit"></i>Save</button>
                            </div>
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
            var url = $('#url').val();
            $('#chalan_view_modal').modal('show');
            $.ajax({
                url:url+'/admin/challan/add/vehicle',
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
            var url = $('#url').val();
            $('#company_modal').modal('show');

            $.ajax({
                url:url+'/admin/challan/add/company',
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
            var url = $('#url').val();

            $.ajax({
                url:url+'/admin/challan/add/company-save',
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
