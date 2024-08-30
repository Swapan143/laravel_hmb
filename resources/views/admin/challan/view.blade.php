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
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class=" emp_para_beside">
                                <p><b>Challan Date:</b></p>
                                <p>{{ date("d-M-Y", strtotime($challan->challan_date)) }}</p>
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
                                <p>{{ $challan->challan_no}}</p>
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
                                <p>{{ get_client_name($challan->client_id) }}</p>
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
                                <p>{{ get_location_name($challan->siding_location_id) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" emp_para_beside">
                                <p><b>Gross Weight : </b></p>
                                <p>{{ $challan->gross_weight ." MT" }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" emp_para_beside">
                                <p><b>Tare Weight : </b></p>
                                <p>{{ $challan->tare_weight ." MT" }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" emp_para_beside">
                                <p><b>Net Weight : </b></p>
                                <p>{{ $challan->net_weight ." MT" }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="emp_para_beside">
                                <p><b>Remarks : </b></p>
                                <p>{{ $challan->remarks}}</p>
                            </div>
                        </div>
                    </div>
                    @if(!empty($challan->image ))
                    <div class="row">
                        <div class="col-md-12">
                            
                                <h3 class="emp_para_beside"> <b>Challan Image</b></h3>
                                
                                <img src="{{ $constant_veriable['API_BASE_URL'] }}storage/challans/{{ $challan->image }}">
                               
                                <a href="{{ $constant_veriable['API_BASE_URL'] }}storage/challans/{{ $challan->image }}" download>
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
