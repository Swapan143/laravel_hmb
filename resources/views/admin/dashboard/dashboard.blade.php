@extends('admin.layouts.master.master')


@section('content')

<div class="row">
    <div class="col-lg-3">
        <div class="ibox float-e-margins border ">
            <div class="ibox-title dash-title dashboard-ibox-title">
                <span class="textsize">Total User</span>
            </div>
            <div class="ibox-content inboxsize">
                <h2 class="dashboard_count">{{ $total_user }}</h2>
                
                <div class="top-1">
                    <a href="{{ url('admin/user/list')}}" class="btn btn-primary mr-right" type="submit">View</a>                                                        

                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="ibox float-e-margins border ">
            <div class="ibox-title dash-title dashboard-ibox-title">
                <span class="textsize">Total Transporter
                </span>
            </div>
            <div class="ibox-content inboxsize">
                <h2 class="dashboard_count">{{ $total_transporter }}</h2>
                
                <div class="top-1">
                    <a href="{{ url('admin/vendor/list')}}" class="btn btn-primary mr-right" type="submit">View</a>                                                        

                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="ibox float-e-margins border ">
            <div class="ibox-title dash-title dashboard-ibox-title">
                <span class="textsize">Total Company</span>
            </div>
            <div class="ibox-content inboxsize">
                <h2 class="dashboard_count">{{ $total_client }}</h2>
                
                <div class="top-1">
                    <a href="{{ url('admin/client/list')}}" class="btn btn-primary  mr-right" type="submit">View</a>                                                        

                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="ibox float-e-margins border ">
            <div class="ibox-title dash-title dashboard-ibox-title">
                <span class="textsize">Total Vehicle</span>
            </div>
            <div class="ibox-content inboxsize">
                <h2 class="dashboard_count">{{ $total_vehicle }}</h2>
               
                <div class="top-1">
                    <a href="{{ url('admin/vehicle/list')}}" class="btn btn-primary  mr-right" type="submit">View</a>                                                        

                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="ibox float-e-margins border ">
            <div class="ibox-title dash-title dashboard-ibox-title">
                <span class="textsize">Total Loading Location</span>
            </div>
            <div class="ibox-content inboxsize">
                <h2 class="dashboard_count">{{ $total_loading_location }}</h2>
                
                <div class="top-1">
                    <a href="{{ url('admin/location/list')}}" class="btn btn-primary mr-right" type="submit">View</a>                                                        

                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="ibox float-e-margins border ">
            <div class="ibox-title dash-title dashboard-ibox-title">
                <span class="textsize">Total Siding Location</span>
            </div>
            <div class="ibox-content inboxsize">
                <h2 class="dashboard_count">{{ $total_siding_location }}</h2>

                <div class="top-1">
                    <a href="{{ url('admin/location/list')}}" class="btn btn-primary  mr-right" type="submit">View</a>                                                        

                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="ibox ">
            <div class="ibox-title dashboard-ibox-title">
                {{-- <span class="label label-success float-right"></span> --}}
                <h5>Today's Challan</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{ $total_challan }}</h1>
                <!-- <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> -->
                
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="ibox ">
            <div class="ibox-title dashboard-ibox-title">
                {{-- <span class="label label-success float-right"></span> --}}
                <h5>Today's Diesel Taken (Ltr)</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{ $total_diesel }}</h1>
                <!-- <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> -->
                
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="ibox">
            <div class="ibox-title">
                <h3>Document expired - Vehicle list</h3>
            </div>

            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-hover" id="expired_vehicle_data_table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Vehicle</th>
                                <th>Permit</th>
                                <th>Tax</th>
                                <th>Fitness</th>
                                <th>Insurance</th>
                                
                            </tr>
                        </thead>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox">
            <div class="ibox-title">
                <h3><h3>Document renew immediately - Vehicle List</h3></h3>
            </div>

            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-hover" id="renew_vehicle_data_table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Vehicle</th>
                                <th>Permit</th>
                                <th>Tax</th>
                                <th>Fitness</th>
                                <th>Insurance</th>
                                
                            </tr>
                        </thead>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(function() 
    {
        var url=$('#url').val();
        var dataTable = $('#expired_vehicle_data_table').DataTable({
        'scrollX': true,
        'searching': false,
        'processing': true,
        "language": {
            processing: '<div class="preloader"><div class="spinner-layer pl-red"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div> '},
        'serverSide': true,
        'serverMethod': 'get',
        'ordering': false,
        'ajax': {
            'url':url+'/admin/vehicle-notification/get-expired-vehicle-data-ajax',
            'data': function(data)
            {
                var search = $('#search').val();

                data.searchSearch = search; 
            }
        },
        columns: [
                { data: 'sl' ,orderable: false},
                { data: 'vehicle_no',orderable: false},
                { data: 'permit_valid',orderable: false},
                { data: 'tax_valid',orderable: false},
                { data: 'fitness_valid',orderable: false},
                { data: 'insurance_valid',orderable: false},
            ],
        });

    });
</script>

<script>
    jQuery(function() 
    {
        var url=$('#url').val();
        var dataTable = $('#renew_vehicle_data_table').DataTable({
        'scrollX': true,
        'searching': false,
        'processing': true,
        "language": {
            processing: '<div class="preloader"><div class="spinner-layer pl-red"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div> '},
        'serverSide': true,
        'serverMethod': 'get',
        'ordering': false,
        'ajax': {
            'url':url+'/admin/vehicle-notification/get-renew-vehicle-data-ajax',
            'data': function(data)
            {
                var search = $('#search').val();

                data.searchSearch = search;
            }
        },
        columns: [
                { data: 'sl' ,orderable: false},
                { data: 'vehicle_no',orderable: false},
                { data: 'permit_valid',orderable: false},
                { data: 'tax_valid',orderable: false},
                { data: 'fitness_valid',orderable: false},
                { data: 'insurance_valid',orderable: false},
            ],
        });

        $('#daterange').on('click', function() 
        {
            dataTable.draw();
        });
        
    });
</script>

@endsection