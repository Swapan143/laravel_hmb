@extends('admin.layouts.master.master')


@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Diesel List</h2>
    </div>
    <div class="col-lg-2 d-flex justify-content-end">
        <a href="{{ url('admin/diesel/add') }}" class="btn btn-primary "><strong>Add</strong></a>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">

        <div class="col-lg-4">
            <div class="form-group">
                <label>From Date</label>
                <input type="hidden" value="{{ $constant_veriable['API_BASE_URL'] }}" id="api_url">
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
            <div class="form-group">
                <label>Vehicle No. </label>
                <select name="vehicle_no" id="vehicle_no" class="form-control banner-input js-example-basic-search" >
                    <option value="">Select vehicle no.</option>
                    @foreach($vehicle_data as $key => $vehicle_row)
                    <option value="<?=$vehicle_row->id?>"><?=$vehicle_row->vehicle_no?></option>
                    @endforeach
                </select>   
            </div> 
        </div>

        <div class="col-lg-3">
            <div class="form-group">
                <label>Transporter Name </label>
                <select name="transporter_id" id="transporter_id" class="form-control banner-input js-example-basic-search" >
                    <option value="">Select transporter name</option>
                    @foreach($transporter_data as $key => $transporter_row)
                    <option value="<?=$transporter_row->id?>"><?=$transporter_row->transporter_name?></option>
                    @endforeach
                </select>   
            </div> 
        </div>

        <div class="col-lg-3">
            <div class="form-group">
                <label>This vehicle serve for the company</label>
                <select name="client_id" id="client_id" class="form-control banner-input js-example-basic-search">
                    <option value="">Select company name</option>
                    @foreach($client_data as $key => $client_row)
                    <option value="<?=$client_row->id?>"><?=$client_row->company_name?></option>
                    @endforeach
                </select>
                  
            </div> 
        </div>

        <div class="col-lg-3">
            <div class="form-group">
                <label>Updated By </label>
                <select name="user_id" id="user_id" class="form-control banner-input js-example-basic-search" >
                    <option value="">Select user</option>
                    <option value="1">Admin</option>
                    @foreach($user_data as $key => $user_row)
                    <option value="<?=$user_row->id?>">{{ get_user_name($user_row->id) }}</option>
                    @endforeach
                </select>   
            </div> 
        </div>

        

        <div class="col-lg-3">
            <button class="btn btn-primary " style="margin-top: 28px;" id="daterange"><strong>Filter</strong></button>
            <button type="button" style="margin-top: 28px;" onclick="refresh_page()" class="btn btn-info">Refresh</button>
            <button class="btn btn-primary " style="margin-top: 28px;" id="download_csv"><strong>CSV</strong></button>
            <a href="{{url('uploads/vehicle/diesel_report_list.csv')}}" download id="download" hidden></a>
        
        </div>

        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <button class="btn btn-danger " style="float: right;" id="delete_multiple"><strong>Delete Multiple</strong></button>
                        <table class="table table-hover" id="diesel_data_table">
                            
                            <thead>
                                <tr>
                                    <th>
                                    
                                        <input type="checkbox"  id="selectAll"  ><span >All</span>
                                    </th> 
                                    <th>Sl. No.</th>
                                    <th>Vehicle No.</th>
                                    <th>Date</th>
                                    <th>Transporter Name</th>
                                    <th>Company Name</th>
                                    <th>Quantity(Ltr)</th> 
                                    <th>Remarks</th>
                                    <th>Diesel Image</th>
                                    <th>Updated By</th>
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
<div class="modal fade" id="diesel_view_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">View Diesel</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="diesel_image_show">
            
        </div>
      </div>
    </div>
</div>


