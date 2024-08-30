<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Client;
use App\Models\Transporter;
use App\Models\Challan;
use App\Models\Location;
use App\Models\Charge;
use Illuminate\Support\Facades\DB;



class ReportController extends Controller
{
    private $viewFolder = 'admin/vehicle/';
    private $routePrefix = 'admin/vehicle';

   
    public function dateWiseReport()
    {
        $master_data=DB::table('tbl_masters')->latest()->first();
        $vehicle_data        = Vehicle::where('status',1)->where('deleted_at',NULL)->get();
        $client_data        = Client::where('status',1)->where('deleted_at',NULL)->get();
        $transporter_data        = Transporter::where('status',1)->where('deleted_at',NULL)->get();
        $siding_location_data        = Location::where('status',1)->where('location_type',2)->where('deleted_at',NULL)->get();
        $loading_location_data        = Location::where('status',1)->where('location_type',1)->where('deleted_at',NULL)->get();
    	return view($this->viewFolder.'date_wise_report',compact('vehicle_data','client_data','transporter_data','siding_location_data','loading_location_data','master_data'));
    }

    public function getDateWiseReportAjax(Request $request)
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

        $challan_date=$request->year.'-'.$request->month;

        $recordsQuery=Challan::selectRaw("distinct(tbl_challans.vehicle_id) as vehicle_id")
        ->selectRaw("COUNT(tbl_challans.vehicle_id) as trips")
        ->selectRaw("SUM(tbl_challans.net_weight) as net_weight")
        ->leftjoin('tbl_vehicles','tbl_challans.vehicle_id','=','tbl_vehicles.id')
        ->leftjoin('tbl_transporters','tbl_vehicles.transporter_id','=','tbl_transporters.id')
        ->where('tbl_challans.deleted_at',NULL)
        ->where('tbl_transporters.id', $request->transporter_id)
        ->where('tbl_challans.client_id', $request->client_id)
        ->where('tbl_challans.siding_location_id', $request->siding_location)
        ->where('tbl_challans.challan_date', 'like', "%$challan_date%");
        $records =$recordsQuery
        ->groupBy('tbl_challans.vehicle_id')
        ->get();
       
        
        $totalRecords = 0;
        $totalRecordswithFilter = $totalRecords;

        $data_arr = array();

        
        $master_data=Charge::where('tbl_charges.location_id',$request->siding_location)->where('tbl_charges.year', $request->year)->where('tbl_charges.month', $request->month)->where('tbl_charges.client_id', $request->client_id)->where('tbl_charges.deleted_at',NULL)->first();

        $total_trips=$total_mt=$total_feright_mt=$total_hsd_ltr_issues=$total_hsd_price_ltr=$total_acc_add_diff=$total_tanker_fare=$total_payable_amt=$total=$total_sub=0;
  
        foreach($records as $k=> $record)
        {
        
            $sl=$k+1;
            $vehicle_no=get_vehicle_name($record->vehicle_id,"1");
            $trips=$record->trips;
            $mt=round($record->net_weight,2);
            $feright_mt=round($mt*@$master_data->freight_charge,2);
            $hsd_ltr_issues=round(DB::table('tbl_diesels')->where('deleted_at',NULL)->where('vehicle_id',$record->vehicle_id)->where('tbl_diesels.date_time', 'like', "%$challan_date%")->sum('quantity'),2);
            $hsd_price_ltr=round($hsd_ltr_issues*@$master_data->diesel_price,2);
            $total=round($feright_mt- $hsd_price_ltr,2);
            $acc_add_diff=round(@$master_data->accidental_rate,2);
            $tanker_fare=round(@$master_data->tanker_fare,2);
            $payable_amt=round(($total-$acc_add_diff-$tanker_fare),2);

            $total_trips=$total_trips+$trips;
            $total_mt=$total_mt+$mt;
            $total_feright_mt=$total_feright_mt+$feright_mt;
            $total_hsd_ltr_issues=$total_hsd_ltr_issues+$hsd_ltr_issues;
            $total_hsd_price_ltr=$total_hsd_price_ltr+$hsd_price_ltr;
            $total_sub=$total_sub+$total;
            $total_acc_add_diff=$total_acc_add_diff+$acc_add_diff;
            $total_tanker_fare=$total_tanker_fare+$tanker_fare;
            $total_payable_amt=$total_payable_amt+$payable_amt;

           
            
                $data_arr[] = array(
                'sl'=>$sl,
                "vehicle_no" => $vehicle_no,
                "trips" => $trips,
                "mt" =>$mt,
                "feright_mt" =>$feright_mt,
                "hsd_ltr_issues" =>$hsd_ltr_issues,
                "hsd_price_ltr" =>$hsd_price_ltr,
                "total" =>$total,
                "acc_add_diff" =>$acc_add_diff,
                "tanker_fare" =>$tanker_fare,
                "payable_amt" =>$payable_amt,
                );
            

        }

