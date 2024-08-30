<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Client;
use App\Models\Transporter;
use App\Models\Diesel;
use App\Models\Location;
use App\Models\Master;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Models\User;


class DieselController extends Controller
{
    private $viewFolder = 'admin/diesel/';
    private $routePrefix = 'admin/diesel';

    public function index()
    {
        $constant_veriable= \Config::get('constant');
        $vehicle_data        = Vehicle::where('status',1)->where('deleted_at',NULL)->get();
        $client_data        = Client::where('status',1)->where('deleted_at',NULL)->get();
        $transporter_data        = Transporter::where('status',1)->where('deleted_at',NULL)->get();
        $user_data        = User::where('status',1)->where('id','!=',1)->where('deleted_at',NULL)->get();
      
    	return view($this->viewFolder.'index',compact('vehicle_data','transporter_data','constant_veriable','user_data','client_data'));
    }

    public function getDataAjax(Request $request)
    {
        //echo DB::connection()->getDatabaseName(); die;

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
        $searchValue = $request->searchSearch;


        $recordsQuery=Diesel::distinct('tbl_diesels.id')
        ->select('tbl_diesels.*','tbl_vehicles.vehicle_no','tbl_vehicles.vehicle_name','tbl_transporters.transporter_name','tbl_vehicles.client_id')
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
       
        if($request->searchVehicleNo!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_vehicles.id', $request->searchVehicleNo);
        }

        if($request->searchTransporterId!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_transporters.id', $request->searchTransporterId);
        }

        if($request->searchClientId!="" )
        {
            $recordsQuery = $recordsQuery->where('tbl_vehicles.client_id', $request->searchClientId);
        }

        if($request->searchUserId!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_diesels.user_id', $request->searchUserId);
        }


        

        $totalRecords = $recordsQuery->count();
        $records =$recordsQuery->skip($start)
        ->take($rowperpage)
        ->orderBy('id', 'DESC')
        ->get();
        
        $totalRecordswithFilter = $totalRecords;

        $data_arr = array();
        $count=0;
        $constant_veriable= \Config::get('constant');
        foreach($records as $k=> $record)
        {
            $vehicle_data=Vehicle::where('id',$record->vehicle_id)->first();
            $sl=$record->sl_no;
            $vehicle_no=get_vehicle_name($record->vehicle_id,'1');
            
            $created_at=date("d-M-Y h:i", strtotime($record->date_time));
            $transporter_name=$record->transporter_name;
            $client_name=get_client_name($record->client_id);
            $quentity=$record->quantity;
            $remarks=$record->remarks;
            if(empty($record->image) || $record->image=='')
            {
                $image_url=asset('assets/admin/img/default_image.png');
                $image='<div class="default_image"><img src="'.$image_url.'"></div>';
            }
            else
            {
                $image='<div class="default_image" onclick="diselImageShow('.$record->id.')" ><img src="'.$constant_veriable['API_BASE_URL'].'/storage/diesel/'.$record->image.'"></div>';
                
            }

            $m="'Are you sure you want to delete?'";
            $action='<a href="'.url($this->routePrefix.'/view/'.$record->id).'" class="waves-effect btn btn-warning" style="font-size: 15px;"><i class="fa fa-eye"></i></a>
            <a href="'.url($this->routePrefix.'/edit/'.$record->id).'" class="waves-effect btn btn-warning mr-1" style="font-size: 15px;margin: 2px;"><i class="fa fa-pencil-square-o"></i></a><a href="'.url($this->routePrefix.'/delete/'.$record->id).'" style="font-size: 15px;" class="waves-effect btn btn-danger" onclick="return confirm('.$m.')"><i class="fa fa-trash"></i></a>';

            $chk='
                  <div>
                  <input class="subcheck" type="checkbox" id="chk_'.$record->id.'" name="selected" value="'.$record->id.'" >
                  <label for="chk_'.$record->id.'"></label>
                  </div>';
            $updated_by=get_user_name($record->user_id,'1');

            $data_arr[] = array(
            'chk'=>$chk,
            'sl'=>$sl,
            "vehicle_no" => $vehicle_no,
            "created_at" =>$created_at,
            "transporter_name" =>$transporter_name,
            "company_name" =>$client_name,
            "quentity" =>$quentity,
            "diesel_image" =>$image,
            "remarks" =>$remarks,
            "updated_by" =>$updated_by,
            "action" =>$action,
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

    public function view($id)
    {
        $constant_veriable= \Config::get('constant');
        $diesel             = Diesel::where('id', '=', $id)->first();
    	return view($this->viewFolder.'view',compact('diesel','constant_veriable'));
    }

    public function add()
    {
        $vehicle_data        = Vehicle::where('status',1)->where('deleted_at',NULL)->get();
    	return view($this->viewFolder.'add',compact('vehicle_data'));
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
			'vehicle_id' => 'required',
			'quantity' => 'required',
		],
        [
            'vehicle_id.required'=> 'Vehicle Number is Required',
            'quantity.required'=> 'Quantity (Litres)  is Required',
        ]);

        $input=$request->all();

        $diesel_price = Master::value('diesel_price');
     

		$diesel = new Diesel;
        $diesel->sl_no = GeneratorSLNO('Diesel');
        $diesel->user_id =Session::get('admin_details')['id'];
        $diesel->date_time =  date('Y-m-d H:i:s');
        $diesel->vehicle_id = $input['vehicle_id'];
        $diesel->transporter_id =  $input['transporter_id'];
        $diesel->quantity = $input['quantity'];
        $diesel->total_amount =  isset($diesel_price) && $diesel_price > 0 ? ($diesel_price * $input['quantity']) : 0;
        $diesel->remarks = $input['remarks'];
        $diesel->save();
        return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
    }

    public function edit($id)
    {
        $diesel             = Diesel::where('id', '=', $id)->first();
        $diesel_price = Master::value('diesel_price');
    	return view($this->viewFolder.'edit',compact('diesel','diesel_price'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
			'date_time' => 'required',
			'quantity' => 'required',
		]);
        $input=$request->all();

        $diesel        = Diesel::where('id', $input['edit_id'])->first();
        $diesel->date_time =  $input['date_time'];
        $diesel->quantity = $input['quantity'];
        $diesel->total_amount =  isset($input['diesel_price']) && $input['diesel_price'] > 0 ? ($input['diesel_price'] * $input['quantity']) : 0;
        $diesel->save();
        
        $diesel->save();
        return redirect($this->routePrefix.'/list')->with('success', 'Updated successfully.');
    }

    public function delete($id)
    {
       
		$client = Diesel::where('id', '=', $id)->first();
        $client->deleted_at = date('Y-m-d');
        $client->save();
    	return redirect()->back()->with('success', 'Deleted updated successfully');
    }

    public function download(Request $request)
    {
        $recordsQuery=Diesel::distinct('tbl_diesels.id')
        ->select('tbl_diesels.*','tbl_vehicles.vehicle_no','tbl_vehicles.vehicle_name','tbl_transporters.transporter_name','tbl_vehicles.client_id')
        ->leftjoin('tbl_vehicles','tbl_diesels.vehicle_id','=','tbl_vehicles.id')
        ->leftjoin('tbl_transporters','tbl_diesels.transporter_id','=','tbl_transporters.id')
        ->where('tbl_diesels.deleted_at',NULL);

        if($request->Fdate!="" && $request->Tdate!="")
        {
            $start_date=date("Y-m-d", strtotime($request->Fdate));
            $end_date=date("Y-m-d", strtotime($request->Tdate));
            $recordsQuery = $recordsQuery
            ->whereDate('tbl_diesels.date_time', '>=', $start_date)
            ->whereDate('tbl_diesels.date_time', '<=', $end_date);
        }
       
        if($request->vehicle_no!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_vehicles.id', $request->vehicle_no);
        }

        if($request->transporter_id!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_transporters.id', $request->transporter_id);
        }

        if($request->client_id!="" )
        {
            $recordsQuery = $recordsQuery->where('tbl_vehicles.client_id', $request->client_id);
        }

        if($request->user_id!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_diesels.user_id', $request->user_id);
        }
   
        $records =$recordsQuery->orderBy('tbl_diesels.id', 'DESC')->get();
        
     
       
        $fileName = 'diesel_report_list.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $delimiter = ","; 
        $filename = "vehicle_report_list_" . date('Y-m-d') . ".csv"; 
        $f = fopen('uploads/vehicle/diesel_report_list.csv', 'w');

        $fields = array('Sl. No.', 'Vehicle No.', 'Date', 'Transporter Name','Company Name','Quantity(Ltr)','Remarks','Updated By'); 
        fputcsv($f, $fields, $delimiter); 
        $data_arr = array();
        $count=0;

        foreach($records as $k=> $record)
        {
            $vehicle_data=Vehicle::where('id',$record->vehicle_id)->first();
            $sl=$record->sl_no;
            $vehicle_no=get_vehicle_name($record->vehicle_id);
            $created_at=date("d-M-Y", strtotime($record->date_time));
            $transporter_name=$record->transporter_name;
            $client_name=get_client_name($record->client_id);
            $quentity=$record->quantity;
            $remarks=$record->remarks;
            $updated_by=get_user_name($record->user_id);

            $lineData = array($sl,$vehicle_no,$created_at,$transporter_name,$client_name,$quentity,$remarks, $updated_by); 

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

    public function dieselImage(Request $request)
    {

        $diesel             = Diesel::where('id', '=', $request->id)->first();
    	return @$diesel->image;
    	
    }

    public function deleteMultiple(Request $request)
    {
        $input=$request->selected;
        
        foreach($input as $val)
        {
           
            $diesel = Diesel::where('id', '=', $val)->first();
            $diesel->deleted_at = date('Y-m-d');
            $diesel->save();
        }
		
    	return true;
    }

}
