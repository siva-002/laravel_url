<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\url;


class UrlShortenerController extends Controller
{
    //
    protected $frontendUrl = "https://a256-2401-4900-1ce3-c6fc-132-8aca-9042-f06f.ngrok-free.app/";
    protected $guestUserLimit = 5;

    public function index()
    {
        return redirect($this->frontendUrl);
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
                $GeneratedCount = url::where('user_id', $request->user_id)->count();
                if (Auth()->guest()) {
                    if ($GeneratedCount >= $this->guestUserLimit) {
                        return response()->json(["status" => 202, "message" => "Guest Quoto exceeded,Please Login to continue"], 202);
                    }
                }
                $shorturl = Str::random(5);
                $data = ["actualurl" => $request->url_value, "shortenedurl" => $shorturl, "user_id" => $request->user_id];
                url::create($data);
                return response()->json(["status" => 200, "message" => "Link Generated", "link" => $shorturl], 200);
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
            } else {
                return response()->json(["status" => 400, "message" => "Url Not found"], 400);
            }
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
