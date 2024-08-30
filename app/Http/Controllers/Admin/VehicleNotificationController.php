<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\VehicleNotification;

use Illuminate\Support\Facades\DB;
// use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\ExportVehicle;
// use App\Classes\PHPExcel;
// require 'vendor/autoload.php';
// require_once __DIR__ . '/vendor/autoload.php'; 
// require __DIR__.'/../vendor/autoload.php';

// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class VehicleNotificationController extends Controller
{
    private $viewFolder = 'admin/vehicle_notification/';
    private $routePrefix = 'admin/vehicle-notification';

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


        $recordsQuery=VehicleNotification::distinct('tbl_vehicle_notifications.id')->select('tbl_vehicle_notifications.*','tbl_vehicles.vehicle_no','tbl_vehicles.vehicle_name')->leftjoin('tbl_vehicles','tbl_vehicle_notifications.vehicle_id','=','tbl_vehicles.id')->where('tbl_vehicle_notifications.deleted_at',NULL);

        if($request->searchFdate!="" && $request->searchTdate!="")
        {
            $start_date=date("Y-m-d", strtotime($request->searchFdate));
            $end_date=date("Y-m-d", strtotime($request->searchTdate));
            $recordsQuery = $recordsQuery
            ->whereDate('tbl_vehicle_notifications.created_at', '>=', $start_date)
            ->whereDate('tbl_vehicle_notifications.created_at', '<=', $end_date);
        }

        if($searchValue!="")
        {
            $recordsQuery=$recordsQuery->where(function ($query) use ($searchValue) {
                $query->where('tbl_vehicles.vehicle_no', 'like', '%' .$searchValue . '%')
                ->orwhere('tbl_vehicles.vehicle_name', 'like', '%' .$searchValue . '%');
            });
        }
   
        $totalRecords = $recordsQuery->count();
        $records =$recordsQuery->skip($start)
        ->take($rowperpage)
        ->orderBy('tbl_vehicle_notifications.id', 'desc')
        ->get();

        
        $totalRecordswithFilter = $totalRecords;

        $data_arr = array();
        $count=0;
        foreach($records as $k=> $record)
        {
            $curent_date=date("d-M-Y");
            $sl=$k+1;
            $vehicle_no=get_vehicle_name($record->vehicle_id);
            $permit_valid=(!empty($record->permit_valid_date))?date("d-M-Y", strtotime($record->permit_valid_date)):'NA';
          
            // style=" border-color:#FF0000;background-color: #FF0000; color:#fff">Expired
            // style=" border-color:#FFFF00;background-color: #FFFF00; color:#1a1717"> Renew Immediately
            // style=" border-color:#008000;background-color: #008000; color:#fff">Valid
            if( $permit_valid !='NA')
            {
                $permit_date_ten_days=date("d-M-Y", strtotime("-10 days", strtotime($permit_valid)));
               
                if( strtotime($curent_date) < strtotime($permit_date_ten_days))
                {
                    $permit_valid_value='<span class ="badge" style=" border-color:#008000;background-color: #008000; color:#fff">'.$permit_valid.'</span>';
                }
                elseif( strtotime($curent_date) >  strtotime($permit_valid))
                {
                    $permit_valid_value='<span class ="badge" style=" border-color:#FF0000;background-color: #F12042; color:#fff">'.$permit_valid.'</span>';
                }
                elseif( strtotime($curent_date) >=  strtotime($permit_date_ten_days) &&  strtotime($curent_date) <=  strtotime($permit_valid))
                {
                    $permit_valid_value='<span class ="badge" style=" border-color:#FFFF00;background-color: #FFFF00; color:#1a1717">'.$permit_valid.'</span>';
                }
            }
            else
            {
                $permit_valid_value='<span>'.$permit_valid.'</span>';
            }
           
           
            
            $tax_valid=(!empty($record->tax_valid_date))?date("d-M-Y", strtotime($record->tax_valid_date)):'NA';
    
            if($tax_valid !='NA')
            {
                $tax_date_ten_days=date("d-M-Y", strtotime("-10 days", strtotime($tax_valid)));

                if( strtotime($curent_date) < strtotime($tax_date_ten_days))
                {
                    $tax_valid_value='<span class ="badge" style=" border-color:#008000;background-color: #008000; color:#fff">'.$tax_valid.'</span>';
                    
                }
                elseif( strtotime($curent_date) >  strtotime($tax_valid))
                {
                    $tax_valid_value='<span class ="badge" style=" border-color:#FF0000;background-color: #F12042; color:#fff">'.$tax_valid.'</span>';
                }
                elseif( strtotime($curent_date) >=  strtotime($tax_date_ten_days) &&  strtotime($curent_date) <=  strtotime($tax_valid))
                {
                    $tax_valid_value='<span class ="badge" style=" border-color:#FFFF00;background-color: #FFFF00; color:#1a1717">'.$tax_valid.'</span>';
                }
            }
            else
            {
                $tax_valid_value='<span>'.$tax_valid.'</span>';
            }
           



            $fitness_valid=(!empty($record->fitness_valid_date))?date("d-M-Y", strtotime($record->fitness_valid_date)):'NA';
          
            if($fitness_valid !='NA')
            {
                
                $fitness_date_ten_days=date("d-M-Y", strtotime("-10 days", strtotime($fitness_valid)));

                if( strtotime($curent_date) < strtotime($fitness_date_ten_days))
                {
                    $fitness_valid_value='<span class ="badge" style=" border-color:#008000;background-color: #008000; color:#fff">'.$fitness_valid.'</span>';
                    
                }
                elseif( strtotime($curent_date) >  strtotime($fitness_valid))
                {
                    $fitness_valid_value='<span class ="badge" style=" border-color:#FF0000;background-color: #F12042; color:#fff">'.$fitness_valid.'</span>';
                }
                elseif( strtotime($curent_date) >=  strtotime($fitness_date_ten_days) &&  strtotime($curent_date) <=  strtotime($fitness_valid))
                {
                    $fitness_valid_value='<span class ="badge" style=" border-color:#FFFF00;background-color: #FFFF00; color:#1a1717">'.$fitness_valid.'</span>';
                }
            }
            else
            {
                $fitness_valid_value='<span>'.$fitness_valid.'</span>';
            }
           

            

            
            $insurance_valid=(!empty($record->insurance_valid_date))?date("d-M-Y", strtotime($record->insurance_valid_date)):'NA';
           
            if($insurance_valid !='NA')
            {

                $insurance_date_ten_days=date("d-M-Y", strtotime("-10 days", strtotime($insurance_valid)));

                if( strtotime($curent_date) < strtotime($insurance_date_ten_days))
                {
                    $insurance_valid_value='<span class ="badge" style=" border-color:#008000;background-color: #008000; color:#fff">'.$insurance_valid.'</span>';
                    
                }
                elseif( strtotime($curent_date) >  strtotime($insurance_valid))
                {
                    $insurance_valid_value='<span class ="badge" style=" border-color:#FF0000;background-color: #F12042; color:#fff">'.$fitness_valid.'</span>';
                }
                elseif( strtotime($curent_date) >=  strtotime($insurance_date_ten_days) &&  strtotime($curent_date) <=  strtotime($insurance_valid))
                {
                    $insurance_valid_value='<span class ="badge" style=" border-color:#FFFF00;background-color: #FFFF00; color:#1a1717">'.$fitness_valid.'</span>';
                }
            }
            else
            {
                $insurance_valid_value='<span>'.$insurance_valid.'</span>';
            }
           

        


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
            $action='<a href="'.url($this->routePrefix.'/edit/'.$record->id).'" class="waves-effect btn btn-warning mr-1" style="font-size: 15px;margin: 2px;"><i class="fa fa-pencil-square-o"></i></a>
                                            
            <a href="'.url($this->routePrefix.'/delete/'.$record->id).'" style="font-size: 15px; margin: 2px;" class="waves-effect btn btn-danger " onclick="return confirm('.$m.')"><i class="fa fa-trash"></i></a>';


            $data_arr[] = array(
            'sl'=>$sl,
            "vehicle_no" =>$vehicle_no,
            "permit_valid" =>$permit_valid_value,
            "tax_valid" =>$tax_valid_value,
            "fitness_valid" => $fitness_valid_value,
            "insurance_valid" => $insurance_valid_value,
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
        $vehicle_noti_data        = VehicleNotification::where('status',1)->where('deleted_at',NULL)->get()->pluck('id');
        $vehicle_data        = Vehicle::where('status',1)->where('deleted_at',NULL)->whereNotIn('id',$vehicle_noti_data)->get();
    

    	return view($this->viewFolder.'add',compact('vehicle_data'));
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
			'vehicle_id' => 'required',
		]);

        $input=$request->all();

		$count = DB::table('tbl_vehicle_notifications')->where('vehicle_id', '=', $input['vehicle_id'])->count();
		
		if ($count == 0) 
		{
			$vehicle_noti = new VehicleNotification;
            $vehicle_noti->vehicle_id = $input['vehicle_id'];
            $vehicle_noti->permit_valid_date = $input['permit_valid_date'];
            $vehicle_noti->tax_valid_date = $input['tax_valid_date'];
            $vehicle_noti->fitness_valid_date = $input['fitness_valid_date'];
            $vehicle_noti->insurance_valid_date = $input['insurance_valid_date'];
            $vehicle_noti->save();
			return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
		} 
		else 
		{
			$roleRecord = DB::table('tbl_vehicle_notifications')->where('vehicle_id', '=', $input['vehicle_id'])->whereNull('deleted_at')->first();
			if (!empty($roleRecord)) 
			{
				return redirect($this->routePrefix.'/add')->with('error', 'Duplicate Data');
			} 
			else 
			{
				$vehicle_noti = new VehicleNotification;
                $vehicle_noti->vehicle_id = $input['vehicle_id'];
                $vehicle_noti->permit_valid_date = $input['permit_valid_date'];
                $vehicle_noti->tax_valid_date = $input['tax_valid_date'];
                $vehicle_noti->fitness_valid_date = $input['fitness_valid_date'];
                $vehicle_noti->insurance_valid_date = $input['insurance_valid_date'];
                $vehicle_noti->save();
				return redirect($this->routePrefix.'/list')->with('success', 'Successfully Submitted');
			}
		}
    }

    public function status(Request $request)
    {
        
        $input=$request->all();
		$vehicle_noti = VehicleNotification::where('id', '=', $input['id'])->first();
		
        if($vehicle_noti)
        {
            if ($vehicle_noti->status == '0') 
            {
                $vehicle_noti->status = '1';
            } 
            else 
            {
                $vehicle_noti->status = '0';
            }
            $vehicle_noti->save();
            return true;
        }
        else
        {
            return false;
        }
    }

    public function edit($id)
    {
        $vehicle_data        = Vehicle::where('status',1)->where('deleted_at',NULL)->get();
		$vehicle_noti_data             = VehicleNotification::where('id', '=', $id)->first();
    	return view($this->viewFolder.'edit',compact('vehicle_data','vehicle_noti_data'));
    }

    public function delete($id)
    {
       
		$vehicle = VehicleNotification::where('id', '=', $id)->first();
        $vehicle->deleted_at = date('Y-m-d');
        $vehicle->save();
    	return redirect()->back()->with('success', 'Deleted updated successfully');
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
			'vehicle_id' => 'required',
		]);
        $input=$request->all();
        $vehicle_noti        = VehicleNotification::where('id', $input['edit_id'])->first();
        $vehicle_noti->permit_valid_date = $input['permit_valid_date'];
        $vehicle_noti->tax_valid_date = $input['tax_valid_date'];
        $vehicle_noti->fitness_valid_date = $input['fitness_valid_date'];
        $vehicle_noti->insurance_valid_date = $input['insurance_valid_date'];
        $vehicle_noti->save();
        return redirect($this->routePrefix.'/list')->with('success', 'Updated successfully.');
    }

    public function csvExportOld(Request $request)
    {
       
        $fileName = 'vehicle_report_list.csv';
        $headers = array(
            "Content-Encoding"=> "UTF-8",
            "Content-type"        => "text/csv;charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
            
        );

        $delimiter = ","; 
        $filename = "vehicle_report_list_" . date('Y-m-d') . ".csv"; 
        $f = fopen('uploads/vehicle/vehicle_report_list.csv', 'w');

        $fields = array('Sl. No.', 'Vehicle No', 'Permit Valid Upto', 'Tax Valid Upto','Fitness Valid Upto','Insurance Valid Upto'); 
        fputcsv($f, $fields, $delimiter); 
        
        // Output each row of the data, format line as csv and write to file pointer 
        $vehicle_data=VehicleNotification::where('deleted_at',NULL)->get();
        // pr($user_data);
        $count=1;
        foreach ($vehicle_data as  $vehicle_row) 
        {
            $sl_no =$count;
            $vehicle_no=$vehicle_row->vehicle_id;
            $permit_valid_upto=$vehicle_row->permit_valid_date;
            $tax_valid_upto=$vehicle_row->tax_valid_date;
            $fitness_valid_upto=$vehicle_row->fitness_valid_date;
            $insurance_valid_upto=$vehicle_row->insurance_valid_date;
            
            $lineData = array($sl_no,$vehicle_no,$permit_valid_upto,$tax_valid_upto,$fitness_valid_upto,$insurance_valid_upto); 

            fputcsv($f, $lineData, $delimiter); 
            $count++;
        } 
        
        // Move back to beginning of file 
        fseek($f, 0); 
        
        // Set headers to download file rather than displayed 
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '";'); 

    
        //output all remaining data on a file pointer 
        // fpassthru($f); 
        fclose($f);
        return "success";
    }
   
    public function csvExport(Request $request)
    {
        // include(app_path() . '\Classes\PHPExcel.php');
        // require 'Classes\PHPExcel.php';  
        // $spreadsheet = new Spreadsheet();
        // $activeWorksheet = $spreadsheet->getActiveSheet();
        // $activeWorksheet->setCellValue('A1', 'Hello World !');

        // $writer = new Xlsx($spreadsheet);
        // $writer->save('hello world.xlsx');
        // return Excel::download(new ExportVehicle, 'vehicle.xlsx');

        // $client_id=$_POST['client_id'];
		// $from_date=$_POST['from_date'];
		// $to_date=$_POST['to_date'];
		// $sql = "SELECT cm.client_name,so.id,so.rst_no, so.vehicel_number, so.gross, so.net, so.deduct,so.deduction_percent,date_format(so.gross_date_time,'%d-%m-%Y') as gross_date_time,so.rate,so.final,so.document,so.created_ts,so.fine_leaf FROM stock_out so INNER JOIN client_mst cm ON cm.id=so.client_id where date_format(so.created_ts,'%Y-%m-%d') between '$from_date' and '$to_date' and cm.id = '$client_id'";
		// //echo $sql;
		// $query = $conn->query($sql);
		// while($row = $query->fetch_assoc())
		// {
		// 	$ret[]=$row;
		// }
		//$return_data = array("status" => true, "stock_list" =>$ret);
		
		//echo json_encode($return_data); 
		$objPHPExcel = new PHPExcel();  
		// Set the active Excel worksheet to sheet 0 
		$objPHPExcel->setActiveSheetIndex(0);  
		// Initialise the Excel row number 

		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'SL No.');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Date');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Customer Name');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'RST No');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Fine Leaf %');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'NET');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Rate Per Kg');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Total Amount');
		
		
		$styleArray = array(
		'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '000000'),
        ));

		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);
		
		foreach(range('A','H') as $columnID)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}

		//start while loop to get data  
		// $rowCount = 2;
		// $rowCount_new = 1;
		//$ref_no =0;
		// $amt =0;
		// $existtempinid = array();
		// while($row = $query->fetch_assoc())  
		// {
			

		// 	$objPHPExcel->getActiveSheet()->setCellValue('A'.$rowCount, $rowCount_new++); 
		// 	$objPHPExcel->getActiveSheet()->setCellValue('B'.$rowCount, $row['gross_date_time']); 
		// 	$objPHPExcel->getActiveSheet()->setCellValue('C'.$rowCount,$row['client_name']);
		// 	$objPHPExcel->getActiveSheet()->setCellValue('D'.$rowCount, $row['rst_no']);
		// 	$objPHPExcel->getActiveSheet()->setCellValue('E'.$rowCount, $row['fine_leaf']);
		// 	$objPHPExcel->getActiveSheet()->setCellValue('F'.$rowCount, $row['net']);
		// 	$objPHPExcel->getActiveSheet()->setCellValue('G'.$rowCount, $row['rate']);
		// 	$objPHPExcel->getActiveSheet()->setCellValue('H'.$rowCount, $row['final']);
		// 	$rowCount++;
		// } 
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_start();
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'."Product_list".date('jS-F-y H-i-s').".xlsx".'"');
		header('Cache-Control: max-age=0');
		$objWriter->save("php://output");
		$xlsData = ob_get_contents();
		ob_end_clean();

		$file_name= 'Expenses_List'.$curdate;
		$return_data =  array(
			'status' => true,'file_name'=>$file_name,
			'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData),
			'query'=>$query
		);
		echo json_encode($return_data);	
    }

    public function getExpiredVehicleDataAjax(Request $request)
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
        // $search_arr = $request->get('search');

        // $columnIndex = $columnIndex_arr[0]['column'];
        // $columnName = $columnName_arr[$columnIndex]['data'];
        // $columnSortOrder = $order_arr[0]['dir'];
        //$searchValue = $search_arr['value'];
        // $searchValue = $request->searchSearch;


        $recordsQuery=VehicleNotification::distinct('tbl_vehicle_notifications.id')->select('tbl_vehicle_notifications.*','tbl_vehicles.vehicle_no','tbl_vehicles.vehicle_name')->leftjoin('tbl_vehicles','tbl_vehicle_notifications.vehicle_id','=','tbl_vehicles.id')->where('tbl_vehicle_notifications.deleted_at',NULL);

        

        $totalRecords = $recordsQuery->count();
        $records =$recordsQuery->skip($start)
        ->take($rowperpage)
        ->orderBy('tbl_vehicle_notifications.id', 'desc')
        ->get();

        
        $totalRecordswithFilter = $totalRecords;

        $data_arr = array();
        $count=0;
        foreach($records as $k=> $record)
        {
            $curent_date=date("d-M-Y");
            $sl=$k+1;
            $vehicle_no=get_vehicle_name($record->vehicle_id,'1');
            $permit_valid=(!empty($record->permit_valid_date))?date("d-M-Y", strtotime($record->permit_valid_date)):'NA';
            $curent_date_ten_days=date("d-M-Y", strtotime("-10 days", strtotime($curent_date)));

            $check_valid_data=0;
            $permit_valid_value='';

            

            if( $permit_valid !='NA')
            {
                
                if( strtotime($curent_date) >  strtotime($permit_valid))
                {
                    $permit_valid_value='<span class ="badge" style=" border-color:#FF0000;background-color: #F12042; color:#fff">'.$permit_valid.'</span>';
                    $check_valid_data=1;
                }
                
            }
            else
            {
                $permit_valid_value='<span>'.$permit_valid.'</span>';
            }
            
            $tax_valid=(!empty($record->tax_valid_date))?date("d-M-Y", strtotime($record->tax_valid_date)):'NA';

            $tax_valid_value='';
            if($tax_valid !='NA')
            {
                if( strtotime($curent_date) >  strtotime($tax_valid))
                {
                    $tax_valid_value='<span class ="badge" style=" border-color:#FF0000;background-color: #FF0000; color:#fff">'.$tax_valid.'</span>';
                    $check_valid_data=1;
                }
            }
            else
            {
                $tax_valid_value='<span>'.$tax_valid.'</span>';
            }
        

            $fitness_valid=(!empty($record->fitness_valid_date))?date("d-M-Y", strtotime($record->fitness_valid_date)):'NA';
            $fitness_valid_value='';
            if($fitness_valid !='NA')
            {
                if( strtotime($curent_date) >  strtotime($fitness_valid))
                {
                    $fitness_valid_value='<span class ="badge" style=" border-color:#FF0000;background-color: #FF0000; color:#fff">'.$fitness_valid.'</span>';
                    $check_valid_data=1;
                }
            }
            else
            {
                $fitness_valid_value='<span>'.$fitness_valid.'</span>';
            }
            
            $insurance_valid=(!empty($record->insurance_valid_date))?date("d-M-Y", strtotime($record->insurance_valid_date)):'NA';
            $insurance_valid_value='';
            if($insurance_valid !='NA')
            {
                if( strtotime($curent_date) >  strtotime($insurance_valid))
                {
                    $insurance_valid_value='<span class ="badge" style=" border-color:#FF0000;background-color: #FF0000; color:#fff">'.$insurance_valid.'</span>';
                    $check_valid_data=1;
                }
            }
            else
            {
                $insurance_valid_value='<span>'.$insurance_valid.'</span>';
            }
            
            if($check_valid_data ==1)
            {
                $data_arr[] = array(
                    'sl'=>$sl,
                    "vehicle_no" =>$vehicle_no,
                    "permit_valid" =>$permit_valid_value,
                    "tax_valid" =>$tax_valid_value,
                    "fitness_valid" => $fitness_valid_value,
                    "insurance_valid" => $insurance_valid_value,
                );
            }
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

    public function getRenewVehicleDataAjax(Request $request)
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

        // $columnIndex = $columnIndex_arr[0]['column'];
        // $columnName = $columnName_arr[$columnIndex]['data'];
        // $columnSortOrder = $order_arr[0]['dir'];
        //$searchValue = $search_arr['value'];
        // $searchValue = $request->searchSearch;


        $recordsQuery=VehicleNotification::distinct('tbl_vehicle_notifications.id')->select('tbl_vehicle_notifications.*','tbl_vehicles.vehicle_no','tbl_vehicles.vehicle_name')->leftjoin('tbl_vehicles','tbl_vehicle_notifications.vehicle_id','=','tbl_vehicles.id')->where('tbl_vehicle_notifications.deleted_at',NULL);

        

        $totalRecords = $recordsQuery->count();
        $records =$recordsQuery->skip($start)
        ->take($rowperpage)
        ->orderBy('tbl_vehicle_notifications.id', 'desc')
        ->get();

        
        $totalRecordswithFilter = $totalRecords;

        $data_arr = array();
        $count=0;
        foreach($records as $k=> $record)
        {
            $curent_date=date("d-M-Y");
            $sl=$k+1;
            $vehicle_no=get_vehicle_name($record->vehicle_id,'1');
            $permit_valid=(!empty($record->permit_valid_date))?date("d-M-Y", strtotime($record->permit_valid_date)):'NA';
            $curent_date_ten_days=date("d-M-Y", strtotime("-10 days", strtotime($curent_date)));

            $check_valid_data=0;
            $permit_valid_value='';
            if( $permit_valid !='NA')
            {
                $permit_date_ten_days=date("d-M-Y", strtotime("-10 days", strtotime($permit_valid)));
               
                if( strtotime($curent_date) >=  strtotime($permit_date_ten_days) &&  strtotime($curent_date) <=  strtotime($permit_valid))
                {
                    $permit_valid_value='<span class ="badge" style=" border-color:#FFFF00;background-color: #FFFF00; color:#1a1717">'.$permit_valid.'</span>';
                    $check_valid_data=1;
                }

               
            }
            else
            {
                $permit_valid_value='<span>'.$permit_valid.'</span>';
            }
    
            $tax_valid=(!empty($record->tax_valid_date))?date("d-M-Y", strtotime($record->tax_valid_date)):'NA';

            $tax_valid_value='';
            if($tax_valid !='NA')
            {
                $tax_date_ten_days=date("d-M-Y", strtotime("-10 days", strtotime($tax_valid)));
               
                if( strtotime($curent_date) >=  strtotime($tax_date_ten_days) &&  strtotime($curent_date) <=  strtotime($tax_valid))
                {
                    $tax_valid_value='<span class ="badge" style=" border-color:#FFFF00;background-color: #FFFF00; color:#1a1717">'.$tax_valid.'</span>';
                    $check_valid_data=1;
                }

            }
            else
            {
                $tax_valid_value='<span>'.$tax_valid.'</span>';
            }
    

            $fitness_valid=(!empty($record->fitness_valid_date))?date("d-M-Y", strtotime($record->fitness_valid_date)):'NA';
            $fitness_valid_value='';
            if($fitness_valid !='NA')
            {
                $fitness_date_ten_days=date("d-M-Y", strtotime("-10 days", strtotime($fitness_valid)));
               
                if( strtotime($curent_date) >=  strtotime($fitness_date_ten_days) &&  strtotime($curent_date) <=  strtotime($fitness_valid))
                {
                    $fitness_valid_value='<span class ="badge" style=" border-color:#FFFF00;background-color: #FFFF00; color:#1a1717">'.$fitness_valid.'</span>';
                    $check_valid_data=1;
                }
                
            }
            else
            {
                $fitness_valid_value='<span>'.$fitness_valid.'</span>';
            }
        
            $insurance_valid=(!empty($record->insurance_valid_date))?date("d-M-Y", strtotime($record->insurance_valid_date)):'NA';
            $insurance_valid_value='';
            if($insurance_valid !='NA')
            {
                $insurance_date_ten_days=date("d-M-Y", strtotime("-10 days", strtotime($insurance_valid)));
               
                if( strtotime($curent_date) >=  strtotime($insurance_date_ten_days) &&  strtotime($curent_date) <=  strtotime($insurance_valid))
                {
                    $insurance_valid_value='<span class ="badge" style=" border-color:#FFFF00;background-color: #FFFF00; color:#1a1717">'.$insurance_valid.'</span>';
                    $check_valid_data=1;
                }
               
            }
            else
            {
                $insurance_valid_value='<span>'.$insurance_valid.'</span>';
            }
        
            if( $check_valid_data==1)
            {
                $data_arr[] = array(
                    'sl'=>$sl,
                    "vehicle_no" =>$vehicle_no,
                    "permit_valid" =>$permit_valid_value,
                    "tax_valid" =>$tax_valid_value,
                    "fitness_valid" => $fitness_valid_value,
                    "insurance_valid" => $insurance_valid_value,
                );
            }
            

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
}
