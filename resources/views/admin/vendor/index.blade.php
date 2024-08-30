@extends('admin.layouts.master.master')


@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Transporters List - {{ $total_count }}</h2>
    </div>
    <div class="col-lg-2 d-flex justify-content-end">
        <a href="{{ url('admin/vendor/add') }}" class="btn btn-primary " ><strong>Add</strong></a>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">

        <div class="col-lg-4">
            <div class="form-group">
                <label>Search</label>
                <input type="text" class="form-control banner-input" id="search" placeholder="Type transporter name,company Name" name="search" >      
            </div> 
        </div>
        

        <div class="col-lg-4">
           
            <button class="btn btn-primary " style="margin-top: 28px;" id="daterange"><strong>Filter</strong></button>
        
        </div>

        
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">

                </div>

                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-hover" id="vendor_data_table">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Transporter Name</th>
                                    <th>Company Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>No. of Vehicle </th>
                                    <th>SMS</th>
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
        var dataTable = $('#vendor_data_table').DataTable({
        'scrollX': true,
        'searching': false,
        'processing': true,
        "language": {
            processing: '<div class="preloader"><div class="spinner-layer pl-red"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div> '},
        'serverSide': true,
        'serverMethod': 'get',
        'ordering': true,
        'ajax': {
            'url':url+'/admin/vendor/get-data-ajax',
            'data': function(data)
            {
                var Fdate = $('#from_date').val();
                var Tdate = $('#to_date').val();
                var search = $('#search').val();

                data.searchFdate = Fdate;
                data.searchTdate = Tdate;
                data.searchSearch = search;

            }
        },
        columns: [
                { data: 'sl' ,orderable: false},
                { data: 'name'},
                { data: 'company_name',orderable: false},
                { data: 'phone',orderable: false},
                { data: 'email',orderable: false},
                { data: 'no_of_vehicle',orderable: false},
                { data: 'sms_status',orderable: false},
                { data: 'status',orderable: false},
                { data: 'action',orderable: false },
                
            ],
           
        });

        $('#daterange').on('click', function() 
        {
            dataTable.draw();
        });
        
    });

    function sms_change(id)
    {
        if(id==null)
        {
            toastr.options.positionClass = 'toast-top-full-width';
            toastr.error("Something went wrong !");
           
        }
        else
        {
            var url = $('#url').val();

            $.ajax({
                url:url+'/admin/vendor/sms',
                type:'GET',
                data:{
                "_token": $('#csrf_token').val(),
                id:id
                },
                success: function(data)
                {
                    if (data) 
                    {
                    toastr.options.positionClass = 'toast-top-full-width';
                    toastr.success("Status updated sucessfully.");
                    }
                    else
                    {
                    toastr.options.positionClass = 'toast-top-full-width';
                    toastr.success("Something went wrong!");
                    }
                    
                }
            });
        }
    }
</script>

@endsection
