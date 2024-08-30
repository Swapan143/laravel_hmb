<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Session;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    private $viewFolder = 'admin/change_password/';
    public function index()
    {
        $data=User:: where('email',Session::get('admin_details')['email'])->first();
      
        return view($this->viewFolder.'change_password',compact('data'));
    }

    public function changePassword(Request $request)
	{
		$validatedData = $request->validate([
			'old_password' => 'required',
			'new_password' => 'required|same:confirm_password',
			
		]);
		$input = $request->all();
		$admin_data = User::where('email',Session::get('admin_details')['email'])->first();

		if($admin_data)
        {

			if (Hash::check($input['old_password'],$admin_data->password)) 
			{
				$update_password = Hash::make($input['new_password'],[
					'rounds' => 12,
				]);
				$admin_data->password = $update_password;
				$admin_data->save();
				Session::flash('success', 'Password hasbeen changed Successfully'); 
			}	
			else
			{
				Session::flash('message', 'You Entered a Wrong Password'); 
				Session::flash('alert-class', 'alert-danger');
				return redirect('/');
			}
		}
		else
		{
            Session::flash('error', 'You Entered a Wrong Password!'); 
		}
		return redirect()->back();
	}
}
