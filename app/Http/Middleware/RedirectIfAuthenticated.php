<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\UserSettings;
use App\Http\Resources\UserSettings as UserSettingsRessource;
class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!empty(Auth::id())) {
          UserSettings::firstOrCreate(['user_id' => Auth::id()]);
          //return response('{"success"}', 200);
          return new UserSettingsRessource(UserSettings::where('user_id', '=' ,Auth::id())->firstOrFail());
          //  return redirect('/home');
        }

        return $next($request);
        //return response()->json(["data"=>["error_msg"=>"Login failed"]],403);
    }
}
