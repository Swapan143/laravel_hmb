@extends('admin.layouts.master.master')


@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Charges List</h2>
    </div>
    <div class="col-lg-2 d-flex justify-content-end">
        <a href="{{ url('admin/charges/add/'.$id) }}" class="btn btn-primary " ><strong>Add</strong></a>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <input type="hidden"id="location_id" value="{{ $id }}">
        <div class="col-lg-3">
            <div class="form-group">
                <label>Search</label>
                <input type="text" class="form-control banner-input" id="search" placeholder="Type company name,location name" name="search" >      
            </div> 
        </div>

        <div class="col-lg-3">
            <div class="form-group">
                <label>Month <span style="color:red">*</span></label>
                <select name="month" id="month" class="form-control banner-input" data-validation-engine="validate[required]" data-errormessage-value-missing="Month is required" data-prompt-position="bottomLeft">
                    <option selected disabled>Select month</option>
                    <option value="01" >January</option>
                    <option value="02" >February</option>
                    <option value="03" >March</option>
                    <option value="04" >April</option>
                    <option value="05" >May</option>
                    <option value="06" >June</option>
                    <option value="07" >July</option>
                    <option value="08" >August</option>
                    <option value="09" >September</option>
                    <option value="10" >October</option>
                    <option value="11" >November</option>
                    <option value="12" >December</option>
                </select>
                @if($errors->has('month'))
                <div class="error">{!! $errors->first('month') !!}</div>
                @endif
                
            </div>
        </div>

        <div class="col-lg-3">
            <div class="form-group">
                <label>Year <span style="color:red">*</span></label>
                <select name="year" id="year" class="form-control banner-input" data-validation-engine="validate[required]" data-errormessage-value-missing="Year is required" data-prompt-position="bottomLeft">
                    <option selected disabled>Select year</option>
                    @for($i=2020; $i <= 2040; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                @if($errors->has('year'))
                <div class="error">{!! $errors->first('year') !!}</div>
                @endif
                
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
                                    <th>Year Month</th>
                                    <th>Tanker Charge</th>
                                    <th>Freight Charge</th>
                                    <th>Diesel Charge</th>
                                    <th>accidental Charge</th>
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
            'url':url+'/admin/charges/get-data-ajax',
            'data': function(data)
            {
                var search = $('#search').val();
                var location_id = $('#location_id').val();
                var year = $('#year').val();
                var month = $('#month').val();
                
              
                data.searchSearch = search;
                data.location_id = location_id;
                data.year = year;
                data.month = month;


            }
        },
        columns: [
                { data: 'sl' ,orderable: false},
                { data: 'client_name'},
                { data: 'location_name'},
                { data: 'year_month',orderable: false},
                { data: 'tanker_fare',orderable: false},
                { data: 'freight_charge',orderable: false},
                { data: 'diesel_price',orderable: false},
                { data: 'accidental_rate',orderable: false},
                { data: 'status',orderable: false},
                { data: 'action',orderable: false },
                
            ]
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
        $('#month').val('');
        $('#year').val('');
        $('#search').val('');
        $('#type').val('');
        var table = $('#location_data_table').DataTable();
        var info = table.page.info();
        table.page(info.page).draw('page');

    }
</script>

@endsection
