@extends('admin.layouts.master.master')


@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Role List</h2>
    </div>
    <div class="col-lg-2 d-flex justify-content-end">
        <a href="{{ url('admin/role/add') }}" class="btn btn-primary "><strong>Add</strong></a>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">

        {{-- <div class="col-lg-4">
            <div class="form-group">
                <label>From Date</label>
                <input type="date" class="form-control banner-input" id="from_date" placeholder="From date" name="from_date" >      
            </div> 
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label>To Date</label>
                <input type="date" class="form-control banner-input" id="to_date" placeholder="To Date" name="to_date" >
                  
            </div> 
        </div>

        <div class="col-lg-4">
           
            <button class="btn btn-primary " style="margin-top: 28px;" id="daterange"><strong>Filter</strong></button>
        
        </div> --}}

        
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">

                </div>

                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-hover" id="role_data_table">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Role Name</th>
                                    <th>Description</th>
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
        var dataTable = $('#role_data_table').DataTable({
        'scrollX': true,
        'searching': false,
        'processing': true,
        "language": {
            processing: '<div class="preloader"><div class="spinner-layer pl-red"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div> '},
        'serverSide': true,
        'serverMethod': 'get',
        'ordering': true,
        'ajax': {
            'url':url+'/admin/role/get-data-ajax',
            'data': function(data)
            {
                var Fdate = $('#from_date').val();
                var Tdate = $('#to_date').val();

                data.searchFdate = Fdate;
                data.searchTdate = Tdate;

            }
        },
        columns: [
                { data: 'sl' ,orderable: false},
                { data: 'name'},
                { data: 'description',orderable: false},
                { data: 'status',orderable: false},
                { data: 'created_at' },
                { data: 'action',orderable: false },
                
            ],
            "order": [[4, 'desc' ]],
        });

        $('#daterange').on('click', function() 
        {
            dataTable.draw();
        });
        
    });
</script>

@endsection
