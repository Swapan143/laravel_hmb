<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    private $viewFolder = 'admin/client/';
    private $routePrefix = 'admin/client';

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


        $recordsQuery=Client::where('deleted_at',NULL);

        if($request->searchFdate!="" && $request->searchTdate!="")
        {
            $start_date=date("Y-m-d", strtotime($request->searchFdate));
            $end_date=date("Y-m-d", strtotime($request->searchTdate));
            $recordsQuery = $recordsQuery
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date);
        }

        if($searchValue!="")
        {
            $recordsQuery=$recordsQuery->where(function ($query) use ($searchValue) {
                $query->where('company_name', 'like', '%' .$searchValue . '%')
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
            $name=$record->company_name;
            $phone=$record->mobile;
            $gst=$record->gst;
            $challan_format=$record->challan_format;
        
            if(empty($record->image) || $record->image=='')
            {
                $image_url=asset('assets/admin/img/default_image.png');
                $image='<div class="default_image"><img src="'.$image_url.'"></div>';
            }
            else
            {
                $image='<div class="default_image"><img src="'.asset('uploads/company/'.$record->image).'"></div>';
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
            $action='<a href="'.url($this->routePrefix.'/edit/'.$record->id).'" class="waves-effect btn btn-warning" style="font-size: 15px;"><i class="fa fa-pencil-square-o"></i></a>
                                            
            <a href="'.url($this->routePrefix.'/delete/'.$record->id).'" style="font-size: 15px;" class="waves-effect btn btn-danger" onclick="return confirm('.$m.')"><i class="fa fa-trash"></i></a>';


            $data_arr[] = array(
            'sl'=>$sl,
            "name" => $name,
            "image" => $image,
            "phone" =>$phone,
            "gst" =>$gst,
            "challan_format" =>$challan_format,
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
    	return view($this->viewFolder.'add');
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
			'company_name' => 'required',
			'mobile' => 'required',
		
		]);

        $input=$request->all();
        

		$count = DB::table('tbl_clients')->where('mobile', '=', $input['mobile'])->count();

        $fileName='';
        if(isset($input['item_image_company']))
        { 
            $fileName = time().'.'.$input['item_image_company']->extension();  
            $input['item_image_company']->move(public_path('uploads/company'), $fileName);
        }
      
		if ($count == 0) 
		{
			$client = new Client;
			$client->company_name = $input['company_name'];
			$client->mobile = $input['mobile'];
			$client->address = $input['address'];
			$client->gst = $input['gst'];
			$client->challan_format = $input['challan_format'];
			$client->image = $fileName;
			$client->save();
			return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
		} 
		else 
		{
			$roleRecord = DB::table('tbl_clients')->where('mobile', '=', $input['mobile'])->whereNull('deleted_at')->first();
			if (!empty($roleRecord)) 
			{
				return redirect($this->routePrefix.'/add')->with('error', 'Duplicate Data');
			} 
			else 
			{
				$client = new Client;
                $client->company_name = $input['company_name'];
                $client->mobile = $input['mobile'];
                $client->address = $input['address'];
                $client->gst = $input['gst'];
                $client->challan_format = $input['challan_format'];
                $client->image = $fileName;
                $client->save();
				return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
			}
		}
    }

    public function status(Request $request)
    {
        
        $input=$request->all();
		$vendor = Client::where('id', '=', $input['id'])->first();
		
        if($vendor)
        {
            if ($vendor->status == '0') 
            {
                $vendor->status = '1';
            } 
            else 
            {
                $vendor->status = '0';
            }
            $vendor->save();
            return true;
        }
        else
        {
            return false;
        }
    }

    public function edit($id)
    {
		$client             = Client::where('id', '=', $id)->first();
        
    	return view($this->viewFolder.'edit',compact('client'));
    }

    public function delete($id)
    {
       
		$client = Client::where('id', '=', $id)->first();
        $client->deleted_at = date('Y-m-d');
        $client->save();
    	return redirect()->back()->with('success', 'Deleted updated successfully');
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
			'company_name' => 'required',
			'mobile' => 'required',
		]);
        $input=$request->all();

        $fileName=$input['old_image'];
        if(isset($input['item_image_company']))
        { 
            $fileName = time().'.'.$input['item_image_company']->extension();  
            $input['item_image_company']->move(public_path('uploads/company'), $fileName);
            if(isset($input['old_image']))
            {
                if(file_exists(public_path('uploads/company/'.$input['old_image'])))
                {
                    unlink(public_path('uploads/company/'.$input['old_image']));
                }
            }
            
        }
        
        $client        = Client::where('id', $input['edit_id'])->first();
        $client->company_name = $input['company_name'];
        $client->mobile = $input['mobile'];
        $client->address = $input['address'];
        $client->gst = $input['gst'];
        $client->challan_format = $input['challan_format'];
        $client->image = $fileName;
        $client->save();
        return redirect($this->routePrefix.'/list')->with('success', 'Updated successfully.');
    }
   
}
