<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Challan;
use App\Models\Diesel;
use Carbon\Carbon;

if (!function_exists('get_user_menu_new')) 
{

	function get_user_menu_new($user_id)
	{
        $menu_array = null;
		$sub_menu_array = null;
        $nes_sub_menu_array=null;

        $query = DB::table('tbl_modules')
        ->select('*')
        ->where('parent_id', '0')
        ->where('status', '1')
        ->orderBy('order_id', 'ASC')
        ->get();

        if ($query->count() > 0) 
        {
            foreach ($query as $key=> $module) 
            {
                if (is_user_has_access($module->id)) 
                {
                    $menu_array[$key] = array(
                        "id" => $module->id,
                        "name" => $module->module_name,
                        "controller_name" => $module->controller_name,
                        "url" => $module->module_url,
                        "icon" => $module->module_icon
                    );

                    $sub_modules=get_permission_submenue($module->id);
                    
                    if ($sub_modules->count() > 0) 
                    {
                        foreach ($sub_modules as $key2=> $sub_menu) 
                        {
                            // if (is_user_has_access($sub_menu->id)) {
                                $sub_menu_array = array(
                                    "id" => $sub_menu->id,
                                    "name" => $sub_menu->module_name,
                                    "controller_name" => $sub_menu->controller_name,
                                    "url" => $sub_menu->module_url,
                                    "icon" => $sub_menu->module_icon
                                );

                                $menu_array[$key]['sub_menus'][] = $sub_menu_array;
                                $sub_menu_array = null;

                                // nested sub manue
                                $nested_sub_modules = DB::table('tbl_modules')
                                ->select('*')
                                ->where('parent_id', $sub_menu->id)
                                ->where('status', '1')
                                ->get();


                                if ($nested_sub_modules->count() > 0) 
                                {
                                    foreach ($nested_sub_modules as $nes_sub_menu) 
                                    {

                                        $nes_sub_menu_array = array(
                                            "id" => $nes_sub_menu->id,
                                            "name" => $nes_sub_menu->module_name,
                                            "controller_name" => $nes_sub_menu->controller_name,
                                            "url" => $nes_sub_menu->module_url,
                                            "icon" => $nes_sub_menu->module_icon
                                        );

                                        $menu_array[$key]['sub_menus'][$key2]['nes_sub_menus'][] = $nes_sub_menu_array;
                                        $nes_sub_menu_array = null;
                                    }

                                }
                        }
                    }
                }
            }
        }
        //echo "<pre/>"; print_r($menu_array); die;
        return $menu_array;

    }
}

// if visible to user as menu
if (!function_exists('is_user_has_access')) 
{
	function is_user_has_access($module_id)
	{

		$role_id = Session::get('admin_details')['role_id'];
		$user_id = Session::get('admin_details')['id'];
        
        $query = DB::table('tbl_action_permissions')
        ->select('*')
        ->where('role_id', $role_id)
        ->where('module_id', $module_id)
        ->get();

		
		if ($query->count() > 0) 
        {

			$permission = $query[0]->permission;

				if ($permission=='1') 
                {
					return true;
				} 
                else 
                {
					return false;
				}

		} 
        else 
        {
			return false;
		}
		return false;
	}
}

if (!function_exists('get_permission_submenue')) 
{
	function get_permission_submenue($module_id)
	{

		$role_id = Session::get('admin_details')['role_id'];

		$user_id = Session::get('admin_details')['id'];
        
        $query = DB::table('tbl_action_permissions')
        ->select('tbl_modules.*')
        ->leftjoin('tbl_modules','tbl_modules.id','=','tbl_action_permissions.module_id')
        ->where('tbl_action_permissions.role_id', $role_id)
        ->where('tbl_modules.parent_id', $module_id)
        ->where('tbl_modules.status', '1')
        ->where('tbl_action_permissions.permission', '1')
        ->get();

		
        return $query;


	}
}


if (!function_exists('pr'))
{
    function pr($data)
    {
        echo "<pre>";
        print_r($data);
        echo "<pre>";
        die;

    }
}

