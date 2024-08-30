<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Client;
use App\Models\Transporter;
use App\Models\Challan;
use App\Models\Location;
use App\Models\Diesel;
use Illuminate\Support\Facades\DB;



class DieselReportController extends Controller
{
    private $viewFolder = 'admin/report/';
    private $routePrefix = 'admin/report';

   
    public function diesel()
    {
        $client_data        = Client::where('status',1)->where('deleted_at',NULL)->get();
        $transporter_data        = Transporter::where('status',1)->where('deleted_at',NULL)->get();
    	return view('admin/report/diesel',compact('transporter_data','client_data'));
    }

    public function getDieselDataAjax(Request $request)
    {
        $draw = $request->get('draw');
        //dd($draw);
        $start = $request->get("start");
        $rowperpage = $request->get("length");

        $columnIndex_arr = $request->get('order');
        //dd($columnIndex_arr);
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        //$searchValue = $search_arr['value'];
        // $searchValue = $request->searchSearch;

     

        $recordsQuery=Diesel::selectRaw("distinct(tbl_diesels.vehicle_id) as vehicle_id")
        ->selectRaw("SUM(tbl_diesels.quantity) as total_diesel")
        ->leftjoin('tbl_vehicles','tbl_diesels.vehicle_id','=','tbl_vehicles.id')
        ->leftjoin('tbl_transporters','tbl_diesels.transporter_id','=','tbl_transporters.id')
        ->where('tbl_diesels.deleted_at',NULL);

        if($request->searchFdate!="" && $request->searchTdate!="")
        {
            $start_date=date("Y-m-d", strtotime($request->searchFdate));
            $end_date=date("Y-m-d", strtotime($request->searchTdate));
            $recordsQuery = $recordsQuery
            ->whereDate('tbl_diesels.date_time', '>=', $start_date)
            ->whereDate('tbl_diesels.date_time', '<=', $end_date);
        }

        if($request->searchTransporterId!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_transporters.id', $request->searchTransporterId);
        }

        if($request->searchClientId!="" )
        {
            $recordsQuery = $recordsQuery->where('tbl_vehicles.client_id', $request->searchClientId);
        }

        $records =$recordsQuery
        ->groupBy('tbl_diesels.vehicle_id')
        ->get();
       
        
        $totalRecords = 0;
        $totalRecordswithFilter = $totalRecords;
        
        
        $totalRecordswithFilter = $totalRecords;

        $data_arr = array();

        foreach($records as $k=> $record)
        {
        
            $sl=$k+1;
            $vehicle_data=Vehicle::where('id', $record->vehicle_id)->first();
            $vehicle_name=$vehicle_data->vehicle_name;
            $vehicle_number=$vehicle_data->vehicle_no;
            $total_diesel=$record->total_diesel;
            $data_arr[] = array(
                'sl'=>$sl,
                "vehicle_number" =>$vehicle_number,
                "vehicle_name" => $vehicle_name,
                "total_diesel" =>$total_diesel,
                
                );
            

        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );
        //dd($response);
        echo json_encode($response);
       
    }

    public function dieselReportDownload(Request $request)
    {
       
        $recordsQuery=Diesel::selectRaw("distinct(tbl_diesels.vehicle_id) as vehicle_id")
        ->selectRaw("SUM(tbl_diesels.quantity) as total_diesel")
        ->leftjoin('tbl_vehicles','tbl_diesels.vehicle_id','=','tbl_vehicles.id')
        ->leftjoin('tbl_transporters','tbl_diesels.transporter_id','=','tbl_transporters.id')
        ->where('tbl_diesels.deleted_at',NULL);

        if($request->searchFdate!="" && $request->searchTdate!="")
        {
            $start_date=date("Y-m-d", strtotime($request->searchFdate));
            $end_date=date("Y-m-d", strtotime($request->searchTdate));
            $recordsQuery = $recordsQuery
            ->whereDate('tbl_diesels.date_time', '>=', $start_date)
            ->whereDate('tbl_diesels.date_time', '<=', $end_date);
        }

        if($request->searchTransporterId!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_transporters.id', $request->searchTransporterId);
        }

        if($request->searchClientId!="" )
        {
            $recordsQuery = $recordsQuery->where('tbl_vehicles.client_id', $request->searchClientId);
        }

        $records =$recordsQuery
        ->groupBy('tbl_diesels.vehicle_id')
        ->get();
       
    
        $data_arr = array();

        $fileName = 'diesel_report_list.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $delimiter = ","; 
        $filename = "diesel_report_list_" . date('Y-m-d') . ".csv"; 
        $f = fopen('uploads/report/diesel_report_list.csv', 'w');

        $fields = array('Sl. No.', 'Vehicle Name', 'Vehicle Number', 'Total Quantity Taken(Ltr)'); 
        fputcsv($f, $fields, $delimiter); 

        foreach($records as $k=> $record)
        {
        
            $sl=$k+1;
            $vehicle_data=Vehicle::where('id', $record->vehicle_id)->first();
            $vehicle_name=$vehicle_data->vehicle_name;
            $vehicle_number=$vehicle_data->vehicle_no;
            $total_diesel=$record->total_diesel;
            
            $lineData = array($sl,$vehicle_number,$vehicle_name,$total_diesel); 

            fputcsv($f, $lineData, $delimiter);

        }
    
        

        // Move back to beginning of file 
        fseek($f, 0); 
    
        // Set headers to download file rather than displayed 
        header('Content-Type: text/csv'); 
        header('Content-Disposition: attachment; filename="' . $filename . '";'); 
        
        //output all remaining data on a file pointer 
        // fpassthru($f); 
        fclose($f);
        return "success";

        
    }

    
}
