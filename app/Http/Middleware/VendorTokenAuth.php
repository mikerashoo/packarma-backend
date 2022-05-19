<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Session;

class VendorTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $return_array = array();
        // $return_array['success'] = '0';
        try {
            $vendor_token = $request->header('access-token');
            $data = JWTAuth::setToken($vendor_token)->getPayload();
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            // $return_array['success'] = '4';
            // $return_array['message'] = 'Token Expired';
            errorMessage(__('auth.token_expired'), $return_array);

            // return response()->json($return_array, 500);
            // echo json_encode($return_array);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            // $return_array['message'] = 'Authentication Failed';
            errorMessage(__('auth.authentication_failed'), $return_array);
            return response()->json($return_array, 500);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // $return_array['message'] = 'Authentication Failed';
            errorMessage(__('auth.authentication_failed'), $return_array);
            // return response()->json($return_array, 500);
        }
        Session::flash('vendorTokenData', $vendor_token);
        return $next($request);
    }
}
