<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Adminauth; 
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\VehicleNotificationController;
use App\Http\Controllers\Admin\ChallanController;
use App\Http\Controllers\Admin\DieselController;
use App\Http\Controllers\Admin\MasterSettingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TransporterController;
use App\Http\Controllers\Admin\TransporterReportController;
use App\Http\Controllers\Admin\ChargesController;
use App\Http\Controllers\Admin\DieselReportController;









// ============================Admin Route==================================


Route::get('/',[AuthController::class,'index']);
Route::post('admin-login-post',[AuthController::class,'login_post']);
Route::get('/forgot-password',[AuthController::class,'forgorIndex']);
Route::post('save-forgot-password',[AuthController::class,'forgorPassword']);
Route::get('/reset-password/{id}',[AuthController::class,'resetPassword']);
Route::post('/save-reset-password',[AuthController::class,'resetPasswordSave']);

Route::middleware([Adminauth::class])->prefix('admin')->group(function()
{

    Route::get('logout',[AuthController::class,'logout']);
    Route::get('dashboard', [DashboardController::class, 'index']);
    // change password
    Route::get('change-password',[ChangePasswordController::class,'index']);
    Route::post('save-change-password',[ChangePasswordController::class,'changePassword']);

    //mail check
    Route::get('mail-check',[DashboardController::class,'sendMail']);

   //role module
    Route::group(['prefix'=>'role'],function()
    {
        Route::get('/list',[RoleController::class,'index']);
        Route::get('/get-data-ajax',[RoleController::class,'getDataAjax']);
        Route::get('/add',[RoleController::class,'add']);
        Route::post('/store',[RoleController::class,'store']);
        Route::get('/list/status',[RoleController::class,'status']);
        Route::get('/edit/{id}',[RoleController::class,'edit']);
        Route::get('/delete/{id}',[RoleController::class,'delete']);
        Route::get('/{id}',[RoleController::class,'delete']);
        Route::post('/update',[RoleController::class,'update']);
        Route::get('/permission/{id}',[RoleController::class,'role_permission']);
        Route::post('/permission-update',[RoleController::class,'update_role_permission']);
    });

    //user module
    Route::group(['prefix'=>'user'],function()
    {
        Route::get('/send-password',[UserController::class,'SendPassword']);
        Route::get('/list',[UserController::class,'index']);
        Route::get('/get-data-ajax',[UserController::class,'getDataAjax']);
        Route::get('/add',[UserController::class,'add']);
        Route::post('/store',[UserController::class,'store']);
        Route::get('/list/status',[UserController::class,'status']);
        Route::get('/edit/{id}',[UserController::class,'edit']);
        Route::get('/delete/{id}',[UserController::class,'delete']);
        Route::get('/{id}',[UserController::class,'delete']);
        Route::post('/update',[UserController::class,'update']);
        
    });

    //vendor module
    Route::group(['prefix'=>'vendor'],function()
    {
        Route::get('/sms',[VendorController::class,'smsStatusUpdate']);
        Route::get('/list',[VendorController::class,'index']);
        Route::get('/get-data-ajax',[VendorController::class,'getDataAjax']);
        Route::get('/add',[VendorController::class,'add']);
        Route::post('/store',[VendorController::class,'store']);
        Route::get('/list/status',[VendorController::class,'status']);
        Route::get('/edit/{id}',[VendorController::class,'edit']);
        Route::get('/delete/{id}',[VendorController::class,'delete']);
        Route::get('/{id}',[VendorController::class,'delete']);
        Route::post('/update',[VendorController::class,'update']);
    });

    //vehicle module
    Route::group(['prefix'=>'client'],function()
    {
        Route::get('/list',[ClientController::class,'index']);
        Route::get('/get-data-ajax',[ClientController::class,'getDataAjax']);
        Route::get('/add',[ClientController::class,'add']);
        Route::post('/store',[ClientController::class,'store']);
        Route::get('/list/status',[ClientController::class,'status']);
        Route::get('/edit/{id}',[ClientController::class,'edit']);
        Route::get('/delete/{id}',[ClientController::class,'delete']);
        Route::get('/{id}',[ClientController::class,'delete']);
        Route::post('/update',[ClientController::class,'update']);
    });

    //vehicle module
    Route::group(['prefix'=>'vehicle'],function()
    {
        Route::get('/download',[VehicleController::class,'download']);
        Route::get('/siding-location',[VehicleController::class,'sidingLocation']);
        Route::get('/list/log-details',[VehicleController::class,'companyDetails']);
        Route::get('/import-csv',[VehicleController::class,'importCsv']);
        Route::post('/upload-csv',[VehicleController::class,'uploadCsv']);
        Route::get('/list',[VehicleController::class,'index']);
        Route::get('/get-data-ajax',[VehicleController::class,'getDataAjax']);
        Route::get('/add',[VehicleController::class,'add']);
        Route::post('/store',[VehicleController::class,'store']);
        Route::get('/list/status',[VehicleController::class,'status']);
        Route::get('/edit/{id}',[VehicleController::class,'edit']);
        Route::get('/delete/{id}',[VehicleController::class,'delete']);
        Route::get('/{id}',[VehicleController::class,'delete']);
        Route::post('/update',[VehicleController::class,'update']);
    });

    //vehicle module
    Route::group(['prefix'=>'report'],function()
    {
        // vechile report
        Route::get('/download',[ReportController::class,'download']);
        Route::get('/date-wise-report',[ReportController::class,'dateWiseReport']);
        Route::get('/get-data-date-wise-report-ajax',[ReportController::class,'getDateWiseReportAjax']);
        Route::get('/location',[ReportController::class,'locationList']);
        Route::get('/show-title',[ReportController::class,'showTitle']);

        // transporter report
        Route::get('/transporter',[TransporterReportController::class,'transporter']);
        Route::get('/get-data-transporter-report-ajax',[TransporterReportController::class,'getTransporterDataAjax']);
        Route::get('/transporter-download',[TransporterReportController::class,'transporterReportDownload']);

        // desel report
        Route::get('/diesel',[DieselReportController::class,'diesel']);
        Route::get('/get-data-diesel-report-ajax',[DieselReportController::class,'getDieselDataAjax']);
        Route::get('/diesel-download',[DieselReportController::class,'dieselReportDownload']);

    });

    //vehicle notification
    Route::group(['prefix'=>'vehicle-notification'],function()
    {
        Route::get('/get-expired-vehicle-data-ajax',[VehicleNotificationController::class,'getExpiredVehicleDataAjax']);
        Route::get('/get-renew-vehicle-data-ajax',[VehicleNotificationController::class,'getRenewVehicleDataAjax']);
        Route::get('/csv-export',[VehicleNotificationController::class,'csvExport']);
        Route::get('/list',[VehicleNotificationController::class,'index']);
        Route::get('/get-data-ajax',[VehicleNotificationController::class,'getDataAjax']);
        Route::get('/add',[VehicleNotificationController::class,'add']);
        Route::post('/store',[VehicleNotificationController::class,'store']);
        Route::get('/list/status',[VehicleNotificationController::class,'status']);
        Route::get('/edit/{id}',[VehicleNotificationController::class,'edit']);
        Route::get('/delete/{id}',[VehicleNotificationController::class,'delete']);
        Route::get('/{id}',[VehicleNotificationController::class,'delete']);
        Route::post('/update',[VehicleNotificationController::class,'update']);
        
    });

    //location module
    Route::group(['prefix'=>'location'],function()
    {
        Route::get('/list',[LocationController::class,'index']);
        Route::get('/get-data-ajax',[LocationController::class,'getDataAjax']);
        Route::get('/add',[LocationController::class,'add']);
        Route::post('/store',[LocationController::class,'store']);
        Route::get('/list/status',[LocationController::class,'status']);
        Route::get('/edit/{id}',[LocationController::class,'edit']);
        Route::get('/delete/{id}',[LocationController::class,'delete']);
        Route::get('/{id}',[LocationController::class,'delete']);
        Route::post('/update',[LocationController::class,'update']);
    });

    //location module
    Route::group(['prefix'=>'charges'],function()
    {
        Route::get('/list/{id}',[ChargesController::class,'index']);
        Route::get('/get-data-ajax',[ChargesController::class,'getDataAjax']);
        Route::get('/add/{id}',[ChargesController::class,'add']);
        Route::post('/store',[ChargesController::class,'store']);
        Route::get('/list/{id}/status',[ChargesController::class,'status']);
        Route::get('/edit/{id}',[ChargesController::class,'edit']);
        Route::get('/delete/{id}',[ChargesController::class,'delete']);
        Route::get('/{id}',[ChargesController::class,'delete']);
        Route::post('/update',[ChargesController::class,'update']);
    });

    //location module
    Route::group(['prefix'=>'challan'],function()
    {
        Route::get('/delete-multiple',[ChallanController::class,'deleteMultiple']);
        Route::get('/download',[ChallanController::class,'download']);
        Route::get('/add/company',[ChallanController::class,'companyList']);
        Route::get('/add/company-save',[ChallanController::class,'companySave']);
        Route::get('/list',[ChallanController::class,'index']);
        Route::get('/get-data-ajax',[ChallanController::class,'getDataAjax']);
        Route::get('/view/{id}',[ChallanController::class,'view']);
        Route::get('/list/image',[ChallanController::class,'challanImage']);
        Route::get('/add',[ChallanController::class,'add']);
        Route::post('/store',[ChallanController::class,'store']);
        Route::get('/add/vehicle',[ChallanController::class,'getVehicle']);
        Route::get('/edit/{id}',[ChallanController::class,'edit']);
        Route::post('/update',[ChallanController::class,'update']);
        Route::get('/delete/{id}',[ChallanController::class,'delete']);


    
    });

    //location module
    Route::group(['prefix'=>'diesel'],function()
    {
        Route::get('/delete-multiple',[DieselController::class,'deleteMultiple']);
        Route::get('/download',[DieselController::class,'download']);
        Route::get('/list',[DieselController::class,'index']);
        Route::get('/get-data-ajax',[DieselController::class,'getDataAjax']);
        Route::get('/view/{id}',[DieselController::class,'view']);
        Route::get('/list/image',[DieselController::class,'dieselImage']);
        Route::get('/add',[DieselController::class,'add']);
        Route::post('/store',[DieselController::class,'store']);
        Route::get('/edit/{id}',[DieselController::class,'edit']);
        Route::post('/update',[DieselController::class,'update']);
        Route::get('/delete/{id}',[DieselController::class,'delete']);
    
    });

    //setting module
    Route::group(['prefix'=>'setting'],function()
    {
        Route::group(['prefix'=>'version'],function()
        {
            Route::get('/',[SettingController::class,'addVersion']);
            Route::post('/update',[SettingController::class,'updateVersion']);
        });

        Route::group(['prefix'=>'master'],function()
        {
            Route::get('/',[MasterSettingController::class,'masterSetting']);
            Route::post('/update',[MasterSettingController::class,'masterSettingUpdate']);
        });

        Route::group(['prefix'=>'sms'],function()
        {
            Route::get('/',[TransporterController::class,'smsSetting']);
            Route::get('/update',[TransporterController::class,'smsSettingUpdate']);
        });
    });

    
});

