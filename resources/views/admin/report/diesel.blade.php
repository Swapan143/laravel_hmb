@extends('admin.layouts.master.master')


@section('content')
<link href="{{ URL::asset('assets/admin') }}/css/daterangepicker.css" rel="stylesheet">
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Diesel Report</h2>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
            <label for="sel1">Date Filter</label>
                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                    <input type="hidden" name="from_date" id="from_date" value="" /> 
                    <input type="hidden" name="to_date" id="to_date" value=""  />
                </div>
            </div>
        </div>

        <div class="col-lg-6">
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

        <div class="col-lg-4">
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
        

        <div class="col-lg-4">
           
            <button class="btn btn-primary " style="margin-top: 28px;" id="daterange"><strong>Filter</strong></button>
            <button class="btn btn-primary " style="margin-top: 28px;" id="download_csv"><strong><i class="fa fa-download" aria-hidden="true"></i>
                CSV Download</strong></button>
            <a href="{{url('uploads/report/diesel_report_list.csv')}}" download id="download" hidden></a>
        
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
                                    <th>Vehicle Number</th>
                                    <th>Vehicle Name</th>
                                    <th>Total Quantity Taken(Ltr)</th> 
                                    
                                </tr>
                            </thead>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::asset('assets/admin') }}/js/moment.min.js"></script>
<script src="{{ URL::asset('assets/admin') }}/js/daterangepicker.min.js"></script>
<script type="text/javascript">
    jQuery(function() 
    {
        var start = moment();
        var end = moment();
    
        function cb(start, end) 
        {
            //console.log('ddddddddddddfffffffffff',start.format('YYYY-MM-DD'));
            $('#from_date').val(start.format('YYYY-MM-DD'));
            $('#to_date').val(end.format('YYYY-MM-DD'));
            
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
    
        cb(start, end);
    
    });
</script>



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
        'paging': false,
        'ajax': {
            'url':url+'/admin/report/get-data-diesel-report-ajax',
            'data': function(data)
            {
                var Fdate = $('#from_date').val();
                var Tdate = $('#to_date').val();
                var transporter_id = $('#transporter_id').val();
                var client_id = $('#client_id').val();

                data.searchFdate = Fdate;
                data.searchTdate = Tdate;
                data.searchTransporterId = transporter_id;
                data.searchClientId= client_id;

            }
        },
        columns: [
                { data: 'sl' ,orderable: false},
                { data: 'vehicle_number',orderable: false},
                { data: 'vehicle_name'},
                { data: 'total_diesel',orderable: false},
                
                
            ],
           
        });

        $('#daterange').on('click', function() 
        {
            dataTable.draw();
        });

        $('#download_csv').on('click', function() 
        {
            
            var Fdate = $('#from_date').val();
            var Tdate = $('#to_date').val();
            var transporter_id = $('#transporter_id').val();
            var client_id = $('#client_id').val();

            $.ajax({
                url:url+'/admin/report/diesel-download',
                type:'GET',
                data:{
                "_token": $('#csrf_token').val(),
                transporter_id:transporter_id,
                client_id:client_id,
                searchFdate:Fdate,
                searchTdate:Tdate,
                },
                success: function(data)
                {
                    document.getElementById('download').click();
                    
                }
            });
        });
        
    });

    
</script>

@endsection
