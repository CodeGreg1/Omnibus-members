<?php

namespace Modules\Affiliates\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Modules\Affiliates\Models\AffiliateUser;
use Modules\Affiliates\Events\AffiliateClicked;

class DetectAffiliate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $code = $request->query('r');
        if ($code) {
            $affiliateUser = AffiliateUser::whereCode($code)->first();
            if ($affiliateUser) {
                $affiliate = false;
                $affCookie = Cookie::get('aff');

                if (!$affCookie) {
                    $affiliate = $code;
                    event(new AffiliateClicked($code));
                } else {
                    if ($affCookie !== $code) {
                        $affiliate = $code;
                    }
                }

                if ($affiliate) {
                    $lifetime = intval(setting('affiliate_cookie_lifetime', 60)) * 1440;
                    $response->withCookie(cookie('aff', $affiliate, $lifetime));
                }
            }
        }

        return $response;
    }
}
