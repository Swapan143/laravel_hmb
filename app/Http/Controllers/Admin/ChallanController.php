<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Client;
use App\Models\Transporter;
use App\Models\Challan;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Models\User;



class ChallanController extends Controller
{
    private $viewFolder = 'admin/challan/';
    private $routePrefix = 'admin/challan';

    public function index()
    {
        $constant_veriable= \Config::get('constant');
        $vehicle_data        = Vehicle::where('status',1)->where('deleted_at',NULL)->get();
        $client_data        = Client::where('status',1)->where('deleted_at',NULL)->get();
        $transporter_data        = Transporter::where('status',1)->where('deleted_at',NULL)->get();
        $siding_location_data        = Location::where('status',1)->where('location_type',2)->where('deleted_at',NULL)->get();
        $loading_location_data        = Location::where('status',1)->where('location_type',1)->where('deleted_at',NULL)->get();
        $user_data        = User::where('status',1)->where('id','!=',1)->where('deleted_at',NULL)->get();
    	return view($this->viewFolder.'index',compact('vehicle_data','client_data','transporter_data','siding_location_data','loading_location_data','constant_veriable','user_data'));
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


        $recordsQuery=Challan::distinct('tbl_challans.id')
        ->select('tbl_challans.*','tbl_vehicles.vehicle_no','tbl_vehicles.vehicle_name')
        ->leftjoin('tbl_vehicles','tbl_challans.vehicle_id','=','tbl_vehicles.id')
        ->leftjoin('tbl_transporters','tbl_vehicles.transporter_id','=','tbl_transporters.id')
        ->where('tbl_challans.deleted_at',NULL);

        if($request->searchFdate!="" && $request->searchTdate!="")
        {
            $start_date=date("Y-m-d", strtotime($request->searchFdate));
            $end_date=date("Y-m-d", strtotime($request->searchTdate));
            $recordsQuery = $recordsQuery
            ->whereDate('tbl_challans.challan_date', '>=', $start_date)
            ->whereDate('tbl_challans.challan_date', '<=', $end_date);
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
            $recordsQuery = $recordsQuery->where('tbl_challans.client_id', $request->searchClientId);
        }

        if($request->searchLoadingLocation!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_challans.loading_location_id', $request->searchLoadingLocation);
        }

        if($request->searchSidingLocation!="" )
        {
            $recordsQuery = $recordsQuery->where('tbl_challans.siding_location_id', $request->searchSidingLocation);
        }

        if($request->searchUserId!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_challans.user_id', $request->searchUserId);
        }

        if($request->searchChalanNo!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_challans.challan_no','LIKE','%'.$request->searchChalanNo.'%' );
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
            $challan_no=$record->challan_no;
            $created_at=date("d-M-Y", strtotime($record->challan_date));
            $transporter_name=get_transporter_name($vehicle_data->transporter_id);
            $client_name=get_client_name($record->client_id);
            $siding_location=get_location_name($record->siding_location_id);

            if(empty($record->image) || $record->image=='')
            {
                $image_url=asset('assets/admin/img/default_image.png');
                $image='<div class="default_image"><img src="'.$image_url.'"></div>';
            }
            else
            {
                $image='<div class="default_image" onclick="challanImageShow('.$record->id.')" ><img src="'.$constant_veriable['API_BASE_URL'].'/storage/challans/'.$record->image.'"></div>';
                
            }
            $m="'Are you sure you want to delete?'";
            $action='<a href="'.url($this->routePrefix.'/view/'.$record->id).'" class="waves-effect btn btn-warning" style="font-size: 15px;"><i class="fa fa-eye"></i></a>
            <a href="'.url($this->routePrefix.'/edit/'.$record->id).'" class="waves-effect btn btn-warning mr-1" style="font-size: 15px;margin: 2px;"><i class="fa fa-pencil-square-o"></i></a><a href="'.url($this->routePrefix.'/delete/'.$record->id).'" style="font-size: 15px;" class="waves-effect btn btn-danger" onclick="return confirm('.$m.')"><i class="fa fa-trash"></i></a>';

            $chk='
                <div>
                <input class="subcheck" type="checkbox" id="chk_'.$record->id.'" name="selected" value="'.$record->id.'" >
                <label for="chk_'.$record->id.'"></label>
                </div>';

            $grosh=$record->gross_weight;
            $tare=$record->tare_weight;
            $net=$record->net_weight;
            $updated_by=get_user_name($record->user_id,'1');


            $data_arr[] = array(
                'chk'=>$chk,
                'sl'=>$sl,
                "vehicle_no" => $vehicle_no,
                "created_at" =>$created_at,
                "challan_no" => $challan_no,
                "grosh" => $grosh,
                "tare" => $tare,
                "net" => $net,
                "transporter_name" =>$transporter_name,
                "client_name" =>$client_name,
                "siding_location" =>$siding_location,
                "challan_image" =>$image,
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
        $challan             = Challan::where('id', '=', $id)->first();
        // dd($challan);
    	return view($this->viewFolder.'view',compact('challan','constant_veriable'));
    	
    }

    public function challanImage(Request $request)
    {

        $challan             = Challan::where('id', '=', $request->id)->first();
    	return @$challan->image;
    	
    }
    
    public function add()
    {
        
        $vehicle_data        = Vehicle::where('status',1)->where('deleted_at',NULL)->get();
        $siding_location_data        = Location::where('status',1)->where('location_type',2)->where('deleted_at',NULL)->get();
        $loading_location_data        = Location::where('status',1)->where('location_type',1)->where('deleted_at',NULL)->get();
    	return view($this->viewFolder.'add',compact('vehicle_data','siding_location_data','loading_location_data'));
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'challan_date' => 'required',
			'vehicle_id' => 'required',
			'challan_number' => 'required',
			'loading_location' => 'required',
			'siding_location' => 'required',
			'gross_weight' => 'required',
			'tare_weight' => 'required',
		],
        [
            'vehicle_id.required'=> 'Vehicle Number is Required',
        ]);

        $input=$request->all();
        
        
		$challan = new Challan;
        $challan->sl_no = GeneratorSLNO('Challan');
        $challan->user_id =Session::get('admin_details')['id'];
        $challan->client_id = $input['client_id'];
        $challan->vehicle_id = $input['vehicle_id'];
        $challan->challan_date =  $input['challan_date'];
        $challan->challan_no = $input['challan_number'];
        $challan->loading_location_id = $input['loading_location'];
        $challan->siding_location_id = $input['siding_location'];
        $challan->tare_weight = $input['tare_weight'];
        $challan->gross_weight = $input['gross_weight'];
        $challan->net_weight = $input['net_weight'];
        $challan->remarks = $input['remarks'];
        $challan->save();
        

        if(isset($input['item_image_vehicle']) && isset($challan->id))
        { 
           
            $url ="https://staging-api.hmbprojects.in/api/handle-challan-update-web";
            
            $dir = $input['item_image_vehicle']->getRealPath();// full directory of the file
            $cFile = curl_file_create($dir);
            $post = array('image'=> $cFile,'id' => $challan->id); // Parameter to be sent    
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result=json_decode(curl_exec($ch));
            curl_close ($ch);

        }

        return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
    }

