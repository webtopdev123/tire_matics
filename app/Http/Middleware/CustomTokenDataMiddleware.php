<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PermissionRole;
use App\Models\Merchant;

class CustomTokenDataMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $permissions = PermissionRole::with('permission')
                ->where('role_id', $user->role_id)
                ->get()
                ->map(function ($permission) {
                    return (object) [
                        'permission_code' => $permission['permission']['permission_code'],
                        'permission_role_read' => $permission['permission_role_read'],
                        'permission_role_create' => $permission['permission_role_create'],
                        'permission_role_update' => $permission['permission_role_update'],
                        'permission_role_delete' => $permission['permission_role_delete'],
                    ];
                })
                ->toArray();
            
            if (!$request->is('logout') && !$request->is('logout/*')) {

              //for Admin

              $current_switched_userRoleId = Session::get('current_switched_userRoleId');

              if ($current_switched_userRoleId !== null) {
                  Auth::user()->role_id=$current_switched_userRoleId;
                  $user->role_id=$current_switched_userRoleId;
              }

              //end Admin

              $currentSwitchedMerchantId = Session::get('current_switched_merchantId');

              if ($currentSwitchedMerchantId !== null) {
                  Auth::user()->merchant_id=$currentSwitchedMerchantId;
              }

              $currentSwitchedUserId = Session::get('current_switched_userId');
              if ($currentSwitchedUserId !== null) {
                  Auth::user()->id=$currentSwitchedUserId;
                  $user->id=$currentSwitchedUserId;
              }

              $currentSwitchedUserName = Session::get('current_switched_userName');
              if ($currentSwitchedUserName !== null) {
                  Auth::user()->name=$currentSwitchedUserName;
              }

            }

            $userData = [
                'user_id' => $user->id,
                'role_id' => $user->role_id,
                'merchant_id' => Auth::user()->merchant_id,
                'permissions' => $permissions,
            ];

            if (!$request->ajax()) {
                $merchantData = [];
                $merchantPermission = collect($permissions)->first(function ($permission) {
                    return $permission->permission_code == 'merchant' && $permission->permission_role_read == 1;
                });

                if ($merchantPermission) {
                    $merchantData = Merchant::orderBy('merchant_name')->get();
                }

                $user_userId = Session::get('user_userId');
                $userId=0;
                if ($user_userId !== null) {
                  $userId=$user_userId;
                }

                if($user->id == '1' || $userId=='1')
                  $userData['merchant'] = $merchantData;
               
            }

            $request->merge([
                'user_data' => $userData,
            ]);
            // $token = $user->tokens()->first();

            // if ($token && !empty($token->expires_at)) {
            //   if ($token->expires_at < now()) {
            //     $routeName = $request->route()->getName();

            //     if ($routeName && !in_array($routeName, ['auth-login', 'logout'])) {
            //       return redirect()->route('logout');
            //     }
            //   }
            // }

            if ($user->status != 1) {
                $routeName = $request->route()->getName();

                if ($routeName && !in_array($routeName, ['auth-login', 'logout'])) {
                    return redirect()->route('logout');
                }
            }
        }

        return $next($request);
    }
}
