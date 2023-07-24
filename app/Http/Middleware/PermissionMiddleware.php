<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\Permissions;
use Illuminate\Http\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    use Permissions;

    public function handle(Request $request, Closure $next, string $access)
    {
        $url =  $request->route()->getPrefix() != '' ?  $request->route()->getPrefix() : $request->path();
        $url = substr($url, 0, 1) == '/' ? str_replace('/','',$url) : $url;
        $data = $this->accessPermissions($access, $url);
        
        if(!empty($data)) {
            if(count($data['permissions']) > 0) {
                if(isset($data['permissions'][0][$access]) && $data['permissions'][0][$access]) {
                    return $next($request);
                }
            }
        }
        $err = array(
            'code' => 401,
            'message' => 'Akses tidak diizinkan.'
        );

        if($request->ajax()) {
            return response()->json($err,401);
        }
        
        return new Response(view('errors.errors',compact('err')));
    }
}
