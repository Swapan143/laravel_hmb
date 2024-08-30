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



class VehicleController extends Controller
{
    private $viewFolder = 'admin/vehicle/';
    private $routePrefix = 'admin/vehicle';

    

    public function index()
    {
        $client_data        = Client::where('status',1)->where('deleted_at',NULL)->get();
        $transporter_data        = Transporter::where('status',1)->where('deleted_at',NULL)->get();
        $siding_location_data        = Location::where('status',1)->where('location_type',2)->where('deleted_at',NULL)->get();
    	return view($this->viewFolder.'index',compact('client_data','transporter_data','siding_location_data'));
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


        $recordsQuery=Vehicle::where('deleted_at',NULL);

        if($request->searchFdate!="" && $request->searchTdate!="")
        {
            $start_date=date("Y-m-d", strtotime($request->searchFdate));
            $end_date=date("Y-m-d", strtotime($request->searchTdate));
            $recordsQuery = $recordsQuery
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date);
        }

        if($request->searchTransporter_id!="")
        {
            $recordsQuery = $recordsQuery->where('transporter_id', $request->searchTransporter_id);
        }

        if($request->searchClient_id!="" )
        {
            $recordsQuery = $recordsQuery->where('client_id', $request->searchClient_id);
        }

        if($request->siding_location!="")
        {
            $recordsQuery = $recordsQuery->where('vehicle_siding_location', $request->siding_location);
        }
        

        if($searchValue!="")
        {
            $recordsQuery=$recordsQuery->where(function ($query) use ($searchValue) {
                $query->where('vehicle_no', 'like', '%' .$searchValue . '%')
                ->orwhere('vehicle_name', 'like', '%' .$searchValue . '%')
                ->orwhere('vehicle_chassis_no', 'like', '%' .$searchValue . '%')
                ->orwhere('engine_number', 'like', '%' .$searchValue . '%')
                ->orwhere('owner_name', 'like', '%' .$searchValue . '%')
                ->orwhere('contact_number', 'like', '%' .$searchValue . '%');
            });
        }
   
        $totalRecords = $recordsQuery->count();
        $records =$recordsQuery->skip($start)
        ->take($rowperpage)
        ->orderBy('id', 'DESC')
        ->get();
        
        $totalRecordswithFilter = $totalRecords;

