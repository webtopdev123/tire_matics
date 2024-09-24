<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Merchant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateMerchantApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $merchantId = $request->route('merchant_id');
        $token = $request->bearerToken(); // This automatically extracts the token from the Authorization header

        if (!$merchantId || !$token) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }

        $merchant = Merchant::where('merchant_encrypt_id', $merchantId)
            ->where('api_token', $token)
            ->first();

        $merchantExist = Merchant::where('merchant_encrypt_id', $merchantId)->first();

        if($merchantExist->merchant_api_failed_access >= 10) {
          return response()->json(['error' => 'Unauthorized bearer token access limited, Please contact customer service.'], 401);
        }

        if (!$merchant) {
            if ($merchantExist) {
                $merchantExist->increment('merchant_api_failed_access');

                return response()->json(['error' => 'Unauthorized bearer token incorrect.'], 401);
            }

            return response()->json(['error' => 'Unauthorized.'], 401);
        }

        // $sanctumToken = $merchant->createToken('merchant-api-token');

        // Attach the generated Sanctum token to the request for later use
        // $request->attributes->add(['sanctum_token' => $sanctumToken]);

        return $next($request);
    }
}
