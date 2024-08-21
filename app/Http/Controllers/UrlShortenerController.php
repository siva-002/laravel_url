<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\url;


class UrlShortenerController extends Controller
{
    //

    public function index()
    {
        return json_encode(["status" => 200, "messsage" => "backend working"]);
    }

    public function getCsrf(){
    return response()->json(['csrfToken' => csrf_token()]);
    }

    public function storeUrl(Request $request)
    {
        // $urllink = "https://Facebook";
        // $user_id = 122332;
        // if (filter_var($request->urllink, FILTER_VALIDATE_URL)) {
        //     try {
        //         $shorturl = Str::random(5);
        //         $data = ["actualurl" => $request->urllink, "shortenedurl" => $shorturl, "user_id" => $request->user_id];
        //         url::create($data);
        //         return json_encode(["status" => 5200, "message" => "Link Generated", "link" => $request->shorturl]);
        //     } catch (Exception $e) {
        //         return json_encode(["status" => 500, "message" => $e->getMessage()]);
        //     }
        // }
        return json_encode(["status" => 200, "message" => "Valid Url"]);
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
            return json_encode(["status" => 500, "message" => $e->getMessage()]);
        }
    }

    public function deleteUrl(Request $request)
    {
        try {
            $findUrl = url::where('shortenedurl', $request->url)->first();
            if ($findUrl) {
                $findUrl->delete();
                return json_encode(["status" => 200, "message" => "Url removed"]);
            } else {
                return json_encode(["status" => 404, "message" => "Url Not found"]);
            }
        } catch (Exception $e) {
            return json_encode(["status" => 500, "message" => $e->getMessage()]);
        }
    }
}
