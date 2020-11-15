<?php

namespace App\Http\Middleware;

use App\Models\Auth\Role;
use App\Exceptions\ApiException;
use App\Traits\apiJsonReturnTrait;
use Cartalyst\Sentinel\Hashing\BcryptHasher;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;

class SentinelPermission
{
    use apiJsonReturnTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {

        if ($role === 'api') {
            if (\Auth::guard('api')->user() == null) {
                throw new ApiException('Unauthorized', 403);
            }

            return $next($request);
        } else {
            Sentinel::setHasher(new BcryptHasher());

            $user = Sentinel::check();

            if (!$user) {
                return redirect()->guest('auth/login');
            }

            #This Is Root User?
            $roles = Sentinel::getRoles()->pluck('slug')->all();
            if (is_array($roles)) {
                if (in_array('root', $roles)) {
                    return $next($request);
                }
            }

            #Check Access When User Is Not Root
            if ($user->hasAccess($role)) {
                return $next($request);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response(trans('backpack::base.unauthorized'), 401);
            }

            return abort(401, 'Unauthorized action.');
        }
    }
}