if (!function_exists('get_role_name')) 
{
	function get_role_name($id)
	{

        $result = DB::table('tbl_roles')->where('id', $id)
        ->first();
        return @$result->role_name;


	}
}

if (!function_exists('get_transporter_name')) 
{
	function get_transporter_name($id)
	{

        $result = DB::table('tbl_transporters')->where('id', $id)
        ->first();
        return @$result->transporter_name;


	}
}

if (!function_exists('get_client_name')) 
{
	function get_client_name($id)
	{

        $result = DB::table('tbl_clients')->where('id', $id)
        ->first();
        return @$result->company_name;


	}
}

if (!function_exists('get_no_vehicle')) 
{
	function get_no_vehicle($id)
	{

        $count = DB::table('tbl_vehicles')->where('transporter_id', $id)->where('deleted_at',NULL)
        ->count();
        return @$count;


	}
}


if (!function_exists('get_vehicle_name')) 
{
	function get_vehicle_name($id, $lineBreak=null)
	{

        $result = DB::table('tbl_vehicles')->where('id', $id)->where('deleted_at',NULL)
        ->first();
        if(@$result->vehicle_name)
        {
            if($lineBreak=="1")
            {
                return @$result->vehicle_no .'<br/> ( '.@$result->vehicle_name.' )';
            }
            return @$result->vehicle_no .' ( '.@$result->vehicle_name.' )';
        }
        else
        {
            return @$result->vehicle_no;
        }


	}
}

if (!function_exists('get_location_name')) 
{
	function get_location_name($id)
	{

        $result = DB::table('tbl_locations')->where('id', $id)->where('deleted_at',NULL)
        ->first();
        
        return @$result->location_name;

	}
}

if (!function_exists('get_total_diesel_consumed')) 
{
	function get_total_diesel_consumed($id)
	{

        $total_diesel= DB::table('tbl_diesels')->where('vehicle_id', $id)->where('deleted_at',NULL)->sum('quantity');
        return $total_diesel;
	}
}

if (!function_exists('get_total_trip_done')) 
{
	function get_total_trip_done($id)
	{

        $total_trip = DB::table('tbl_challans')->where('vehicle_id', $id)->where('deleted_at',NULL)
        ->count();
        return $total_trip;


	}
}

if (!function_exists('GeneratorSLNO')) 
{
	function GeneratorSLNO($model)
	{
        if ($model == 'Challan') 
        {
            $slNo = Challan::orderBy('id', 'DESC')->value('sl_no');
        }
        else 
        {
            $slNo = Diesel::orderBy('id', 'DESC')->value('sl_no');
        }

        if (!empty($slNo)) 
        {
            $pattern = '/\d+/';
            preg_match_all($pattern, $slNo, $matches);

            $lettersOnly = preg_replace("/[^a-zA-Z]/", "", $slNo);

            $m = $lettersOnly;
            $y = isset($matches[0]) && isset($matches[0][0]) ? $matches[0][0] : '';

            $ym = $m.''.$y;

            if (Carbon::now()->format('My') == $ym) {
                $sl = isset($matches[0]) && isset($matches[0][1]) ? (int) $matches[0][1] + 1 : 1;

                return Carbon::now()->format('My').'-'.sprintf("%03d", $sl);
            }

        }
        return Carbon::now()->format('My').'-'.sprintf("%03d", 1);
	}
}

if (!function_exists('get_user_name')) 
{
	function get_user_name($id,$lineBreak=null)
	{

        $result = DB::table('tbl_users')->where('id', $id)->where('deleted_at',NULL)
        ->first();
        $name=@$result->first_name.' '.@$result->last_name;
        if(@$result->id==1)
        {
        
            return 'Admin';
        }
        else
        {
            if(!empty(@$result->mobile))
            {
                if($lineBreak=="1")
                {
                    return @$name.'<br/>('.@$result->mobile.')';
                }
                return @$name.' ('.@$result->mobile.')';
            }
            return @$name;
        }


	}
}










    