        if($total_trips)
        {
            $data_arr[] = array(
                'sl'=>'',
                "vehicle_no" => 'Subtotal',
                "trips" => $total_trips,
                "mt" =>round($total_mt,2),
                "feright_mt" =>round($total_feright_mt,2),
                "hsd_ltr_issues" =>round($total_hsd_ltr_issues,2),
                "hsd_price_ltr" =>round($total_hsd_price_ltr,2),
                "total" =>round($total_sub,2),
                "acc_add_diff" =>round($total_acc_add_diff,2),
                "tanker_fare" =>round($total_tanker_fare,2),
                "payable_amt" =>round($total_payable_amt,2),
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

    public function download(Request $request)
    {

        $challan_date=$request->year.'-'.$request->month;

        $recordsQuery=Challan::selectRaw("distinct(tbl_challans.vehicle_id) as vehicle_id")
        ->selectRaw("COUNT(tbl_challans.vehicle_id) as trips")
        ->selectRaw("SUM(tbl_challans.net_weight) as net_weight")
        ->leftjoin('tbl_vehicles','tbl_challans.vehicle_id','=','tbl_vehicles.id')
        ->leftjoin('tbl_transporters','tbl_vehicles.transporter_id','=','tbl_transporters.id')
        ->where('tbl_challans.deleted_at',NULL)
        ->where('tbl_transporters.id', $request->transporter_id)
        ->where('tbl_challans.client_id', $request->client_id)
        ->where('tbl_challans.siding_location_id', $request->siding_location)
        ->where('tbl_challans.challan_date', 'like', "%$challan_date%");

   
        $records =$recordsQuery->groupBy('tbl_challans.vehicle_id')->get();
        
        $fileName = 'date_wise_report_list.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $delimiter = ","; 
        $filename = "vehicle_report_list_" . date('Y-m-d') . ".csv"; 
        $f = fopen('uploads/vehicle/date_wise_report_list.csv', 'w');


        $client=Client::where('id',$request->client_id)->first();
        $transporter=Transporter::where('id',$request->transporter_id)->first();
        $location=Location::where('id',$request->siding_location)->first();

        $master_data=Charge::where('tbl_charges.location_id',$request->siding_location)->where('tbl_charges.year', $request->year)->where('tbl_charges.month', $request->month)->where('tbl_charges.client_id', $request->client_id)->where('tbl_charges.deleted_at',NULL)->first();
       
        $my=$request->year.'-'.$request->month.'-01';

        $title=date("M-Y", strtotime($my)).'-'.$transporter->transporter_name.'-'.$location->location_name.'-'.$client->company_name;
    	echo $title.'##'.@$master_data->freight_charge."##".@$master_data->diesel_price."##".@$master_data->accidental_rate."##".@$master_data->tanker_fare;

        $title=date("M-Y", strtotime($my)).'-'.$transporter->transporter_name.'-'.$location->location_name.'-'.$client->company_name;
        $show_f='Feright/MT ('.@$master_data->freight_charge.')';
        $show_d='HSD Price/Ltr ('.@$master_data->diesel_price.')';
        $show_a='Acc Add Diff ('.@$master_data->accidental_rate.')';
        $show_t='Tanker Fare ('.@$master_data->tanker_fare.')';

        $fields = array('', '', '', '',$title,'','','', '', '',''); 
        fputcsv($f, $fields, $delimiter); 
        $fields = array('Sl. No.', 'Vehicle No.', 'Trips', 'MT',$show_f,'HSD Ltr Issues',$show_d,'Total', $show_a, $show_t,'Payable Amt'); 
        fputcsv($f, $fields, $delimiter); 
       

        

        $total_trips=$total_mt=$total_feright_mt=$total_hsd_ltr_issues=$total_hsd_price_ltr=$total_acc_add_diff=$total_tanker_fare=$total_payable_amt=$total=$total_sub=0;

        foreach($records as $k=> $record)
        {

            $sl=$k+1;
            $vehicle_no=get_vehicle_name($record->vehicle_id,"1");
            $trips=$record->trips;
            $mt=round($record->net_weight,2);
            $feright_mt=round($mt*@$master_data->freight_charge,2);
            $hsd_ltr_issues=round(DB::table('tbl_diesels')->where('deleted_at',NULL)->where('vehicle_id',$record->vehicle_id)->where('tbl_diesels.date_time', 'like', "%$challan_date%")->sum('quantity'),2);
            $hsd_price_ltr=round($hsd_ltr_issues*@$master_data->diesel_price,2);
            $total=round($feright_mt- $hsd_price_ltr,2);
            $acc_add_diff=round(@$master_data->accidental_rate,2);
            $tanker_fare=round(@$master_data->tanker_fare,2);
            $payable_amt=round(($total-$acc_add_diff-$tanker_fare),2);

            $total_trips=$total_trips+$trips;
            $total_mt=$total_mt+$mt;
            $total_feright_mt=$total_feright_mt+$feright_mt;
            $total_hsd_ltr_issues=$total_hsd_ltr_issues+$hsd_ltr_issues;
            $total_hsd_price_ltr=$total_hsd_price_ltr+$hsd_price_ltr;
            $total_sub=$total_sub+$total;
            $total_acc_add_diff=$total_acc_add_diff+$acc_add_diff;
            $total_tanker_fare=$total_tanker_fare+$tanker_fare;
            $total_payable_amt=$total_payable_amt+$payable_amt;

            $lineData = array($sl,$vehicle_no,$trips,$mt,$feright_mt,$hsd_ltr_issues,$hsd_price_ltr,$total,$acc_add_diff,$tanker_fare,$payable_amt); 

            fputcsv($f, $lineData, $delimiter); 
            

        }

        $lineData = array('','','','','','','','','','',''); 
        fputcsv($f, $lineData, $delimiter); 

        $lineData = array('','Subtotal',$total_trips,$total_mt,$total_feright_mt,$total_hsd_ltr_issues,$total_hsd_price_ltr,$total_sub,$total_acc_add_diff,$total_tanker_fare,$total_payable_amt); 
        fputcsv($f, $lineData, $delimiter); 

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

    public function locationList(Request $request)
    {
        $input  =   $request->all();

        $all_location             = Location::where('status',1)->where('location_type',2)->where('client_id',$input['client_id'])->where('deleted_at',NULL)->get();
        $html='<option value="">Select location</option>';
        foreach($all_location as $location)
        {
           
            $html .='<option value="'.$location->id.'" >'.$location->location_name.'</option>';

        }
    	return $html;
    }

    public function showTitle(Request $request)
    {
        
        $client=Client::where('id',$request->client_id)->first();
        $transporter=Transporter::where('id',$request->transporter_id)->first();
        $location=Location::where('id',$request->siding_location)->first();

        $master_data=Charge::where('tbl_charges.location_id',$request->siding_location)->where('tbl_charges.year', $request->year)->where('tbl_charges.month', $request->month)->where('tbl_charges.client_id', $request->client_id)->where('tbl_charges.deleted_at',NULL)->first();
       
        $my=$request->year.'-'.$request->month.'-01';

        $title=date("M-Y", strtotime($my)).'-'.$transporter->transporter_name.'-'.$location->location_name.'-'.$client->company_name;
    	echo $title.'##'.@$master_data->freight_charge."##".@$master_data->diesel_price."##".@$master_data->accidental_rate."##".@$master_data->tanker_fare;
    }

    
}
