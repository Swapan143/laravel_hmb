<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\helpers;
class Adminauth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(Session::has('admin_details'))
        {
            return $next($request);
        }
        else
        {
            Session::flash('message', 'Please login first'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect('/');
        }
        
    }
}
