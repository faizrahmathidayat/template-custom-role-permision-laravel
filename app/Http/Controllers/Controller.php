<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Traits\ApiResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiResponse;

    public static function messageAlertSuccess($message)
    {
        $message = ['flag_alert' => 'success', 'message' => $message];
        return $message;
    } 

    public static function messageAlertError($message)
    {
        $message = ['flag_alert' => 'danger', 'message' => $message];
        return $message;
    }

    public static function errorPage($code = 500, $message = 'ERROR')
    {
        $err = array(
            'code' => $code,
            'message' => $message
        );
        return view('errors.errors',compact('err'));
    }
}
