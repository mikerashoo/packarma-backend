<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class BasicAuth
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
        $AUTH_USER = 'admin';
        $AUTH_PASS = 'mypcot';
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $is_not_authenticated = (!$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
            $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
        );
        // if ($is_not_authenticated) {
        //     $return_array = array();
        //     $return_array['success'] = '0';
        //     $return_array['message'] = 'Authentication Failed';
        //     echo json_encode($return_array);
        //     exit;
        // }
        if ($is_not_authenticated) {
            errorMessage(__('auth.authentication_failed'), $return_array);
            exit;
        }
        if (!$request->header('platform')) {
            errorMessage(__('auth.platform_require'), $return_array);
            exit;
        }
        if (!in_array($request->header('platform'), config('global.PLATFORM'))) {
            errorMessage(__('auth.invalid_platform'), $return_array);
            exit;
        }

        if (!$request->header('version')) {
            errorMessage(__('auth.version_require'), $return_array);
            exit;
        }

        // if (!is_numeric($request->header('version'))) {
        //     errorMessage(__('auth.invalid_version'), $return_array);
        //     exit;
        // }

        if (!$request->header('device-id')) {
            errorMessage(__('auth.device_id_require'), $return_array);
            exit;
        }

        // if (!ctype_digit($request->header('imei-no'))) {
        //     errorMessage(__('auth.invalid_imei'), $return_array);
        //     exit;
        // }

        if (!$request->country_id) {
            errorMessage(__('auth.country_require'), $return_array);
            exit;
        }
        $lang = $request->header('Accept-Language', null);
        if (!empty($lang)) {
            \App::setLocale($lang);
        }
        return $next($request);
    }
}
