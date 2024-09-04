<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Userid;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;

class UpgradePlanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // getting top plan id
        $maxPlan = Status::max('id');
        // getting logged in user current plan
        $userPlan = Userid::where('user_id', Auth::user()->user_id)->first()->user_status;

        // checking the user already in max plan
        if ($maxPlan == $userPlan) {
            return response()->json(["status" => 200, "message" => "No need to upgrade,you are in premium plan"], 200);
        }

        return $next($request);
    }
}
