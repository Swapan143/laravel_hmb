<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Client;
use Illuminate\Support\Facades\DB;


class LocationController extends Controller
{
    private $viewFolder = 'admin/location/';
    private $routePrefix = 'admin/location';

    public function index()
    {
        return view($this->viewFolder.'index');
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


        $recordsQuery=Location::distinct('tbl_locations.id')
        ->select('tbl_locations.*','tbl_clients.company_name')
        ->leftjoin('tbl_clients','tbl_locations.client_id','=','tbl_clients.id')
        ->where('tbl_locations.deleted_at',NULL);

        if($request->searchFdate!="" && $request->searchTdate!="")
        {
            $start_date=date("Y-m-d", strtotime($request->searchFdate));
            $end_date=date("Y-m-d", strtotime($request->searchTdate));
            $recordsQuery = $recordsQuery
            ->whereDate('tbl_locations.created_at', '>=', $start_date)
            ->whereDate('tbl_locations.created_at', '<=', $end_date);
        }

        if($request->searchType!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_locations.location_type', $request->searchType);
        }

        if($searchValue!="")
        {
            $recordsQuery=$recordsQuery->where(function ($query) use ($searchValue) {
                $query->where('tbl_locations.location_name', 'like', '%' .$searchValue . '%')
                ->orwhere('tbl_clients.company_name', 'like', '%' .$searchValue . '%');
               
            });
        }
   
        $totalRecords = $recordsQuery->count();
        $records =$recordsQuery->skip($start)
        ->take($rowperpage)
        ->orderBy('tbl_locations.id', 'DESC')
        ->get();
        
        $totalRecordswithFilter = $totalRecords;

        $data_arr = array();
        $count=0;
        foreach($records as $k=> $record)
        {

            $sl=$k+1;
            $client_id=get_client_name($record->client_id);
            $name=$record->location_name;
            $location_type='';
            $charge='';
            if($record->location_type==1)
            {
                $location_type='Loading Location';
            }
            else
            {
                $location_type='Siding Location';
                $charge='<a href="'.url('admin/charges/list/'.$record->id).'" class="btn btn-info" style="font-size: 15px;">
                <i class="fa fa-eye"></i></a>';
            }
            
            $created_at=date("d-M-Y", strtotime($record->created_at));

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
            $action='<a href="'.url($this->routePrefix.'/edit/'.$record->id).'" class="waves-effect btn btn-warning" style="font-size: 15px;"><i class="fa fa-pencil-square-o"></i></a>
                                            
            <a href="'.url($this->routePrefix.'/delete/'.$record->id).'" style="font-size: 15px;" class="waves-effect btn btn-danger" onclick="return confirm('.$m.')"><i class="fa fa-trash"></i></a>';


            $data_arr[] = array(
            'sl'=>$sl,
            "client_id" => $client_id,
            "name" => $name,
            "type" =>$location_type,
            "charge" =>$charge,
            "status" =>$status,
            "created_at" =>$created_at,
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
    	return view($this->viewFolder.'add',compact('client_data'));
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'client_id' => 'required',
                'name' => 'required',
                'type' => 'required',
            ],
            [
                'client_id.required'=> 'Company Name is Required', // custom message
            ]);
        $input=$request->all();
        
        
		$count = DB::table('tbl_locations')->where('location_name', '=', $input['name'])->count();
		
		if ($count == 0) 
		{
			$location = new Location;
            $location->client_id = $input['client_id'];
			$location->location_name = $input['name'];
			$location->location_type = $input['type'];
			$location->save();
			return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
		} 
		else 
		{
			$locationRecord = DB::table('tbl_locations')->where('location_name', '=', $input['name'])->whereNull('deleted_at')->first();
			if (!empty($locationRecord)) 
			{
				return redirect($this->routePrefix.'/add')->with('error', 'Duplicate Data');
			} 
			else 
			{
				$location = new Location;
                $location->client_id = $input['client_id'];
                $location->location_name = $input['name'];
                $location->location_type = $input['type'];
                $location->save();
				return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
			}
		}
    }

    public function status(Request $request)
    {
        
        $input=$request->all();
		$location = Location::where('id', '=', $input['id'])->first();
		
        if($location)
        {
            if ($location->status == '0') 
            {
                $location->status = '1';
            } 
            else 
            {
                $location->status = '0';
            }
            $location->save();
            return true;
        }
        else
        {
            return false;
        }
    }

    public function edit($id)
    {
		$location = Location::where('id', '=', $id)->first();
        $client_data        = Client::where('status',1)->where('deleted_at',NULL)->get();
    	return view($this->viewFolder.'edit',compact('location','client_data'));
    }

    public function delete($id)
    {
       
		$location = Location::where('id', '=', $id)->first();
        $location->deleted_at = date('Y-m-d');
        $location->save();
    	return redirect()->back()->with('success', 'Deleted updated successfully');
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate(
            [
                'client_id' => 'required',
                'name' => 'required',
                'type' => 'required',
		    ],
            [
                'client_id.required'=> 'Company Name is Required', // custom message
            ]
        );
        $input=$request->all();
        

        $location        = Location::where('id', $input['edit_id'])->first();
        $location->client_id = $input['client_id'];
        $location->location_name = $input['name'];
        $location->location_type = $input['type'];
        $location->save();
        return redirect($this->routePrefix.'/list')->with('success', 'Updated successfully.');
    }

    
   
}