<script>
    jQuery(function() 
    {
        var url=$('#url').val();
        var dataTable = $('#diesel_data_table').DataTable({
        'scrollX': true,
        'searching': false,
        'processing': true,
        "language": {
            processing: '<div class="preloader"><div class="spinner-layer pl-red"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div> '},
        'serverSide': true,
        'serverMethod': 'get',
        'ordering': true,
        'ajax': {
            'url':url+'/admin/diesel/get-data-ajax',
            'data': function(data)
            {
                var vehicle_no = $('#vehicle_no').val();
                var transporter_id = $('#transporter_id').val();
                var Fdate = $('#from_date').val();
                var Tdate = $('#to_date').val();
                var user_id = $('#user_id').val();
                var client_id = $('#client_id').val();
                
                data.searchVehicleNo= vehicle_no;
                data.searchTransporterId = transporter_id;
                data.searchFdate = Fdate;
                data.searchTdate = Tdate;
                data.searchUserId = user_id;
                data.searchClientId= client_id;


            }
        },
        columns: [
                { data: 'chk' ,orderable: false},
                { data: 'sl' ,orderable: false},
                { data: 'vehicle_no'},
                { data: 'created_at' },
                { data: 'transporter_name',orderable: false},
                { data: 'company_name',orderable: false},
                { data: 'quentity',orderable: false},
                { data: 'remarks',orderable: false},
                { data: 'diesel_image',orderable: false},
                { data: 'updated_by',orderable: false},
                { data: 'action',orderable: false },
                
            ],
            "order": [[3, 'desc' ]],
        });

        $('#daterange').on('click', function() 
        {
            dataTable.draw();
        });

        $('#vehicle_no').on('change', function() 
        {
            dataTable.draw();
        });

        $('#transporter_id').on('change', function() 
        {
            dataTable.draw();
        });

        $('#user_id').on('change', function() 
        {
            dataTable.draw();
        });

        $('#client_id').on('change', function() 
        {
            dataTable.draw();
        });

        $('#download_csv').on('click', function() 
        {
            
            var vehicle_no = $('#vehicle_no').val();
            var transporter_id = $('#transporter_id').val();
            var user_id = $('#user_id').val();
            var Fdate = $('#from_date').val();
            var Tdate = $('#to_date').val();
            var url = $('#url').val();
            var client_id = $('#client_id').val();

            $.ajax({
                url:url+'/admin/diesel/download',
                type:'GET',
                data:{
                "_token": $('#csrf_token').val(),
                vehicle_no:vehicle_no,
                transporter_id:transporter_id,
                Fdate:Fdate,
                Tdate:Tdate,
                user_id:user_id,
                client_id:client_id
                },
                success: function(data)
                {
                    document.getElementById('download').click();
                    
                }
            });
        });

        

        
    });

    function refresh_page()
    {
        $('#vehicle_no').val('');
        $('#transporter_id').val('');
        $('#from_date').val('');
        $('#to_date').val('');

        var table = $('#diesel_data_table').DataTable();
        var info = table.page.info();
        table.page(info.page).draw('page');

    }

    function diselImageShow(id)
    {
        var api_url = $('#api_url').val();
        // alert(api_url);
        var current_url = $('#current_url').val();
        $('#diesel_view_modal').modal('show');
        $.ajax({
            url:current_url+'/image',
            type:'GET',
            data:{
            "_token": $('#csrf_token').val(),
            id:id
            },
            success: function(data)
            {
                var image_url=api_url+'storage/diesel/'+data;
                // alert(image_url);
                var image='<img src="'+image_url+'">';
                $('#diesel_image_show').html('');
                $('#diesel_image_show').append(image);
            }
        });
        
    }

    $('#selectAll').click(function(e){
           
           var table= $(e.target).closest('table');
           
           $('.subcheck').prop('checked', this.checked);
          
    });
    
    $('#delete_multiple').click(function(e)
    {

        var selected = new Array();
        jQuery("input[name='selected']").each(function() 
        {
            if(this.checked==true)
            { 
                selected.push(this.value);
            }
            
        });
        
        if(selected.length === 0)
        {
            toastr.options.positionClass = 'toast-top-full-width';
            toastr.error("Please select checkbox");
            return false;
        }
        else
        {
         
            var current_url = $('#url').val();
            $.ajax({
                url:current_url+'/admin/diesel/delete-multiple',
                type:'GET',
                data:{
                "_token": $('#csrf_token').val(),
                selected:selected
                },
                success: function(data)
                {
                    toastr.options.positionClass = 'toast-top-full-width';
                    toastr.success("Deleted sucessfully.");

                    var table = $('#diesel_data_table').DataTable();
                    var info = table.page.info();
                    table.page(info.page).draw('page');
         
                }
            });
        }
    });
</script>

@endsection
