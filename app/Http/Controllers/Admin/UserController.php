<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mail;
use App\Mail\SendPasswordMail;

class UserController extends Controller
{
    private $viewFolder = 'admin/user/';
    private $routePrefix = 'admin/user';

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


        $recordsQuery=User::where('deleted_at',NULL);

        if($request->searchFdate!="" && $request->searchTdate!="")
        {
            $start_date=date("Y-m-d", strtotime($request->searchFdate));
            $end_date=date("Y-m-d", strtotime($request->searchTdate));
            $recordsQuery = $recordsQuery
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date);
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
            $name=$record->first_name.' '.$record->last_name;
            $role=get_role_name($record->role_id);
            
            if($record->type=='1')
            {
                $user_role="Admin";
            }
            elseif($record->type=='2')
            {
                $user_role="Challan";
            }
            elseif($record->type=='3')
            {
                $user_role="Diesel";
            }
            elseif($record->type=='4')
            {
                $user_role="ChallanAndDiesel";
            }
            
            $email=$record->email;
            $phone=$record->mobile;
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

            if($record->type==1)
            {
                $action .='<a onclick="send_password('.$record->id.')" class="waves-effect btn btn-warning ml-1" style="font-size: 15px;"><i class="fa fa-key"></i></a>';
            }


            $data_arr[] = array(
            'sl'=>$sl,
            "name" => $name,
            "role" => $role,
            "user_role" => $user_role,
            "email" =>$email,
            "phone" =>$phone,
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
        $role_data        = Role::where('status',1)->where('deleted_at',NULL)->get();
    	return view($this->viewFolder.'add',compact('role_data'));
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required',
			'mobile' => 'required',
			'role_id' => 'required',
			'type' => 'required',
		]);

        $input=$request->all();
        $password='';
        $randon_pass=substr($input['first_name'], 0, 3).'@'.substr($input['mobile'],-4);
        if($input['type']==1)
        {
            $password = Hash::make($randon_pass,[
                'rounds' => 12,
            ]);
        }
		$count = DB::table('tbl_users')->where('email', '=', $input['email'])->count();
		
		if ($count == 0) 
		{
			$user = new User;
			$user->first_name = $input['first_name'];
			$user->last_name = $input['last_name'];
			$user->email = $input['email'];
			$user->mobile = $input['mobile'];
			$user->role_id = $input['role_id'];
			$user->type = $input['type'];
            $user->password = $password;
			$user->country_code = "+91";
			$user->save();
            if($input['type']==1)
            {
                $mail = $input['email'];
                $name =  $input['first_name'].' '.$input['last_name'];
                $mailData = array('name' =>$name ,'mail' =>$mail ,'password' =>$randon_pass);
                Mail::to($mail)->send(new SendPasswordMail($mailData));
            }
			return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
		} 
		else 
		{
			$roleRecord = DB::table('tbl_users')->where('email', '=', $input['email'])->whereNull('deleted_at')->first();
			if (!empty($roleRecord)) 
			{
				return redirect($this->routePrefix.'/add')->with('error', 'Duplicate Data');
			} 
			else 
			{

				$user = new User;
                $user->first_name = $input['first_name'];
                $user->last_name = $input['last_name'];
                $user->email = $input['email'];
                $user->mobile = $input['mobile'];
                $user->role_id = $input['role_id'];
                $user->type = $input['type'];
                $user->password = $password;
                $user->country_code = "+91";
                $user->save();
                if($input['type']==1)
                {
                    $mail = $input['email'];
                    $name =  $input['first_name'].' '.$input['last_name'];
                    $mailData = array('name' =>$name ,'mail' =>$mail ,'password' =>$randon_pass);
                    Mail::to($mail)->send(new SendPasswordMail($mailData));
                }
				return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
			}
		}
    }

    public function status(Request $request)
    {
        
        $input=$request->all();
		$user = User::where('id', '=', $input['id'])->first();
		
        if($user)
        {
            if ($user->status == '0') 
            {
                $user->status = '1';
            } 
            else 
            {
                $user->status = '0';
            }
            $user->save();
            return true;
        }
        else
        {
            return false;
        }
    }

    public function edit($id)
    {
		$user             = User::where('id', '=', $id)->first();
        $role_data        = Role::where('status',1)->where('deleted_at',NULL)->get();
    	return view($this->viewFolder.'edit',compact('user','role_data'));
    }

    public function delete($id)
    {
       
		$user = User::where('id', '=', $id)->first();
        $user->deleted_at = date('Y-m-d');
        $user->save();
    	return redirect()->back()->with('success', 'Deleted updated successfully');
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required',
			'mobile' => 'required',
			'role_id' => 'required',
			'type' => 'required',
		]);
        $input=$request->all();

        $password='';
        $randon_pass=substr($input['first_name'], 0, 3).'@'.substr($input['mobile'],-4);
        if($input['type']==1)
        {
            $password = Hash::make($randon_pass,[
                'rounds' => 12,
            ]);
        }

        $user        = User::where('id', $input['edit_id'])->first();
        $user->first_name = $input['first_name'];
        $user->last_name = $input['last_name'];
        $user->email = $input['email'];
        $user->mobile = $input['mobile'];
        $user->role_id = $input['role_id'];
        $user->password = $password;
        $user->type = $input['type'];
        $user->save();

        if($input['type']==1)
        {
            $mail = $input['email'];
            $name =  $input['first_name'].' '.$input['last_name'];
            $mailData = array('name' =>$name ,'mail' =>$mail ,'password' =>$randon_pass);
            Mail::to($mail)->send(new SendPasswordMail($mailData));
        }
        return redirect($this->routePrefix.'/list')->with('success', 'Updated successfully.');
    }

    public function SendPassword(Request $request)
    {
       
        $input=$request->all();
        $user        = User::where('id', $input['edit_id'])->first();

    
        if(!empty($user->email))
        {
            $password='';
            $randon_pass=substr($user->first_name, 0, 3).'@'.substr($user->mobile,-4);
            if($user->type==1)
            {
                $password = Hash::make($randon_pass,[
                    'rounds' => 12,
                ]);
        
                $user->password = $password;
                $user->save();

            
                $mail = $user->email;
                $name =  $user->first_name.' '.$user->last_name;
                $mailData = array('name' =>$name ,'mail' =>$mail ,'password' =>$randon_pass);
                Mail::to($mail)->send(new SendPasswordMail($mailData));
                echo "1";
            }
            else
            {
                echo "2";
            }
            
        }
        else
        {
            echo "2";
        }
        
    }
   
}
