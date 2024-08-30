@extends('admin.layouts.master.master')


@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Companies List</h2>
    </div>
    <div class="col-lg-2 d-flex justify-content-end">
        <a href="{{ url('admin/client/add') }}" class="btn btn-primary "><strong>Add</strong></a>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight"> 
    <div class="row">

        <div class="col-lg-4">
            <div class="form-group">
                <label>Search</label>
                <input type="text" class="form-control banner-input" id="search" placeholder="Type company name,mobile no." name="search" >      
            </div> 
        </div>
        

        <div class="col-lg-4">
           
            <button class="btn btn-primary " style="margin-top: 28px;" id="daterange"><strong>Filter</strong></button>
            <button type="button" style="margin-top: 28px;" onclick="refresh_page()" class="btn btn-info">Refresh</button>
        
        </div>

        
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">

                </div>

                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-hover" id="client_data_table">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Company Name</th>
                                    <th>Company Logo</th>
                                    <th>Company Contact No.</th>
                                    <th>GST No.</th>
                                    <th>Challan Format</th>
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



<script>
    jQuery(function() 
    {
        var url=$('#url').val();
        var dataTable = $('#client_data_table').DataTable({
        'scrollX': true,
        'searching': false,
        'processing': true,
        "language": {
            processing: '<div class="preloader"><div class="spinner-layer pl-red"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div> '},
        'serverSide': true,
        'serverMethod': 'get',
        'ordering': true,
        'ajax': {
            'url':url+'/admin/client/get-data-ajax',
            'data': function(data)
            {
                var search = $('#search').val();
                data.searchSearch = search;

            }
        },
        columns: [
                { data: 'sl' ,orderable: false},
                { data: 'name'},
                { data: 'image',orderable: false},
                { data: 'phone',orderable: false},
                { data: 'gst',orderable: false},
                { data: 'challan_format',orderable: false},
                { data: 'status',orderable: false},
                { data: 'action',orderable: false },
                
            ],
            
        });

        $('#daterange').on('click', function() 
        {
            dataTable.draw();
        });
        
    });

    function refresh_page()
    {
        $('#search').val('');
        var table = $('#client_data_table').DataTable();
        var info = table.page.info();
        table.page(info.page).draw('page');

    }
</script>

@endsection
