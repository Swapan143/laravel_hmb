<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Transporter;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Location;
use App\Models\Challan;
use App\Models\Diesel;

use Mail;
use App\Mail\TestMail;


class DashboardController extends Controller
{
    private $viewFolder = 'admin/dashboard/';
    public function index()
    {
        $total_user=User::where('status',1)->where('deleted_at',NULL)->count();
        $total_transporter=Transporter::where('status',1)->where('deleted_at',NULL)->count();
        $total_client=Client::where('status',1)->where('deleted_at',NULL)->count();
        $total_vehicle=Vehicle::where('status',1)->where('deleted_at',NULL)->count();
        $total_loading_location=Location::where('status',1)->where('deleted_at',NULL)->where('location_type',1)->count();
        $total_siding_location=Location::where('status',1)->where('deleted_at',NULL)->where('location_type',2)->count();
        $today=Date('Y-m-d');
        $total_challan=Challan::where('status',1)->where('deleted_at',NULL) ->whereDate('created_at', '=', $today)->count();
        $total_diesel=Diesel::where('status',1)->where('deleted_at',NULL) ->whereDate('created_at', '=', $today)->sum('quantity');


        return view($this->viewFolder.'dashboard',compact('total_user','total_transporter','total_client','total_vehicle','total_loading_location','total_siding_location','total_challan','total_diesel'));
    }

    public function sendMail()
    {
        $mail = 'swapan.kanrar143@gmail.com';
        $mailData = array('name' => 'swapan kanrar', 'email' => 'swapan.kanrar143@gmail.com');
        Mail::to($mail)->send(new TestMail($mailData));

    }

}
