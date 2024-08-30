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



class TransporterReportController extends Controller
{
    private $viewFolder = 'admin/report/';
    private $routePrefix = 'admin/report';

   
    public function transporter()
    {
    	return view('admin/report/transporter');
    }

    public function getTransporterDataAjax(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");
        $columnIndex_arr = $request->get('order');
 
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $request->searchSearch;


        $recordsQuery=Transporter::where('deleted_at',NULL);

        if($searchValue!="")
        {
            $recordsQuery=$recordsQuery->where(function ($query) use ($searchValue) {
                $query->where('transporter_name', 'like', '%' .$searchValue . '%')
                ->orwhere('mobile', 'like', '%' .$searchValue . '%');
               
            });
        }
   
        $totalRecords = $recordsQuery->count();
        $records =$recordsQuery->skip($start)
        ->take($rowperpage)
        ->get();
        
        $totalRecordswithFilter = $totalRecords;

        $data_arr = array();
        $count=0;
        foreach($records as $k=> $record)
        {

            $sl=$k+1;
            $name=$record->transporter_name;
            $mobile=$record->mobile;
            $no_of_vehicle=get_no_vehicle($record->id);
            $total_challan=0;
            $net_wt=0;
            $total_diesel=0;

            $challanQuery=Challan::selectRaw("COUNT(tbl_challans.id) as total_challan")
            ->selectRaw("SUM(tbl_challans.net_weight) as net_weight")
            ->leftjoin('tbl_vehicles','tbl_challans.vehicle_id','=','tbl_vehicles.id')
            ->leftjoin('tbl_transporters','tbl_vehicles.transporter_id','=','tbl_transporters.id')
            ->where('tbl_challans.deleted_at',NULL)
            ->where('tbl_transporters.id', $record->id);

            if($request->searchFdate!="" && $request->searchTdate!="")
            {
                $start_date=date("Y-m-d", strtotime($request->searchFdate));
                $end_date=date("Y-m-d", strtotime($request->searchTdate));
                $challanQuery = $challanQuery
                ->whereDate('tbl_challans.created_at', '>=', $start_date)
                ->whereDate('tbl_challans.created_at', '<=', $end_date);
            }

            $challan =$challanQuery->first();
            $total_challan=($challan->total_challan>0)? "$challan->total_challan":"0";
            $net_wt=($challan->net_weight>0)?"$challan->net_weight":"0.00";
         

            $deselQuery=Diesel::where('transporter_id', $record->id)->where('deleted_at',NULL);

            if($request->searchFdate!="" && $request->searchTdate!="")
            {
                $start_date=date("Y-m-d", strtotime($request->searchFdate));
                $end_date=date("Y-m-d", strtotime($request->searchTdate));
                $deselQuery = $deselQuery
                ->whereDate('date_time', '>=', $start_date)
                ->whereDate('date_time', '<=', $end_date);
            }

            $total_diesel = $deselQuery->sum('quantity');

            


            $data_arr[] = array(
            'sl'=>$sl,
            "name" => $name,
            "company_name" =>$mobile,
            "no_of_vehicle" =>$no_of_vehicle,
            "total_challan" =>$total_challan,
            "net_wt" =>$net_wt,
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

    public function transporterReportDownload(Request $request)
    {
       
        $searchValue = $request->searchSearch;


        $recordsQuery=Transporter::where('deleted_at',NULL);

        if($searchValue!="")
        {
            $recordsQuery=$recordsQuery->where(function ($query) use ($searchValue) {
                $query->where('transporter_name', 'like', '%' .$searchValue . '%')
                ->orwhere('mobile', 'like', '%' .$searchValue . '%');
               
            });
        }
   
        $records =$recordsQuery->get();
    
        $fileName = 'date_wise_report_list.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $delimiter = ","; 
        $filename = "transporter_report_list_" . date('Y-m-d') . ".csv"; 
        $f = fopen('uploads/report/transporter_report_list.csv', 'w');

        $fields = array('Sl. No.', 'Transporter Name', 'Phone', 'No. of Vehicle','Total Challan','Net Wt(MT)','Total Diesel'); 
        fputcsv($f, $fields, $delimiter); 
       
        foreach($records as $k=> $record)
        {

            $sl=$k+1;
            $name=$record->transporter_name;
            $mobile=$record->mobile;
            $no_of_vehicle=get_no_vehicle($record->id);
            $total_challan=0;
            $net_wt=0;
            $total_diesel=0;

            $challanQuery=Challan::selectRaw("COUNT(tbl_challans.id) as total_challan")
            ->selectRaw("SUM(tbl_challans.net_weight) as net_weight")
            ->leftjoin('tbl_vehicles','tbl_challans.vehicle_id','=','tbl_vehicles.id')
            ->leftjoin('tbl_transporters','tbl_vehicles.transporter_id','=','tbl_transporters.id')
            ->where('tbl_challans.deleted_at',NULL)
            ->where('tbl_transporters.id', $record->id);

            if($request->searchFdate!="" && $request->searchTdate!="")
            {
                $start_date=date("Y-m-d", strtotime($request->searchFdate));
                $end_date=date("Y-m-d", strtotime($request->searchTdate));
                $challanQuery = $challanQuery
                ->whereDate('tbl_challans.created_at', '>=', $start_date)
                ->whereDate('tbl_challans.created_at', '<=', $end_date);
            }

            $challan =$challanQuery->first();
            $total_challan=($challan->total_challan>0)? "$challan->total_challan":"0";
            $net_wt=($challan->net_weight>0)?"$challan->net_weight":"0.00";
         

            $deselQuery=Diesel::where('transporter_id', $record->id)->where('deleted_at',NULL);

            if($request->searchFdate!="" && $request->searchTdate!="")
            {
                $start_date=date("Y-m-d", strtotime($request->searchFdate));
                $end_date=date("Y-m-d", strtotime($request->searchTdate));
                $deselQuery = $deselQuery
                ->whereDate('date_time', '>=', $start_date)
                ->whereDate('date_time', '<=', $end_date);
            }

            $total_diesel = $deselQuery->sum('quantity');

            $lineData = array($sl,$name,$mobile,$no_of_vehicle,$total_challan,$net_wt,$total_diesel); 

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
