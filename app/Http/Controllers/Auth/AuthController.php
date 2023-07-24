<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponse;
    public function formLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function doLogin(Request $request)
    {

        try {
            $rules = array(
                'user' => 'required',
                'pass' => 'required'
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->errorResponse('Data tidak valid', 422, $validator->errors());
            } else {
                $userdata = array(
                    'username' => $request->user,
                    'password' => $request->pass
                );
                if (Auth::attempt($userdata)) {
                    if(Auth::user()->is_active) {
                        return $this->successResponse();
                    }
                    Auth::logout();
                    return $this->errorResponse('Akun anda tidak aktif', 401);
                } else {
                    return $this->errorResponse('Username atau Password salah.', 401);
                }
            }
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('formLogin');
    }
}
