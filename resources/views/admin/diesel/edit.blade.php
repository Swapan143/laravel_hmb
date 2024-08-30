@extends('admin.layouts.master.master')


@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><b>Diesel - Date: {{ date("d-M-Y", strtotime($diesel->date_time)) }}</b></h2>
    </div>
    {{-- <div class="col-lg-2 d-flex justify-content-end">
        <a href="{{ url('admin/user/add') }}" class="btn btn-primary add-btn"><strong>Add</strong></a>
    </div> --}}
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <form method="post" id="meta_form" action="{{ url('admin/diesel/update') }}" enctype="multipart/form-data">
                        
        @csrf
        <input type="hidden" name="edit_id" value="{{ $diesel->id }}">
        <input type="hidden" id="diesel_price" name="diesel_price" value="{{ $diesel_price }}">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Diesel Date : </b></p>
                                    <input type="date" class="form-control disel-input" id="date_time"  name="date_time"  value="{{ date("Y-m-d", strtotime($diesel->date_time)) }}" data-validation-engine="validate[required]" data-errormessage-value-missing="Required" data-prompt-position="topLeft">
                                    @if($errors->has('date_time'))
                                    <div class="error">{!! $errors->first('date_time') !!}</div>
                                    @endif
                                   
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Serial No : </b></p>
                                    <p>{{ $diesel->sl_no}}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Vehicle No : </b></p>
                                    <p>{{ get_vehicle_name($diesel->vehicle_id) }}</p>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Transporter Name :  </b></p>
                                    <p>{{ get_transporter_name($diesel->transporter_id) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Company Name :  </b></p>
                                    @php
                                    $vehicle_data=App\Models\Vehicle::where('id',$diesel->vehicle_id)->first();
                                    @endphp
                                    <p>{{ get_client_name($vehicle_data->client_id) }}</p>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Quantity : </b></p>
                                    <input type="text" class="form-control disel-input only_integer" id="quantity" placeholder="Enter quantity" name="quantity" value="{{ intval($diesel->quantity) }}" data-validation-engine=" validate[required]" data-errormessage-value-missing="Required" data-prompt-position="topLeft">
                                    @if($errors->has('quantity'))
                                    <div class="error">{!! $errors->first('quantity') !!}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Total Amount : </b></p>
                                    <p id="show_amount">{{ $diesel->total_amount}}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" emp_para_beside">
                                    <p><b>Remarks : </b></p>
                                    <p>{{ $diesel->remarks}}</p>
                                </div>
                            </div>
                        

                            
                        </div>
                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-12" style="display: flex; justify-content: center">
                                <a class="btn btn-info-back float-right mr-1" href="{{url('admin/diesel/list')}}">Back to List</a>
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
 

        $('#quantity').keyup(function() 
        {
            var quantity = $('#quantity').val();
            var diesel_price = $('#diesel_price').val();
            $('#show_amount').html('');
            if(quantity==null)
            {
                toastr.options.positionClass = 'toast-top-full-width';
                toastr.error("please enter quantiyty");
            
            }
            else
            {
                var total=parseFloat(quantity)*parseFloat(diesel_price);
                $('#show_amount').html(total.toFixed(2));
                
            }
            
            
        });
    })


</script>


@endsection