    public function getVehicle(Request $request)
    {
        
        $vehicle_data        = Vehicle::where('id',$request->id)->first();

        $transporter_name=get_transporter_name($vehicle_data->transporter_id);
        $client_name=get_client_name($vehicle_data->client_id);

        $data=[
            'transporter_id'=>$vehicle_data->transporter_id,
            'transporter_name'=>$transporter_name,
            'client_id'=>$vehicle_data->client_id,
            'client_name'=>$client_name,
        ];

        echo json_encode($data);
        
    }

    
    public function companyList(Request $request)
    {

        $input  =   $request->all();

        $all_client             = Client::where('deleted_at',NULL)->get();
        $html='<option value="">Select company</option>';
        foreach($all_client as $client)
        {
            if($client->company_name != $input['client_name'])
            {
                $html .='<option value="'.$client->id.'" data-company_name="'.$client->company_name.'">'.$client->company_name.'</option>';
            }
        }
    	return $html;
    	
    }

    public function companySave(Request $request)
    {
        
        $input=$request->all();
        
        $vehicle        = Vehicle::where('id', $input['vehicle_id'])->first();
        $vehicle->client_id = $input['company_id'];
        $vehicle->save();

        DB::table('tbl_vehicles_logs')->insert([
            'vehicle_id' => $input['vehicle_id'],
            'client_id' => $input['company_id'],
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return  true;
    }

    public function edit($id)
    {
        $constant_veriable= \Config::get('constant');
        $challan             = Challan::where('id', '=', $id)->first();
        $client_data        = Client::where('status',1)->where('deleted_at',NULL)->get();
        $location             = Location::where('client_id',@$challan->client_id)->where('location_type','2')->where('deleted_at',NULL)->get();
        // dd($challan);
    	return view($this->viewFolder.'edit',compact('challan','constant_veriable','client_data','location'));
    	
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
			'challan_date' => 'required',
			'challan_number' => 'required',
			'gross_weight' => 'required',
			'tare_weight' => 'required',
			'client_name' => 'required',
			'vehicle_siding_location' => 'required',
		]);
        $input=$request->all();
     
        
        $challan = Challan::where('id', $input['edit_id'])->first();
        $challan->challan_date =  $input['challan_date'];
        $challan->challan_no = $input['challan_number'];
        $challan->tare_weight = $input['tare_weight'];
        $challan->gross_weight = $input['gross_weight'];
        $challan->net_weight = $input['net_weight'];
        $challan->client_id = $input['client_name'];
        $challan->siding_location_id = $input['vehicle_siding_location'];
        $challan->remarks = $input['remarks'];
        $challan->save();
        return redirect($this->routePrefix.'/list')->with('success', 'Updated successfully.');
    }

