@extends('admin.layouts.master.master')


@section('content')
<link href="{{ URL::asset('assets/admin') }}/css/daterangepicker.css" rel="stylesheet">
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Transporters Report</h2>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-md-4">
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

        <div class="col-lg-4">
            <div class="form-group">
                <label>Search</label>
                <input type="text" class="form-control banner-input" id="search" placeholder="Type transporter name,mobile" name="search" >      
            </div> 
        </div>
        

        <div class="col-lg-4">
           
            <button class="btn btn-primary " style="margin-top: 28px;" id="daterange"><strong>Filter</strong></button>
            <button class="btn btn-primary " style="margin-top: 28px;" id="download_csv"><strong><i class="fa fa-download" aria-hidden="true"></i>
                CSV Download</strong></button>
            <a href="{{url('uploads/report/transporter_report_list.csv')}}" download id="download" hidden></a>
        
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
                                    <th>Phone</th>
                                    <th>No. of Vehicle </th>
                                    <th>Total Challan</th>
                                    <th>Net Wt(MT)</th>
                                    <th>Total Diesel</th>
                                    
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
        'ajax': {
            'url':url+'/admin/report/get-data-transporter-report-ajax',
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
                { data: 'no_of_vehicle',orderable: false},
                { data: 'total_challan',orderable: false},
                { data: 'net_wt',orderable: false },
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
            var search = $('#search').val();
           

            $.ajax({
                url:url+'/admin/report/transporter-download',
                type:'GET',
                data:{
                "_token": $('#csrf_token').val(),
                searchSearch:search,
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
