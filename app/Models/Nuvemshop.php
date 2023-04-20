<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Nuvemshop extends Model
{
    use HasFactory;    
    public static function tokenGet( $code ) {
        $data = [
            "client_id"=> env("NS_ID"),
            "client_secret"=> env("NS_SECRET"),
            "grant_type"=> "authorization_code",
            "code"=> $code
        ];

        return $response = Http::withHeaders([
            'Content-Type' => "application/json"
        ])->post("https://www.nuvemshop.com.br/apps/authorize/token", $data )
        ->json();
    }

    public static function storeGet( $store_id = null, $token = null ) {

        if( !Auth::check() )
            return;

        if( !$store_id || !$token ) {
            $store = DB::table('store')
                ->where('user_id',Auth::user()->id)
                ->first();
            
            $store_id = $store->nuvemshop_store_id;
            $token = $store->nuvemshop_store_token;
        }
        

        $url = env('NS_URL') . '/' . $store_id . '/store';

        $NsApiGet = Http::withHeaders([
            'Authentication' => 'bearer '.$token,
            'User-Agent' => 'Frete Fixo (sampisolution.com.br)'
        ])->get($url)->json();

        return $NsApiGet;
    }
}