    public function delete($id)
    {
       
		$client = Challan::where('id', '=', $id)->first();
        $client->deleted_at = date('Y-m-d');
        $client->save();
    	return redirect()->back()->with('success', 'Deleted updated successfully');
    }

    public function download(Request $request)
    {

        $recordsQuery=Challan::distinct('tbl_challans.id')
        ->select('tbl_challans.*','tbl_vehicles.vehicle_no','tbl_vehicles.vehicle_name')
        ->leftjoin('tbl_vehicles','tbl_challans.vehicle_id','=','tbl_vehicles.id')
        ->leftjoin('tbl_transporters','tbl_vehicles.transporter_id','=','tbl_transporters.id')
        ->where('tbl_challans.deleted_at',NULL);

        if($request->Fdate!="" && $request->Tdate!="")
        {
            $start_date=date("Y-m-d", strtotime($request->Fdate));
            $end_date=date("Y-m-d", strtotime($request->Tdate));
            $recordsQuery = $recordsQuery
            ->whereDate('tbl_challans.challan_date', '>=', $start_date)
            ->whereDate('tbl_challans.challan_date', '<=', $end_date);
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
            $recordsQuery = $recordsQuery->where('tbl_challans.client_id', $request->client_id);
        }

        if($request->loading_location!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_challans.loading_location_id', $request->loading_location);
        }

        if($request->siding_location!="" )
        {
            $recordsQuery = $recordsQuery->where('tbl_challans.siding_location_id', $request->siding_location);
        }

        if($request->user_id!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_challans.user_id', $request->user_id);
        }

        if($request->chalan_no!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_challans.challan_no','LIKE','%'.$request->chalan_no.'%' );
        }
   
        $records =$recordsQuery->orderBy('id', 'DESC')->get();
        
       
        $fileName = 'challan_report_list.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $delimiter = ","; 
        $filename = "challan_report_list_" . date('Y-m-d') . ".csv"; 
        $f = fopen('uploads/vehicle/challan_report_list.csv', 'w');

        $fields = array('Sl. No.', 'Vehicle No.', 'Date', 'Challan No.',  'Gross wt (MT)' , 'Tare wt (MT)' , 'Net wt (MT)','Transporter Name','Client Name','Siding Location','Remarks','Updated By'); 
        fputcsv($f, $fields, $delimiter); 
        $data_arr = array();
        $count=0;

        foreach($records as $k=> $record)
        {

            $vehicle_data=Vehicle::where('id',$record->vehicle_id)->first();
            $sl=$record->sl_no;
            $vehicle_no=get_vehicle_name($record->vehicle_id);
            $challan_no=$record->challan_no;
            $created_at=date("d-M-Y", strtotime($record->challan_date));
            $transporter_name=get_transporter_name($vehicle_data->transporter_id);
            $client_name=get_client_name($record->client_id);
            $siding_location=get_location_name($record->siding_location_id);
            $grosh=$record->gross_weight;
            $tare=$record->tare_weight;
            $net=$record->net_weight;
            $remarks=$record->remarks;
            $updated_by=get_user_name($record->user_id);
            

            $lineData = array($sl,$vehicle_no,$created_at,$challan_no,$grosh,$tare,$net,$transporter_name,$client_name,$siding_location,$remarks,$updated_by); 

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

    public function deleteMultiple(Request $request)
    {
        $input=$request->selected;
        
        foreach($input as $val)
        {
           
            $challan = Challan::where('id', '=', $val)->first();
            $challan->deleted_at = date('Y-m-d');
            $challan->save();
        }
		
    	return true;
    }


}
