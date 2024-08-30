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
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class=" emp_para_beside">
                                <p><b>Diesel Date : </b></p>
                                <p>{{ date("d-M-Y", strtotime($diesel->date_time)) }}</p>
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
                                <p>{{ $diesel->quantity}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" emp_para_beside">
                                <p><b>Total Amount : </b></p>
                                <p>{{ $diesel->total_amount}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" emp_para_beside">
                                <p><b>Remarks : </b></p>
                                <p>{{ $diesel->remarks}}</p>
                            </div>
                        </div>
                       

                        
                    </div>

                    @if(!empty($diesel->image ))
                    <div class="row">
                        <div class="col-md-12">
                            
                                <h3 class="emp_para_beside"> <b>Diesel Image</b></h3>
                                
                                <img src="{{ $constant_veriable['API_BASE_URL'] }}storage/diesel/{{ $diesel->image }}">
                               
                                <a href="{{ $constant_veriable['API_BASE_URL'] }}storage/diesel/{{ $diesel->image }}" download>
                                    <button type="button" style="margin-top: 28px;" class="btn btn-info">Download</button>
                                </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
