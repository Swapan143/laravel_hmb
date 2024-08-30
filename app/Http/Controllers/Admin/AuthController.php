<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Mail;
use App\Mail\ResetPasswordMail;

class AuthController extends Controller
{
    private $viewFolder = 'admin/auth/';

    public function index()
    {
    	return view($this->viewFolder.'admin_login');
    }

 			
    public function login_post(Request $request)
    {
		$input = $request->all();
	
	
    	$validatedData = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    		],
    		[
    			'email.required' => 'Email Field Is required',
    			'email.email' => 'Please input a valid email',
    			'password.required' => 'Password Field Is required',
    		]);

			
			$admin = User::where('email',$input['email'])->where('deleted_at',NULL)->first();
				
		
    	 	if(!empty($admin))
    	 	{
				if($admin->type==1)
				{
					if(Hash::check($input['password'],$admin->password))
					{
						if($admin->status=='1')
						{
							$admin_details = json_decode(json_encode($admin),True);

							Session::put('admin_details',$admin_details);
							
							return redirect('admin/dashboard')->with('success', 'Login successfully');
						}
						elseif($admin->status=='2')
						{
							Session::flash('message', 'You are inactive'); 
							Session::flash('alert-class', 'alert-danger');
							return redirect('/');
						}
						elseif($admin->status=='3')
						{
							Session::flash('message', 'You are suspend'); 
							Session::flash('alert-class', 'alert-danger');
							return redirect('/');
						}
					}
					else
					{
						Session::flash('message', 'You enter wrong password'); 
						Session::flash('alert-class', 'alert-danger');
						return redirect('/');
					}
				}
				else
				{
					Session::flash('message', 'You are do not access account'); 
					Session::flash('alert-class', 'alert-danger');
					return redirect('/');
				}

    	 	}
    	 	else
    	 	{
    	 		Session::flash('message', 'You enter wrong email'); 
            	Session::flash('alert-class', 'alert-danger');
    	 		return redirect('/');
    	 	}



    }

	public function forgorIndex()
    {
    	return view($this->viewFolder.'admin_forgot');
    }

	public function forgorPassword(Request $request)
    {
		$input = $request->all();
	
	
    	$validatedData = $request->validate([
        'email' => 'required|email',
    		],
    		[
    			'email.required' => 'Email Field Is required',
    			'email.email' => 'Please input a valid email',
    		]);

			
			$admin = User::where('email',$input['email'])->first();
				
		
    	 	if(!empty($admin))
    	 	{
				if($admin->type==1)
				{
					
					if(empty($admin->deleted_at))
					{
						if($admin->status=='1')
						{
							$url=url('/reset-password/'.$admin->id);
							$mail = $admin->email;
							$name = $admin->first_name.' '.$admin->last_name;
							$mailData = array('name' =>$name ,'link' =>$url);
							Mail::to($mail)->send(new ResetPasswordMail($mailData));
							return redirect('/')->with('success', 'Send Link successfully.Please check your email.');
						}
						elseif($admin->status=='2')
						{
							Session::flash('message', 'You are inactive'); 
							Session::flash('alert-class', 'alert-danger');
							return redirect('/forgot-password');
						}
						elseif($admin->status=='3')
						{
							Session::flash('message', 'You are suspend'); 
							Session::flash('alert-class', 'alert-danger');
							return redirect('/forgot-password');
						}
					}
					else
					{
						Session::flash('message', 'You account is deleted'); 
						Session::flash('alert-class', 'alert-danger');
						return redirect('/forgot-password');
					}
				}
				else
				{
					Session::flash('message', 'You are do not access account'); 
					Session::flash('alert-class', 'alert-danger');
					return redirect('/forgot-password');
				}

    	 	}
    	 	else
    	 	{
    	 		Session::flash('message', 'You enter wrong email'); 
            	Session::flash('alert-class', 'alert-danger');
    	 		return redirect('/forgot-password');
    	 	}



    }

	public function resetPassword($id)
    {
		$user_id=$id;
    	return view($this->viewFolder.'admin_reset',compact('user_id'));
    }

	public function resetPasswordSave(Request $request)
	{
		$validatedData = $request->validate([
			'confirm_password' => 'required',
			'new_password' => 'required|same:confirm_password',
			
		]);
		$input = $request->all();
		$admin_data = User::where('id',$input['eid'])->first();
		
		if($admin_data)
        {

			if ($input['new_password']==$input['confirm_password']) 
			{
				$update_password = Hash::make($input['new_password'],[
					'rounds' => 12,
				]);
				$admin_data->password = $update_password;
				$admin_data->save();
				Session::flash('success', 'Password hasbeen changed Successfully'); 
				return redirect('/');
			}	
			else
			{
				Session::flash('message', 'You Entered a Wrong Password'); 
				Session::flash('alert-class', 'alert-danger');
				return redirect()->back();
			}
		}
		else
		{
            Session::flash('error', 'You Entered a Wrong Password!'); 
			return redirect()->back();
		}
		
	}

    public function logout()
    {
    	Session::forget('admin_details');
    	return redirect('/');
    }
}