        $data_arr = array();
        $count=0;
        foreach($records as $k=> $record)
        {

            $sl=$k+1;
            $vehicle_no=$record->vehicle_no;
            $vehicle_name=$record->vehicle_name;
            $vehicle_siding_location=get_location_name($record->vehicle_siding_location);
            $vehicle_chassis_no=$record->vehicle_chassis_no;
            $transporter_name=get_transporter_name($record->transporter_id);
            $company_name=get_client_name($record->client_id);
            $owner_name='';
            if(!empty($record->owner_name))
            {
                if(!empty($record->contact_number))
                {
                    $owner_name=$record->owner_name.'<br/>'.$record->contact_number;

                }
                else
                {
                    $owner_name=$record->owner_name;
                }
            }
            $diesel_consumed=get_total_diesel_consumed($record->id);
            $trip_done=get_total_trip_done($record->id);;
      
            $created_at=date("d-M-Y", strtotime($record->created_at));


            if(empty($record->image) || $record->image=='')
            {
                $image_url=asset('assets/admin/img/default_image.png');
                $image='<div class="default_image"><img src="'.$image_url.'"></div>';
            }
            else
            {
                $image_url="'$record->image'";
                $image='<div class="default_image" onclick="vehicleImageShow('.$image_url.')" ><img src="'.asset('uploads/vehicle/'.$record->image).'"></div>';
                
            }

            $status_check='';
            if($record->status=="1")
            {
                $status_check='checked';
            }
            $status='<label class="switch">
                <input type="checkbox" value="'.$record->id.'" onchange="status_change(this.value)" '.$status_check.'>
                <span class="slider round"></span>
                </label>';
            $m="'Are you sure you want to delete?'";
            $action='<a href="'.url($this->routePrefix.'/edit/'.$record->id).'" class="waves-effect btn btn-warning mr-1" style="font-size: 15px;margin: 2px;"><i class="fa fa-pencil-square-o"></i></a>
                                            
            <a href="'.url($this->routePrefix.'/delete/'.$record->id).'" style="font-size: 15px; margin: 2px;" class="waves-effect btn btn-danger " onclick="return confirm('.$m.')"><i class="fa fa-trash"></i></a>';

            $services_company='<span>'.$company_name.'</span></br><span class="btn-info-back mr-1"   onclick="viewLog('.$record->id.')" style="padding: 0 2px 2px;cursor: pointer; background-color: #025146;">View Log</span>';


            $data_arr[] = array(
            'sl'=>$sl,
            "vehicle_no" =>$vehicle_no,
            "vehicle_name" =>$vehicle_name,
            "vehicle_image" =>$image,
            "vehicle_siding_location" =>$vehicle_siding_location,
            "vehicle_chassis_no" =>$vehicle_chassis_no,
            "transporter_name" => $transporter_name,
            "company_name" => $services_company,
            "owner_name" => $owner_name,
            "diesel_consumed" => $diesel_consumed,
            "trip_done" => $trip_done,
            "status" =>$status,
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

    public function add()
    {
        $client_data        = Client::where('status',1)->where('deleted_at',NULL)->get();
        $transporter_data        = Transporter::where('status',1)->where('deleted_at',NULL)->get();
    	return view($this->viewFolder.'add',compact('client_data','transporter_data'));
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
			'vehicle_no' => 'required',
			'vehicle_chassis_no' => 'required',
            'transporter_name' => 'required',
            'client_name' => 'required',
            'vehicle_siding_location' => 'required',
		]);

        $input=$request->all();

		$count = DB::table('tbl_vehicles')->where('vehicle_no', '=', $input['vehicle_no'])->count();
        $fileName='';
        if(isset($input['item_image_vehicle']))
        { 
            $fileName = time().'.'.$input['item_image_vehicle']->extension();  
            $input['item_image_vehicle']->move(public_path('uploads/vehicle'), $fileName);
        }
		
		if ($count == 0) 
		{
			$vehicle = new Vehicle;
            $vehicle->vehicle_no = $input['vehicle_no'];
            $vehicle->vehicle_name = $input['vehicle_name'];
            $vehicle->vehicle_chassis_no = $input['vehicle_chassis_no'];
            $vehicle->transporter_id = $input['transporter_name'];
            $vehicle->client_id = $input['client_name'];
            $vehicle->vehicle_siding_location = $input['vehicle_siding_location'];
            $vehicle->engine_number = $input['engine_number'];
            $vehicle->owner_name = $input['owner_name'];
            $vehicle->contact_number = $input['contact_number'];
            $vehicle->vehicle_added_on = $input['vehicle_added_on'];
            $vehicle->image = $fileName;

            $vehicle->save();
			return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
		} 
		else 
		{
			$roleRecord = DB::table('tbl_vehicles')->where('vehicle_no', '=', $input['vehicle_no'])->whereNull('deleted_at')->first();
			if (!empty($roleRecord)) 
			{
				return redirect($this->routePrefix.'/add')->with('error', 'Duplicate Data');
			} 
			else 
			{
				$vehicle = new Vehicle;
                $vehicle->vehicle_no = $input['vehicle_no'];
                $vehicle->vehicle_name = $input['vehicle_name'];
                $vehicle->vehicle_chassis_no = $input['vehicle_chassis_no'];
                $vehicle->transporter_id = $input['transporter_name'];
                $vehicle->client_id = $input['client_name'];
                $vehicle->vehicle_siding_location = $input['vehicle_siding_location'];
                $vehicle->engine_number = $input['engine_number'];
                $vehicle->owner_name = $input['owner_name'];
                $vehicle->contact_number = $input['contact_number'];
                $vehicle->vehicle_added_on = $input['vehicle_added_on'];
                $vehicle->image = $fileName;
 
                $vehicle->save();
				return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
			}
		}
    }

    public function status(Request $request)
    {
        
        $input=$request->all();
		$vehicle = Vehicle::where('id', '=', $input['id'])->first();
		
        if($vehicle)
        {
            if ($vehicle->status == '0') 
            {
                $vehicle->status = '1';
            } 
            else 
            {
                $vehicle->status = '0';
            }
            $vehicle->save();
            return true;
        }
        else
        {
            return false;
        }
    }

    public function edit($id)
    {
		$vehicle             = Vehicle::where('id', '=', $id)->first();
        $client_data        = Client::where('status',1)->where('deleted_at',NULL)->get();
        $transporter_data        = Transporter::where('status',1)->where('deleted_at',NULL)->get();
        $location             = Location::where('client_id',$vehicle->client_id)->where('location_type','2')->where('deleted_at',NULL)->get();
        
    	return view($this->viewFolder.'edit',compact('vehicle','client_data','transporter_data','location'));
    }

