<?php

namespace Modules\Subscriptions\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsSubscribed
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
        if (!$request->user() || setting('allow_subscriptions') !== 'enable') {
            return redirect(route('user.subscriptions.pricings.index'));
        }

        $subscription = $request->user()->subscription();
        if ($subscription) {
            if ($subscription->ended() || !$subscription->onGracePeriod()) {
                return redirect(route('user.subscriptions.pricings.index'));
            }

            if ($subscription->isManual() && 24 >= $subscription->getLastEndedTotalHours()) {
                return redirect(route('user.subscriptions.manual-payment-checkout', $subscription));
            }

            return $next($request);
        } else {
            return redirect(route('user.subscriptions.pricings.index'));
        }

        return $next($request);
    }
}