<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SettingController extends Controller
{
    private $viewFolder = 'admin/setting/version/';
    private $routePrefix = 'admin/setting/version';

    public function addVersion()
    {
		$app_version=DB::table('tbl_settings')->latest()->first();
    	return view($this->viewFolder.'edit',compact('app_version'));
    }

    public function updateVersion(Request $request)
    {
        $validatedData = $request->validate([
			'version' => 'required',
			'status' => 'required',
		]);
        $input=$request->all();
        DB::table('tbl_settings')->where('id', $input['edit_id'])->update( array('version'=>$input['version'],'medium'=>'android','status'=>$input['status']) );
    
        return redirect($this->routePrefix)->with('success', 'Updated successfully.');
    }
}