    public function delete($id)
    {
		$vehicle = Vehicle::where('id', '=', $id)->first();
        $vehicle->deleted_at = date('Y-m-d');
        $vehicle->save();
    	return redirect()->back()->with('success', 'Deleted updated successfully');
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
			'vehicle_no' => 'required',
			'vehicle_chassis_no' => 'required',
            'transporter_name' => 'required',
            'client_name' => 'required',
            'vehicle_siding_location' => 'required',
		]);
        $input=$request->all();
        $fileName=$input['old_image'];
        if(isset($input['item_image_vehicle']))
        { 
            $fileName = time().'.'.$input['item_image_vehicle']->extension();  
            $input['item_image_vehicle']->move(public_path('uploads/vehicle'), $fileName);
            if(isset($input['old_image']))
            {
                if(file_exists(public_path('uploads/vehicle/'.$input['old_image'])))
                {
                    unlink(public_path('uploads/vehicle/'.$input['old_image']));
                }
            }
            
        }
        $vehicle        = Vehicle::where('id', $input['edit_id'])->first();
        $vehicle->vehicle_no = $input['vehicle_no'];
        $vehicle->vehicle_name = $input['vehicle_name'];
        $vehicle->vehicle_chassis_no = $input['vehicle_chassis_no'];
        $vehicle->transporter_id = $input['transporter_name'];
        $vehicle->client_id = $input['client_name'];
        $vehicle->vehicle_siding_location = $input['vehicle_siding_location'];
        $vehicle->engine_number = $input['engine_number'];
        $vehicle->owner_name = $input['owner_name'];
        $vehicle->contact_number = $input['contact_number'];
        $vehicle->vehicle_added_on = $input['vehicle_added_on'];
        $vehicle->image = $fileName;
        $vehicle->save();
        return redirect($this->routePrefix.'/list')->with('success', 'Updated successfully.');
    }

    public function importCsv()
    {
    	return view($this->viewFolder.'import');
    }

    public function uploadCsv(Request $request)
    {
        return redirect()->back()->with('success', 'Working Progresh.');
        $validatedData = $request->validate([
			'file' => 'required',
		]);
        
        $file = $request->file('file');
        // echo "<pre/>"; print_r($file); die;
        if(!$file)
        {
            return redirect()->back()->with('error', 'Please select csv file.');
        }

        // File Details
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        // Valid File Extensions
        $valid_extension = array("csv");

        // 2MB in Bytes
        $maxFileSize = 2097152;

        // Check file extension
        if(in_array(strtolower($extension),$valid_extension))
        {
            // Check file size
            if($fileSize <= $maxFileSize)
            {
                // File upload location
                $location = 'uploads/temp_vehicle';

                // Upload file
                $file->move($location,$filename);

                // Import CSV to Database
                $filepath = public_path($location."/".$filename);

                // Reading file
                $file = fopen($filepath,"r");

                $importData_arr = array();
                $i = 0;

                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) 
                {
                    $num = count($filedata );

                    // Skip first row (Remove below comment if you want to skip the first row)
                    if($i == 0)
                    {
                        $i++;
                        continue;
                    }
                    for ($c=0; $c < $num; $c++) 
                    {
                        // if($filedata[$c])
                        // {
                            $importData_arr[$i][] = $filedata[$c];
                        // }
                    }
                    $i++;
                }
                fclose($file);

                // pr($importData_arr);

                // die;
                // Insert to MySQL database
                $model_name='';
                $importCount=0;
                $duplicateCount=0;
                
                // echo count($importData_arr); die;
                if($importData_arr)
                {
                    $insert=[];
                    foreach($importData_arr as $importData)
                    {
                        if(!empty($importData[0]))
                        {
                            $vehicle_check=Vehicle::where('vehicle_no', $importData[0])->first();
                            if(empty(@$vehicle_check->id))
                            {
                                $vehi = new Vehicle;
                                $vehi->vehicle_no = $importData[0];
                                $vehi->vehicle_name = $importData[1];
                                $vehi->vehicle_chassis_no = $importData[2];
                                $vehi->driver_name = $importData[3];
                                $vehi->engine_number = $importData[4];
                                $vehi->rfid_number = $importData[5];
                                $vehi->vehicle_added_on = date("Y-m-d", strtotime($importData[6]));
                                $vehi->save();
                            }
                            else
                            {
                                $vehicle        = Vehicle::where('vehicle_no', $importData[0])->first();
                                $vehicle->vehicle_no = $importData[0];
                                $vehicle->vehicle_name = $importData[1];
                                $vehicle->vehicle_chassis_no = $importData[2];
                                $vehicle->driver_name = $importData[3];
                                $vehicle->engine_number = $importData[4];
                                $vehicle->rfid_number = $importData[5];
                                $vehicle->vehicle_added_on =date("Y-m-d", strtotime($importData[6])) ;
                                $vehicle->save();
                            }
                        }
                            
                    }
                    return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
                }
                else
                {
                    return redirect()->back()->with('error', 'Empty csv file.');
                
                }
            }
            else
            {
                return redirect()->back()->with('error', 'File too large. File must be less than 2MB.');
            }

        }
        else
        {
            return redirect()->back()->with('error', 'Invalid File Extension.');
        }
    }

    public function companyDetails(Request $request)
    {
        $log_details=DB::table('tbl_vehicles_logs')->where('vehicle_id',$request->vehicle_id)->orderBy('id', 'desc')->get();
      
        $html='<div class="row">
            <table class="table table-hover">
            <thead>
                <tr>
                    <th>Sl. No.</th>
                    <th>Vehicle No.</th>
                    <th>Company Name</th>
                    <th>Change Date</th>
                </tr>
            </thead>
            <tbody>';
            foreach($log_details as $key => $log)
            {
                $vehicle_no=get_vehicle_name($log->vehicle_id);
                $client_name=get_client_name($log->client_id);
                $created_at=date("d-M-Y", strtotime($log->created_at));
                $html .='<tr>
                    <td>'.($key+1).'</td>
                    <td>'.$vehicle_no.'</td>
                    <td>'.$client_name.'</td>
                    <td>'.$created_at.'</td>
                </tr>';
            }
            $html .='</tbody>
            </table>
        </div>';
        return $html;
    }

    public function sidingLocation(Request $request)
    {
        $input  =   $request->all();

        $location             = Location::where('client_id',$input['client_name'])->where('location_type','2')->where('deleted_at',NULL)->get();
        $html='<option value="">Select vehicle siding location</option>';
        foreach($location as $row)
        {
            
            $html .='<option value="'.$row->id.'">'.$row->location_name.'</option>';
        
        }
    	return $html;
    }

    public function download(Request $request)
    {

        $recordsQuery=Vehicle::where('deleted_at',NULL);

        
        if($request->transporter_id!="")
        {
            $recordsQuery = $recordsQuery->where('transporter_id', $request->transporter_id);
        }

        if($request->client_id!="" )
        {
            $recordsQuery = $recordsQuery->where('client_id', $request->client_id);
        }
        
        
        $searchValue=$request->search;
        if($searchValue!="")
        {
            $recordsQuery=$recordsQuery->where(function ($query) use ($searchValue) {
                $query->where('vehicle_no', 'like', '%' .$searchValue . '%')
                ->orwhere('vehicle_name', 'like', '%' .$searchValue . '%')
                ->orwhere('vehicle_chassis_no', 'like', '%' .$searchValue . '%')
                ->orwhere('engine_number', 'like', '%' .$searchValue . '%')
                ->orwhere('owner_name', 'like', '%' .$searchValue . '%')
                ->orwhere('contact_number', 'like', '%' .$searchValue . '%');
            });
        }
   
        $records =$recordsQuery->orderBy('id', 'DESC')->get();
        
       
        $fileName = 'vehicle_report_list.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $delimiter = ","; 
        $filename = "vehicle_report_list_" . date('Y-m-d') . ".csv"; 
        $f = fopen('uploads/vehicle/vehicle_report_list.csv', 'w');

        $fields = array('Sl. No.', 'Vehicle No.', 'Vehicle Name', 'Vehicle Siding Location','Vehicle Chassis No.','Transporter Name','Serving Company','Vehicle Owner Name','Contact Number','Total Diesel Consumed(Ltr)','Total Trip Done'); 
        fputcsv($f, $fields, $delimiter); 
        $data_arr = array();
        $count=0;

        foreach($records as $k=> $record)
        {

            $sl=$k+1;
            $vehicle_no=$record->vehicle_no;
            $vehicle_name=$record->vehicle_name;
            $vehicle_siding_location=get_location_name($record->vehicle_siding_location);
            $vehicle_chassis_no=$record->vehicle_chassis_no;
            $transporter_name=get_transporter_name($record->transporter_id);
            $company_name=get_client_name($record->client_id);
            $owner_name=$record->owner_name;
            $contact_number=$record->contact_number;
            
            $diesel_consumed=get_total_diesel_consumed($record->id);
            $trip_done=get_total_trip_done($record->id);
      
            $created_at=date("d-M-Y", strtotime($record->created_at));

            $lineData = array($sl,$vehicle_no,$vehicle_name,$vehicle_siding_location,$vehicle_chassis_no,$transporter_name,$company_name,$owner_name,$contact_number,$diesel_consumed,$trip_done); 

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
