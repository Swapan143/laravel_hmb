<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transporter;
use Illuminate\Support\Facades\DB;


class VendorController extends Controller
{
    private $viewFolder = 'admin/vendor/';
    private $routePrefix = 'admin/vendor';

    public function index()
    {
        $total_count=Transporter::where('deleted_at',NULL)->count();
    	return view($this->viewFolder.'index',compact('total_count'));
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


        $recordsQuery=Transporter::where('deleted_at',NULL);

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
            $company_name=$record->company_name;
            $phone=$record->mobile;
            $email=$record->email;
            $no_of_vehicle=get_no_vehicle($record->id);
            $created_at=date("d-M-Y", strtotime($record->created_at));

            $sms_check='';
            if($record->sms=="1")
            {
                $sms_check='checked';
            }
            $sms_status='<label class="switch">
                <input type="checkbox" value="'.$record->id.'" onchange="sms_change(this.value)" '.$sms_check.'>
                <span class="slider round"></span>
                </label>';

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
            "company_name" =>$company_name,
            "phone" =>$phone,
            "email" =>$email,
            "no_of_vehicle" =>$no_of_vehicle,
            "sms_status" =>$sms_status,
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
			'transporter_name' => 'required',
			'mobile' => 'required',
		]);

        $input=$request->all();
        $password=12345678;
		$count = DB::table('tbl_transporters')->where('mobile', '=', $input['mobile'])->count();
		
		if ($count == 0) 
		{
			$vendor = new Transporter;
			$vendor->transporter_name = $input['transporter_name'];
			$vendor->company_name = $input['company_name'];
			$vendor->mobile = $input['mobile'];
			$vendor->email = $input['email'];
			$vendor->address = $input['address'];
			$vendor->save();
			return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
		} 
		else 
		{
			$roleRecord = DB::table('tbl_transporters')->where('mobile', '=', $input['mobile'])->whereNull('deleted_at')->first();
			if (!empty($roleRecord)) 
			{
				return redirect($this->routePrefix.'/add')->with('error', 'Duplicate Data');
			} 
			else 
			{
				$vendor = new Transporter;
                $vendor->transporter_name = $input['transporter_name'];
                $vendor->company_name = $input['company_name'];
                $vendor->mobile = $input['mobile'];
                $vendor->email = $input['email'];
                $vendor->address = $input['address'];
                $vendor->save();
				return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
			}
		}
    }

    public function status(Request $request)
    {
        
        $input=$request->all();
		$vendor = Transporter::where('id', '=', $input['id'])->first();
		
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
		$vendor             = Transporter::where('id', '=', $id)->first();
        
    	return view($this->viewFolder.'edit',compact('vendor'));
    }

    public function delete($id)
    {
       
		$vendor = Transporter::where('id', '=', $id)->first();
        $vendor->deleted_at = date('Y-m-d');
        $vendor->save();
    	return redirect()->back()->with('success', 'Deleted updated successfully');
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
			'transporter_name' => 'required',
			'mobile' => 'required',
		]);
        $input=$request->all();
        $vendor        = Transporter::where('id', $input['edit_id'])->first();
        $vendor->transporter_name = $input['transporter_name'];
        $vendor->company_name = $input['company_name'];
        $vendor->mobile = $input['mobile'];
        $vendor->email = $input['email'];
        $vendor->address = $input['address'];
        $vendor->save();
        return redirect($this->routePrefix.'/list')->with('success', 'Updated successfully.');
    }

    public function smsStatusUpdate(Request $request)
    {
        
        $input=$request->all();
		$transporters = DB::table('tbl_transporters')->where('id', '=', $input['id'])->first();
		
        if($transporters)
        {
            if ($transporters->sms == '0') 
            {
                $transporter_sms = '1';
            } 
            else 
            {
                $transporter_sms = '0';
            }
            DB::table('tbl_transporters')->where('id', $input['id'])->update( 
            array(
                'sms'=>$transporter_sms
                ) 
            );
            return true;
        }
        else
        {
            return false;
        }
    }
   
}
