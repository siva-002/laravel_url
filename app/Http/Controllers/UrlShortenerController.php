<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\url;
use App\Models\Userid;
use App\Models\Status;


class UrlShortenerController extends Controller
{
    //
    // protected $frontendUrl = "https://a256-2401-4900-1ce3-c6fc-132-8aca-9042-f06f.ngrok-free.app/";
    protected $guestUserLimit;

    public function index()
    {
        return redirect(env('REACT_APP_URL'));
        // return json_encode(["status" => 200, "messsage" => "backend working"]);
    }


    public function getCsrf()
    {
        return response()->json(['csrfToken' => csrf_token()]);
    }

    public function storeUrl(Request $request)
    {
        if (filter_var($request->url_value, FILTER_VALIDATE_URL)) {
            try {
                // for checking dupicate url generate request
                $duplicateCheck = url::where("user_id", $request->user_id)->where('actualurl', $request->url_value)->first();
                if ($duplicateCheck) {
                    return response()->json(["status" => 409, "message" => "Url Already Exists $duplicateCheck->shortenedurl"], 409);
                }

                $GeneratedCount = url::where('user_id', $request->user_id)->count();
                $status = Userid::where('user_id', $request->user_id)->first()->user_status;
                $userlimit = Status::where('id', $status)->first()->user_limit;

                $guestlimit = Status::where('id', 1)->first()->user_limit;

                // for not logged in users
                if (Auth::guest() && $GeneratedCount >= $guestlimit) {
                    return response()->json(["status" => 202, "message" => "Guest Quoto exceeded,Please Login to continue"], 202);
                }
                // for logged in users
                if (Auth::user() && $userlimit != "nill" && $GeneratedCount >= $userlimit) {
                    return response()->json(["status" => 203, "message" => "Please Upgrade your plan to continue"], 203);
                }
                // if ($GeneratedCount >= $this->guestUserLimit) {
                //     return response()->json(["status" => 202, "message" => "Guest Quoto exceeded,Please Login to continue"], 202);
                // }
                $shorturl = Str::random(5);
                $data = ["actualurl" => $request->url_value, "shortenedurl" => $shorturl, "user_id" => $request->user_id];
                url::create($data);
                return response()->json(["status" => 200, "message" => "Link Generated", "link" => ["shortenedurl" => $shorturl, "actualurl" => $request->url_value]], 200);
            } catch (Exception $e) {
                return response()->json(["status" => 500, "message" => $e->getMessage()], 500);
            }
        }
        return response()->json(["status" => 400, "message" => "Not a Valid Url"], 400);
    }

    public function redirectUrl($url)
    {
        try {
            $findUrl = url::where('shortenedurl', $url)->first();
            if ($findUrl) {
                return redirect($findUrl->actualurl);
            } else {
                return view("notfound");
            }
        } catch (Exception $e) {
            return response()->json(["status" => 500, "message" => $e->getMessage()], 500);
        }
    }

    public function deleteUrl(Request $request)
    {
        try {
            $findUrl = url::where('shortenedurl', $request->url_value)->first();
            if ($findUrl) {
                $findUrl->delete();
                return response()->json(["status" => 200, "message" => "Url removed"], 200);
            }
            return response()->json(["status" => 400, "message" => "Url Not found"], 400);

        } catch (Exception $e) {
            return response()->json(["status" => 500, "message" => $e->getMessage()], 500);
        }
    }

    // for getting user generated urls from db
    public function getUrlData(Request $request)
    {
        try {
            $finddata = url::where('user_id', $request->user_id)->get();
            return response()->json(["status" => 200, "data" => $finddata], 200);

        } catch (Exception $e) {
            return response()->json(["status" => 500, "message" => $e->getMessage()], 500);
        }
    }
}
