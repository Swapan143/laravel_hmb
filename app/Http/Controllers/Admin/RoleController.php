<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Module;
use Illuminate\Support\Facades\DB;


class RoleController extends Controller
{
    private $viewFolder = 'admin/role/';
    private $routePrefix = 'admin/role';

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


        $recordsQuery=Role::where('deleted_at',NULL);

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
            $name=$record->role_name;
            $description=$record->role_description;
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
                                            
            <a href="'.url($this->routePrefix.'/delete/'.$record->id).'" style="font-size: 15px;" class="waves-effect btn btn-danger" onclick="return confirm('.$m.')"><i class="fa fa-trash"></i></a>

            <a href="'.url($this->routePrefix.'/permission/'.$record->id).'" style="font-size: 15px;" class="btn btn-primary waves-effect details-control">SET PERMISSION</a>';


            $data_arr[] = array(
            'sl'=>$sl,
            "name" => $name,
            "description" =>$description,
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
    	return view($this->viewFolder.'add');
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
			'name' => 'required',
		]);
        $input=$request->all();

		$count = DB::table('tbl_roles')->where('role_name', '=', $input['name'])->count();
		
		if ($count == 0) 
		{
			$role = new Role;
			$role->role_name = $input['name'];
			$role->role_description = $input['description'];
			$role->save();
			return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
		} 
		else 
		{
			$roleRecord = DB::table('tbl_roles')->where('role_name', '=', $input['name'])->whereNull('deleted_at')->first();
			if (!empty($roleRecord)) 
			{
				return redirect($this->routePrefix.'/add')->with('error', 'Duplicate Data');
			} 
			else 
			{
				$role = new Role;
                $role->role_name = $input['name'];
                $role->role_description = $input['description'];
                $role->save();
				return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
			}
		}
    }

    public function status(Request $request)
    {
        
        $input=$request->all();
		$role = Role::where('id', '=', $input['id'])->first();
		
        if($role)
        {
            if ($role->status == '0') 
            {
                $role->status = '1';
            } 
            else 
            {
                $role->status = '0';
            }
            $role->save();
            return true;
        }
        else
        {
            return false;
        }
    }

    public function edit($id)
    {
		$role = Role::where('id', '=', $id)->first();
    	return view($this->viewFolder.'edit',compact('role'));
    }

    public function delete($id)
    {
       
		$role = Role::where('id', '=', $id)->first();
        $role->deleted_at = date('Y-m-d');
        $role->save();
    	return redirect()->back()->with('success', 'Deleted updated successfully');
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
			'name' => 'required',
		]);
        $input=$request->all();
        $role        = Role::where('id', $input['edit_id'])->first();
        $role->role_name = $input['name'];
        $role->role_description = $input['description'];
        $role->save();
        return redirect($this->routePrefix.'/list')->with('success', 'Updated successfully.');
    }

    public function role_permission($role)
    {
        $module_array = [];
        $Model = new Role();
        $roles= $Model->select('*')->where('id', $role)->first();

        $permission = DB::table('tbl_action_permissions')->select('*')
            ->where('role_id',$role)
            ->where('permission',1)
            ->get();

        $permission_array=[];

        if($permission)
        {
            foreach ($permission as $key => $perVal) 
            {
                $permission_array[]=$perVal->module_id;
            }
        }

        $modules= Module::select('*')->where('parent_id','0')->where('status','1')->get();
        if ($modules->count() > 0) 
        {
            foreach ($modules as $module) 
            {
                $sub_modules= Module::select('*')->where('parent_id',$module->id)->where('status','1')->get();
                if ($sub_modules->count() > 0) 
                {
                    $module_array[] = array('module' => $module, 'sub_modules' => $sub_modules);
                }
                else
                {
                    $module_array[] = array('module' => $module, 'sub_modules' => '');
                }

            }
        }

      
        // echo "<pre/>"; print_r($module_array); die;
       return view($this->viewFolder.'permission_module',['modules'=>$module_array,'roles'=>$roles,'permission_array'=>$permission_array]);
    }   

    public function update_role_permission(Request $request)
    {
		$input=$request->all();
		
        if(isset($input['module_id']) && !empty($input['module_id']))
        {
            $action_perm_role = DB::table('tbl_action_permissions')->select('*')
            ->where('role_id',$input['roleId'])
            ->get();
           
            $perValArray=[];
            foreach ($action_perm_role as $value) 
            {
                $perValArray[]=$value->module_id;
            }

            $result = array_diff($perValArray, $input['module_id']);
            // echo "<pre/>"; print_r($result); die;
            foreach ($result as  $value1) 
            {
                $update = [
                    'permission'=>0
                ];

                DB::table('tbl_action_permissions') ->where('role_id',@$input['roleId'])
                ->where('module_id',$value1)
                ->update($update);
            }

            foreach ($input['module_id'] as $key => $moduleVal) 
            {
                $action_perm_exist = DB::table('tbl_action_permissions')->select('*')
                ->where('role_id',@$input['roleId'])
                ->where('module_id',$moduleVal)
                ->get();
                
                if(count($action_perm_exist)>0)
                {

                    $update1 = [
                        'permission'=>1
                    ];
    
                    DB::table('tbl_action_permissions') ->where('role_id',@$input['roleId'])
                    ->where('module_id',$moduleVal)
                    ->update($update1);

                }
                else
                {
                    $insert = [
                        'module_id' => @$moduleVal,
                        'role_id'=> @$input['roleId'],
                        'status'=>1,
                        'permission'=>1
                    ];
                
                    DB::table('tbl_action_permissions')->insert($insert);
                }
                //echo "<pre/>"; print_r($action_perm_exist); die;
            }

        }
        else
        {
            DB::table('tbl_action_permissions')->where('role_id', @$input['roleId'])->delete();
        }
		
        return redirect('admin/role/list')->with('success', 'Role updated successfully.');
        
	}
   
}
