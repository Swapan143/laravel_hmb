@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Vehicle Report</h2>
    </div>
    
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-4">
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

        <div class="col-lg-4">
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
    
        <div class="col-lg-4">
            <div class="form-group">
                <label>Transporter Name <span style="color:red">*</span></label>
                <select name="transporter_id" id="transporter_id" class="form-control banner-input js-example-basic-search" >
                    <option value="">Select transporter name</option>
                    @foreach($transporter_data as $key => $transporter_row)
                    <option value="<?=$transporter_row->id?>"><?=$transporter_row->transporter_name?></option>
                    @endforeach
                </select>   
            </div> 
        </div>

        <div class="col-lg-4">
            <div class="form-group">
                <label>This vehicle serve for the company<span style="color:red">*</span></label>
                <select name="client_id" id="client_id" class="form-control banner-input js-example-basic-search" onchange="siddingLocation()">
                    <option value="">Select company name</option>
                    @foreach($client_data as $key => $client_row)
                    <option value="<?=$client_row->id?>"><?=$client_row->company_name?></option>
                    @endforeach
                </select>
                  
            </div> 
        </div>

        <div class="col-lg-4">
            <div class="form-group">
                <label>Siding Location <span style="color:red">*</span></label>
                <select name="siding_location" id="siding_location" class="form-control banner-input js-example-basic-search" >
                    
                </select>   
            </div> 
        </div>
       
        <div class="col-lg-3">
           
            <button class="btn btn-primary " style="margin-top: 28px;" id="daterange"><strong>Filter</strong></button>
            <button type="button" style="margin-top: 28px;" onclick="refresh_page()" class="btn btn-info">Refresh</button>
            <button class="btn btn-primary " style="margin-top: 28px;" id="download_csv"><strong><i class="fa fa-download" aria-hidden="true"></i>
                CSV</strong></button>
            <a href="{{url('uploads/vehicle/date_wise_report_list.csv')}}" download id="download" hidden></a>
            
        </div>

        
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h3 id="show_title"></h3>
                </div>

                <div class="ibox-content">
                    <div class="table-responsive">
                       
                        <table class="table table-hover" id="vehicle_report">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Vehicle No.</th>
                                    <th>Trips</th>
                                    <th>MT</th>
                                    <th>Feright Charge /MT <span id="feright"></span></th>
                                    <th>HSD Ltr Issues</th>
                                    <th>HSD Price/Ltr <span id="hsd"></span></th>
                                    <th>Total</th>
                                    <th>Acc Add Diff <span id="acc"></span></th>
                                    <th>Tanker Fare <span id="tanker"></span></th>
                                    <th>Payable Amt</th>
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
        var dataTable = $('#vehicle_report').DataTable({
        'scrollX': true,
        'searching': false,
        'processing': true,
        "language": {
            processing: '<div class="preloader"><div class="spinner-layer pl-red"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div> '},
        'serverSide': true,
        'serverMethod': 'get',
        'ordering': true,
        'paging': false,
        "rowCallback": function( row, data ) 
        {    
            if ( data.vehicle_no =="Subtotal") 
            {
                $('td', row).css('background-color', '#6bf085');
                $('td', row).css('font-weight', 'bold');
            }
        },
        'ajax': {
            'url':url+'/admin/report/get-data-date-wise-report-ajax',
            'data': function(data)
            {
                var month = $('#month').val();
                var year = $('#year').val();
                var transporter_id = $('#transporter_id').val();
                var client_id = $('#client_id').val();
                var siding_location = $('#siding_location').val();
            
                data.month= month;
                data.year= year;
                data.transporter_id = transporter_id;
                data.client_id= client_id;
                data.siding_location= siding_location;
                
            }
        },
        columns: [
            { data: 'sl' ,orderable: false},
                { data: 'vehicle_no',orderable: false},
                { data: 'trips',orderable: false},
                { data: 'mt',orderable: false},
                { data: 'feright_mt',orderable: false},
                { data: 'hsd_ltr_issues',orderable: false},
                { data: 'hsd_price_ltr',orderable: false},
                { data: 'total',orderable: false},
                { data: 'acc_add_diff',orderable: false},
                { data: 'tanker_fare',orderable: false},
                { data: 'payable_amt',orderable: false},
                
            ],
            
        });

        $('#daterange').on('click', function() 
        {
            var table = $('#vehicle_report').DataTable();
            var info = table.page.info();
            table.page(info.page).draw('page');

            var month = $('#month').val();
            var year = $('#year').val();
            var transporter_id = $('#transporter_id').val();
            var client_id = $('#client_id').val();
            var siding_location = $('#siding_location').val();
            
            if( month =='' || year =='' || transporter_id =='' || client_id ==''|| siding_location =='')
            {
                toastr.options.positionClass = 'toast-top-full-width';
                toastr.error("please select all mandatory field!");
            }
            else
            {   

                $.ajax({
                    url:url+'/admin/report/show-title',
                    type:'GET',
                    data:{
                    "_token": $('#csrf_token').val(),
                    month:month,
                    year:year,
                    transporter_id:transporter_id,
                    client_id:client_id,
                    siding_location:siding_location
                    },
                    success: function(data)
                    {
                        var res=data.split("##");
                       
                        $('#show_title').html('');
                        $('#feright').html('');
                        $('#hsd').html('');
                        $('#acc').html('');
                        $('#tanker').html('');

                        $('#show_title').html(res[0]);
                        $('#feright').html('('+res[1]+')');
                        $('#hsd').html('('+res[2]+')');
                        $('#acc').html('('+res[3]+')');
                        $('#tanker').html('('+res[4]+')');
                    }
                });
                dataTable.draw();
            }
        });

        $('#download_csv').on('click', function() 
        {
            
            var month = $('#month').val();
            var year = $('#year').val();
            var transporter_id = $('#transporter_id').val();
            var client_id = $('#client_id').val();
            var siding_location = $('#siding_location').val();

            $.ajax({
                url:url+'/admin/report/download',
                type:'GET',
                data:{
                "_token": $('#csrf_token').val(),
                month:month,
                year:year,
                transporter_id:transporter_id,
                client_id:client_id,
                siding_location:siding_location
                },
                success: function(data)
                {
                    document.getElementById('download').click();
                    
                }
            });
        }); 
    });



    function siddingLocation()
    {
        var client_id = $('#client_id').val();  
        var url = $('#url').val();
        $.ajax({
            url:url+'/admin/report/location',
            type:'GET',
            data:{
            "_token": $('#csrf_token').val(),
            client_id:client_id
            },
            success: function(data)
            {
                $('#siding_location').html("");
                $('#siding_location').html(data);
                
            }
        });
     
    }

    function refresh_page()
    {
        $('#month').val('');
        $('#year').val('');
        $('#transporter_id').val('');
        $('#client_id').val('');
        $('#siding_location').val('');
        var table = $('#vehicle_report').DataTable();
        var info = table.page.info();
        table.page(info.page).draw('page');
    }
</script>

@endsection
