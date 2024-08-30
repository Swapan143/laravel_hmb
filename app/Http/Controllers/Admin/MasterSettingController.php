<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MasterSettingController extends Controller
{
    private $viewFolder = 'admin/setting/master';
    private $routePrefix = 'admin/setting/master';


    public function masterSetting()
    {
		$master_data=DB::table('tbl_masters')->latest()->first();
    	return view($this->viewFolder.'/edit',compact('master_data'));
    }

    public function masterSettingUpdate(Request $request)
    {
        $validatedData = $request->validate([
			'tanker_fare' => 'required',
			'freight_charge' => 'required',
			'diesel_price' => 'required',
			'accidental_rate' => 'required',
		]);
        $input=$request->all();
      
        DB::table('tbl_masters')->where('id', $input['edit_id'])->update( 
            array(
                'tanker_fare'=>$input['tanker_fare'],
                'freight_charge'=>$input['freight_charge'],
                'diesel_price'=>$input['diesel_price'],
                'accidental_rate'=>$input['accidental_rate']
                ) 
            );
    
        return redirect($this->routePrefix)->with('success', 'Updated successfully.');
    }
}