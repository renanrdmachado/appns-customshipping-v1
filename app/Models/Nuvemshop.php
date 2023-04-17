<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

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
}
