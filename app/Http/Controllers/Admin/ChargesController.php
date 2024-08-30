<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Client;
use App\Models\Charge;
use Illuminate\Support\Facades\DB;


class ChargesController extends Controller
{
    private $viewFolder = 'admin/charges/';
    private $routePrefix = 'admin/charges';

    public function index($id)
    {
    	return view($this->viewFolder.'index',compact('id'));
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
        $searchValue = $request->searchSearch;


        $recordsQuery=Charge::where('tbl_charges.location_id',$request->location_id)->where('tbl_charges.deleted_at',NULL);

        if($request->year!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_charges.year', $request->year);
        }

        if($request->month!="")
        {
            $recordsQuery = $recordsQuery->where('tbl_charges.month', $request->month);
        }

        if($searchValue!="")
        {
            $recordsQuery=$recordsQuery->where(function ($query) use ($searchValue) {
                $query->where('tbl_charges.location_name', 'like', '%' .$searchValue . '%')
                ->orwhere('tbl_charges.client_name', 'like', '%' .$searchValue . '%');
               
            });
        }
   
        $totalRecords = $recordsQuery->count();
        $records =$recordsQuery->skip($start)
        ->take($rowperpage)
        ->orderBy('tbl_charges.id', 'DESC')
        ->get();
        
        $totalRecordswithFilter = $totalRecords;

        $data_arr = array();
        $count=0;
        foreach($records as $k=> $record)
        {

            $sl=$k+1;
            $client_name=$record->client_name;
            $location_name=$record->location_name;
            $year_month=$record->year_month;
            $tanker_fare=$record->tanker_fare;
            $freight_charge=$record->freight_charge;
            $diesel_price=$record->diesel_price;
            $accidental_rate=$record->accidental_rate;

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
            "client_name" => $client_name,
            "location_name" => $location_name,
            "year_month" =>$year_month,
            "tanker_fare" =>$tanker_fare,
            "freight_charge" =>$freight_charge,
            "diesel_price" =>$diesel_price,
            "accidental_rate" =>$accidental_rate,
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

    public function add($id)
    {
        $location = Location::where('id', '=', $id)->first();
        $client = Client::where('id', '=', $location->client_id)->first();
    	return view($this->viewFolder.'add',compact('location','client','id'));
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'client_name' => 'required',
                'location_name' => 'required',
                'month' => 'required',
                'year' => 'required',
                
            ]);
        $input=$request->all();
        
        
		$count = DB::table('tbl_charges')->where('location_id', $input['location_id'])->where('client_id', $input['client_id'])->where('year', $input['year'])->where('month', $input['month'])->count();
		
		if ($count == 0) 
		{
			$charge = new Charge;
            $charge->location_id = $input['location_id'];
			$charge->location_name = $input['location_name'];
			$charge->client_id = $input['client_id'];
			$charge->client_name = @$input['client_name'];
			$charge->year = $input['year'];
			$charge->month = $input['month'];
			$charge->year_month = $input['year'].'-'.$input['month'];
			$charge->tanker_fare = $input['tanker_fare'];
			$charge->freight_charge = $input['freight_charge'];
			$charge->diesel_price = $input['diesel_price'];
			$charge->accidental_rate = $input['accidental_rate'];
			$charge->save();
			return redirect($this->routePrefix.'/list/'.$input['location_id'])->with('success', 'Successfully Submitted');
		} 
		else 
		{
			$locationRecord =  DB::table('tbl_charges')->where('location_id', $input['location_id'])->where('client_id', $input['client_id'])->where('year', $input['year'])->where('month', $input['month'])->whereNull('deleted_at')->first();
			if (!empty($locationRecord)) 
			{
				return redirect($this->routePrefix.'/add/'.$input['location_id'])->with('error', 'Duplicate Data');
			} 
			else 
			{
				$charge = new Charge;
                $charge->location_id = $input['location_id'];
                $charge->location_name = $input['location_name'];
                $charge->client_id = $input['client_id'];
                $charge->client_name = @$input['client_name'];
                $charge->year = $input['year'];
                $charge->month = $input['month'];
                $charge->year_month = $input['year'].'-'.$input['month'];
                $charge->tanker_fare = $input['tanker_fare'];
                $charge->freight_charge = $input['freight_charge'];
                $charge->diesel_price = $input['diesel_price'];
                $charge->accidental_rate = $input['accidental_rate'];
                $charge->save();
                return redirect($this->routePrefix.'/list/'.$input['location_id'])->with('success', 'Successfully Submitted');
			}
		}
    }

    public function status(Request $request)
    {
        
        $input=$request->all();
		$charge = Charge::where('id', '=', $input['id'])->first();
		
        if($charge)
        {
            if ($charge->status == '0') 
            {
                $charge->status = '1';
            } 
            else 
            {
                $charge->status = '0';
            }
            $charge->save();
            return true;
        }
        else
        {
            return false;
        }
    }

    public function edit($id)
    {
		$charge = Charge::where('id', '=', $id)->first();
    	return view($this->viewFolder.'edit',compact('charge'));
    }

    public function delete($id)
    {
       
		$charge = Charge::where('id', '=', $id)->first();
        $charge->deleted_at = date('Y-m-d');
        $charge->save();
    	return redirect()->back()->with('success', 'Deleted updated successfully');
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate(
        [
            'client_name' => 'required',
            'location_name' => 'required',
            'month' => 'required',
            'year' => 'required',
            
        ]);

        $input=$request->all();
       

        $charge        = Charge::where('id', $input['edit_id'])->first();
        $charge->location_id = $input['location_id'];
        $charge->location_name = $input['location_name'];
        $charge->client_id = $input['client_id'];
        $charge->client_name = @$input['client_name'];
        $charge->year = $input['year'];
        $charge->month = $input['month'];
        $charge->year_month = $input['year'].'-'.$input['month'];
        $charge->tanker_fare = $input['tanker_fare'];
        $charge->freight_charge = $input['freight_charge'];
        $charge->diesel_price = $input['diesel_price'];
        $charge->accidental_rate = $input['accidental_rate'];
        $charge->save();
        return redirect($this->routePrefix.'/list/'.$input['location_id'])->with('success', 'Updated successfully.');
    }

    
   
}
