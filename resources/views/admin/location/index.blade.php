@extends('admin.layouts.master.master')


@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Locations List</h2>
    </div>
    <div class="col-lg-2 d-flex justify-content-end">
        <a href="{{ url('admin/location/add') }}" class="btn btn-primary " ><strong>Add</strong></a>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">

        <div class="col-lg-4">
            <div class="form-group">
                <label>Search</label>
                <input type="text" class="form-control banner-input" id="search" placeholder="Type company name,location name" name="search" >      
            </div> 
        </div>

        <div class="col-lg-4">
            <div class="form-group">
                <label>Location Type</label>
                <select name="type" id="type" class="form-control banner-input validate[required]" data-errormessage-value-missing="Type is required" data-prompt-position="bottomLeft">
                    <option value="" selected disabled>Select location type</option>
                    <option value="1">Loading Location</option>
                    <option value="2" >Siding Location</option>
                    
                </select>   
            </div> 
        </div>

        <div class="col-lg-3">
            <button class="btn btn-primary " style="margin-top: 28px;" id="daterange"><strong>Filter</strong></button>
            <button type="button" style="margin-top: 28px;" onclick="refresh_page()" class="btn btn-info">Refresh</button>
        
        </div>
        

        

        
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">

                </div>

                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-hover" id="location_data_table">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Company Name</th>
                                    <th>Location Name</th>
                                    <th>Location Type</th>
                                    <th>Charge</th>
                                    <th>Status</th>
                                    <th>Date</th>
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



<script>
    jQuery(function() 
    {
        var url=$('#url').val();
        var dataTable = $('#location_data_table').DataTable({
        'scrollX': true,
        'searching': false,
        'processing': true,
        "language": {
            processing: '<div class="preloader"><div class="spinner-layer pl-red"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div> '},
        'serverSide': true,
        'serverMethod': 'get',
        'ordering': true,
        'ajax': {
            'url':url+'/admin/location/get-data-ajax',
            'data': function(data)
            {
                var search = $('#search').val();
                var type = $('#type').val();
                data.searchType = type;
                data.searchSearch = search;


            }
        },
        columns: [
                { data: 'sl' ,orderable: false},
                { data: 'client_id'},
                { data: 'name'},
                { data: 'type',orderable: false},
                { data: 'charge',orderable: false},
                { data: 'status',orderable: false},
                { data: 'created_at' },
                { data: 'action',orderable: false },
                
            ],
            "order": [[6, 'desc' ]],
        });

        $('#daterange').on('click', function() 
        {
            dataTable.draw();
        });

        $('#type').on('change', function() 
        {
            dataTable.draw();
        });

        
        
    });

    function refresh_page()
    {
        $('#search').val('');
        $('#type').val('');
        var table = $('#location_data_table').DataTable();
        var info = table.page.info();
        table.page(info.page).draw('page');

    }
</script>

@endsection
