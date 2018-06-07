<?php

namespace App\Http\Middleware;

use App\Models\Log;
use App\Models\User;
use Closure;
use PPC\TeamServiceSDK\UcenterService;
use \Illuminate\Routing\Router;
use Illuminate\Support\Facades\Redis;
use App\Model\Employee;
use PPC\TeamServiceSDK\Utils\PPCResponse;

use App\Lib\MTResponse;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // check token
        $token = $request->input("token");

        if (!$token) {
            MTResponse::jsonResponse("对不起，您没有登录", RESPONSE_NO_LOGIN);
        }

        $userObj = new  User();

        $userInfo = $userObj->loginCheck($token);

        if (!$userInfo) {
            MTResponse::jsonResponse("对不起，您没有登录", RESPONSE_NO_LOGIN);
        }

        // save global user info
        $_SERVER['userInfo'] = $userInfo;

        $uri = $request->getRequestUri();
        $requestData = json_encode($request->all());

        Log::saveLog($uri, $requestData);

        return $next($request);
    }
}
