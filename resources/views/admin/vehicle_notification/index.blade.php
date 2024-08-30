@extends('admin.layouts.master.master')


@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Vehicle Notification List</h2>
    </div>
    <div class="col-lg-2 d-flex justify-content-end">
        <a href="{{ url('admin/vehicle-notification/add') }}" class="btn btn-primary "><strong>Add</strong></a>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-3">
            <div class="form-group">
                <label>Search</label>
                <input type="text" class="form-control banner-input" id="search" placeholder="Type anything..." name="search" >      
            </div> 
        </div>

        <div class="col-lg-3">
           
            <button class="btn btn-primary " style="margin-top: 28px;" id="daterange"><strong>Filter</strong></button>
            {{-- <a href="{{ url('admin/vehicle-notification/csv-export') }}" class="btn btn-primary " style="margin-top: 28px;"><strong>Export CSV</strong></a> --}}
            {{-- <button class="btn btn-info" style="margin-top: 28px;" onclick="exportCsv();">Export CSV</button>
            <a href="{{url('uploads/vehicle/vehicle_report_list.csv')}}" download id="download" hidden></a> --}}
        
        </div>

        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="mt-2">
                        <span class ="badge" style=" border-color:#FF0000;background-color: #FF0000; color:#fff">Expired</span>
                        <span class ="badge" style=" border-color:#FFFF00;background-color: #FFFF00; color:#1a1717"> Renew Immediately</span>
                        <span class ="badge" style=" border-color:#008000;background-color: #008000; color:#fff">Valid</span>
                        <span style="color:#020202;float: right;font-weight: bold;">Yellow = Document expiry date is between 10 days from expire date </span>
                    </div>
                </div>

                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-hover" id="vehicle_data_table">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Vehicle No</th>
                                    <th>Permit Valid Upto</th>
                                    <th>Tax Valid Upto</th>
                                    <th>Fitness Valid Upto</th>
                                    <th>Insurance Valid Upto</th>
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
            'url':url+'/admin/vehicle-notification/get-data-ajax',
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
                { data: 'status',orderable: false},
                { data: 'action',orderable: false },
                
            ],
        });

        $('#daterange').on('click', function() 
        {
            dataTable.draw();
        });
        
    });

    function exportCsv() 
    {
        var url=$('#url').val();
        jQuery.ajax({
            url: url+'/admin/vehicle-notification/csv-export',
            data: {
                
            },
            type: "GET",
            success: function(data) 
            {
                document.getElementById('download').click();
            }
        });
    
}
</script>

@endsection
