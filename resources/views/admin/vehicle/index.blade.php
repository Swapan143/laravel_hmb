@extends('admin.layouts.master.master')


@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Vehicle List</h2>
    </div>
    <div class="col-lg-2 d-flex justify-content-end">
        {{-- <a href="{{asset('uploads/vehicle/vehicle_demo.csv')}}" class="btn btn-primary mr-1"><strong>Sample Data <i class="fa fa-download"></i></strong></a>
        <a href="{{ url('admin/vehicle/import-csv') }}" class="btn btn-primary mr-1"><strong>Import CSV <i class="fa fa-file"></i></strong></a> --}}
        <a href="{{ url('admin/vehicle/add') }}" class="btn btn-primary "><strong>Add</strong></a>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label>Transporter Name </label>
                <select name="transporter_id" id="transporter_id" class="form-control banner-input " >
                    <option value="">Select transporter name</option>
                    @foreach($transporter_data as $key => $transporter_row)
                    <option value="<?=$transporter_row->id?>"><?=$transporter_row->transporter_name?></option>
                    @endforeach
                </select>   
            </div> 
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label>This vehicle serve for the company</label>
                <select name="client_id" id="client_id" class="form-control banner-input ">
                    <option value="">Select company name</option>
                    @foreach($client_data as $key => $client_row)
                    <option value="<?=$client_row->id?>"><?=$client_row->company_name?></option>
                    @endforeach
                </select>
                  
            </div> 
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label>Siding Location </label>
                <select name="siding_location" id="siding_location" class="form-control banner-input js-example-basic-search" >
                    <option value="">Select siding location</option>
                    @foreach($siding_location_data as $key => $siding_row)
                    <option value="<?=$siding_row->id?>"><?=$siding_row->location_name?></option>
                    @endforeach
                </select>   
            </div> 
        </div>

        <div class="col-lg-4">
            <div class="form-group">
                <label>Search</label>
                <input type="text" class="form-control banner-input" id="search" placeholder="Type text to search" name="search" >      
            </div> 
        </div>
        

        <div class="col-lg-4">
           
            <button class="btn btn-primary " style="margin-top: 28px;" id="daterange"><strong>Filter</strong></button>
            <button class="btn btn-primary " style="margin-top: 28px;" id="download_csv"><strong>Download CSV</strong></button>
            <a href="{{url('uploads/vehicle/vehicle_report_list.csv')}}" download id="download" hidden></a>
        </div>

        
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">

                </div>

                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-hover" id="vehicle_data_table">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Vehicle No.</th>
                                    <th>Vehicle Name</th>
                                    <th>Image</th>
                                    <th>Vehicle Siding Location</th>
                                    <th>Vehicle Chassis No.</th>
                                    <th>Transporter Name</th>
                                    <th>Serving Company</th>
                                    <th>Owner Name</th>
                                    <th>Total Diesel Consumed(Ltr)</th>
                                    <th>Total Trip Done</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="vehicle_company_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="exampleModalLabel"><b>Company Log Details</b></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="log_show">
            
        </div>
      </div>
    </div>
</div>

{{-- vehicle image show --}}
<!-- Modal -->
<div class="modal fade" id="vehicle_view_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">View Image</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="vehicle_image_show">
            
        </div>
      </div>
    </div>
</div>





<script>
    jQuery(function() 
    {
        var url=$('#url').val();
        var dataTable = $('#vehicle_data_table').DataTable({
        'scrollX': true,
        'searching': false,
        'processing': true,
        "language": {
            processing: '<div class="preloader"><div class="spinner-layer pl-red"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div> '},
        'serverSide': true,
        'serverMethod': 'get',
        'ordering': true,
        'ajax': {
            'url':url+'/admin/vehicle/get-data-ajax',
            'data': function(data)
            {
                var transporter_id = $('#transporter_id').val();
                var client_id = $('#client_id').val();
                var search = $('#search').val();
                var siding_location = $('#siding_location').val();

                data.searchTransporter_id = transporter_id;
                data.searchClient_id= client_id;
                data.searchSearch = search;
                data.siding_location = siding_location;
                
            }
        },
        columns: [
                { data: 'sl' ,orderable: false},
                { data: 'vehicle_no',orderable: false},
                { data: 'vehicle_name',orderable: false},
                { data: 'vehicle_image',orderable: false},
                { data: 'vehicle_siding_location',orderable: false},
                { data: 'vehicle_chassis_no',orderable: false},
                { data: 'transporter_name',orderable: false},
                { data: 'company_name',orderable: false},
                { data: 'owner_name',orderable: false},
                { data: 'diesel_consumed',orderable: false},
                { data: 'trip_done',orderable: false},
                { data: 'status',orderable: false},
                { data: 'action',orderable: false },
                
            ],
           
        });

        $('#daterange').on('click', function() 
        {
            dataTable.draw();
        });

        $('#transporter_id').on('change', function() 
        {
            dataTable.draw();
        });

        $('#client_id').on('change', function() 
        {
            dataTable.draw();
        });

        $('#siding_location').on('change', function() 
        {
            dataTable.draw();
        });

        $('#download_csv').on('click', function() 
        {
            
            var transporter_id = $('#transporter_id').val();
            var client_id = $('#client_id').val();
            var search = $('#search').val();
            var url = $('#url').val();

            $.ajax({
                url:url+'/admin/vehicle/download',
                type:'GET',
                data:{
                "_token": $('#csrf_token').val(),
                transporter_id:transporter_id,
                client_id:client_id,
                search:search
                },
                success: function(data)
                {
                    document.getElementById('download').click();
                    
                }
            });
        });
        
    });

    function viewLog(id)
    {
        var vehicle_id =id;
       
        $('#vehicle_company_modal').modal('show');
        var current_url = $('#current_url').val();
        $.ajax({
            url:current_url+'/log-details',
            type:'GET',
            data:{
            "_token": $('#csrf_token').val(),
            vehicle_id:vehicle_id
            },
            success: function(data)
            {
                $('#log_show').html("");
                $('#log_show').html(data);
                
            }
        });
    }

    function vehicleImageShow(data)
    {
        
        var url = $('#url').val();
        $('#vehicle_view_modal').modal('show');
        var image_url=url+'/uploads/vehicle/'+data;
                
        var image='<img src="'+image_url+'">';
        $('#vehicle_image_show').html('');
        $('#vehicle_image_show').append(image);
        
    }
</script>

@endsection
