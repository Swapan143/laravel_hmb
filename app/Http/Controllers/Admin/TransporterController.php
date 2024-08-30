<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TransporterController extends Controller
{
    private $viewFolder = 'admin/setting/master';
    private $routePrefix = 'admin/setting/master';


    public function smsSetting()
    {
		$master_data=DB::table('tbl_masters')->latest()->first();
    	return view($this->viewFolder.'/sms',compact('master_data'));
    }

    
    public function smsSettingUpdate(Request $request)
    {
        
        $input=$request->all();
		$master = DB::table('tbl_masters')->where('id', '=', $input['id'])->first();
		
        if($master)
        {
            if ($master->transporter_sms == '0') 
            {
                $transporter_sms = '1';
            } 
            else 
            {
                $transporter_sms = '0';
            }
            DB::table('tbl_masters')->where('id', $input['id'])->update( 
            array(
                'transporter_sms'=>$transporter_sms
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